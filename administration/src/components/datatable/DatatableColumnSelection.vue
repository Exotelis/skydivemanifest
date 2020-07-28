<template>
  <div class="align-items-center d-flex">
    <div class="dropdown">
      <button aria-expanded="false"
              aria-haspopup="true"
              class="btn btn-datatable_utility custom-icon dropdown-toggle"
              data-toggle="dropdown"
              type="button"
              data-offset="-1"
              :id="'datatable_columnSelection-' + uuid">
        <span class="mdi mdi-cog"></span> {{ $t('component.datatableColumnSelection.title') }}
      </button>
      <div class="dropdown-menu p-4" :aria-labelledby="'datatable_columnSelection-' + uuid">
        <h6 class="m-0">{{ $t('component.datatableColumnSelection.title') }}</h6>
        <div class="dropdown-divider my-3"></div>
        <i18n path="component.datatableColumnSelection.description" tag="p">
          <template v-slot:max>
            <strong>{{ max }}</strong>
          </template>
        </i18n>

        <form class="limit-height">
          <div class="form-group mb-2" v-for="(column, key) in selectionColumns" :key="key">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input"
                     type="checkbox"
                     :checked="syncVisible.includes(column.prop)"
                     :disabled="(syncVisible.length <= 1 && syncVisible.includes(column.prop))
                       || (syncVisible.length >= max && !syncVisible.includes(column.prop))"
                     :id="'datatable_columnSelection-' + column.prop + '-' + uuid"
                     :value="column.prop"
                     @change="toggleColumn($event)">
              <label class="custom-control-label pointer" :for="'datatable_columnSelection-' + column.prop + '-' + uuid">
                {{ column.label }}
              </label>
            </div>
          </div>
        </form>

        <div class="dropdown-divider my-3"></div>
        <div class="d-flex justify-content-between">
          <a @click="resetToDefaults()">{{ $t('component.datatableColumnSelection.reset') }}</a>
          <span class="datatable_columnSelection-shown ml-3 text-muted text-nowrap">{{ syncVisible.length }} / {{ max }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import _ from 'lodash';
import { Component, Mixins, Prop, PropSync } from 'vue-property-decorator';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import DatatableConfigMixin from '@/mixins/DatatableConfigMixin';
import UuidMixin from '@/mixins/UuidMixin';

@Component
export default class DatatableColumnSelection extends Mixins(DatatableConfigMixin, UuidMixin) {
  @Prop({ required: true, type: Array }) readonly columns!: Array<DatatableColumnModel>;
  @Prop({ default: 10, type: Number }) readonly max!: number;
  @PropSync('visible', { default: () => [], type: Array }) syncVisible!: Array<string>;

  default!: Array<string>;

  created (): void {
    this.default = _.cloneDeep(this.syncVisible);

    let config: any|null = this.getConfig(this.tableId, 'columnSelection');

    if (config) {
      this.syncVisible = JSON.parse(config);
      this.$emit('datatable:columnToggle', JSON.parse(config));
    }
  }

  get selectionColumns (): Array<DatatableColumnModel> {
    return this.columns.filter(column => !column.notHideable);
  }

  resetToDefaults (): void {
    this.storeConfig(this.tableId, 'columnSelection', JSON.stringify(this.default));
    this.syncVisible = _.cloneDeep(this.default);
    this.$emit('datatable:columnToggle', this.default);
  }

  toggleColumn (event: Event): void {
    let element: EventTarget|null = event.target;

    if (element === null || !(element instanceof HTMLInputElement)) {
      return;
    }

    let value: string = element.value;
    let tmp: Array<string> = _.cloneDeep(this.syncVisible);

    if (this.syncVisible.includes(value)) {
      if (this.syncVisible.length <= 1) {
        element.checked = true;
        return;
      }
      tmp.splice(tmp.indexOf(value), 1);
    } else {
      if (this.syncVisible.length >= this.max) {
        element.checked = false;
        return;
      }
      tmp.push(value);
    }

    this.syncVisible = tmp;
    this.storeConfig(this.tableId, 'columnSelection', JSON.stringify(tmp));
    this.$emit('datatable:columnToggle', tmp);
  }
}
</script>
