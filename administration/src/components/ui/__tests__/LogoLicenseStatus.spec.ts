import { shallowMount, config } from '@vue/test-utils';
import LogoLicenseStatus from '../LogoLicenseStatus.vue';
import { version } from '@/../package.json';

config.mocks!.$t = (key: any) => key;

describe('LogoLicenseStatus.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(LogoLicenseStatus);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('display the current version', () => {
    expect(component.vm.version).toBe(version);
  });

  it('return "app.license.active"', () => {
    component.setData({ status: 'active' });
    expect(component.vm.getLicenseStatus).toBe('app.license.active');
  });

  it('have license cirle with class active', () => {
    component.setData({ status: 'active' });
    expect(component.find('.status-cirle.active').exists()).toBeTruthy();
  });

  it('return "app.license.inactive"', () => {
    component.setData({ status: 'inactive' });
    expect(component.vm.getLicenseStatus).toBe('app.license.inactive');
  });

  it('have license cirle with class inactive', () => {
    component.setData({ status: 'inactive' });
    expect(component.find('.status-cirle.inactive').exists()).toBeTruthy();
  });

  it('return "app.license.undefined"', () => {
    expect(component.vm.getLicenseStatus).toBe('app.license.undefined');
  });

  it('have license cirle without classes active or inactive', () => {
    expect(component.find('.status-cirle.active').exists() || component.find('.status-cirle.inactive').exists())
      .toBeFalsy();
  });
});
