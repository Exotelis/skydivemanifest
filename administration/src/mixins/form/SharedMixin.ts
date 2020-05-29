import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { autocompleteValidator, inputmodeValidator, spellcheckValidator } from '@/validators';

// Available on all input elements as well as select and textarea

@Component({})
export default class SharedMixin extends Vue {
  @Prop({ default: null, validator: autocompleteValidator }) readonly autocomplete!: string;
  @Prop({ default: false }) readonly autofocus!: boolean;
  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ required: true }) readonly id!: string;
  @Prop({ default: null, validator: inputmodeValidator }) readonly inputmode!: string;
  @Prop({ default: 'false', validator: spellcheckValidator }) readonly spellcheck!: string;
  @Prop({ default: null }) readonly tabindex!: number;
  @Prop({ default: null }) readonly value!: string;
}
