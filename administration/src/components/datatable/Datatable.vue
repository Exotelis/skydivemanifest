<template>
  <div :class="['datatable shadow table-responsive', loading ? 'loading' : '']">
    <table :class="[
      'table table-bordered table-hover',
      density === 'medium'? 'table-md' : '',
      density === 'small'? 'table-sm' : '',
      ]">
      <caption v-if="caption !== ''">{{ caption }}</caption>

      <colgroup>
        <col v-if="selectable" class="datatable_col-selectable">
        <col v-for="column in getVisibleColumns()"
             :class="['datatable_col-' + column.prop]"
             :key="column.prop">
        <col v-if="actions.length > 0" class="datatable_col-actions">
      </colgroup>

      <!-- Head -->
      <thead class="thead-light">
        <!-- Utility bar top -->
        <tr class="datatable_utility-area-top" v-if="!hideUtilityBarTop">
          <td class="datatable_utility-bar-top" :colspan="getColspan">
            <div :class="utilityBarTopClasses ? utilityBarTopClasses: ''">
              <slot name="utility-bar-top">
                <div class="d-flex">
                  <datatable-refresh class="border-right datatable_utility-component mr-3 p-0"
                                     @datatable:refresh="onRefresh">
                  </datatable-refresh>
                  <datatable-filters-toggle class="border-left datatable_utility-component ml-auto p-0"
                                            :visible="filtersVisible"
                                            @datatable:filtersToggle="onFiltersToggle">
                  </datatable-filters-toggle>
                  <datatable-column-selection class="border-left datatable_utility-component p-0"
                                              :columns="columns"
                                              :table-id="tableId"
                                              :visible="visibleColumns"
                                              @datatable:columnToggle="onColumnSelectionChange">
                  </datatable-column-selection>
                  <datatable-sort-mode class="border-left datatable_utility-component p-0"
                                       :mode="sortMode"
                                       :table-id="tableId"
                                       @datatable:sortModeChanged="onSortModeChange">
                  </datatable-sort-mode>
                  <datatable-density class="border-left datatable_utility-component p-0"
                                     :density="density"
                                     :table-id="tableId"
                                     @datatable:densityChanged="onDensityChange">
                  </datatable-density>
                </div>
                <div class="border-top d-flex" v-if="filtersVisible || hasActiveFilter()">
                  <datatable-filters class="datatable_utility-component"
                                     :filters="filters"
                                     :filters-visible.sync="filtersVisible"
                                     @datatable:filtersChanged="onFiltersChanged">
                  </datatable-filters>
                </div>
                <div class="border-top d-flex">
                  <datatable-actions class="datatable_utility-component mr-3"
                                     v-if="bulkActions.length > 0"
                                     :actions="bulkActions"
                                     :items="selection"
                                     :mode="actionModes.bulk">
                  </datatable-actions>
                  <datatable-rows-per-page
                    class="border-left datatable_utility-component ml-auto"
                    :current="params.limit"
                    :rows-per-page="perPage"
                    @datatable:rowsPerPageChanged="onRowsPerPageChange">
                  </datatable-rows-per-page>
                  <pagination class="border-left datatable_utility-component"
                              :current="params.page"
                              :from="response.from"
                              :last="response.last_page"
                              :to="response.to"
                              :total="response.total"
                              @pagination:changed="onPageChange">
                  </pagination>
                </div>
              </slot>
            </div>
          </td>
        </tr>

        <!-- Table header label -->
        <tr class="datatable_column-labels">
          <th class="datatable_selection"
              scope="col"
              v-if="selectable"
              @click.self="() => { $refs.selectAll.click(); }">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input"
                     id="datatable_selectAll"
                     ref="selectAll"
                     type="checkbox" v-model="selectAll">
              <label class="custom-control-label" for="datatable_selectAll"></label>
            </div>
          </th>
          <th scope="col"
              v-for="column in getVisibleColumns()"
              @click="column.sortable && !loading
                ? handleSort(column.sortKey || column.prop)
                : $event.preventDefault"
              :class="[
                'text-nowrap',
                column.alignBody ? 'text-' + column.alignBody : '',
                column.sortable ? 'datatable_sortableHeader pointer' : '',
                !loading ? '' : 'user-select-none'
              ]"
              :key="column.prop">
            <span>{{ column.label }}
              <span v-if="column.sortable"
                    :class="['datatable_sort mdi ml-1', determineSortIcon(column.sortKey || column.prop)]">
              </span>
            </span>
          </th>
          <th class="text-center text-truncate" scope="col" v-if="actions.length > 0">
            {{ $t('component.datatable.actions') }}
          </th>
        </tr>

        <!-- Loading bar -->
        <tr class="datatable_loading-bar">
          <td class="p-0 position-relative" :colspan="getColspan">
            <template v-if="loading">
              <div :class="['loading-bar position-absolute w-100', loading ? '' : 'hidden']"></div>
            </template>
          </td>
        </tr>
      </thead>

      <!-- Foot - Bottom utility bar -->
      <tfoot class="tfoot-light" v-if="!hideUtilityBarBottom">
        <tr>
          <td class="datatable_utility-bar-bottom" :colspan="getColspan">
            <div :class="utilityBarBottomClasses ? utilityBarBottomClasses: ''">
              <slot name="utility-bar-bottom">
                <datatable-rows-per-page
                  class="datatable_utility-component mr-auto"
                  :current="params.limit"
                  :rows-per-page="perPage"
                  @datatable:rowsPerPageChanged="onRowsPerPageChange">
                </datatable-rows-per-page>
                <pagination class="datatable_utility-component"
                            :current="params.page"
                            :from="response.from"
                            :last="response.last_page"
                            :to="response.to"
                            :total="response.total"
                            @pagination:changed="onPageChange">
                </pagination>
              </slot>
            </div>
          </td>
        </tr>
      </tfoot>

      <!-- Body -->
      <tbody :class="loading ? 'user-select-none' : ''">
        <template v-if="response.data === null || response.data.length === 0">
          <tr class="no-hover">
            <td :colspan="getColspan">
              <div class="text-center py-3" v-if="loading">
                {{ $t('component.datatable.loading') }}
              </div>
              <div class="align-items-center d-flex flex-column py-5" v-else>
                <img alt="no results" class="datatable_no-results" src="@/assets/images/no-results.svg">
                <div class="datatable_no-records-found h6 mt-5 mb-0 text-secondary">
                  {{ $t('component.datatable.noRecordsFound') }}
                </div>
                <div class="datatable_no-records-filter-hint mt-3 text-center" v-if="hasActiveFilter()">
                  <span>{{ $t('component.datatable.filterCombination') }}</span><br>
                  <a @click="clearParamFilters()">{{ $t('component.datatable.clearFilters') }}</a>
                </div>
                <div class="datatable_no-records-add-item-hint mt-3 text-center" v-else>
                  {{ $t('component.datatable.addItem') }}<br>
                </div>
              </div>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr v-for="(row, key) in response.data" :class="selection.includes(row) ? 'selected': ''" :key="key">
            <td class="datatable_selection" v-if="selectable" @click.self="triggerCheckbox(row)">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input"
                       type="checkbox"
                       v-model="selection"
                       :id="'datatable_select-' + key"
                       :value="row">
                <label class="custom-control-label" :for="'datatable_select-' + key"></label>
              </div>
            </td>
            <td v-for="column in getVisibleColumns()"
                :class="[column.alignBody ? 'text-' + column.alignBody : '', column.classes]"
                :key="column.prop">
              <template v-if="!loading">
                <div v-if="column.propCustom" v-html="column.propCustom(resolvePath(column.prop, row))"></div>
                <template v-else>
                  {{ resolvePath(column.prop, row) }}
                </template>
              </template>

              <template v-else>
                <div class="datatable_loadingPlaceholder"><div></div></div>
              </template>
            </td>
            <td class="align-middle p-0" v-if="actions.length > 0">
              <!-- Row actions -->
              <datatable-actions :actions="actions" :items="row"></datatable-actions>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import _ from 'lodash';
