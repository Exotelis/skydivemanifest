export const autocompleteList: Array<string> = ['off', 'on', 'name', 'honorific-prefix', 'given-name',
  'additional-name', 'family-name', 'honorific-suffix', 'nickname', 'email', 'username', 'new-password',
  'current-password', 'organization-title', 'organization', 'street-address', 'address-line1', 'address-line2',
  'address-line3', 'address-level4', 'address-level3', 'address-level2', 'address-level1', 'country', 'country-name',
  'postal-code', 'cc-name', 'cc-given-name', 'cc-additional-name', 'cc-family-name', 'cc-number', 'cc-exp',
  'cc-exp-month', 'cc-exp-year', 'cc-csc', 'cc-type', 'transaction-currency', 'transaction-amount', 'language', 'bday',
  'bday-day', 'bday-month', 'bday-year', 'sex', 'tel', 'url', 'photo'];

export const inputmodeList: Array<string> = ['verbatim', 'latin', 'latin-name', 'latin-prose', 'full-width-latin',
  'kana', 'katakana', 'numeric', 'tel', 'email', 'url'];

export function autocompleteValidator (value: string): boolean {
  return autocompleteList.includes(value);
}

export function iconValidator (value: string): boolean {
  return value.substr(0, 4) === 'mdi-';
}

export function inputmodeValidator (value: string): boolean {
  return inputmodeList.includes(value);
}

export function maxMinLengthValidator (value: number): boolean {
  return value >= 0;
}

export function spellcheckValidator (value: string): boolean {
  return ['true', 'false', ''].includes(value);
}
