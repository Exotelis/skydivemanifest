<template>
  <div class="align-items-center d-flex text-nowrap">
    <label class="mb-0 mr-2" :for="'datatable_rowsPerPage-' + uuid">
      {{ $t('component.datatableRowsPerPage.rowsPerPage') }}
    </label>
    <select class="datatable_rowsPerPage" v-model="syncCurrent" :id="'datatable_rowsPerPage-' + uuid">
      <option v-for="number in rowsPerPage" :key="number" :selected="number === syncCurrent" :value="number">
        {{ number }}
      </option>
    </select>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop, PropSync } from 'vue-property-decorator';
import UuidMixin from '@/mixins/UuidMixin';

@Component
export default class DatatableRowsPerPage extends Mixins(UuidMixin) {
  @Prop({ required: true, type: Array }) readonly rowsPerPage!: Array<number>;
  @PropSync('current', { required: true, type: Number }) syncCurrent!: number;

  created (): void {
    this.$on('update:current', this.changePerRow);

    // Check if current is in rowsPerPage. If yes, determine new value
    if (this.rowsPerPage.indexOf(this.syncCurrent) === -1) {
      this.syncCurrent = this.determineNewCurrent(this.syncCurrent);
    }
  }

  changePerRow (limit: number): void {
    this.$emit('datatable:rowsPerPageChanged', limit);
  }

  determineNewCurrent (current: number): number {
    const min = Math.min(...this.rowsPerPage);
    const max = Math.max(...this.rowsPerPage);

    if (current < min) {
      return min;
    }

    if (current > max) {
      return max;
    }

    return this.rowsPerPage.reduce((a: number, b: number): number => {
      return Math.abs(b - current) < Math.abs(a - current) ? b : a;
    });
  }
}
</script>
