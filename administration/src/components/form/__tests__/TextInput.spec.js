import { shallowMount } from '@vue/test-utils';
import TextInput from '../TextInput';

describe('TextInput.vue', () => {
  let component;

  beforeEach(() => {
    component = shallowMount(TextInput, {
      propsData: {
        id: 'testId',
        label: 'Test label'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the text input', () => {
    expect(component.props().autocomplete).toBe(null);
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().description).toBe(null);
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().errorText).toBe(null);
    expect(component.props().form).toBe(null);
    expect(component.props().inputmode).toBe(null);
    expect(component.props().list).toBe(null);
    expect(component.props().maxlength).toBe(null);
    expect(component.props().minlength).toBe(null);
    expect(component.props().pattern).toBe(null);
    expect(component.props().placeholder).toBe(null);
    expect(component.props().readonly).toBeFalsy();
    expect(component.props().required).toBeFalsy();
    expect(component.props().spellcheck).toBeFalsy();
    expect(component.props().tabindex).toBe(null);
  });

  it('check if class required is not set if field is not required', () => {
    expect(component.find('.form-group').classes()).not.toContain('required');
  });

  it('check if class required is set if field is required', () => {
    component.setProps({ required: true });
    expect(component.find('.form-group').classes()).toContain('required');
  });

  it('check if the attribute for and the text of the label is set correctly', () => {
    expect(component.find('label').attributes().for).toBe('testId');
    expect(component.find('label').text()).toBe('Test label');
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

  it('not render description text without description set', () => {
    expect(component.find('#testIdDescription').exists()).toBeFalsy();
  });

  it('render description text with description set', () => {
    component.setProps({ description: 'Description message' });
    expect(component.find('#testIdDescription').exists()).toBeTruthy();
  });

  it('display the correct description text', () => {
    component.setProps({ description: 'Description message' });
    expect(component.find('#testIdDescription').text()).toBe('Description message');
  });

  it('not render error message without errorText set', () => {
    expect(component.find('.invalid-feedback').exists()).toBeFalsy();
  });

  it('render error message with errorText set', () => {
    component.setProps({ errorText: 'Validation failed' });
    expect(component.find('.invalid-feedback').exists()).toBeTruthy();
  });

  it('display the correct error text', () => {
    component.setProps({ errorText: 'Validation failed' });
    expect(component.find('.invalid-feedback').text()).toBe('Validation failed');
  });
});
