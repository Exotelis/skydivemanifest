import { shallowMount } from '@vue/test-utils';
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';

describe('LoadingSpinner.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(LoadingSpinner);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
