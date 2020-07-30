import { config } from '@vue/test-utils';
import UserMixin from '../UserMixin';

jest.mock('@/services/UserRoleService');

config.mocks!.$t = (key: any) => key;

describe('UserMixin.ts', () => {
  let mixin: any;

  beforeEach(() => {
    mixin = new UserMixin();
    mixin.$t = (key: any) => key;
  });

  it('format the birth date correctly', () => {
    expect(mixin.propCustomDob('2018-10-23')).toContain('10/23/2018');

    const day: string = String((new Date()).getDate()).padStart(2, '0');
    const month: string = String((new Date()).getMonth() + 1).padStart(2, '0');
    expect(mixin.propCustomDob('2000-' + month + '-' + day))
      .toBe('<span class="mdi mdi-party-popper text-primary"></span> ' + month + '/' + day +
        '/2000 <span class="mdi mdi-party-popper text-primary"></span>');
  });

  it('format email verified correctly', () => {
    expect(mixin.propCustomEmailVerified('2020-05-01 14:42:36'))
      .toBe('<span class="mdi mdi-check-bold text-success"></span>');
    expect(mixin.propCustomEmailVerified(null))
      .toBe('<span class="mdi mdi-close-thick text-danger"></span>');
  });

  it('format the gender correctly', () => {
    expect(mixin.propCustomGender('d'))
      .toBe('<span class="diverse mdi mdi-gender-non-binary"></span> diverse');
    expect(mixin.propCustomGender('f'))
      .toBe('<span class="female mdi mdi-gender-female"></span> female');
    expect(mixin.propCustomGender('m'))
      .toBe('<span class="male mdi mdi-gender-male"></span> male');
    expect(mixin.propCustomGender('u'))
      .toBe('');
  });

  it('format is active correctly', () => {
    expect(mixin.propCustomIsActive(true))
      .toBe('<span class="mdi mdi-circle-medium text-success"></span> Enabled');
    expect(mixin.propCustomIsActive(false))
      .toBe('<span class="mdi mdi-circle-medium text-danger"></span> Disabled');
  });

  it('format the locale correctly', () => {
    expect(mixin.propCustomLocale('en'))
      .toBe('english');
    expect(mixin.propCustomLocale('de'))
      .toBe('german');
  });

  it('format the role correctly', () => {
    expect(mixin.propCustomRole({ name: 'Admin', color: '#000000' }))
      .toBe('<span style="background-color: #000000;color: #fff" class="badge">Admin</span>');
  });
});
