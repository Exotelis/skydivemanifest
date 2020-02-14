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
import mixinBasic from '@/components/form/mixinBasic';
import mixinInputmode from '@/components/form/mixinInputmode';
import mixinList from '@/components/form/mixinList';
import mixinReadonly from '@/components/form/mixinReadonly';
import mixinSpellcheck from '@/components/form/mixinSpellcheck';
import mixinText from '@/components/form/mixinText';

export default {
  name: 'TextInput',
  mixins: [mixinBasic, mixinInputmode, mixinList, mixinReadonly, mixinSpellcheck, mixinText]
};
</script>
