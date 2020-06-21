/* eslint camelcase: 0 */

export default interface ResetPasswordModel {
  email: string,
  password: string,
  password_confirmation: string,
  token: string,
}
