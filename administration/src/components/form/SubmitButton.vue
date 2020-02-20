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

<script>
import mixinButton from '@/mixins/mixinButton';

export default {
  name: 'SubmitButton',
  mixins: [mixinButton]
};
</script>
