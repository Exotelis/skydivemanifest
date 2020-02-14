<template>
  <input type="hidden"
         :form="form"
         :id="id"
         :name="id"
         :value="value">
</template>

<script>
export default {
  name: 'HiddenInput',
  props: {
    id: { required: true },
    form: { default: null },
    value: { default: null }
  }
};
</script>