import Vue from 'vue';
import { AxiosResponse } from 'axios';
import { Component, Prop, Watch } from 'vue-property-decorator';
import { ToastPlugin } from 'bootstrap-vue';

import { ActionMode } from '@/enum/ActionMode';
import { BaseFilterInputModel } from '@/models/datatable/DatatableFilterModels';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import { DatatableDataModel } from '@/models/datatable/DatatableDataModel';
import { DatatableServiceModel } from '@/models/datatable/DatatableServiceModel';
import { Density } from '@/enum/Density';
import { SortMode } from '@/enum/SortMode';

import DatatableActions from '@/components/datatable/DatatableActions.vue';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';
import DatatableColumnSelection from '@/components/datatable/DatatableColumnSelection.vue';
import DatatableDensity from '@/components/datatable/DatatableDensity.vue';
import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFilters from '@/components/datatable/DatatableFilters.vue';
import DatatableFiltersToggle from '@/components/datatable/DatatableFiltersToggle.vue';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';
import DatatableRefresh from '@/components/datatable/DatatableRefresh.vue';
import DatatableRowsPerPage from '@/components/datatable/DatatableRowsPerPage.vue';
import DatatableSortMode from '@/components/datatable/DatatableSortMode.vue';
import Pagination from '@/components/ui/Pagination.vue';

