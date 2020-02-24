import { shallowMount, config } from '@vue/test-utils';
import flushPromises from 'flush-promises';
import LanguageSelector from '../LanguageSelector.vue';

config.mocks!.$t = (key: any) => key;

const factory = (values = {}) => {
  return shallowMount(LanguageSelector, {
    mocks: {
      $route: {
        path: '/',
        name: 'mock',
        ...values
      }
    }
  });
};

describe('LanguageSelector.vue', () => {
  afterEach(() => {
    localStorage.clear();
  });

  it('is Vue instance', () => {
    const component: any = factory();
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('update localStorage', async () => {
    const component: any = factory();
    await component.vm.changeLanguage('de');
    await flushPromises();
    expect(localStorage.getItem('locale')).toBe('de');
  });

  it('be page with default title', async () => {
    const component: any = factory();
    await component.vm.changeLanguage('de');
    await flushPromises();
    expect(document.title).toBe('Skydivemanifest Administration');
  });

  it('be page with detailed title', async () => {
    const component: any = factory({ meta: { title: 'Mock' } });
    component.vm.changeLanguage('de');
    await flushPromises();
    expect(document.title).toBe('Mock | Skydivemanifest Administration');
  });
});
