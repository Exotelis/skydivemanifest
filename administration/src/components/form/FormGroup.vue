<template>
  <div class="form-group" :class="{ 'required': required, 'row': horizontal }">
    <slot v-if="label" name="label">
      <label :for="labelFor ? labelFor : null"
             :class="[horizontal ? [
               'col-form-label',
               labelColXxs ? 'col-' + labelColXxs : '',
               labelColXs ? 'col-xs-' + labelColXs : '',
               labelColSm ? 'col-sm-' + labelColSm : '',
               labelColMd ? 'col-md-' + labelColMd : '',
               labelColLg ? 'col-lg-' + labelColLg : '',
               labelColXl ? 'col-xl-' + labelColXl : '',
               labelColXxl ? 'col-xxl-' + labelColXxl : '',
               labelColXxxl ? 'col-xxxl-' + labelColXxxl : '',
               labelSize ? 'col-form-label-' + labelSize : '',
               ] : '', { 'sr-only': hideLabel }]">{{ label }}</label>
    </slot>

    <template v-if="horizontal">
      <div class="col">
        <slot></slot>

        <slot v-if="description" name="description">
          <small :id="labelFor ? labelFor + 'Description' : null" class="form-text text-muted">{{ description }}</small>
        </slot>
        <slot v-if="invalidFeedback" name="invalidFeedback">
          <div class="invalid-feedback">{{ invalidFeedback }}</div>
        </slot>
        <slot v-if="validFeedback" name="validFeedback">
          <div class="valid-feedback">{{ validFeedback }}</div>
        </slot>
      </div>
    </template>

    <template v-else>
      <slot></slot>

      <slot v-if="description" name="description">
        <small :id="labelFor ? labelFor + 'Description' : null" class="form-text text-muted">{{ description }}</small>
      </slot>
      <slot v-if="invalidFeedback" name="invalidFeedback">
        <div class="invalid-feedback">{{ invalidFeedback }}</div>
      </slot>
      <slot v-if="validFeedback && !invalidFeedback" name="validFeedback">
        <div class="valid-feedback">{{ validFeedback }}</div>
      </slot>
    </template>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { FormFieldSize } from '@/enum/FormFieldSize';

@Component({})
export default class FormGroup extends Vue {
  @Prop({ default: null }) readonly description!: string;
  @Prop([Boolean]) readonly hideLabel!: boolean;
  @Prop([Boolean]) readonly horizontal!: boolean;
  @Prop({ default: null }) readonly invalidFeedback!: string;
  @Prop({ default: null }) readonly label!: string;
  @Prop({ default: null }) readonly labelFor!: string;
  @Prop({ default: null }) readonly labelColXxs!: number;
  @Prop({ default: 2 }) readonly labelColXs!: number;
  @Prop({ default: null }) readonly labelColSm!: number;
  @Prop({ default: null }) readonly labelColMd!: number;
  @Prop({ default: null }) readonly labelColLg!: number;
  @Prop({ default: null }) readonly labelColXl!: number;
  @Prop({ default: null }) readonly labelColXxl!: number;
  @Prop({ default: null }) readonly labelColXxxl!: number;
  @Prop({ default: null }) readonly labelSize!: FormFieldSize;
  @Prop({ default: null }) readonly validFeedback!: string;

  required: boolean = false;

  mounted () {
    this.required = this.$el.querySelector('input[required]') !== null ||
      this.$el.querySelector('select[required]') !== null ||
      this.$el.querySelector('textarea[required]') !== null;
  }
}
</script>
