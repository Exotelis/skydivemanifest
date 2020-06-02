import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { iconValidator } from '@/validators';

@Component({})
export default class ButtonMixin extends Vue {
  @Prop({ default: false }) readonly block!: boolean;
  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ default: null, validator: iconValidator }) readonly icon!: string;
  @Prop({ required: true }) readonly id!: string;
  @Prop({ default: false }) readonly loading!: boolean;
  @Prop({ default: false }) readonly rightAligned!: boolean;
  @Prop({ default: null }) readonly tabindex!: string;
  @Prop({ default: 'button' }) readonly type!: string;
  @Prop({ default: 'primary' }) readonly variant!: string;
}
