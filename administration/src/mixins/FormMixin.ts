import { Component, Mixins } from 'vue-property-decorator';
import DirtyModalMixin from '@/mixins/DirtyModalMixin';
import FormInterface from '@/interfaces/FormInterface';

@Component
export default class FormMixin extends Mixins(DirtyModalMixin) implements FormInterface {
  disabledSubmit: boolean = true;
  error: string|null = null;
  loading: boolean = false;
  successMessage: string|null = null;

  async handleSubmit (): Promise<any> {}
}
