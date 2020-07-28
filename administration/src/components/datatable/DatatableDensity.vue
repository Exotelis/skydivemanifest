<template>
  <div class="align-items-center d-flex">
    <div class="dropdown">
      <button aria-expanded="false"
              aria-haspopup="true"
              class="btn btn-datatable_utility custom-icon dropdown-toggle"
              data-toggle="dropdown"
              type="button"
              data-offset="-1"
              :id="'datatable_density-' + uuid">
        <span class="mdi mdi-format-line-weight"></span> {{ $t('component.datatableDensity.title') }}
      </button>
      <div class="dropdown-menu p-4" :aria-labelledby="'datatable_density-' + uuid">
        <h6 class="m-0">{{ $t('component.datatableDensity.title') }}</h6>
        <div class="dropdown-divider my-3"></div>

        <form>
          <p>
            {{ $t('component.datatableDensity.description') }}
          </p>
          <div class="form-group mb-2">
            <div class="custom-control custom-radio" v-for="d in densityList" :key="d">
              <input class="custom-control-input"
                     name="datatable_density"
                     type="radio"
                     v-model="syncDensity"
                     :value="d"
                     :id="'datatable_density-' + d + '-' + uuid">
              <label class="custom-control-label pointer" :for="'datatable_density-' + d + '-' + uuid">
                {{ $t('component.datatableDensity.' + d) }}
              </label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, PropSync } from 'vue-property-decorator';
import { Density } from '@/enum/Density';
import DatatableConfigMixin from '@/mixins/DatatableConfigMixin';
import UuidMixin from '@/mixins/UuidMixin';

@Component
export default class DatatableDensity extends Mixins(DatatableConfigMixin, UuidMixin) {
  @PropSync('density', { default: Density.m }) syncDensity!: Density;

  densityList: any = Density;

  created (): void {
    this.$on('update:density', this.onDensityChange);

    let config: any|null = this.getConfig(this.tableId, 'density');

    if (config) {
      this.syncDensity = JSON.parse(config);
    }
  }

  onDensityChange (density: Density): void {
    this.storeConfig(this.tableId, 'density', JSON.stringify(density));
    this.$emit('datatable:densityChanged', density);
  }
}
</script>
