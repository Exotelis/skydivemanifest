import { config, shallowMount } from '@vue/test-utils';
import { FormFieldSize } from '@/enum/FormFieldSize';
import SelectWrapper from '../SelectWrapper.vue';

config.mocks!.$t = (key: any) => key;

describe('SelectWrapper.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        options: [
          { text: 'Foo', value: 'foo' },
          { text: 'Bar', value: 'bar' },
          { text: 'Baz', value: 'baz', disabled: true },
          { label: 'Group',
            options: [
              { text: 'GroupFoo', value: 'groupfoo' },
              { text: 'GroupBar', value: 'groupbar' }
            ]
          }
        ],
        value: ''
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the select wrapper', () => {
    expect(component.props().autocomplete).toBeNull();
    expect(component.props().autofocus).toBeFalsy();
    expect(component.props().disabled).toBeFalsy();
    expect(component.props().fieldSize).toBeNull();
    expect(component.props().form).toBeNull();
    expect(component.props().inputmode).toBeNull();
    expect(component.props().multiple).toBeFalsy();
    expect(component.props().required).toBeFalsy();
    expect(component.props().size).toBe(0);
    expect(component.props().spellcheck).toBe('false');
    expect(component.props().tabindex).toBeNull();
    expect(component.props().value).toBe('');
  });

  it('check if the attribute autocomplete of the select field is email', () => {
    component.setProps({ autocomplete: 'language' });
    expect(component.find('select').attributes().autocomplete).toBe('language');
  });

  it('check if the attribute autofocus of the select field is true', () => {
    component.setProps({ autofocus: true });
    expect(component.find('select').attributes().autofocus).toBeTruthy();
  });

  it('check if the attribute disabled of the select field is true', () => {
    component.setProps({ disabled: true });
    expect(component.find('select').attributes().disabled).toBeTruthy();
  });

  it('check if the class \'form-control-sm\' does exist on select field', () => {
    component.setProps({ fieldSize: FormFieldSize.sm });
    expect(component.find('select').classes()).toContain('form-control-sm');
  });

  it('check if the attribute form of the select field is formId', () => {
    component.setProps({ form: 'formId' });
    expect(component.find('select').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the select field is testId', () => {
    expect(component.find('select').attributes().id).toBe('testId');
  });

  it('check if the attribute inputmode of the select field is tel', () => {
    component.setProps({ inputmode: 'latin' });
    expect(component.find('select').attributes().inputmode).toBe('latin');
  });

  it('check if the attribute multiple of the select field is true', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        multiple: true,
        options: [ { text: 'Foo', value: 'foo' } ],
        value: ['foo']
      }
    });
    expect(wrapper.find('select').attributes().multiple).toBeTruthy();
  });

  it('check if the attribute name of the input select is testId', () => {
    expect(component.find('select').attributes().name).toBe('testId');
  });

  it('check if the attribute required of the select field is true', () => {
    component.setProps({ required: true });
    expect(component.find('select').attributes().required).toBeTruthy();
  });

  it('check if the attribute size of the select field is 5', () => {
    component.setProps({ size: 5 });
    expect(component.find('select').attributes().size).toBe('5');
  });

  it('check if the attribute spellcheck of the select field is true', () => {
    component.setProps({ spellcheck: true });
    expect(component.find('select').attributes().spellcheck).toBeTruthy();
  });

  it('check if the attribute tabindex of the select field is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('select').attributes().tabindex).toBe('10');
  });

  it('check if initialSelection is set correctly when select is not multiple and empty', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        options: [ { text: 'Foo', value: 'foo' } ],
        value: ''
      }
    });
    expect(wrapper.html()).toContain('form.placeholder.select');
    expect(wrapper.vm.initialSelection).toBeFalsy();
  });

  it('check if initialSelection is set correctly when select is not multiple and not empty', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        options: [ { text: 'Foo', value: 'foo' } ],
        value: 'foo'
      }
    });
    expect(wrapper.html()).not
      .toContain('<option disabled="disabled" value="">form.placeholder.select</option>');
    expect(wrapper.vm.initialSelection).toBeTruthy();
  });

  it('check if initialSelection is set correctly when select is multiple and empty', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        multiple: true,
        options: [ { text: 'Foo', value: 'foo' } ],
        value: []
      }
    });
    expect(wrapper.html()).not
      .toContain('<option disabled="disabled" value="">form.placeholder.select</option>');
    expect(wrapper.vm.initialSelection).toBeFalsy();
  });

  it('check if initialSelection is set correctly when select is multiple and not empty', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        multiple: true,
        options: [ { text: 'Foo', value: 'foo' } ],
        value: ['foo']
      }
    });
    expect(wrapper.html()).not
      .toContain('<option disabled="disabled" value="">form.placeholder.select</option>');
    expect(wrapper.vm.initialSelection).toBeTruthy();
  });

  it('check if event is emitted when the select value changes', async () => {
    component.find('select').setValue(['foo', 'bar']);
    await component.vm.$nextTick();
    expect(component.emitted().input).toBeTruthy();
  });

  it('check if localValue is set to value when an option is selected', () => {
    component.vm.onValueUpdate('foo');
    expect(component.vm.localValue).toStrictEqual('foo');
  });

  it('check if localValue is set to value when a multiple options are selected', () => {
    component.vm.onValueUpdate(['foo', 'bar']);
    expect(component.vm.localValue).toStrictEqual(['foo', 'bar']);
  });

  it('check if method isOpt is return the correct value', () => {
    expect(component.vm.isOpt({ text: 'Foo', value: 'foo' })).toBeTruthy();
    expect(component.vm.isOpt({ label: 'Group',
      options: [
        { text: 'GroupFoo', value: 'groupfoo' }
      ]
    })).toBeFalsy();
  });

  it('check if method isOptGroup is return the correct value', () => {
    expect(component.vm.isOptGroup({ text: 'Foo', value: 'foo' })).toBeFalsy();
    expect(component.vm.isOptGroup({ label: 'Group',
      options: [
        { text: 'GroupFoo', value: 'groupfoo' }
      ]
    })).toBeTruthy();
  });

  it('check the correct html structure', () => {
    const structure: string[] = [
      '<option disabled="disabled" value="">form.placeholder.select</option>',
      '<option value="foo">Foo</option>',
      '<option value="bar">Bar</option>',
      '<option disabled="disabled" value="baz">Baz</option>',
      '<optgroup label="Group"><option value="groupfoo">GroupFoo</option><option value="groupbar">GroupBar</option></optgroup>'
    ];

    for (let key in Object.keys(component.vm.$el.children)) {
      expect(structure[key]).toBe(component.vm.$el.children[key].outerHTML);
    }
  });

  it('check if handleInput returns because event.target is null', () => {
    component.vm.handleInput({ target: null } as InputEvent);
    expect(component.vm.localValue).toBe(null);
  });

  it('check if handleInput sets the correct value to localValue when not multiple', () => {
    component.setValue('foo');
    component.find('select').trigger('input');
    expect(component.vm.localValue).toBe('foo');
  });

  it('check if handleInput sets the correct value to localValue when multiple', () => {
    const wrapper: any = shallowMount(SelectWrapper, {
      propsData: {
        id: 'testId',
        multiple: true,
        options: [ { text: 'Foo', value: 'foo' } ],
        value: ['foo']
      }
    });
    wrapper.find('select').trigger('input');
    expect(wrapper.vm.localValue).toStrictEqual(['foo']);
  });
});
