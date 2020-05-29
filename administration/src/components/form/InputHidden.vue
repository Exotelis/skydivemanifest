<template>
  <input type="hidden"
         :form="form"
         :id="id"
         :name="id"
         :value="value">
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class InputHidden extends Mixins(SharedMixin) {}
</script>
