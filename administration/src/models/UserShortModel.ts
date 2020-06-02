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

enum Gender {
  m = 'm',
  f = 'f',
  d = 'd',
  u = 'u'
}
