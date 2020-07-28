<template>
  <div class="d-flex datatable_filters flex-column p-0 w-100">
    <!-- Filter form -->
    <form class="datatable_utility-component"
          novalidate
          v-if="syncFiltersVisible"
          v-validate
          @submit.prevent="applyFilters()">
      <div class="d-flex flex-wrap">
        <fieldset class="flex-shrink-1" v-for="(filter, key) in filterModel" :key="key">
          <legend>{{ filter.label }}</legend>

          <!-- Exact filter -->
          <template v-if="filter instanceof exactFilter">
            <form-group class="datatable_filters-exact-formGroup"
                        hide-label
                        :invalid-feedback="errors['datatable_filters-exact-' + filter.getExactProp() + '-' + uuid]"
                        :label="filter.getLabel()"
                        :label-for="'datatable_filters-exact-' + filter.getExactProp() + '-' + uuid">
              <template v-if="filter.exact.inputType === filterInputTypes.select">
                <select-wrapper :field-size="formFieldSize.sm"
                                v-model="filter.exact.value"
                                :id="'datatable_filters-exact-' + filter.getExactProp() + '-' + uuid"
                                :options="filter.exact.options"></select-wrapper>
              </template>
              <template v-else>
                <component v-model.trim="filter.exact.value"
                           :field-size="formFieldSize.sm"
                           :id="'datatable_filters-exact-' + filter.getExactProp() + '-' + uuid"
                           :is="'input-' + filter.exact.inputType">
                </component>
              </template>
            </form-group>
          </template>

          <!-- fromTo filter -->
          <template v-if="filter instanceof fromToFilter">
            <form-group class="datatable_filters-exact-formGroup"
                        horizontal
                        label-col-xxs="3"
                        v-if="filter.exact"
                        :invalid-feedback="errors['datatable_filters-exact-' + filter.getExactProp() + '-' + uuid]"
                        :label="filter.hasExactLabel() ?
                          filter.getExactLabel() :
                          $t('component.datatableFilters.exact')"
                        :label-col-xs="null"
                        :label-for="'datatable_filters-exact-' + filter.getExactProp() + '-' + uuid"
                        :label-size="formFieldSize.sm">
              <component v-model.trim="filter.exact.value"
                         :field-size="formFieldSize.sm"
                         :id="'datatable_filters-exact-' + filter.getExactProp() + '-' + uuid"
                         :is="'input-' + filter.exact.inputType">
              </component>
            </form-group>
            <form-group class="datatable_filters-from-formGroup"
                        horizontal
                        label-col-xxs="3"
                        v-if="filter.from"
                        :invalid-feedback="errors['datatable_filters-from-' + filter.getFromProp() + '-' + uuid]"
                        :label="filter.hasFromLabel() ? filter.getFromLabel() : $t('component.datatableFilters.from')"
                        :label-col-xs="null"
                        :label-for="'datatable_filters-from-' + filter.getFromProp() + '-' + uuid"
                        :label-size="formFieldSize.sm">
              <component v-model.trim="filter.from.value"
                         :field-size="formFieldSize.sm"
                         :id="'datatable_filters-from-' + filter.getFromProp() + '-' + uuid"
                         :is="'input-' + filter.from.inputType">
              </component>
            </form-group>
            <form-group class="datatable_filters-to-formGroup"
                        horizontal
                        label-col-xxs="3"
                        v-if="filter.to"
                        :invalid-feedback="errors['datatable_filters-to-' + filter.getToProp() + '-' + uuid]"
                        :label="filter.hasToLabel() ? filter.getToLabel() : $t('component.datatableFilters.to')"
                        :label-col-xs="null"
                        :label-for="'datatable_filters-to-' + filter.getToProp() + '-' + uuid"
                        :label-size="formFieldSize.sm">
              <component v-model.trim="filter.to.value"
                         :field-size="formFieldSize.sm"
                         :id="'datatable_filters-to-' + filter.getToProp() + '-' + uuid"
                         :is="'input-' + filter.to.inputType">
              </component>
            </form-group>
          </template>
        </fieldset>
      </div>

      <!-- Filter buttons -->
      <div>
        <button-wrapper right-aligned
                        type="submit"
                        :button-size="formFieldSize.sm"
                        :id="'datatable_filters-apply-' + uuid">
          {{ $t('component.datatableFilters.applyFilters') }}
        </button-wrapper>
        <button-wrapper right-aligned
                        variant="link"
                        :button-size="formFieldSize.sm"
                        :id="'datatable_filters-cancel-' + uuid"
                        @click.native="syncFiltersVisible = false">
          {{ $t('component.datatableFilters.cancel') }}
        </button-wrapper>
      </div>
    </form>

    <!-- Active filters-->
    <div :class="['align-items-center d-flex datatable_utility-component', syncFiltersVisible ? 'border-top' : '']"
         v-if="hasActiveFilter()">
      <span class="mr-3 text-nowrap">{{ $t('component.datatableFilters.activeFilters') }}:</span>
      <div>
        <template v-for="(filter, key) in filters">
          <span class="badge badge-secondary mr-2 pointer"
                v-if="filter.hasValue()"
                :key="key"
                @click="clearFilter(key)">
            {{ filter.label }}:
            <template v-if="filter.hasExactValue()">
              <template v-if="filter.exact.inputType === filterInputTypes.select">
                {{ $t('component.datatableFilters.select.' + filter.getExactProp() + '.' + filter.getExactValue()) }}
              </template>
              <template v-else>
              {{ filter.getExactValue() }}
              </template>
            </template>
            <template v-else-if="filter instanceof fromToFilter && filter.hasFromToValues()">
              {{ filter.getFromValue() }} - {{ filter.getToValue() }}
            </template>
            <template v-else-if="filter instanceof fromToFilter && filter.hasFromValue()">
              {{ filter.getFromValue() }} - ...
            </template>
            <template v-else-if="filter instanceof fromToFilter && filter.hasToValue()">
              ... - {{ filter.getToValue() }}
            </template>
            <i class="mdi mdi-close ml-1"></i>
          </span>
        </template>
      </div>
      <a class="ml-auto text-nowrap" @click="clearFilters()">{{ $t('component.datatableFilters.clearFilters') }}</a>
    </div>
  </div>
