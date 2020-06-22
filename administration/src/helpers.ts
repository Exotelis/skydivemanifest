import UserShortModel from '@/models/UserShortModel';

export function capitalize (s: string): string {
  return s.charAt(0).toUpperCase() + s.slice(1);
}

export function getCookie (name: string): string|undefined {
  return document.cookie
    .split(';')
    .map(c => c.trim())
    .filter(cookie => {
      return cookie.substring(0, name.length + 1) === `${name}=`;
    })
    .map(cookie => {
      return decodeURIComponent(cookie.substring(name.length + 1));
    })[0] || undefined;
}

export function getUser (): UserShortModel {
  const user: any|null = localStorage.getItem('user') as any;

  if (user === null) {
    // Todo - Force logout or show toast
    throw new Error('No user object found in local storage, but it should exist. Please sign in again.');
  }

  return JSON.parse(user) as UserShortModel;
}

export function htmlEntities (str: string): string {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

export function setUserAttribute (key: any, value: any): void {
  const user: UserShortModel = getUser();

  if (key in user) {
    // @ts-ignore
    user[key] = value;
    localStorage.setItem('user', JSON.stringify(user));
  }
}
