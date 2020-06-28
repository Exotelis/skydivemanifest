<template>
  <b-button :block="block"
            :class="[rightAligned ? 'float-right ml-2': 'mr-2',
                     loading ? 'mdi mdi-spin mdi-loading': icon ? 'mdi ' + icon : '']"
            :disabled="loading ? true : disabled"
            :form="form"
            :id="id"
            :name="id"
            :tabindex="tabindex"
            :type="type"
            :variant="variant">
    <slot v-if="!loading"></slot>
    <template v-if="loading">{{ $t('general.loading') }}</template>
  </b-button>
</template>

<script lang="ts">
import Vue from 'vue';
import ButtonMixin from '@/mixins/ButtonMixin';
import { Component, Mixins } from 'vue-property-decorator';
import { ButtonPlugin } from 'bootstrap-vue';

Vue.use(ButtonPlugin);

@Component({})
export default class ButtonWrapper extends Mixins(ButtonMixin) {}
</script>
