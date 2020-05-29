import { shallowMount } from '@vue/test-utils';
import InputPassword from '../InputPassword.vue';

describe('InputPassword.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(InputPassword, {
      propsData: {
        id: 'testId'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the password input', () => {
    expect(component.props().autocomplete).toBe(null);
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().form).toBe(null);
    expect(component.props().inputmode).toBe(null);
    expect(component.props().isToggleable).toBe(false);
    expect(component.props().list).toBe(null);
    expect(component.props().maxlength).toBe(null);
    expect(component.props().minlength).toBe(null);
    expect(component.props().pattern).toBe(null);
    expect(component.props().placeholder).toBe(null);
    expect(component.props().readonly).toBeFalsy();
    expect(component.props().required).toBeFalsy();
    expect(component.props().tabindex).toBe(null);
    expect(component.props().value).toBe(null);
  });

  it('check if password is invisible by default', () => {
    expect(component.vm.isVisible).toBeFalsy();
  });

  it('check if an element with class input-group does not exist if isToggleable is false', () => {
    expect(component.find('.input-group').exists()).toBeFalsy();
  });

  it('check if an element with class input-group exists if isToggleable is true', () => {
    component.setProps({ isToggleable: true });
    expect(component.find('.input-group').exists()).toBeTruthy();
  });

  it('check if an element with class input-group-append does not exist if isToggleable is false', () => {
    expect(component.find('.input-group-append').exists()).toBeFalsy();
  });

  it('check if an element with class input-group-append exists if isToggleable is true', () => {
    component.setProps({ isToggleable: true });
    expect(component.find('.input-group-append').exists()).toBeTruthy();
  }); // TODO

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

  it('check if the attribute tabindex of the input field is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('input').attributes().tabindex).toBe('10');
  });

  it('check if the attribute type of the input field is password', () => {
    expect(component.find('input').attributes().type).toBe('password');
  });

  it('check if the attribute type of the input field is text if the toggle icon has been clicked', () => {
    component.setData({ isVisible: true });
    expect(component.find('input').attributes().type).toBe('text');
  });

  it('not render toggle icon without isToggleable to be true', () => {
    expect(component.find('.password-toggle').exists()).toBeFalsy();
  });

  it('render toggle icon with isToggleable to be true', () => {
    component.setProps({ isToggleable: true });
    expect(component.find('.password-toggle').exists()).toBeTruthy();
  });

  it('display the password when the toggle icon is clicked', async () => {
    component.setProps({ isToggleable: true });
    const input = component.find('input');

    expect(input.attributes().type).toBe('password');
    component.find('.password-toggle').trigger('click');
    await component.vm.$nextTick();
    expect(input.attributes().type).toBe('text');
  });

  it('change the toggle icon when it is clicked', async () => {
    component.setProps({ isToggleable: true });
    const toggleIcon = component.find('.password-toggle');

    expect(toggleIcon.classes()).toContain('mdi-eye-outline');
    toggleIcon.trigger('click');
    await component.vm.$nextTick();
    expect(toggleIcon.classes()).toContain('mdi-eye-off-outline');
  });
});
