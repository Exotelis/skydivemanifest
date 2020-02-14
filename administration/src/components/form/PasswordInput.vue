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

<script>
import mixinBasic from '@/components/form/mixinBasic';
import mixinInputmode from '@/components/form/mixinInputmode';
import mixinList from '@/components/form/mixinList';
import mixinReadonly from '@/components/form/mixinReadonly';
import mixinText from '@/components/form/mixinText';

export default {
  name: 'PasswordInput',
  data: function () {
    return {
      isVisible: false
    };
  },
  props: {
    isToggleable: { default: false, type: Boolean }
  },
  mixins: [mixinBasic, mixinInputmode, mixinList, mixinReadonly, mixinText]
};
</script>
