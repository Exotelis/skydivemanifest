import { shallowMount } from '@vue/test-utils';
import FormGroup from '../FormGroup.vue';

const factory = () => {
  return shallowMount(FormGroup, {
    slots: {
      default: '<input required>'
    }
  });
};

describe('FormGroup.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(FormGroup);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the form group', () => {
    expect(component.props().description).toBe(null);
    expect(component.props().horizontal).toBeFalsy();
    expect(component.props().invalidFeedback).toBe(null);
    expect(component.props().labelColXs).toBe(2);
    expect(component.props().labelColSm).toBe(null);
    expect(component.props().labelColMd).toBe(null);
    expect(component.props().labelColLg).toBe(null);
    expect(component.props().labelColXl).toBe(null);
    expect(component.props().labelColXxl).toBe(null);
    expect(component.props().labelColXxxl).toBe(null);
    expect(component.props().label).toBe(null);
    expect(component.props().labelFor).toBe(null);
    expect(component.props().validFeedback).toBe(null);
  });

  it('check if class required is not set if the containing form element is not required', () => {
    expect(component.find('.form-group').classes()).not.toContain('required');
  });

  it('check if class required is set if the containing form element is required', () => {
    const wrapper = factory();
    expect(wrapper.find('.form-group').classes()).toContain('required');
  });

  it('not render description text without description set', () => {
    expect(component.find('.form-text.text-muted').exists()).toBeFalsy();
  });

  it('render description text with description set', () => {
    component.setProps({ description: 'Description message' });
    expect(component.find('.form-text.text-muted').exists()).toBeTruthy();
  });

  it('display the correct description text', () => {
    component.setProps({ description: 'Description message' });
    expect(component.find('.form-text.text-muted').text()).toBe('Description message');
  });

  it('check if attribute id is missing on description when labelFor is undefined', () => {
    component.setProps({ description: 'Description message' });
    expect(component.find('.form-text.text-muted').attributes().id).toBeUndefined();
  });

  it('check if attribute id is set on description when labelFor is defined', () => {
    component.setProps({ description: 'Description message', labelFor: 'test' });
    expect(component.find('.form-text.text-muted').attributes().id).toBe('testDescription');
  });

  it('check if class row is not set if horizontal is false', () => {
    expect(component.find('.form-group').classes()).not.toContain('row');
  });

  it('check if class row is set if horizontal is true', () => {
    component.setProps({ horizontal: true });
    expect(component.find('.form-group').classes()).toContain('row');
  });

  it('check if element with class col don\'t exist when horizontal is false', () => {
    expect(component.find('.col').exists()).toBeFalsy();
  });

  it('check if element with class col exist when horizontal is true', () => {
    component.setProps({ horizontal: true });
    expect(component.find('.col').exists()).toBeTruthy();
  });

  it('not render error message without invalidFeedback set', () => {
    expect(component.find('.invalid-feedback').exists()).toBeFalsy();
  });

  it('render error message with invalidFeedback set', () => {
    component.setProps({ invalidFeedback: 'Validation failed' });
    expect(component.find('.invalid-feedback').exists()).toBeTruthy();
  });

  it('display the correct error text', () => {
    component.setProps({ invalidFeedback: 'Validation failed' });
    expect(component.find('.invalid-feedback').text()).toBe('Validation failed');
  });

  it('check if the label is missing if attribute is not set', () => {
    expect(component.find('label').exists()).toBeFalsy();
  });

  it('check if the label is present if attribute is set', () => {
    component.setProps({ label: 'Test' });
    expect(component.find('label').exists()).toBeTruthy();
    expect(component.find('label').text()).toBe('Test');
  });

  it('check if class col-form-label is not set on label if horizontal is false', () => {
    component.setProps({ label: 'Test' });
    expect(component.find('label').classes()).not.toContain('col-form-label');
  });

  it('check if class col-form-label is set on label if horizontal is true', () => {
    component.setProps({ label: 'Test', horizontal: true });
    expect(component.find('label').classes()).toContain('col-form-label');
  });

  it('check if the for attribute is not set on label if labelFor undefined', () => {
    component.setProps({ label: 'Test' });
    expect(component.find('label').attributes().for).toBeUndefined();
  });

  it('check if the attribute for is set correctly', () => {
    component.setProps({ label: 'Test', labelFor: 'test' });
    expect(component.find('label').attributes().for).toBe('test');
  });

  // TODO lavel cols kram
  it('check if class col-xs-2 is not set if horizontal is false', () => {
    component.setProps({ label: 'Test' });
    expect(component.find('label').classes()).not.toContain('col-xs-2');
  });

  it('check if class col-xs-2 is set if horizontal is true', () => {
    component.setProps({ horizontal: true, label: 'Test' });
    expect(component.find('label').classes()).toContain('col-xs-2');
  });

  it('check if all col- classes work correctly', () => {
    component.setProps({
      horizontal: true,
      label: 'Test',
      labelColXs: 6,
      labelColSm: 6,
      labelColMd: 6,
      labelColLg: 6,
      labelColXl: 6,
      labelColXxl: 6,
      labelColXxxl: 6
    });
    expect(component.find('label').classes()).toContain('col-xs-6');
    expect(component.find('label').classes()).toContain('col-sm-6');
    expect(component.find('label').classes()).toContain('col-md-6');
    expect(component.find('label').classes()).toContain('col-lg-6');
    expect(component.find('label').classes()).toContain('col-xl-6');
    expect(component.find('label').classes()).toContain('col-xxl-6');
    expect(component.find('label').classes()).toContain('col-xxxl-6');
  });

  it('not render success message without validFeedback set', () => {
    expect(component.find('.valid-feedback').exists()).toBeFalsy();
  });

  it('render success message with validFeedback set', () => {
    component.setProps({ validFeedback: 'Validation succeeded' });
    expect(component.find('.valid-feedback').exists()).toBeTruthy();
  });

  it('display the correct success text', () => {
    component.setProps({ validFeedback: 'Validation succeeded' });
    expect(component.find('.valid-feedback').text()).toBe('Validation succeeded');
  });
});
