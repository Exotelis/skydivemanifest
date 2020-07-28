import { config, shallowMount } from '@vue/test-utils';
import { Density } from '@/enum/Density';
import DatatableDensity from '@/components/datatable/DatatableDensity.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(DatatableDensity, {
    propsData: {
      ...props
    }
  });
};

describe('DatatableDensity.vue', () => {
  afterEach(() => {
    localStorage.clear();
  });

  it('is Vue instance', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of DatatableDensity', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.props().density).toBe(Density.m);
  });

  it('set density to small', () => {
    const wrapper: any = factory({ tableId: 'tests', density: Density.s });
    expect(wrapper.props().density).toBe(Density.s);
  });

  it('define uuid', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('not emit event on creation without local storage entry', () => {
    const wrapper: any = factory({ tableId: 'tests' });
    expect(wrapper.emitted('datatable:densityChanged')).toBeFalsy();
  });

  it('emit events on creation with local storage entry', () => {
    const obj: any = { 'density': JSON.stringify(Density.s) };
    localStorage.setItem('datatable_tests', JSON.stringify(obj));
    const wrapper: any = factory({ tableId: 'tests' });

    expect(wrapper.emitted('update:density').length).toBe(1);
    expect(wrapper.emitted('update:density')).toBeTruthy();
    expect(wrapper.emitted('update:density')[0]).toEqual(['small']);
    expect(wrapper.emitted('datatable:densityChanged').length).toBe(1);
    expect(wrapper.emitted('datatable:densityChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:densityChanged')[0]).toEqual(['small']);
  });

  it('not update when already selected', async () => {
    const wrapper: any = factory({ tableId: 'tests', density: Density.s });
    const selector = wrapper.find('#datatable_density-small-' + wrapper.vm.uuid);

    selector.trigger('click');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('update:density')).toBeFalsy();
    expect(wrapper.emitted('datatable:densityChanged')).toBeFalsy();
  });

  it('change density to small', async () => {
    const wrapper: any = factory({ tableId: 'tests' });
    const selector = wrapper.find('#datatable_density-small-' + wrapper.vm.uuid);

    selector.trigger('click');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('update:density')).toBeTruthy();
    expect(wrapper.emitted('update:density')[0]).toEqual(['small']);
    expect(wrapper.emitted('datatable:densityChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:densityChanged')[0]).toEqual(['small']);

    const storage: any = JSON.parse(localStorage.getItem('datatable_tests')!)['density'];
    expect(storage).toBe(JSON.stringify(Density.s));
  });
});
