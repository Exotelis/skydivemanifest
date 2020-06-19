import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import FormInterface from '@/interfaces/FormInterface';

@Component({})
export default class FormMixin extends Vue implements FormInterface {
  disabledSubmit: boolean = true;
  error: string|null = null;
  loading: boolean = false;

  async handleSubmit (): Promise<any> {}
}
