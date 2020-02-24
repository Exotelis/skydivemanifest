import VueI18n from 'vue-i18n';
import { defaultLanguage, i18n, loadLanguageAsync } from '@/i18n';

describe('i18n', () => {
  it('i18n is instance of VueI18n', () => {
    expect(i18n).toBeInstanceOf(VueI18n);
  });

  it('assign "en" as default language', () => {
    expect(defaultLanguage).toBe('en');
  });

  it('throw "invalid language identifier" error', () => {
    expect(() => {
      loadLanguageAsync('notvalid');
    }).toThrowError('Invalid language identifier');
  });

  it('load selected language and return the correct language identifier', async () => {
    const lang = await loadLanguageAsync('de');
    expect(i18n.availableLocales).toContain('de');
    expect(lang).toBe('de');
  });

  it('return already loaded language identifier', async () => {
    await loadLanguageAsync('de');
    const lang = await loadLanguageAsync('en');
    expect(lang).toBe('en');
  });

  it('set correct locale and update html lang', async () => {
    await loadLanguageAsync('de');
    expect(i18n.locale).toBe('de');
    expect(document.documentElement.getAttribute('lang')).toBe('de');
  });

  it('throw "language not found" error', async () => {
    try {
      await loadLanguageAsync('en-us');
      expect(true).toBeFalsy(); // Fail if above expression doesn't throw anything.
    } catch (e) {
      expect(e.message).toBe('Language "en-us" could not be loaded, use fallback instead');
    }
  });
});
