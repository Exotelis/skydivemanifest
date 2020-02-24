<template>
  <div class="form-group" :class="{ 'required': required }">
    <label :for="id">{{ label }}</label>
    <input class="form-control"
           type="text"
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
           :spellcheck="spellcheck"
           :tabindex="tabindex"
           :value="value">
    <small v-if="description" :id="id + 'Description'" class="form-text text-muted">{{ description }}</small>
    <div v-if="errorText" class="invalid-feedback">{{ errorText }}</div>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import BasicMixin from '@/mixins/BasicMixin';
import InputmodeMixin from '@/mixins/InputmodeMixin';
import ListMixin from '@/mixins/ListMixin';
import ReadonlyMixin from '@/mixins/ReadonlyMixin';
import SpellcheckMixin from '@/mixins/SpellcheckMixin';
import TextMixin from '@/mixins/TextMixin';

@Component({})
export default class InputText extends Mixins(BasicMixin, InputmodeMixin, ListMixin, ReadonlyMixin, SpellcheckMixin,
  TextMixin) {}
</script>
