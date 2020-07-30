import Vue, { DirectiveOptions } from 'vue';
import { ToastPlugin } from 'bootstrap-vue';
import AuthService from '@/services/AuthService';

Vue.use(ToastPlugin);

const directive: DirectiveOptions = {
  inserted (el, binding, vnode) {
    const vm: Vue = vnode.context!;

    el.addEventListener('click', async (event) => {
      event.preventDefault();

      try {
        await AuthService.logout();
        vm.$router.go(0);
      } catch (e) {
        if (e.response.status === 401) {
          await vm.$router.push('login');
        } else {
          vm.$bvToast.toast(e.response.data.message, {
            title: vm.$t('error.signOutError') as string,
            autoHideDelay: 5000,
            appendToast: false,
            variant: 'danger',
            solid: true
          });
        }
      }
    });
  }
};

export default directive;
