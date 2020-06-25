import { NavigationType } from '@/enum/NavigationType';

export interface NavigationModel {
  children?: Array<NavigationModel>
  icon?: string;
  path?: string;
  title?: string;
  type: NavigationType;
}
