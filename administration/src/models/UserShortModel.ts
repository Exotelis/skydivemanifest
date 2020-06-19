/* eslint camelcase: 0 */
import { Gender } from '@/enum/Gender';

export interface UserShortModel {
  email: string
  firstname: string
  gender: Gender
  id: number
  lastname: string
  locale: string
  password_change: boolean
  permissions: string[]
  timezone?: string
  username?: string
}
