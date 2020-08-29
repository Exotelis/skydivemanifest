import UserRoleMixin from '../UserRoleMixin';

describe('UserRoleMixin.ts', () => {
  let mixin: any;

  beforeEach(() => {
    mixin = new UserRoleMixin();
  });

  it('format the color correctly', () => {
    expect(mixin.propCustomColor('#123456'))
      .toBe('<span style="background-color: #123456;color: #fff" class="badge">#123456</span>');
  });

  it('format isDisabled correctly', () => {
    expect(mixin.propCustomIsDeletable(true))
      .toBe('<span class="mdi mdi-lock-open-variant"></span>');
    expect(mixin.propCustomIsDeletable(false))
      .toBe('<span class="mdi mdi-lock text-danger"></span>');
  });

  it('format isEditable correctly', () => {
    expect(mixin.propCustomIsEditable(true))
      .toBe('<span class="mdi mdi-lock-open-variant"></span>');
    expect(mixin.propCustomIsEditable(false))
      .toBe('<span class="mdi mdi-lock text-danger"></span>');
  });
});
