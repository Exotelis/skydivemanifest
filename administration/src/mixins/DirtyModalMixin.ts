import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { ModalPlugin } from 'bootstrap-vue';
import { Route } from 'vue-router';

Component.registerHooks([
  'beforeRouteLeave'
]);

Vue.use(ModalPlugin);

@Component
export default class DirtyModalMixin extends Vue {
  dirty: boolean = false;

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

  setDirty (): void {
    if (!this.dirty) {
      // Emit only if it's necessary
      this.$emit('form:dirty', true);
    }
    this.dirty = true;
  }

  unsetDirty (): void {
    if (this.dirty) {
      // Emit only if it's necessary
      this.$emit('form:dirty', false);
    }
    this.dirty = false;
  }
}
