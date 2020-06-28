<template>
  <input type="date"
         @input="$emit('input', $event.target.value)"
         :autocomplete="autocomplete"
         :autofocus="autofocus"
         :class="[ readonly && plaintext ? 'form-control-plaintext' : 'form-control' ]"
         :disabled="disabled"
         :form="form"
         :id="id"
         :inputmode="inputmode"
         :list="list"
         :max="max"
         :min="min"
         :name="id"
         :readonly="readonly"
         :required="required"
         :spellcheck="spellcheck"
         :step="step"
         :tabindex="tabindex"
         :value="value">
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import ListMixin from '@/mixins/form/ListMixin';
import NumericMixin from '@/mixins/form/NumericMixin';
import ReadonlyMixin from '@/mixins/form/ReadonlyMixin';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class InputDate extends Mixins(ListMixin, NumericMixin, ReadonlyMixin, RequiredMixin, SharedMixin) {}
</script>
