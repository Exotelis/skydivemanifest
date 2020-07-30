<template>
  <div class="align-items-center d-flex">
    <div class="dropdown">
      <button aria-expanded="false"
              aria-haspopup="true"
              class="btn btn-datatable_utility custom-icon dropdown-toggle"
              data-toggle="dropdown"
              type="button"
              data-offset="-1"
              :id="'datatable_sortMode-' + uuid">
        <span class="mdi mdi-sort"></span> {{ $t('component.datatableSortMode.title') }}
      </button>
      <div class="dropdown-menu p-4" :aria-labelledby="'datatable_sortMode-' + uuid">
        <h6 class="m-0">{{ $t('component.datatableSortMode.title') }}</h6>
        <div class="dropdown-divider my-3"></div>

        <form>
          <p>
            {{ $t('component.datatableSortMode.description') }}
          </p>
          <div class="form-group mb-2">
            <div class="custom-control custom-radio">
              <input class="custom-control-input"
                     name="datatable_sortMode"
                     type="radio"
                     v-model="syncMode"
                     value="single"
                     :id="'datatable_sortMode-single-' + uuid">
              <label class="custom-control-label pointer" :for="'datatable_sortMode-single-' + uuid">
                {{ $t('component.datatableSortMode.single') }}
              </label>
            </div>
            <div class="custom-control custom-radio">
              <input class="custom-control-input"
                     name="datatable_sortMode"
                     type="radio"
                     value="multi"
                     v-model="syncMode"
                     :id="'datatable_sortMode-multi-' + uuid">
              <label class="custom-control-label pointer" :for="'datatable_sortMode-multi-' + uuid">
                {{ $t('component.datatableSortMode.multi') }}
              </label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop, PropSync } from 'vue-property-decorator';
import { SortMode } from '@/enum/SortMode';
import DatatableConfigMixin from '@/mixins/DatatableConfigMixin';
import UuidMixin from '@/mixins/UuidMixin';

@Component
export default class DatatableSortMode extends Mixins(DatatableConfigMixin, UuidMixin) {
  @PropSync('mode', { default: SortMode.multi }) syncMode!: SortMode;

  created (): void {
    this.$on('update:mode', this.onModeChange);

    let config: any|null = this.getConfig(this.tableId, 'sortMode');

    if (config) {
      this.syncMode = JSON.parse(config);
    }
  }

  onModeChange (mode: SortMode): void {
    this.storeConfig(this.tableId, 'sortMode', JSON.stringify(mode));
    this.$emit('datatable:sortModeChanged', mode);
  }
}
</script>
