/* eslint camelcase: 0 */
import { Gender } from '@/enum/Gender';

export default interface RegisterModel {
  dob: string
  email: string
  firstname: string
  gender?: Gender
  lastname: string
  locale?: string
  password: string
  password_confirmation: string
  timezone?: string
  username?: string
  tos: boolean
}