Vue.use(ToastPlugin);

@Component({
  components: {
    DatatableActions,
    DatatableColumnSelection,
    DatatableDensity,
    DatatableFilters,
    DatatableFiltersToggle,
    DatatableRefresh,
    DatatableRowsPerPage,
    DatatableSortMode,
    Pagination
  }
})
export default class Datatable extends Vue {
  @Prop({ default: () => [], type: Array }) readonly actions!: Array<DatatableActionModel>;
  @Prop({ default: () => [], type: Array }) readonly bulkActions!: Array<DatatableActionModel>;
  @Prop({ default: '' }) readonly caption!: string;
  @Prop({ required: true }) readonly columns!: Array<DatatableColumnModel>;
  @Prop({ default: () => [], type: Array }) readonly filterConfig!: Array<DatatableBaseFilter>;
  @Prop([Boolean]) readonly hideUtilityBarBottom!: boolean;
  @Prop([Boolean]) readonly hideUtilityBarTop!: boolean;
  @Prop([Boolean]) readonly historyMode!: boolean;
  @Prop({ default: () => [10, 25, 50, 100, 250], type: Array }) readonly perPage!: Array<number>;
  @Prop([Boolean]) readonly selectable!: boolean;
  @Prop({ required: true }) readonly service!: DatatableServiceModel;
  @Prop({ required: true, type: String }) readonly tableId!: string;
  @Prop({ default: 'd-flex', type: String }) readonly utilityBarBottomClasses!: string;
  @Prop({ default: 'd-flex flex-column bg-white', type: String }) readonly utilityBarTopClasses!: string;

  actionModes: any = ActionMode;
  density: Density = Density.m;
  filters: Array<DatatableBaseFilter> = [];
  filtersVisible: boolean = false;
  loading: boolean = true;
  params: any = { limit: 50, page: 1, sort: '' };
  paramsDefault: any = Object.assign({}, this.params);
  paramsDefaultCheck: boolean = false;
  response: DatatableDataModel = {
    'current_page': 0,
    'data': null,
    'from': 0,
    'last_page': 0,
    'per_page': 0,
    'to': 0,
    'total': 0
  };
  selection: Array<object> = [];
  sortIcons: Array<string> = [
    'mdi-sort',
    'mdi-sort-reverse-variant text-primary',
    'mdi-sort-variant text-primary'
  ]; // 0 = unsorted - 1 = asc - 2 = desc
  sortMode: SortMode = SortMode.multi;
  visibleColumns: Array<string> = this.columns.filter(column => !column.hide).map(column => column.prop);

  created (): void {
    // Apply filter config
    this.filters = _.cloneDeep(this.filterConfig);

    // If history mode is enabled
    if (this.historyMode) {
      let query: any = this.fixQueryTypes(this.$router.currentRoute.query);

      if (!_.isEmpty(this.$router.currentRoute.query) && !_.isEqual(this.params, query)) {
        this.deserializeFilters();
        this.params = Object.assign({}, this.params, query);
        return;
      }
    }

    this.getData();
  }

