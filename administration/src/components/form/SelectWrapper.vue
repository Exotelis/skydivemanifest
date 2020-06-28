<template>
  <select class="form-control"
          v-model="localValue"
          @input="handleInput"
          :autocomplete="autocomplete"
          :autofocus="autofocus"
          :disabled="disabled"
          :form="form"
          :id="id"
          :inputmode="inputmode"
          :multiple="multiple"
          :name="id"
          :required="required"
          :size="size"
          :spellcheck="spellcheck"
          :tabindex="tabindex"
          :value="value">
    <option :value="null" disabled v-if="!multiple && !initialSelection">
      <slot name="placeholder" v-if="!multiple && !initialSelection">{{ $t('form.placeholder.select') }}</slot>
    </option>
    <slot></slot>
    <template v-for="option in options">
      <option v-if="isOpt(option)"
              :disabled="option.disabled"
              :key="option.value"
              :value="option.value">{{ option.text }}</option>
      <optgroup v-if="isOptGroup(option)"
                :disabled="option.disabled"
                :key="option.value"
                :label="option.label">
        <option v-for="option in option.options"
                :disabled="option.disabled"
                :key="option.value"
                :value="option.value">{{ option.text }}</option>
      </optgroup>
    </template>
  </select>
</template>

<script lang="ts">
import { Component, Mixins, Prop, Watch } from 'vue-property-decorator';
import { OptGroupInterface as OptGroup } from '@/interfaces/OptGroupInterface';
import { OptInterface as Opt } from '@/interfaces/OptInterface';
import { Options } from '@/types/Options';
import { SelectChild } from '@/types/SelectChild';
import MultipleMixin from '@/mixins/form/MultipleMixin';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';

@Component({})
export default class SelectWrapper extends Mixins(MultipleMixin, RequiredMixin, SharedMixin) {
  @Prop({ default: null }) readonly options!: Options;
  @Prop({ default: 0 }) readonly size!: number;

  initialSelection: boolean = false;
  localValue: string|number|boolean|object|null|Array<string|number|boolean|object> = this.value;

  handleInput (event: InputEvent) {
    const { target } = event;

    if (target === null || !(target instanceof HTMLSelectElement)) {
      return;
    }
    /* istanbul ignore next */
    const selected = Array.from(target.options)
      .filter((opt: HTMLOptionElement) => opt.selected)
      .map((opt: HTMLOptionElement) => ('_value' in opt ? (opt as any)._value : opt.value));

    this.localValue = target.multiple ? selected : selected[0];
  }

  isOpt (option: SelectChild): option is Opt {
    return (option as Opt).value !== undefined;
  }

  isOptGroup (option: SelectChild): option is OptGroup {
    return (option as OptGroup).options !== undefined;
  }

  mounted () {
    // Check if an initial value is selected
    this.initialSelection = !(this.value === '' ||
      (typeof this.value === 'object' && Object.keys(this.value).length < 1));

    // If no initial value is set,
    if (!this.initialSelection && !this.multiple) {
      this.localValue = this.$el.firstChild!.nodeValue;
    }
  }

  @Watch('localValue')
  onLocalValueUpdate (val: string|number|boolean|object|null|Array<string|number|boolean|object>): void {
    this.$emit('input', this.localValue);
  }

  @Watch('value')
  onValueUpdate (val: string|number|boolean|object|null|Array<string|number|boolean|object>): void {
    this.localValue = val;
  }
}
</script>
