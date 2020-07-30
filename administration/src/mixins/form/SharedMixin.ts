import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { autocompleteValidator, inputmodeValidator, spellcheckValidator } from '@/validators';
import { FormFieldSize } from '@/enum/FormFieldSize';

// Available on all input elements as well as select and textarea

@Component({})
export default class SharedMixin extends Vue {
  @Prop({ default: null, validator: autocompleteValidator }) readonly autocomplete!: string;
  @Prop([Boolean]) readonly autofocus!: boolean;
  @Prop([Boolean]) readonly disabled!: boolean;
  @Prop({ default: null }) readonly fieldSize!: FormFieldSize;
  @Prop({ default: null }) readonly form!: string;
  @Prop({ required: true }) readonly id!: string;
  @Prop({ default: null, validator: inputmodeValidator }) readonly inputmode!: string;
  @Prop({ default: 'false', validator: spellcheckValidator }) readonly spellcheck!: string;
  @Prop({ default: null }) readonly tabindex!: number;
  @Prop({ default: null }) readonly value!: string|null;
}