  async getData (): Promise<any> {
    this.loading = true;
    this.selection = []; // Clear selection

    try {
      let response: AxiosResponse = await this.service(this.params);

      // Check if page > total pages - If yes, replace page in current route with last page
      if (this.params.page > response.data.last_page) {
        let params = _.cloneDeep(this.params);
        params.page = response.data.last_page;

        if (this.historyMode) {
          // Replace will trigger the $route watcher which will reload the data
          await this.$router.replace({
            path: this.$router.currentRoute.path,
            query: params
          });
        } else {
          this.params = Object.assign({}, this.params, params);
        }

        return;
      }

      this.$emit('datatable:beforeRefresh');
      this.response = response.data;
      this.$emit('datatable:refreshed');
    } catch (e) {
      // Report error
      this.$bvToast.toast(e.response.data.message, {
        appendToast: false,
        autoHideDelay: 5000,
        solid: true,
        title: this.$t('component.datatable.couldNotLoadData') as string,
        variant: 'danger'
      });
    }

    this.loading = false;
  }

  getVisibleColumns (): Array<DatatableColumnModel> {
    return this.columns.filter(column => this.visibleColumns.includes(column.prop));
  }

  resolvePath (keyPath: string, row: any): any {
    return keyPath.split('.').reduce((previous, current) => previous[current], row);
  }

  /**
   * Filtering methods
   */

  clearParamFilters (): void {
    for (let key in this.params) {
      if (key.substr(0, 6) === 'filter') {
        this.$set(this.params, key, undefined);
      }
    }
  }

  deserializeFilters (): void {
    if (_.isEmpty(this.$router.currentRoute.query)) {
      // Reset to defaults in history mode if query is empty
      this.filters = _.cloneDeep(this.filterConfig);
      return;
    }

    // Clear old filters
    for (let key in this.filters) {
      this.filters[key].clearValues();
    }

    // Deserialize
    for (let key in this.$router.currentRoute.query) {
      if (key.substr(0, 6) === 'filter') {
        const prop: string = key.substr(7, key.length - 8);

        for (let filterKey in this.filters) {
          const model: BaseFilterInputModel|undefined = this.filters[filterKey].findProp(prop);
          if (model) {
            model.value = this.$router.currentRoute.query[key] as string;
          }
        }
      }
    }

    this.filters = _.cloneDeep(this.filters);
  }

  hasActiveFilter (): boolean {
    for (let key in this.filters) {
      if (this.filters[key].hasValue()) {
        return true;
      }
    }
    return false;
  }

  serializeFilters (): void {
    this.clearParamFilters();

    for (let key in this.filters) {
      let filter: DatatableBaseFilter = this.filters[key];

      if (filter.hasValue()) {
        // Exact
        if (filter instanceof DatatableExactFilter) {
          this.$set(this.params, `filter[${filter.exact!.prop}]`, filter.getExactValue());
        }

        // FromTo
        if (filter instanceof DatatableFromToFilter) {
          if (filter.hasExactValue()) {
            this.$set(this.params, `filter[${filter.getExactProp()}]`, filter.getExactValue());
            continue;
          }
          if (filter.hasFromValue()) {
            this.$set(this.params, `filter[${filter.getFromProp()}]`, filter.getFromValue());
          }

          if (filter.hasToValue()) {
            this.$set(this.params, `filter[${filter.getToProp()}]`, filter.getToValue());
          }
        }
      }
    }
  }

  /**
   * Sorting methods
   */

  determineSortIcon (key: string): string {
    let sortFields = this.params.sort.split(',');
    if (sortFields.indexOf(key) !== -1) {
      return this.sortIcons[1];
    }

    if (sortFields.indexOf('-' + key) !== -1) {
      return this.sortIcons[2];
    }

    return this.sortIcons[0];
  }

