<template>
  <main id="welcome">
    <slot><router-view></router-view></slot>
  </main>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component({})
export default class WelcomeLayout extends Vue {}
</script>
