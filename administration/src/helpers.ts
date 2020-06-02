import { UserShortModel } from '@/models/UserShortModel';

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
