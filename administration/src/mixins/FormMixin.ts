import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { Route } from 'vue-router';
import FormInterface from '@/interfaces/FormInterface';

Component.registerHooks([
  'beforeRouteEnter',
  'beforeRouteLeave',
  'beforeRouteUpdate'
]);
@Component({})
export default class FormMixin extends Vue implements FormInterface {
  dirty: boolean = false;
  disabledSubmit: boolean = true;
  error: string|null = null;
  loading: boolean = false;
  successMessage: string|null = null;

  beforeRouteLeave (to: Route, from: Route, next: any) {
    if (this.dirty) {
      this.$bvModal.msgBoxConfirm(this.$t('component.leavePageModal.text') as string, {
        cancelTitle: this.$t('component.leavePageModal.cancelButton') as string,
        centered: true,
        footerClass: 'p-2',
        okTitle: this.$t('component.leavePageModal.okButton') as string,
        okVariant: 'link',
        title: this.$t('component.leavePageModal.title') as string
      })
        .then(value => {
          if (value) {
            next();
          } else {
            next(false);
          }
        })
        .catch(() => { next(); });
    } else {
      next();
    }
  }

  async handleSubmit (): Promise<any> {}
}
