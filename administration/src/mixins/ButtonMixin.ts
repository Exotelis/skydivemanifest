import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { iconValidator } from '@/validators';
import { FormFieldSize } from '@/enum/FormFieldSize';

@Component({})
export default class ButtonMixin extends Vue {
  @Prop([Boolean]) readonly block!: boolean;
  @Prop({ default: null }) readonly buttonSize!: FormFieldSize;
  @Prop([Boolean]) readonly disabled!: boolean;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ default: null, validator: iconValidator }) readonly icon!: string;
  @Prop({ required: true }) readonly id!: string;
  @Prop([Boolean]) readonly loading!: boolean;
  @Prop([Boolean]) readonly rightAligned!: boolean;
  @Prop({ default: null }) readonly tabindex!: string;
  @Prop({ default: 'button' }) readonly type!: string;
  @Prop({ default: 'primary' }) readonly variant!: string;
}
