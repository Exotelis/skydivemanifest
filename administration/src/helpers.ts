import jwtDecode from 'jwt-decode';
import UserShortModel from '@/models/UserShortModel';
import Vue, { VNode } from 'vue';

export function apiErrorsToList (errors: object): VNode {
  const vue: Vue = new Vue();
  const li: Array<VNode> = [];
  const errorArray: Array<string> = flattenApiErrors(errors);

  for (let key in errorArray) {
    li.push(vue.$createElement('li', errorArray[key]));
  }

  return vue.$createElement('ul', { class: ['mb-0', 'mt-1'] }, li);
}

export function capitalize (s: string): string {
  return s.charAt(0).toUpperCase() + s.slice(1);
}

export function checkPermissions (a: Array<string>, b: Array<string> = getUserPermissions()): boolean {
  return a.every((permission: string) => b.includes(permission));
}

export function colorYiq (color: string): string {
  // This function is inspired by the bootstrap framework - Thank you for the great work :-)
  let rgb = hexToRGB(color);
  let yiq = ((rgb[0] * 299) + (rgb[1] * 587) + (rgb[2] * 114)) / 1000;

  if (yiq >= 150) {
    return '#212529';
  }

  return '#fff';
}

export function flattenApiErrors (errors: object): Array<string> {
  return Object.values(errors).flat();
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

export function getErrorMessage (e: any): string {
  return e.response ? e.response.data.message : e.message;
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

export function hexToRGB (hex: string): Array<number> {
  let r: any = 0;
  let g: any = 0;
  let b: any = 0;

  // 3 digit long hex (#fff)
  if (hex.length === 4) {
    r = '0x' + hex[1] + hex[1];
    g = '0x' + hex[2] + hex[2];
    b = '0x' + hex[3] + hex[3];

    // 6 digit long hex (#ffffff)
  } else if (hex.length === 7) {
    r = '0x' + hex[1] + hex[2];
    g = '0x' + hex[3] + hex[4];
    b = '0x' + hex[5] + hex[6];
  }

  return [parseInt(r), parseInt(g), parseInt(b)];
}

export function htmlEntities (str: string): string {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

export function insertAt (array: Array<any>, index: number, ...elements: any): void {
  array.splice(index, 0, ...elements);
}

export function uuidv4 (): string {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c: string) => {
    let r: number = Math.random() * 16 | 0;
    let v: any = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}
