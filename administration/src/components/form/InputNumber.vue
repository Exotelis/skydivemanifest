<template>
  <input type="number"
         @input="$emit('input', $event.target.value)"
         :autocomplete="autocomplete"
         :autofocus="autofocus"
         :class="[
           fieldSize ? 'form-control-' + fieldSize : '',
           readonly && plaintext ? 'form-control-plaintext' : 'form-control'
         ]"
         :disabled="disabled"
         :form="form"
         :id="id"
         :inputmode="inputmode"
         :list="list"
         :max="max"
         :min="min"
         :name="id"
         :placeholder="placeholder"
         :readonly="readonly"
         :required="required"
         :spellcheck="spellcheck"
         :step="step"
         :tabindex="tabindex"
         :value="value">
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import ListMixin from '@/mixins/form/ListMixin';
import NumericMixin from '@/mixins/form/NumericMixin';
import ReadonlyMixin from '@/mixins/form/ReadonlyMixin';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class InputNumber extends Mixins(ListMixin, NumericMixin, ReadonlyMixin, RequiredMixin, SharedMixin) {
  @Prop({ default: null }) readonly placeholder!: string;
}
</script>
