<template>
  <div class="form-group" :class="{ 'required': required, 'position-relative': isToggleable }">
    <label :for="id">{{ label }}</label>
    <input class="form-control"
           @input="$emit('input', $event.target.value)"
           :autocomplete="autocomplete"
           :autofocus="autofocus"
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
           :tabindex="tabindex"
           :type="isVisible ? 'text' : 'password'"
           :value="value">
    <small v-if="description" :id="id + 'Description'" class="form-text text-muted">{{ description }}</small>
    <div v-if="errorText" class="invalid-feedback">{{ errorText }}</div>
    <span v-if="isToggleable"
          @click="isVisible = !isVisible"
          :class="['mdi', isVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline' ]"></span>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import BasicMixin from '@/mixins/BasicMixin';
import InputmodeMixin from '@/mixins/InputmodeMixin';
import ListMixin from '@/mixins/ListMixin';
import ReadonlyMixin from '@/mixins/ReadonlyMixin';
import TextMixin from '@/mixins/TextMixin';

@Component({})
export default class InputPassword extends Mixins(BasicMixin, InputmodeMixin, ListMixin, ReadonlyMixin, TextMixin) {
  @Prop({ default: false }) readonly isToggleable!: boolean;
  isVisible: boolean = false;
}
</script>