  handleSort (key: string): void {
    // Single sort
    if (this.sortMode === SortMode.single) {
      switch (this.params.sort) {
        case key:
          this.params.sort = '-' + key;
          break;
        case '-' + key:
          this.params.sort = '';
          break;

        default:
          this.params.sort = key;
      }

      return;
    }

    // MultiSort enabled
    let sortFields = this.params.sort === '' ? [] : this.params.sort.split(',');

    if (sortFields.indexOf(key) !== -1) {
      // Sort descending
      sortFields[sortFields.indexOf(key)] = '-' + key;
    } else if (sortFields.indexOf('-' + key) !== -1) {
      // Disable sort
      sortFields.splice(sortFields.indexOf('-' + key), 1);
    } else {
      // Sort ascending
      sortFields.push(key);
    }

    this.params.sort = sortFields.join(',');
  }

  /**
   * Getter / Setter
   */

  get getColspan (): number {
    let correction = 0;

    if (this.actions.length > 0) {
      correction++;
    }

    if (this.selectable) {
      correction++;
    }

    return this.getVisibleColumns().length + correction;
  }

  get selectAll (): boolean {
    if (this.response.data === null || this.response.data.length === 0) {
      return false;
    }

    // Set indeterminate state
    (this.$el.querySelector('#datatable_selectAll')! as HTMLInputElement).indeterminate =
      this.selection.length > 0 && this.selection.length < this.response.data.length;

    return this.response.data.length === this.selection.length;
  }

  set selectAll (value: boolean) {
    const data = _.cloneDeep(this.response.data);
    if (data === null || data.length === 0) {
      return;
    }

    value ? this.selection = data : this.selection = [];
  }

  /**
   * Event listener
   */

  onColumnSelectionChange (visibleColumns: Array<string>): void {
    this.visibleColumns = visibleColumns;
  }

  onDensityChange (density: Density) {
    this.density = density;
  }

  onFiltersChanged (filters: Array<DatatableBaseFilter>) {
    this.filters = filters;
    this.serializeFilters();
  }

  onPageChange (page: number): void {
    this.params.page = page;
  }

  onFiltersToggle (visible: boolean): void {
    this.filtersVisible = visible;
  }

  onRefresh (): void {
    this.getData();
  }

  onRowsPerPageChange (limit: number): void {
    this.params.limit = limit;
  }

  onSortModeChange (mode: SortMode): void {
    this.sortMode = mode;
  }

  triggerCheckbox (row: object): void {
    const idx: number = _.findIndex(this.selection, (obj: object) => { return _.isEqual(obj, row); });
    idx !== -1 ? this.selection.splice(idx, 1) : this.selection.push(row);
  }

  /**
   * Watcher
   */

  @Watch('$route.query', { deep: true })
  queryChange (query: any): void {
    this.deserializeFilters();
    this.params = this.fixQueryTypes(query);
  }

  @Watch('params', { deep: true })
  onParamsChange (params: any): void {
    if (this.historyMode &&
      !_.isEqual(this.fixQueryTypes(this.$router.currentRoute.query), params) &&
      !this.paramsDefaultCheck
    ) {
      this.$router.push({
        path: this.$router.currentRoute.path,
        query: params
      });

      return;
    }

    // This check needs to be performed in case a user navigates back to the page without queries. Both, the queries,
    // as well as the params would be undefined. So we need to set the params to the default value instead, but this
    // will trigger the watcher again. To prevent a route change, we need to set paramsDefaultCheck to true, to prevent
    // a route change!
    if (_.isEmpty(this.params)) {
      this.params = Object.assign({}, this.paramsDefault);
      this.paramsDefaultCheck = true;
      return;
    }
    this.paramsDefaultCheck = false;

    this.getData();
  }

  @Watch('selection', { deep: true })
  onSelectionChange (selection: Array<object>) {
    this.$emit('datatable:selection', selection);
  }

  /**
   * In the history mode the url needs to be changed. When reloading the page or using the back or forward button of the
   * browser, the datatable loads the query from the vue router. However, the params are always stored as strings, but
   * in some cases we need other data types. Here we can convert the types.
   */
  fixQueryTypes (query: object): any {
    let tmp: any = _.cloneDeep(query);

    if (tmp.limit) {
      tmp.limit = parseInt(tmp.limit);
    }
    if (tmp.page) {
      tmp.page = parseInt(tmp.page);
    }

    return tmp;
  }
}
</script>
