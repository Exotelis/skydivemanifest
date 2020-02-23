import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { inputmodeValidator } from '@/validators';

@Component({})
export default class InputmodeMixin extends Vue {
  @Prop({ default: null, validator: inputmodeValidator }) readonly inputmode!: string;
}
