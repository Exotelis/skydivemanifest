export {};

declare global {
  namespace jest {
    interface Matchers<R, T = {}> {
      toContainObject<E extends object[]>(expected: object): R
    }
  }
}
