<template>
  <b-modal body-class="my-3"
           centered
           hide-footer
           hide-header
           id="sign-in-modal"
           modal-class="blur"
           no-close-on-backdrop
           no-close-on-esc
           @show="handleShow">
    <div class="mx-auto my-3 text-center">
      <span class="mdi mdi-clock-outline highlight"></span>
    </div>

    <h3 class="font-weight-normal mb-4 text-center" v-html="$t('login.title.modal', { name: firstname })"></h3>

    <div v-if="error" class="alert alert-danger mx-4" role="alert">
      {{ error }}
    </div>

    <form @submit.prevent="handleLogin" class="mx-4" novalidate>
      <form-group :hide-label="true" :label="$t('login.password.label')">
        <input-password id="sign-in-password"
                        required
                        v-model.trim="form.password"
                        :is-toggleable="true"
                        :placeholder="$t('login.password.placeholder')"></input-password>
      </form-group>
      <button-wrapper block
                      class="mb-3"
                      icon="mdi-login"
                      id="sign-in-modal-signin"
                      type="submit"
                      :disabled="disabledSubmit"
                      :loading="loading">{{ $t('login.remainSignedIn') }}</button-wrapper>
    </form>
    <div class="text-center">
      <a href="#" v-logout><small>{{ $t('login.signOut') }}</small></a>
    </div>
  </b-modal>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Watch } from 'vue-property-decorator';
import { CredentialsModel } from '@/models/CredentialsModel';
import { EventBus } from '@/event-bus';
import { getUser } from '@/helpers';
import { ModalPlugin } from 'bootstrap-vue';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import FormGroup from '@/components/form/FormGroup.vue';
import InputPassword from '@/components/form/InputPassword.vue';
import Logout from '@/directives/LogoutDirective';

Vue.use(ModalPlugin);

interface FormElements {
  password: string;
}

@Component({
  components: { ButtonWrapper, FormGroup, InputPassword },
  directives: { Logout }
})
export default class SignInModal extends Vue {
  disabledSubmit: boolean = true;
  error: string|null = null;
  firstname: string = '';
  form: FormElements = {
    password: ''
  };
  loading: boolean = false;

  mounted () {
    EventBus.$on('sign-in-modal', this.handleSignInModal);
  }

  async handleLogin (): Promise<any> {
    let credentials: CredentialsModel = { username: getUser().username!, password: this.form.password };
    this.loading = true;

    try {
      await AuthService.login(credentials);
      AuthService.checkAuth();
      this.$bvModal.hide('sign-in-modal');
    } catch (e) {
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  handleShow (): void {
    this.firstname = getUser().firstname;
  }

  handleSignInModal (): void {
    this.$bvModal.show('sign-in-modal');
  }

  @Watch('form', { deep: true })
  onFormChange (form: FormElements): void {
    this.disabledSubmit = !(form.password.length > 0);
  }
}
</script>
