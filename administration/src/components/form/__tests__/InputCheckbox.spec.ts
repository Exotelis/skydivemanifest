import { shallowMount } from '@vue/test-utils';
import InputCheckbox from '../InputCheckbox.vue';

const factory = (props = {}) => {
  return shallowMount(InputCheckbox, {
    propsData: {
      id: 'testId',
      ...props
    }
  });
};

describe('InputCheckbox.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of the checkbox input', () => {
    const wrapper: any = factory();
    expect(wrapper.props().ariaLabel).toBeNull();
    expect(wrapper.props().autocomplete).toBeNull();
    expect(wrapper.props().autofocus).toBeFalsy();
    expect(wrapper.props().checked).toBeFalsy();
    expect(wrapper.props().disabled).toBeFalsy();
    expect(wrapper.props().form).toBeNull();
    expect(wrapper.props().inputmode).toBeNull();
    expect(wrapper.props().name).toStrictEqual([]);
    expect(wrapper.props().required).toBeFalsy();
    expect(wrapper.props().spellcheck).toBe('false');
    expect(wrapper.props().tabindex).toBeNull();
    expect(wrapper.props().value).toBeNull();
  });

  it('check if handleInput gets called when checkbox is being selected', () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'handleInput');

    wrapper.setProps({ name: [], value: 'test' });
    wrapper.find('input').trigger('click');
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if the attribute aria-label of the input field is set', () => {
    const wrapper: any = factory();
    wrapper.setProps({ ariaLabel: 'TestLabel' });
    expect(wrapper.find('input').attributes('aria-label')).toBeTruthy();
  });

  it('check if the attribute autocomplete of the input field is email', () => {
    const wrapper: any = factory();
    wrapper.setProps({ autocomplete: 'email' });
    expect(wrapper.find('input').attributes().autocomplete).toBe('email');
  });

  it('check if the attribute autofocus of the input field is true', () => {
    const wrapper: any = factory();
    wrapper.setProps({ autofocus: true });
    expect(wrapper.find('input').attributes().autofocus).toBeTruthy();
  });

  it('check if the attribute disabled of the input field is true', () => {
    const wrapper: any = factory();
    wrapper.setProps({ disabled: true });
    expect(wrapper.find('input').attributes().disabled).toBeTruthy();
  });

  it('check if the attribute form of the input field is formId', () => {
    const wrapper: any = factory();
    wrapper.setProps({ form: 'formId' });
    expect(wrapper.find('input').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the input field is testId', () => {
    const wrapper: any = factory();
    expect(wrapper.find('input').attributes().id).toBe('testId');
  });

  it('check if the attribute inputmode of the input field is tel', () => {
    const wrapper: any = factory();
    wrapper.setProps({ inputmode: 'tel' });
    expect(wrapper.find('input').attributes().inputmode).toBe('tel');
  });

  it('check if the attribute name of the input field is set', () => {
    const wrapper: any = factory();
    wrapper.setProps({ name: ['test', 'otherTest'] });
    expect(wrapper.find('input').attributes().name).toBe('test,otherTest');
  });

  it('check if the attribute required of the input field is true', () => {
    const wrapper: any = factory();
    wrapper.setProps({ required: true });
    expect(wrapper.find('input').attributes().required).toBeTruthy();
  });

  it('check if the attribute spellcheck of the input field is true', () => {
    const wrapper: any = factory();
    wrapper.setProps({ spellcheck: true });
    expect(wrapper.find('input').attributes().spellcheck).toBeTruthy();
  });

  it('check if the attribute tabindex of the input field is 10', () => {
    const wrapper: any = factory();
    wrapper.setProps({ tabindex: 10 });
    expect(wrapper.find('input').attributes().tabindex).toBe('10');
  });

  it('check if the attribute value of the input field is test', () => {
    const wrapper: any = factory();
    wrapper.setProps({ value: 'test' });
    expect(wrapper.find('input').attributes().value).toBe('test');
  });

  it('check if the attribute type of the input field is checkbox', () => {
    const wrapper: any = factory();
    expect(wrapper.find('input').attributes().type).toBe('checkbox');
  });

  it('stop processing if attribute name is not an array reference', () => {
    const wrapper: any = factory();
    const arrayRef: string = '';
    wrapper.setProps({ name: arrayRef, value: 'test' });

    wrapper.vm.handleInput();
    expect(arrayRef).toBe('');
  });

  it('update the array reference when the checkbox is clicked', () => {
    const wrapper: any = factory();
    const arrayRef: Array<any> = [];
    wrapper.setProps({ name: arrayRef, value: 'test' });

    expect(arrayRef).toStrictEqual([]);
    wrapper.find('input').trigger('click');
    expect(arrayRef).toStrictEqual(['test']);
    wrapper.find('input').trigger('click');
    expect(arrayRef).toStrictEqual([]);
  });

  it('check if preselected is already checked', () => {
    const arrayRef: Array<any> = ['test'];
    const wrapper: any = factory({ name: arrayRef, value: 'test' });

    expect(wrapper.vm.checked).toBeTruthy();
  });

  it('check if not preselected is already unchecked', () => {
    const arrayRef: Array<any> = [];
    const wrapper: any = factory({ name: arrayRef, value: 'test' });

    expect(wrapper.vm.checked).toBeFalsy();
  });
});
