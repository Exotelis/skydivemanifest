import { Gender } from '@/enum/Gender';

export interface UserShortModel {
  email: string
  firstname: string
  gender: Gender
  id: number
  lastname: string
  locale: string
  permissions: string[]
  timezone?: string
  username?: string
}
