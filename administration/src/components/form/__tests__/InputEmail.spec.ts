import { shallowMount } from '@vue/test-utils';
import { FormFieldSize } from '@/enum/FormFieldSize';
import InputEmail from '../InputEmail.vue';

describe('InputEmail.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(InputEmail, {
      propsData: {
        id: 'testId'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the email input', () => {
    expect(component.props().autocomplete).toBeNull();
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().fieldSize).toBeNull();
    expect(component.props().form).toBeNull();
    expect(component.props().inputmode).toBeNull();
    expect(component.props().list).toBeNull();
    expect(component.props().maxlength).toBeNull();
    expect(component.props().minlength).toBeNull();
    expect(component.props().multiple).toBeFalsy();
    expect(component.props().pattern).toBeNull();
    expect(component.props().placeholder).toBeNull();
    expect(component.props().plaintext).toBeFalsy();
    expect(component.props().readonly).toBeFalsy();
    expect(component.props().required).toBeFalsy();
    expect(component.props().spellcheck).toBe('false');
    expect(component.props().tabindex).toBeNull();
    expect(component.props().value).toBeNull();
  });

  it('check if event is emitted when the input value changes', async () => {
    component.find('input').setValue('User input');
    await component.vm.$nextTick();
    expect(component.emitted().input).toBeTruthy();
  });

  it('check if the attribute autocomplete of the input field is email', () => {
    component.setProps({ autocomplete: 'email' });
    expect(component.find('input').attributes().autocomplete).toBe('email');
  });

  it('check if the attribute autofocus of the input field is true', () => {
    component.setProps({ autofocus: true });
    expect(component.find('input').attributes().autofocus).toBeTruthy();
  });

  it('check if the attribute disabled of the input field is true', () => {
    component.setProps({ disabled: true });
    expect(component.find('input').attributes().disabled).toBeTruthy();
  });

  it('check if the class \'form-control-sm\' does exist on input field', () => {
    component.setProps({ fieldSize: FormFieldSize.sm });
    expect(component.find('input').classes()).toContain('form-control-sm');
  });

  it('check if the attribute form of the input field is formId', () => {
    component.setProps({ form: 'formId' });
    expect(component.find('input').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the input field is testId', () => {
    expect(component.find('input').attributes().id).toBe('testId');
  });

  it('check if the attribute inputmode of the input field is tel', () => {
    component.setProps({ inputmode: 'tel' });
    expect(component.find('input').attributes().inputmode).toBe('tel');
  });

  it('check if the attribute list of the input field is listId', () => {
    component.setProps({ list: 'listId' });
    expect(component.find('input').attributes().list).toBe('listId');
  });

  it('check if the attribute maxlength of the input field is 10', () => {
    component.setProps({ maxlength: 10 });
    expect(component.find('input').attributes().maxlength).toBe('10');
  });

  it('check if the attribute minlength of the input field is 10', () => {
    component.setProps({ minlength: 10 });
    expect(component.find('input').attributes().minlength).toBe('10');
  });

  it('check if the attribute multiple of the input field is true', () => {
    component.setProps({ multiple: true });
    expect(component.find('input').attributes().multiple).toBeTruthy();
  });

  it('check if the attribute name of the input field is testId', () => {
    expect(component.find('input').attributes().name).toBe('testId');
  });

  it('check if the attribute pattern of the input field is ^validate$', () => {
    component.setProps({ pattern: '^validate$' });
    expect(component.find('input').attributes().pattern).toBe('^validate$');
  });

  it('check if the attribute placeholder of the input field is Placeholder', () => {
    component.setProps({ placeholder: 'Placeholder' });
    expect(component.find('input').attributes().placeholder).toBe('Placeholder');
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

  it('check if the attribute tabindex of the input field is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('input').attributes().tabindex).toBe('10');
  });

  it('check if the attribute type of the input field is text', () => {
    expect(component.find('input').attributes().type).toBe('email');
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
