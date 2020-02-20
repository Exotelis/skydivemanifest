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

<script>
import mixinBasic from '@/mixins/mixinBasic';
import mixinInputmode from '@/mixins/mixinInputmode';
import mixinList from '@/mixins/mixinList';
import mixinReadonly from '@/mixins/mixinReadonly';
import mixinSpellcheck from '@/mixins/mixinSpellcheck';
import mixinText from '@/mixins/mixinText';

export default {
  name: 'TextInput',
  mixins: [mixinBasic, mixinInputmode, mixinList, mixinReadonly, mixinSpellcheck, mixinText]
};
</script>
