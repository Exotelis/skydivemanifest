import Vue from 'vue';
import { BvMsgBoxData } from 'bootstrap-vue/src/components/modal';
import { Component } from 'vue-property-decorator';
import { ModalPlugin } from 'bootstrap-vue';

Vue.use(ModalPlugin);

@Component
export default class DeleteModalMixin extends Vue {
  deleteModal (title: string, message: string, confirmText: string, cancelText?: string): Promise<BvMsgBoxData> {
    return this.$bvModal.msgBoxConfirm(message,
      {
        cancelTitle: cancelText || this.$t('general.cancel') as string,
        centered: true,
        footerClass: 'p-2',
        okTitle: confirmText,
        okVariant: 'danger',
        title: title
      }
    );
  }
}
