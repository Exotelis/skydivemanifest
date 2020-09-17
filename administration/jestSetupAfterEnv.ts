/**
 * Go to the shims.jest.d.ts file in the src folder to add the declarations
 */

expect.extend({
  toContainObject (received, argument) {
    const pass = this.equals(received,
      expect.arrayContaining([
        expect.objectContaining(argument)
      ])
    );

    if (pass) {
      return {
        message: () => (
          `expected ${this.utils.printReceived(received)} not to contain object ${this.utils.printExpected(argument)}`
        ),
        pass: true
      };
    } else {
      return {
        message: () => (
          `expected ${this.utils.printReceived(received)} to contain object ${this.utils.printExpected(argument)}`
        ),
        pass: false
      };
    }
  }
});
