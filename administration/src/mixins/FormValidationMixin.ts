import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component({
  directives: {
    validate: {
      bind: function (el, binding, vnode) {
        const vm: any = vnode.context;
        const element: any = el;

        el.addEventListener('input', () => {
          updateValidationError(vm, element);
        });
        vm.$on('validate', () => {
          updateValidationError(vm, element);
        });
      }
    }
  }
})
export default class FormValidationMixin extends Vue {
  errors: any = {};

  hasValidationError (): boolean {
    return Object.keys(this.errors).some(key => {
      return !!this.errors[key];
    });
  }
}

function validate (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement) {
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
}

function updateValidationError (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement) {
  vm.errors = Object.assign({}, vm.errors, {
    [el.id]: validate(vm, el)
  });
}

function validateMax (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
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

function validateMin (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
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

function validateMaxLength (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
  return vm.$t('error.form.tooLong', { current: el.value.length, max: el.getAttribute('maxlength') });
}

function validateMinLength (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
  return vm.$t('error.form.tooShort', { current: el.value.length, min: el.getAttribute('minlength') });
}

function validatePattern (vm: any): string {
  return vm.$t('error.form.pattern');
}

function validateRequired (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
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

function validateStep (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
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

function validateType (vm: any, el: HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement): string {
  switch (el.type) {
    case 'email':
    case 'number':
    case 'url':
      return vm.$t('error.form.required.' + el.type);

    default:
      return vm.$t('error.form.required.default');
  }
}
