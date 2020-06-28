<template>
  <b-modal body-class="my-3"
           centered
           hide-footer
           hide-header
           id="sign-in-modal"
           modal-class="blur"
           no-close-on-backdrop
           no-close-on-esc>
    <div class="mx-auto my-3 text-center">
      <span class="mdi mdi-clock-outline highlight"></span>
    </div>

    <h3 class="font-weight-normal mb-4 text-center"
        v-html="$t('component.signInModal.title', { name: firstname })"></h3>

    <div v-if="error" class="alert alert-danger mx-4" role="alert">
      {{ error }}
    </div>

    <form @submit.prevent="handleSubmit" class="mx-4" novalidate>
      <form-group :hide-label="true" :label="$t('form.label.password')">
        <input-password id="sign-in-password"
                        is-toggleable
                        required
                        v-model.trim="form.password"
                        :placeholder="$t('form.placeholder.password')"></input-password>
      </form-group>
      <button-wrapper block
                      class="mb-3"
                      icon="mdi-login"
                      id="sign-in-modal-signin"
                      type="submit"
                      :disabled="disabledSubmit"
                      :loading="loading">{{ $t('component.signInModal.remainSignedIn') }}</button-wrapper>
    </form>
    <div class="text-center">
      <a href="#" v-logout><small>{{ $t('general.signOut') }}</small></a>
    </div>
  </b-modal>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Mixins, Watch } from 'vue-property-decorator';
import { EventBus } from '@/event-bus';
import { getUser } from '@/helpers';
import { ModalPlugin } from 'bootstrap-vue';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import CredentialsModel from '@/models/CredentialsModel';
import FormGroup from '@/components/form/FormGroup.vue';
import FormMixin from '@/mixins/FormMixin';
import InputPassword from '@/components/form/InputPassword.vue';
import Logout from '@/directives/LogoutDirective';
import UserShortModel from '@/models/UserShortModel';

Vue.use(ModalPlugin);

@Component({
  components: { ButtonWrapper, FormGroup, InputPassword },
  directives: { Logout }
})
export default class SignInModal extends Mixins(FormMixin) {
  firstname: string = '';
  form: CredentialsModel = {
    username: '',
    password: ''
  };

  mounted () {
    EventBus.$on('sign-in-modal', this.handleSignInModal);
  }

  async handleSubmit (): Promise<any> {
    this.loading = true;

    try {
      await AuthService.login(this.form);
      AuthService.checkAuth();
      this.$bvModal.hide('sign-in-modal');
      this.form.password = '';
      this.error = null;
    } catch (e) {
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  handleSignInModal (): void {
    const user: UserShortModel = getUser();
    this.firstname = user.firstname;
    this.form.username = user.email;
    this.$bvModal.show('sign-in-modal');
  }

  @Watch('form', { deep: true })
  onFormChange (form: CredentialsModel): void {
    this.disabledSubmit = !(form.password.length > 0);
  }
}
</script>
