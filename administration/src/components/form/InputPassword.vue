<template>
  <div :class="{'input-group': isToggleable}">
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
    <div class="input-group-append" v-if="isToggleable">
      <span v-if="isToggleable"
            @click="isVisible = !isVisible"
            :class="['input-group-text password-toggle mdi', isVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline' ]">
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import ListMixin from '@/mixins/form/ListMixin';
import PatternMixin from '@/mixins/form/PatternMixin';
import ReadonlyMixin from '@/mixins/form/ReadonlyMixin';
import RequiredMixin from '@/mixins/form/RequiredMixin';
import SharedMixin from '@/mixins/form/SharedMixin';
import TextualMixin from '@/mixins/form/TextualMixin';

@Component({})
export default class InputPassword extends Mixins(ListMixin, PatternMixin, ReadonlyMixin, RequiredMixin, SharedMixin,
  TextualMixin) {
  @Prop([Boolean]) readonly isToggleable!: boolean;
  isVisible: boolean = false;
}
</script>
