<template>
  <div class="align-items-center d-flex">
    <button class="btn btn-datatable_utility" @click="toggleFilters()">
      <span class="mdi mdi-filter"></span> {{ $t('component.datatableFilters.filters') }}
    </button>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, PropSync } from 'vue-property-decorator';

@Component
export default class DatatableFiltersToggle extends Vue {
  @PropSync('visible', { required: true, type: Boolean }) syncVisible!: boolean;

  toggleFilters (): void {
    this.$emit('datatable:filtersToggle', !this.syncVisible);
    this.syncVisible = !this.syncVisible;
  }
}
</script>
