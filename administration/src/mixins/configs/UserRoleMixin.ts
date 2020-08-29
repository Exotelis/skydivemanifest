import Vue from 'vue';
import { Component } from 'vue-property-decorator';

import { colorYiq } from '@/helpers';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { i18n } from '@/i18n';
import { Position } from '@/enum/Position';

import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';

@Component({})
export default class UserRoleMixin extends Vue {
  columns: Array<DatatableColumnModel> = [
    {
      label: i18n.t('page.userRoles.id') as string,
      prop: 'id',
      sortable: true
    },
    {
      label: i18n.t('page.userRoles.name') as string,
      prop: 'name',
      sortable: true
    },
    {
      label: i18n.t('page.userRoles.color') as string,
      prop: 'color',
      propCustom: this.propCustomColor,
      classes: 'user-select-all',
      sortable: false
    },
    {
      label: i18n.t('page.userRoles.editable') as string,
      prop: 'editable',
      propCustom: this.propCustomIsEditable,
      alignBody: Position.center,
      alignHead: Position.center,
      sortable: true
    },
    {
      label: i18n.t('page.userRoles.deletable') as string,
      prop: 'deletable',
      propCustom: this.propCustomIsDeletable,
      alignBody: Position.center,
      alignHead: Position.center,
      sortable: true
    }
  ];

  filters: Array<DatatableExactFilter|DatatableFromToFilter> = [
    new DatatableExactFilter(
      i18n.t('page.userRoles.id') as string,
      { inputType: FilterInputTypes.text, prop: 'id' }
    ),
    new DatatableExactFilter(
      i18n.t('page.userRoles.name') as string,
      { inputType: FilterInputTypes.text, prop: 'name' }
    ),
    new DatatableExactFilter(
      i18n.t('page.userRoles.editable') as string,
      {
        inputType: FilterInputTypes.select,
        prop: 'editable',
        options: [
          { value: false, text: i18n.t('page.userRoles.protected') as string },
          { value: true, text: i18n.t('page.userRoles.notProtected') as string }
        ]
      }
    ),
    new DatatableExactFilter(
      i18n.t('page.userRoles.deletable') as string,
      {
        inputType: FilterInputTypes.select,
        prop: 'deletable',
        options: [
          { value: false, text: i18n.t('page.userRoles.protected') as string },
          { value: true, text: i18n.t('page.userRoles.notProtected') as string }
        ]
      }
    )
  ];

  propCustomColor (color: string): string {
    let fontColor: string = colorYiq(color);
    return '<span style="background-color: ' + color + ';color: ' + fontColor + '" class="badge">' + color + '</span>';
  }

  propCustomIsDeletable (deletable: boolean): string {
    return deletable
      ? '<span class="mdi mdi-lock-open-variant"></span>'
      : '<span class="mdi mdi-lock text-danger"></span>';
  }

  propCustomIsEditable (editable: boolean): string {
    return editable
      ? '<span class="mdi mdi-lock-open-variant"></span>'
      : '<span class="mdi mdi-lock text-danger"></span>';
  }
}
