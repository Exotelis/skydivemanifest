<template>
  <input type="hidden"
         :form="form"
         :id="id"
         :name="id"
         :value="value">
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component({})
export default class InputHidden extends Vue {
  @Prop({ required: true }) readonly id!: string;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ default: null }) readonly value!: string;
}
</script>
