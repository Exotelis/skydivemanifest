import Vue, { VNode } from 'vue';
import { Component } from 'vue-property-decorator';
import { ToastPlugin } from 'bootstrap-vue';
import { ToastVariant } from '@/enum/ToastVariant';

Vue.use(ToastPlugin);

@Component
export default class ToastMixin extends Vue {
  toast (title: string, message: string|VNode|Array<VNode>, variant: ToastVariant = ToastVariant.default): void {
    this.$bvToast.toast(message, {
      title: title,
      autoHideDelay: 5000,
      appendToast: false,
      variant: variant,
      solid: true
    });
  }
}
