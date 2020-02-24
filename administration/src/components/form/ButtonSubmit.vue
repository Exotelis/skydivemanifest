<template>
  <button type="submit"
          :class="['btn btn-primary', rightAligned ? 'float-right ml-2': 'float-left mr-2',
                   loading ? 'mdi mdi-spin mdi-loading': icon ? 'mdi ' + icon : '']"
          :disabled="loading ? true : disabled"
          :form="form"
          :id="id"
          :name="id"
          :tabindex="tabindex">
    <slot v-if="!loading"></slot>
    <template v-if="loading">{{ $t('general.loading') }}</template>
  </button>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import ButtonMixin from '@/mixins/ButtonMixin';

@Component({})
export default class ButtonSubmit extends Mixins(ButtonMixin) {}
</script>
