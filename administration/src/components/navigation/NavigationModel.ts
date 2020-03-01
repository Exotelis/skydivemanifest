import { NavigationType } from '@/components/navigation/NavigationType';

export interface NavigationModel {
  children?: Array<NavigationModel>
  icon?: string;
  path?: string;
  title?: string;
  type: NavigationType;
}
