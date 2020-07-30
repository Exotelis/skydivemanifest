import Vue from 'vue';
import { AxiosResponse } from 'axios';
import { Component } from 'vue-property-decorator';
import { TranslateResult } from 'vue-i18n';

interface VueHtmlElement extends HTMLFormElement {
  vm?: Vue;
}

@Component({
  directives: {
    validate: {
      bind: function (el, binding, vnode) {
        if (!(el instanceof HTMLFormElement)) {
          throw new Error('The validate directive must be used on a form element');
        }

        const vm: Vue = vnode.context!;
        let element: VueHtmlElement = el;
        element.vm = vm;

        el.addEventListener('input', inputEventListener);
        el.addEventListener('focusout', focusoutEventListener);

        vm.$on('validate', () => {
          let elements: NodeList = getFormElements(element);
          elements.forEach((node) => {
            updateValidationError(vm, node as HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement);
          });
        });
        vm.$on('validateResponse', () => {
          let elements: NodeList = getFormElements(element);
          elements.forEach((node) => {
            checkValidity(vm, node as HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement);
          });
        });
      }
    }
  }
})
export default class FormValidationMixin extends Vue {
  errors: any = {};
  validationTimeout: number[] = [];

  hasValidationError (): boolean {
    return Object.keys(this.errors).some(key => {
      return !!this.errors[key];
    });
  }

  validateResponse (e: any): void {
    const response: AxiosResponse|undefined = e.response;

    if (response === undefined) {
      return;
    }

    if (response.status !== 422) {
      return;
    }

    if (typeof response.data.errors === 'undefined') {
      throw new Error('Response format is not supported for validation.');
    }

    Object.keys(response.data.errors).forEach((key: any) => {
      response.data.errors[key] = response.data.errors[key].join(' ');
    });

    this.errors = Object.assign({}, this.errors, response.data.errors);
    this.$emit('validateResponse');
  }
}

/**
 * Core functions
 */

function inputEventListener (event: Event): void {
  if (!isValidatableFormElement(event.target)) {
    return;
  }
  const vm: Vue = (event.currentTarget as HTMLFormElement).vm;
  const target: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement = event.target as any;

  clearTimeout(vm.$data.validationTimeout[target.id]);
  vm.$data.validationTimeout[target.id] = setTimeout(() => {
    updateValidationError(vm, target);
  }, 600);
}

function focusoutEventListener (event: FocusEvent): void {
  const currentTarget = event.currentTarget as HTMLFormElement;
  const relatedTarget = event.relatedTarget as HTMLFormElement;

  if ((!currentTarget.contains(relatedTarget) && relatedTarget !== null) ||
    relatedTarget instanceof HTMLSelectElement) {
    return;
  }

  if (!isValidatableFormElement(event.target)) {
    return;
  }
  const vm: Vue = currentTarget.vm;
  const target: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement = event.target as any;

  clearTimeout(vm.$data.validationTimeout[target.id]);
  updateValidationError(vm, target);
}

function updateValidationError (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement) {
  vm.$data.errors = Object.assign({}, vm.$data.errors, {
    [el.id]: validate(vm, el) as string|undefined
  });

  checkValidity(vm, el);
}

/**
 * Validation functions
 */

