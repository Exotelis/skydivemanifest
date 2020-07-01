import { shallowMount } from '@vue/test-utils';
import InputDate from '../InputDate.vue';

describe('InputDate.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(InputDate, {
      propsData: {
        id: 'testId'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the date input', () => {
    expect(component.props().autocomplete).toBe(null);
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().form).toBe(null);
    expect(component.props().inputmode).toBe(null);
    expect(component.props().list).toBe(null);
    expect(component.props().max).toBe(null);
    expect(component.props().min).toBe(null);
    expect(component.props().plaintext).toBeFalsy();
    expect(component.props().readonly).toBeFalsy();
    expect(component.props().required).toBeFalsy();
    expect(component.props().spellcheck).toBe('false');
    expect(component.props().step).toBe(null);
    expect(component.props().tabindex).toBe(null);
    expect(component.props().value).toBe(null);
  });

  it('check if event is emitted when the input value changes', async () => {
    component.find('input').setValue('2020-06-06');
    await component.vm.$nextTick();
    expect(component.emitted().input).toBeTruthy();
  });

  it('check if the attribute autocomplete of the input field is email', () => {
    component.setProps({ autocomplete: 'bday' });
    expect(component.find('input').attributes().autocomplete).toBe('bday');
  });

  it('check if the attribute autofocus of the input field is true', () => {
    component.setProps({ autofocus: true });
    expect(component.find('input').attributes().autofocus).toBeTruthy();
  });

  it('check if the attribute disabled of the input field is true', () => {
    component.setProps({ disabled: true });
    expect(component.find('input').attributes().disabled).toBeTruthy();
  });

  it('check if the attribute form of the input field is formId', () => {
    component.setProps({ form: 'formId' });
    expect(component.find('input').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the input field is testId', () => {
    expect(component.find('input').attributes().id).toBe('testId');
  });

  it('check if the attribute inputmode of the input field is tel', () => {
    component.setProps({ inputmode: 'numeric' });
    expect(component.find('input').attributes().inputmode).toBe('numeric');
  });

  it('check if the attribute list of the input field is listId', () => {
    component.setProps({ list: 'listId' });
    expect(component.find('input').attributes().list).toBe('listId');
  });

  it('check if the attribute max of the input field is 10', () => {
    component.setProps({ max: 10 });
    expect(component.find('input').attributes().max).toBe('10');
  });

  it('check if the attribute min of the input field is 10', () => {
    component.setProps({ min: 10 });
    expect(component.find('input').attributes().min).toBe('10');
  });

  it('check if the attribute name of the input field is testId', () => {
    expect(component.find('input').attributes().name).toBe('testId');
  });

  it('check if the attribute readonly of the input field is true', () => {
    component.setProps({ readonly: true });
    expect(component.find('input').attributes().readonly).toBeTruthy();
  });

  it('check if the attribute required of the input field is true', () => {
    component.setProps({ required: true });
    expect(component.find('input').attributes().required).toBeTruthy();
  });

  it('check if the attribute spellcheck of the input field is true', () => {
    component.setProps({ spellcheck: true });
    expect(component.find('input').attributes().spellcheck).toBeTruthy();
  });

  it('check if the attribute step of the input field is 5', () => {
    component.setProps({ step: 5 });
    expect(component.find('input').attributes().step).toBe('5');
  });

  it('check if the attribute tabindex of the input field is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('input').attributes().tabindex).toBe('10');
  });

  it('check if the attribute type of the input field is text', () => {
    expect(component.find('input').attributes().type).toBe('date');
  });

  it('check if class of input field is form-control if plaintext and readonly is false', () => {
    component.setProps({ plaintext: false, readonly: false });
    expect(component.find('input').classes()).toContain('form-control');
    expect(component.find('input').classes()).not.toContain('form-control-plaintext');
  });

  it('check if class of input field is form-control if plaintext is true but readonly is false', () => {
    component.setProps({ plaintext: true, readonly: false });
    expect(component.find('input').classes()).toContain('form-control');
    expect(component.find('input').classes()).not.toContain('form-control-plaintext');
  });

  it('check if class of input field is form-control if plaintext is false but readonly is true', () => {
    component.setProps({ plaintext: false, readonly: true });
    expect(component.find('input').classes()).toContain('form-control');
    expect(component.find('input').classes()).not.toContain('form-control-plaintext');
  });

  it('check if class of input field is form-control-plaintext if plaintext and readonly is true', () => {
    component.setProps({ plaintext: true, readonly: true });
    expect(component.find('input').classes()).toContain('form-control-plaintext');
    expect(component.find('input').classes()).not.toContain('form-control');
  });
});
