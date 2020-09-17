<template>
  <div :class="['custom-control custom-checkbox', { 'custom-control-inline': inline }] ">
    <input class="custom-control-input"
           type="checkbox"
           @input="handleInput"
           :aria-label="ariaLabel"
           :autocomplete="autocomplete"
           :autofocus="autofocus"
           :checked="checked"
           :disabled="disabled"
           :form="form"
           :id="id"
           :inputmode="inputmode"
           :name="name"
           :required="required"
           :spellcheck="spellcheck"
           :tabindex="tabindex"
           :value="value">
    <label class="custom-control-label" :for="id"><slot></slot></label>
    <slot v-if="invalidFeedback" name="invalidFeedback">
      <div class="invalid-feedback">{{ invalidFeedback }}</div>
    </slot>
    <slot v-if="validFeedback && !invalidFeedback" name="validFeedback">
      <div class="valid-feedback">{{ validFeedback }}</div>
    </slot>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class InputCheckbox extends Mixins(RequiredMixin, SharedMixin) {
  @Prop({ default: null, type: String }) readonly ariaLabel!: string;
  @Prop([Boolean]) readonly inline!: boolean;
  @Prop({ default: null }) readonly invalidFeedback!: string;
  @Prop({ default: () => [], type: Array }) readonly name!: Array<any>;
  @Prop({ default: null }) readonly validFeedback!: string;

  checked: boolean = false;

  mounted (): void {
    if (this.name.includes(this.value)) {
      this.checked = true;
    }
  }

  handleInput (): void {
    let modelRef: any = this.name;

    if (!Array.isArray(modelRef)) {
      return;
    }

    let idx: number = modelRef.indexOf(this.value);

    if (idx < 0) {
      modelRef.push(this.value);
    } else {
      modelRef.splice(idx, 1);
    }
  }
}
</script>
