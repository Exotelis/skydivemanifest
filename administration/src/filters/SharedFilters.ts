import { AxiosResponse } from 'axios';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { SelectChild } from '@/types/SelectChild';
import DatatableExactFilter from '@/filters/DatatableExactFilter';
import UserRoleService from '@/services/UserRoleService';

export async function rolesNamesFilter (title: string, prop: string): Promise<DatatableExactFilter> {
  let response: AxiosResponse = await UserRoleService.names();
  let options: Array<SelectChild> = response.data.map((el: string) => { return { text: el, value: el }; });

  return new DatatableExactFilter(
    title,
    {
      inputType: FilterInputTypes.select,
      prop: prop,
      options: options
    }
  );
}
