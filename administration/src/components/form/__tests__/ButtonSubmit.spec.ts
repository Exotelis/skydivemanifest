import { shallowMount, config } from '@vue/test-utils';
import ButtonSubmit from '../ButtonSubmit.vue';

config.mocks!.$t = (key: any) => key;

describe('ButtonSubmit.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(ButtonSubmit, {
      propsData: {
        id: 'testId'
      },
      scopedSlots: {
        default: '<template>Submit</template>'
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the submit button', () => {
    expect(component.props().disabled).toBe(false);
    expect(component.props().form).toBe(null);
    expect(component.props().icon).toBe(null);
    expect(component.props().loading).toBe(false);
    expect(component.props().rightAligned).toBe(false);
    expect(component.props().tabindex).toBe(null);
  });

  it('check if the attribute class contains "float-left" and "mr-2" if button isn\'t right aligned', () => {
    expect(component.find('button').classes()).toContain('float-left');
    expect(component.find('button').classes()).toContain('mr-2');
  });

  it('check if the attribute class contains "float-right" and "ml-2" if button is right aligned', () => {
    component.setProps({ rightAligned: true });
    expect(component.find('button').classes()).toContain('float-right');
    expect(component.find('button').classes()).toContain('ml-2');
  });

  it('check if the attribute class contains the correct loading in the different states', () => {
    expect(component.find('button').classes()).not.toContain('mdi');
    component.setProps({ loading: true, icon: 'mdi-test' });
    expect(component.find('button').classes()).toContain('mdi');
    expect(component.find('button').classes()).toContain('mdi-spin');
    expect(component.find('button').classes()).toContain('mdi-loading');
    expect(component.find('button').classes()).not.toContain('mdi-test');
    component.setProps({ loading: false });
    expect(component.find('button').classes()).toContain('mdi');
    expect(component.find('button').classes()).toContain('mdi-test');
    expect(component.find('button').classes()).not.toContain('mdi-spin');
    expect(component.find('button').classes()).not.toContain('mdi-loading');
  });

  it('check if the attribute disabled of the button is true', () => {
    component.setProps({ disabled: true });
    expect(component.find('button').attributes().disabled).toBeTruthy();
  });

  it('check if the attribute disabled of the button is true if the button is in a loading stage', () => {
    component.setProps({ loading: true });
    expect(component.find('button').attributes().disabled).toBeTruthy();
  });

  it('check if the attribute form of the button is formId', () => {
    component.setProps({ form: 'formId' });
    expect(component.find('button').attributes().form).toBe('formId');
  });

  it('check if the attribute id of the button is testId', () => {
    expect(component.find('button').attributes().id).toBe('testId');
  });

  it('check if the attribute name of the button is testId', () => {
    expect(component.find('button').attributes().name).toBe('testId');
  });

  it('check if the attribute tabindex of the button is 10', () => {
    component.setProps({ tabindex: 10 });
    expect(component.find('button').attributes().tabindex).toBe('10');
  });

  it('check if the text of the button is Submit if loading is false', () => {
    expect(component.find('button').text()).toBe('Submit');
  });

  it('check if the text of the button is general.loading if loading is true', () => {
    component.setProps({ loading: true });
    expect(component.find('button').text()).toBe('general.loading');
  });
});
