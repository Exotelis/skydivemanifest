/* eslint camelcase: 0 */

export default interface RoleModel {
  id?: number
  color?: string
  created_at?: string
  deletable?: boolean
  editable?: boolean
  name?: string
  permissions?: Array<string>
  updated_at?: string
}
