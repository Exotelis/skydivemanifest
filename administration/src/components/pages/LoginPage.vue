<template>
  <div>
    <h3>{{ $t('page.login.formHeader') }}</h3>

    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>

    <form @submit.prevent="handleSubmit" novalidate v-validate>
      <form-group label-for="username"
                  :invalid-feedback="errors.username"
                  :label="$t('form.label.usernameEmail')">
        <input-text autofocus
                    id="username"
                    required
                    v-model.trim="form.username"
                    :placeholder="$t('form.placeholder.usernameEmail')"></input-text>
      </form-group>
      <form-group label-for="password"
                  :invalid-feedback="errors.password"
                  :label="$t('form.label.password')">
        <input-password id="password"
                        required
                        v-model.trim="form.password"
                        is-toggleable
                        :placeholder="$t('form.placeholder.password')"></input-password>
      </form-group>
      <button-wrapper block
                      class="mb-4"
                      icon="mdi-login"
                      id="signin"
                      type="submit"
                      :disabled="disabledSubmit"
                      :loading="loading">{{ $t('general.signIn') }}</button-wrapper>
    </form>
    <div class="text-center">
      <router-link to="/register">
        <small>
          <strong>{{ $t('general.signUp') }}</strong>
        </small>
      </router-link>
      <span> | </span>
      <router-link to="/password-forgot">
        <small>
          <strong>{{ $t('general.forgotPassword') }}</strong>
        </small>
      </router-link>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import CredentialsModel from '@/models/CredentialsModel';
import FormGroup from '@/components/form/FormGroup.vue';
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';

@Component({
  components: {
    ButtonWrapper,
    FormGroup,
    InputPassword,
    InputText
  }
})
export default class LoginPage extends Mixins(FormMixin, FormValidationMixin) {
  form: CredentialsModel = {
    username: '',
    password: ''
  };

  async handleSubmit (): Promise<any> {
    let credentials: CredentialsModel = { username: this.form.username, password: this.form.password };
    this.loading = true;

    try {
      await AuthService.login(credentials);

      if (AuthService.mustAcceptTos()) {
        await this.$router.push('/accept-tos');
      } else if (AuthService.passwordChangeRequired()) {
        await this.$router.push('/password-change');
      } else {
        await this.$router.push('/');
      }
    } catch (e) {
      this.error = e.response.data.message;
      this.validateResponse(e);
    }

    this.loading = false;
  }

  @Watch('form', { deep: true })
  onFormChange (form: CredentialsModel): void {
    this.disabledSubmit = !(form.username.length > 0 && form.password.length > 0);
  }
}
</script>
