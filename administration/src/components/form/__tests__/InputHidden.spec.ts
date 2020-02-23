import { shallowMount } from '@vue/test-utils';
import InputHidden from '../InputHidden.vue';

describe('InputHidden.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(InputHidden, {
      propsData: {
        id: 'testId'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the hidden input', () => {
    expect(component.props().form).toBe(null);
    expect(component.props().value).toBe(null);
  });

  it('check if the attribute form of the input field is formId', () => {
    component.setProps({ form: 'formId' });
    expect(component.find('input').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the input field is testId', () => {
    expect(component.find('input').attributes().id).toBe('testId');
  });

  it('check if the attribute name of the input field is testId', () => {
    expect(component.find('input').attributes().name).toBe('testId');
  });

  it('check if the attribute name of the input field is testId', () => {
    component.setProps({ value: 'hidden input' });
    expect(component.find('input').attributes().value).toBe('hidden input');
  });
});
