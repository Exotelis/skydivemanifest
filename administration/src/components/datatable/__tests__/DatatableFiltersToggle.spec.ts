import { config, shallowMount } from '@vue/test-utils';
import DatatableFiltersToggle from '@/components/datatable/DatatableFiltersToggle.vue';

config.mocks!.$t = (key: any) => key;

describe('DatatableFiltersToggle.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(DatatableFiltersToggle, {
      propsData: {
        visible: true
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('emit toggle event', async () => {
    component.find('.btn-datatable_utility').trigger('click');

    component.vm.$nextTick();

    expect(component.emitted('datatable:filtersToggle')).toBeTruthy();
    expect(component.emitted('datatable:filtersToggle')[0]).toEqual([false]);
    expect(component.emitted('update:visible')).toBeTruthy();
    expect(component.emitted('update:visible')[0]).toEqual([false]);
  });
});
