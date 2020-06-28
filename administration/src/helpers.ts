import jwtDecode from 'jwt-decode';
import UserShortModel from '@/models/UserShortModel';

export function capitalize (s: string): string {
  return s.charAt(0).toUpperCase() + s.slice(1);
}

export function checkPermissions (a: Array<string>, b: Array<string> = getUserPermissions()): boolean {
  return a.some((permission: string) => b.includes(permission));
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
  const cookie: string|undefined = getCookie('XSRF-TOKEN');

  if (!cookie) {
    throw new Error('No user found. Please sign in again.');
  }

  const decryptedPayload: any = jwtDecode(cookie);
  return decryptedPayload.user;
}

export function getUserId (): number|undefined {
  const cookie: string|undefined = getCookie('XSRF-TOKEN');

  if (!cookie) {
    return;
  }

  const decryptedPayload: any = jwtDecode(cookie);
  return decryptedPayload.user.id;
}

export function getUserPermissions (): Array<string> {
  const cookie: string|undefined = getCookie('XSRF-TOKEN');

  if (!cookie) {
    return [];
  }

  const decryptedPayload: any = jwtDecode(cookie);
  return decryptedPayload.scopes;
}

export function htmlEntities (str: string): string {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}
