import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { autocompleteValidator } from '@/validators';

@Component({})
export default class BasicMixin extends Vue {
  @Prop({ default: null, validator: autocompleteValidator }) readonly autocomplete!: boolean;
  @Prop({ default: false }) readonly autofocus!: boolean;
  @Prop({ default: null }) readonly description!: string;
  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly errorText!: string;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ required: true }) readonly id!: string;
  @Prop({ required: true }) readonly label!: string;
  @Prop({ default: false }) readonly required!: boolean;
  @Prop({ default: null }) readonly tabindex!: string;
  @Prop({ default: null }) readonly value!: string;
}
