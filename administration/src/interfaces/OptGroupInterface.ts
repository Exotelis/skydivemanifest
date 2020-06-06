import { OptInterface as Opt } from '@/interfaces/OptInterface';

export interface OptGroupInterface {
  disabled?: boolean;
  label: string;
  options: Opt[];
}
