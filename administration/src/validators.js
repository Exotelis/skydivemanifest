export const autocompleteList = ['off', 'on', 'name', 'honorific-prefix', 'given-name', 'additional-name',
  'family-name', 'honorific-suffix', 'nickname', 'email', 'username', 'new-password', 'current-password',
  'organization-title', 'organization', 'street-address', 'address-line1', 'address-line2', 'address-line3',
  'address-level4', 'address-level3', 'address-level2', 'address-level1', 'country', 'country-name', 'postal-code',
  'cc-name', 'cc-given-name', 'cc-additional-name', 'cc-family-name', 'cc-number', 'cc-exp', 'cc-exp-month',
  'cc-exp-year', 'cc-csc', 'cc-type', 'transaction-currency', 'transaction-amount', 'language', 'bday', 'bday-day',
  'bday-month', 'bday-year', 'sex', 'tel', 'url', 'photo'];

export const inputmodeList = ['verbatim', 'latin', 'latin-name', 'latin-prose', 'full-width-latin', 'kana', 'katakana',
  'numeric', 'tel', 'email', 'url'];

export function autocompleteValidator (value) {
  return autocompleteList.indexOf(value) !== -1;
}

export function iconValidator (value) {
  return value.substr(0, 4) === 'mdi-';
}

export function inputmodeValidator (value) {
  return inputmodeList.indexOf(value) !== -1;
}
