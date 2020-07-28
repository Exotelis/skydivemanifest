<template>
  <nav :aria-label="$t('component.pagination.ariaLabel')" class="align-items-center d-flex text-nowrap">
    <div class="mr-3 pagination-records" v-if="!hideRecords">
      {{ $t('component.pagination.records', { from: from ? from : 0 , to: to, total: total }) }}
    </div>

    <ul class="pagination">
      <template v-if="syncCurrent === 1">
        <li class="page-item disabled">
          <a aria-disabled="true" class="page-link" tabindex="-1">&laquo;</a>
        </li>
        <li class="page-item disabled">
          <a aria-disabled="true" class="page-link" tabindex="-1">&lsaquo;</a>
        </li>
      </template>
      <template v-else>
        <li class="page-item">
          <a class="page-link pointer text-primary" @click="changePage(1)">&laquo;</a>
        </li>
        <li class="page-item">
          <a class="page-link pointer text-primary" @click="changePage(syncCurrent - 1)">&lsaquo;</a>
        </li>
      </template>

      <li class="page-item mx-3">
        <label class="sr-only" :for="'pagination_pageSelector-' + uuid">{{ $t('component.pagination.select') }}</label>
        <select class="selection" v-model="syncCurrent" :id="'pagination_pageSelector-' + uuid">
          <option v-for="i in last" :key="i" :selected="i === syncCurrent" :value="i">{{ i }}</option>
        </select>
        <span class="ml-2">{{ $t('component.pagination.of', { last: last }) }}</span>
      </li>

      <template v-if="syncCurrent === last || last < 1">
        <li class="page-item disabled">
          <a aria-disabled="true" class="page-link" tabindex="-1">&rsaquo;</a>
        </li>
        <li class="page-item disabled">
          <a aria-disabled="true" class="page-link" tabindex="-1">&raquo;</a>
        </li>
      </template>
      <template v-else>
        <li class="page-item">
          <a class="page-link pointer text-primary" @click="changePage(syncCurrent + 1)">&rsaquo;</a>
        </li>
        <li class="page-item">
          <a class="page-link pointer text-primary" @click="changePage(last)">&raquo;</a>
        </li>
      </template>
    </ul>
  </nav>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop, PropSync, Watch } from 'vue-property-decorator';
import { uuidv4 } from '@/helpers';

@Component
export default class Pagination extends Vue {
  @Prop({ required: true }) readonly from!: null|number;
  @Prop([Boolean]) readonly hideRecords!: boolean;
  @Prop({ required: true, type: Number }) readonly last!: number;
  @Prop({ required: true }) readonly to!: null|number;
  @Prop({ required: true, type: Number }) readonly total!: number;
  @PropSync('current', { required: true, type: Number }) syncCurrent!: number;

  uuid!: string;

  created (): void {
    this.uuid = uuidv4();
    this.$on('update:current', this.changePage);
  }

  changePage (page: number): void {
    this.$emit('pagination:changed', page);
  }

  @Watch('last')
  onLastUpdate (last: number): void {
    if (this.syncCurrent > last) {
      this.syncCurrent = last;
    }
  }
}
</script>
