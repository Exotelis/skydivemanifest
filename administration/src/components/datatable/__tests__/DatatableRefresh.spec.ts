import { config, shallowMount } from '@vue/test-utils';
import DatatableRefresh from '@/components/datatable/DatatableRefresh.vue';

config.mocks!.$t = (key: any) => key;

describe('DatatableRefresh.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(DatatableRefresh);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('emit refresh event', async () => {
    component.find('.btn-datatable_utility').trigger('click');

    component.vm.$nextTick();

    expect(component.emitted('datatable:refresh')).toBeTruthy();
  });
});
