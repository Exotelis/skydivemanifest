import { shallowMount } from '@vue/test-utils';
import { FormFieldSize } from '@/enum/FormFieldSize';
import InputColor from '../InputColor.vue';

describe('InputColor.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(InputColor, {
      propsData: {
        id: 'testId'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the color input', () => {
    expect(component.props().autocomplete).toBeNull();
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().fieldSize).toBeNull();
    expect(component.props().form).toBeNull();
    expect(component.props().inputmode).toBeNull();
    expect(component.props().list).toBeNull();
    expect(component.props().spellcheck).toBe('false');
    expect(component.props().tabindex).toBeNull();
    expect(component.props().value).toBeNull();
  });

  it('check if event is emitted when the input value changes', async () => {
    component.find('input').setValue('#ffffff');
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

  it('check if the attribute name of the input field is testId', () => {
    expect(component.find('input').attributes().name).toBe('testId');
  });

  it('check if the attribute spellcheck of the input field is true', () => {
    component.setProps({ spellcheck: true });
    expect(component.find('input').attributes().spellcheck).toBeTruthy();
  });

  it('check if the attribute tabindex of the input field is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('input').attributes().tabindex).toBe('10');
  });

  it('check if the attribute type of the input field is color', () => {
    expect(component.find('input').attributes().type).toBe('color');
  });
});
