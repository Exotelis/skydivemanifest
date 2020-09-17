/* eslint camelcase: 0 */
import { Gender } from '@/enum/Gender';

export default interface UserModel {
  id: number
  created_at: string
  default_invoice: null|number
  default_shipping: null|number
  deleted_at: null|string
  dob: string
  email: string
  email_verified_at: null|string
  firstname: string
  gender: Gender
  is_active: boolean
  last_logged_in: null|string
  lastname: string
  locale: string
  lock_expires: null|string
  middlename: null|string
  mobile: null|string
  name: null|string
  password_change: boolean
  phone: null|string
  role: any
  updated_at: string
  username: null|string
  timezone: null|string
}
