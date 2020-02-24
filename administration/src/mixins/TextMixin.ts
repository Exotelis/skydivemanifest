import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component({})
export default class TextMixin extends Vue {
  @Prop({ default: null }) readonly maxlength!: number;
  @Prop({ default: null }) readonly minlength!: number;
  @Prop({ default: null }) readonly pattern!: string;
  @Prop({ default: null }) readonly placeholder!: string;
}
