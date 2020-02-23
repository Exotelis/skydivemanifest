<template>
  <div>
    {{ $t('pages.title.dashboard') }}<br>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component({})
export default class DashboardPage extends Vue {}
</script>
