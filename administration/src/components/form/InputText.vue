<template>
  <input type="text"
         @input="$emit('input', $event.target.value)"
         :autocomplete="autocomplete"
         :autofocus="autofocus"
         :class="[ readonly && plaintext ? 'form-control-plaintext' : 'form-control' ]"
         :disabled="disabled"
         :form="form"
         :id="id"
         :inputmode="inputmode"
         :list="list"
         :maxlength="maxlength"
         :minlength="minlength"
         :name="id"
         :pattern="pattern"
         :placeholder="placeholder"
         :readonly="readonly"
         :required="required"
         :spellcheck="spellcheck"
         :tabindex="tabindex"
         :value="value">
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import ListMixin from '@/mixins/form/ListMixin';
import PatternMixin from '@/mixins/form/PatternMixin';
import ReadonlyMixin from '@/mixins/form/ReadonlyMixin';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';
import TextualMixin from '@/mixins/form/TextualMixin';

@Component({})
export default class InputText extends Mixins(ListMixin, PatternMixin, ReadonlyMixin, RequiredMixin, SharedMixin,
  TextualMixin) {}
</script>
