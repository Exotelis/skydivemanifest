import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { ToastPlugin } from 'bootstrap-vue';

import { colorYiq, insertAt } from '@/helpers';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { Gender } from '@/enum/Gender';
import { i18n } from '@/i18n';
import { Position } from '@/enum/Position';
import { rolesNamesFilter } from '@/filters/SharedFilters';

import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';

Vue.use(ToastPlugin);

@Component({})
export default class UserMixin extends Vue {
  columns: Array<DatatableColumnModel> = [
    {
      label: i18n.t('page.users.id') as string,
      prop: 'id',
      sortable: true
    },
    {
      label: i18n.t('page.users.lastname') as string,
      prop: 'lastname',
      sortable: true
    },
    {
      label: i18n.t('page.users.firstname') as string,
      prop: 'firstname',
      sortable: true
    },
    {
      label: i18n.t('page.users.middlename') as string,
      prop: 'middlename',
      sortable: true,
      hide: true
    },
    {
      label: i18n.t('page.users.dob') as string,
      prop: 'dob',
      propCustom: this.propCustomDob,
      alignBody: Position.right,
      alignHead: Position.right,
      sortable: true
    },
    {
      label: i18n.t('page.users.email') as string,
      prop: 'email',
      classes: 'user-select-all',
      sortable: true
    },
    {
      label: i18n.t('page.users.username') as string,
      prop: 'username',
      hide: true,
      sortable: true
    },
    {
      label: i18n.t('page.users.gender') as string,
      prop: 'gender',
      propCustom: this.propCustomGender,
      hide: true,
      sortable: true
    },
    {
      label: i18n.t('page.users.phone') as string,
      prop: 'phone',
      hide: true
    },
    {
      label: i18n.t('page.users.mobile') as string,
      prop: 'mobile',
      hide: true
    },
    {
      label: i18n.t('page.users.language') as string,
      prop: 'locale',
      propCustom: this.propCustomLocale,
      hide: true,
      sortable: true
    },
    {
      label: i18n.t('page.users.timezone') as string,
      prop: 'timezone',
      hide: true,
      sortable: true
    },
    {
      label: i18n.t('page.users.isActive') as string,
      prop: 'is_active',
      propCustom: this.propCustomIsActive,
      sortable: true
    },
    {
      label: i18n.t('page.users.emailVerified') as string,
      prop: 'email_verified_at',
      propCustom: this.propCustomEmailVerified,
      alignBody: Position.center,
      sortable: false
    },
    {
      label: i18n.t('page.users.role') as string,
      prop: 'role',
      propCustom: this.propCustomRole,
      sortable: true,
      sortKey: 'role'
    }
  ];

  filters: Array<DatatableExactFilter|DatatableFromToFilter> = [
    new DatatableExactFilter(
      i18n.t('page.users.id') as string,
      { inputType: FilterInputTypes.number, prop: 'id' }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.email') as string,
      { inputType: FilterInputTypes.email, prop: 'email' }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.lastname') as string,
      { inputType: FilterInputTypes.text, prop: 'lastname' }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.firstname') as string,
      { inputType: FilterInputTypes.text, prop: 'firstname' }
    ),
    new DatatableFromToFilter(
      i18n.t('page.users.age') as string,
      { inputType: FilterInputTypes.number, prop: 'age' },
      { inputType: FilterInputTypes.number, prop: 'age_eot' },
      { inputType: FilterInputTypes.number, prop: 'age_eyt' }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.gender') as string,
      {
        inputType: FilterInputTypes.select,
        prop: 'gender',
        options: [
          { value: Gender.d, text: i18n.t('general.gender.d') as string },
          { value: Gender.f, text: i18n.t('general.gender.f') as string },
          { value: Gender.m, text: i18n.t('general.gender.m') as string },
          { value: Gender.u, text: i18n.t('general.gender.u') as string }
        ]
      }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.isActive') as string,
      {
        inputType: FilterInputTypes.select,
        prop: 'is_active',
        options: [
          { value: true, text: i18n.t('general.enabled') as string },
          { value: false, text: i18n.t('general.disabled') as string }
        ]
      }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.emailVerified') as string,
      {
        inputType: FilterInputTypes.select,
        prop: 'email_verified',
        options: [
          { value: true, text: i18n.t('general.yes') as string },
          { value: false, text: i18n.t('general.no') as string }
        ]
      }
    ),
    new DatatableExactFilter(
      i18n.t('page.users.username') as string,
      { inputType: FilterInputTypes.text, prop: 'username' }
    )
  ];

  filtersLazyLoading: boolean = false;

  async loadAsyncFilters (): Promise<any> {
    this.filtersLazyLoading = true;

    try {
      let roleFilter: DatatableExactFilter = await rolesNamesFilter(this.$t('page.users.role') as string, 'role');

      // Insert filters
      insertAt(this.filters, 9, roleFilter);
    } catch (e) {
      this.$bvToast.toast(this.$t('component.datatableFilters.couldNotLoadMessage') as string, {
        title: this.$t('component.datatableFilters.couldNotLoad') as string,
        autoHideDelay: 5000,
        appendToast: false,
        variant: 'danger',
        solid: true
      });
    }

    this.filtersLazyLoading = false;
  }

  propCustomDob (dob: string): string {
    let birthDate: Date = new Date(dob);
    let today: Date = new Date();

    if ((birthDate.getMonth() + 1 + '-' + birthDate.getDate()) === (today.getMonth() + 1 + '-' + today.getDate())) {
      let icon: string = '<span class="mdi mdi-party-popper text-primary"></span>';
      return icon + ' ' + i18n.d(Date.parse(dob), 'date') + ' ' + icon;
    }

    return i18n.d(Date.parse(dob), 'date');
  }

  propCustomEmailVerified (verified: string|null): string {
    return verified != null
      ? '<span class="mdi mdi-check-bold text-success"></span>'
      : '<span class="mdi mdi-close-thick text-danger"></span>';
  }

  propCustomGender (gender: string): string {
    let genderString: string = i18n.t('general.gender.' + gender) as string;
    if (gender === Gender.d) {
      return '<span class="diverse mdi mdi-gender-non-binary"></span> ' + genderString;
    }
    if (gender === Gender.f) {
      return '<span class="female mdi mdi-gender-female"></span> ' + genderString;
    }
    if (gender === Gender.m) {
      return '<span class="male mdi mdi-gender-male"></span> ' + genderString;
    }

    return '';
  }

  propCustomIsActive (isActive: boolean): string {
    return isActive
      ? '<span class="mdi mdi-circle-medium text-success"></span> ' + i18n.t('general.enabled')
      : '<span class="mdi mdi-circle-medium text-danger"></span> ' + i18n.t('general.disabled');
  }

  propCustomLocale (locale: string): string {
    return i18n.t('languages.' + locale) as string;
  }

  propCustomRole ({ name, color }: any): string {
    let fontColor: string = colorYiq(`${color}`);
    return `<span style="background-color: ${color};color: ` + fontColor + `" class="badge">${name}</span>`;
  }
}
