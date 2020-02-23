import {
  autocompleteList,
  autocompleteValidator,
  iconValidator,
  inputmodeList,
  inputmodeValidator
} from '@/validators';

describe('validators', () => {
  it('return true for any value in autocompleteList', () => {
    for (let i in autocompleteList) {
      expect(autocompleteValidator(autocompleteList[i])).toBeTruthy();
    }
  });

  it('return false if value is not in autocompleteList', () => {
    expect(autocompleteValidator('test')).toBeFalsy();
  });

  it('return true when the icon class starts with mdi-', () => {
    expect(iconValidator('mdi-test')).toBeTruthy();
  });

  it('return false when the icon class doesn\'t start with mdi-', () => {
    expect(iconValidator('test')).toBeFalsy();
  });

  it('return true for any value in inputmodeList', () => {
    for (let i in inputmodeList) {
      expect(inputmodeValidator(inputmodeList[i])).toBeTruthy();
    }
  });

  it('return false if value is not in inputmodeList', () => {
    expect(inputmodeValidator('test')).toBeFalsy();
  });
});
