<template>
  <span class="d-flex justify-content-center mdi mdi-48px mdi-loading mdi-spin text-primary"></span>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component
export default class LoadingSpinner extends Vue {}
</script>
