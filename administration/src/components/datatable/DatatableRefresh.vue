<template>
  <div class="align-items-center d-flex">
    <button class="btn btn-datatable_utility" @click="$emit('datatable:refresh')">
      <span class="mdi mdi-reload"></span> {{ $t('component.datatableRefresh.refresh') }}
    </button>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component
export default class DatatableRefresh extends Vue {}
</script>
