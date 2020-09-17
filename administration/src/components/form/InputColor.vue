<template>
  <input type="color"
         @input="$emit('input', $event.target.value)"
         :autocomplete="autocomplete"
         :autofocus="autofocus"
         :class="[
           'form-control py-1',
           fieldSize ? 'form-control-' + fieldSize : ''
         ]"
         :disabled="disabled"
         :form="form"
         :id="id"
         :inputmode="inputmode"
         :list="list"
         :name="id"
         :spellcheck="spellcheck"
         :tabindex="tabindex"
         :value="value">
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import ListMixin from '@/mixins/form/ListMixin';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class InputColor extends Mixins(ListMixin, SharedMixin) {}
</script>