function validate (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult|undefined {
  // Required fields
  if (el.validity.valueMissing) {
    return validateRequired(vm, el);
  }

  // Type validation
  if (el.validity.typeMismatch || el.validity.badInput) {
    return validateType(vm, el);
  }

  // Min- and maxlength
  if (el.validity.tooLong) {
    return validateMaxLength(vm, el);
  }

  if (el.validity.tooShort) {
    return validateMinLength(vm, el);
  }

  // Min and max
  if (el.validity.rangeOverflow) {
    return validateMax(vm, el);
  }

  if (el.validity.rangeUnderflow) {
    return validateMin(vm, el);
  }

  // Step
  if (el.validity.stepMismatch) {
    return validateStep(vm, el);
  }

  // Pattern
  if (el.validity.patternMismatch) {
    return validatePattern(vm);
  }

  // Confirmation
  if (el.id.substr(-13) === '_confirmation') {
    return validateConfirmation(vm, el);
  }
}

function validateConfirmation (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement):
  TranslateResult|undefined {
  let siblingId:string = el.id.replace('_confirmation', '');
  const element: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement|null =
    vm.$el.querySelector('#' + siblingId);

  if (element === null) {
    return;
  }

  if (el.value !== element.value) {
    return vm.$t('error.form.confirmation');
  }
}

function validateMax (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  let max: any = el.getAttribute('max');

  switch (el.type) {
    case 'date':
      max = vm.$d(Date.parse(max));
      return vm.$t('error.form.rangeOverflow.date', { max: max });
    case 'datetime-local':
      max = vm.$d(Date.parse(max), 'datetime');
      return vm.$t('error.form.rangeOverflow.datetime', { max: max });
    case 'month':
      max = vm.$d(Date.parse(max), 'month');
      return vm.$t('error.form.rangeOverflow.month', { max: max });
    case 'number':
      return vm.$t('error.form.rangeOverflow.number', { max: max });
    case 'time':
      max = vm.$d(Date.parse('January 01, 1970 ' + max), 'time');
      return vm.$t('error.form.rangeOverflow.time', { max: max });
    case 'week':
      return vm.$t('error.form.rangeOverflow.week', { max: max });

    default:
      return vm.$t('error.form.rangeOverflow.default', { max: max });
  }
}

function validateMin (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  let min: any = el.getAttribute('min');

  switch (el.type) {
    case 'date':
      min = vm.$d(Date.parse(min));
      return vm.$t('error.form.rangeUnderflow.date', { min: min });
    case 'datetime-local':
      min = vm.$d(Date.parse(min), 'datetime');
      return vm.$t('error.form.rangeUnderflow.datetime', { min: min });
    case 'month':
      min = vm.$d(Date.parse(min), 'month');
      return vm.$t('error.form.rangeUnderflow.month', { min: min });
    case 'number':
      return vm.$t('error.form.rangeUnderflow.number', { min: min });
    case 'time':
      min = vm.$d(Date.parse('January 01, 1970 ' + min), 'time');
      return vm.$t('error.form.rangeUnderflow.time', { min: min });
    case 'week':
      return vm.$t('error.form.rangeUnderflow.week', { min: min });

    default:
      return vm.$t('error.form.rangeUnderflow.default', { min: min });
  }
}

function validateMaxLength (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  return vm.$t('error.form.tooLong', { current: el.value.length, max: el.getAttribute('maxlength') });
}

function validateMinLength (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  return vm.$t('error.form.tooShort', { current: el.value.length, min: el.getAttribute('minlength') });
}

function validatePattern (vm: Vue): TranslateResult {
  return vm.$t('error.form.pattern');
}

function validateRequired (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  switch (el.type) {
    case 'checkbox':
    case 'file':
    case 'number':
    case 'radio':
      return vm.$t('error.form.required.' + el.type);

    default:
      return vm.$t('error.form.required.text');
  }
}

function validateStep (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  let min: number = el.type === 'number'
    ? Number(el.getAttribute('min'))
    : Number(Date.parse(el.getAttribute('min')!));
  let step: number = Number(el.getAttribute('step'));
  let value: number = el.type === 'number' ? Number(el.value) : Number(Date.parse(el.value));

  switch (el.type) {
    case 'date':
      step = step * 86400000;
      break;
    case 'datetime-local':
    case 'time':
      step = step * 1000;
      break;
    case 'month':
      step = step * 2678400000;
      break;
    case 'week':
      step = step * 604800000;
      break;
  }
  let lowerBoundary: number = value - ((value - min) % step);
  let upperBoundary: number = lowerBoundary + step;

  switch (el.type) {
    case 'date':
      return vm.$t('error.form.step.boundary',
        { lowerBoundary: vm.$d(lowerBoundary), upperBoundary: vm.$d(upperBoundary) });
    case 'datetime-local':
      return vm.$t('error.form.step.boundary',
        { lowerBoundary: vm.$d(lowerBoundary, 'datetime'), upperBoundary: vm.$d(upperBoundary, 'datetime') });
    case 'month':
      return vm.$t('error.form.step.boundary',
        { lowerBoundary: vm.$d(lowerBoundary, 'month'), upperBoundary: vm.$d(upperBoundary, 'month') });
    case 'number':
      return vm.$t('error.form.step.boundary', { lowerBoundary: lowerBoundary, upperBoundary: upperBoundary });

    default:
      return vm.$t('error.form.step.default');
  }
}

function validateType (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): TranslateResult {
  switch (el.type) {
    case 'email':
    case 'number':
    case 'url':
      return vm.$t('error.form.required.' + el.type);

    default:
      return vm.$t('error.form.required.default');
  }
}

/**
 * Helper functions
 */

function checkValidity (vm: Vue, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): boolean {
  if (vm.$data.errors[el.id]) {
    setInvalid(el);
    return false;
  }

  setValid(el);
  return true;
}

function getFormElements (element: HTMLFormElement): NodeList {
  return element.querySelectorAll('input, select, textarea');
}

function isValidatableFormElement (target: EventTarget|null): boolean {
  return target instanceof HTMLInputElement ||
    target instanceof HTMLSelectElement ||
    target instanceof HTMLTextAreaElement;
}

function setInvalid (el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): void {
  if (!el.classList.contains('is-invalid')) {
    el.classList.add('is-invalid');
    if (el.parentElement!.localName !== 'form') {
      el.parentElement!.classList.add('is-invalid');
    }
  }

  if (el.classList.contains('is-valid')) {
    el.classList.remove('is-valid');
    el.parentElement!.classList.remove('is-valid');
  }
}

function setValid (el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): void {
  if (!el.classList.contains('is-valid')) {
    el.classList.add('is-valid');
    if (el.parentElement!.localName !== 'form') {
      el.parentElement!.classList.add('is-valid');
    }
  }

  if (el.classList.contains('is-invalid')) {
    el.classList.remove('is-invalid');
    el.parentElement!.classList.remove('is-invalid');
  }
}
