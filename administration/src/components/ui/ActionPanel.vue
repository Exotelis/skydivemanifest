<template>
  <div class="action-panel border-bottom mb-3 mt-n3 mx-n3 px-3 py-2"
       v-if="this.$slots.default || backButton">
    <div :class="['d-flex flex-wrap justify-content-end', { 'container': container }]">
      <button class="btn btn-link mdi mdi-arrow-left text-secondary"
              type="button"
              v-if="backButton"
              @click="backPath === null ? $router.go(-1) : $router.push({ path: backPath })">
        {{ $t('general.back') }}
      </button>
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component
export default class UsersPage extends Vue {
  @Prop([Boolean]) readonly backButton!: boolean;
  @Prop({ default: null }) readonly backPath!: null|string;
  @Prop([Boolean]) readonly container!: boolean;
}
</script>
