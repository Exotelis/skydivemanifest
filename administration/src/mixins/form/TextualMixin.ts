import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { maxMinLengthValidator } from '@/validators';

// Available on input with type - email password search tel text url | the textarea element

@Component({})
export default class TextualMixin extends Vue {
  @Prop({ default: null, validator: maxMinLengthValidator }) readonly maxlength!: number;
  @Prop({ default: null, validator: maxMinLengthValidator }) readonly minlength!: number;
  @Prop({ default: null }) readonly placeholder!: string;
}