</template>

<script lang="ts">
import _ from 'lodash';
import { Component, Mixins, Prop, PropSync, Watch } from 'vue-property-decorator';

import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { FormFieldSize } from '@/enum/FormFieldSize';

import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';
import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';
import FormGroup from '@/components/form/FormGroup.vue';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputDate from '@/components/form/InputDate.vue';
import InputEmail from '@/components/form/InputEmail.vue';
import InputNumber from '@/components/form/InputNumber.vue';
import InputText from '@/components/form/InputText.vue';
import SelectWrapper from '@/components/form/SelectWrapper.vue';
import UuidMixin from '../../mixins/UuidMixin';

@Component({
  components: { ButtonWrapper, FormGroup, InputDate, InputEmail, InputNumber, InputText, SelectWrapper }
})
export default class DatatableFilters extends Mixins(FormValidationMixin, UuidMixin) {
  @Prop({ default: () => [], type: Array }) filters!: Array<DatatableBaseFilter>;
  @PropSync('filtersVisible', { default: false, type: Boolean }) syncFiltersVisible!: boolean;

  filterModel!: Array<DatatableBaseFilter>;

  exactFilter = DatatableExactFilter;
  filterInputTypes = FilterInputTypes;
  formFieldSize = FormFieldSize;
  fromToFilter = DatatableFromToFilter;

  applyFilters (): void {
    // Clear from and to if exact is defined
    for (let key in this.filterModel) {
      let filter: DatatableBaseFilter = this.filterModel[key];
      if (filter instanceof DatatableFromToFilter &&
        filter.hasExactValue() &&
        (filter.hasFromValue() || filter.hasToValue())
      ) {
        filter.clearFromToValues();
      }
    }

    // Emit event and hide filter options
    this.emitEvent();
    this.syncFiltersVisible = false;
  }

  clearFilter (key: number): void {
    this.filterModel[key].clearValues();
    this.emitEvent();
  }

  clearFilters (): void {
    for (let key in this.filterModel) {
      let filter: DatatableBaseFilter = this.filterModel[key];
      filter.clearValues();
    }
    this.emitEvent();
  }

  created () {
    // Create clone for filter model
    this.filterModel = _.cloneDeep(this.filters);
  }

  emitEvent (): void {
    this.$emit('datatable:filtersChanged', _.cloneDeep(this.filterModel));
  }

  hasActiveFilter (): boolean {
    for (let key in this.filters) {
      if (this.filters[key].hasValue()) {
        return true;
      }
    }

    return false;
  }

  /**
   * Watcher
   */

  @Watch('filters', { deep: true })
  onFiltersUpdate (): void {
    this.filterModel = _.cloneDeep(this.filters);
  }
}
</script>
