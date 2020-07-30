<template>
  <div class="my-4">
    <h3>{{ $t('page.title.passwordReset') }}</h3>

    <template v-if="error === null && successMessage != null">
      <p>{{ successMessage }}</p>
    </template>

    <template v-else>
      <p>{{ $t('page.passwordReset.infoText') }}</p>

      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" novalidate v-validate>
        <form-group label-for="email"
                    :invalid-feedback="errors.email"
                    :label="$t('form.label.email')">
          <input-email id="email"
                       required
                       v-model.trim="form.email"
                       :placeholder="$t('form.placeholder.email')"
                       :plaintext="emailPreFilled"
                       :readonly="emailPreFilled"></input-email>
        </form-group>
        <form-group label-for="token"
                    :invalid-feedback="errors.token"
                    :label="$t('form.label.securityToken')">
          <input-text id="token"
                      required
                      v-model.trim="form.token"
                      :placeholder="$t('form.placeholder.securityToken')"
                      :plaintext="tokenPreFilled"
                      :readonly="tokenPreFilled"></input-text>
        </form-group>

        <!-- Password row -->
        <div class="form-row">
          <div class="col-md-6">
            <form-group label-for="password"
                        :invalid-feedback="errors.password"
                        :label="$t('form.label.password')">
              <input-password id="password"
                              is-toggleable
                              required
                              v-model.trim="form.password"
                              :placeholder="$t('form.placeholder.password')"></input-password>
            </form-group>
          </div>
          <div class="col-md-6">
            <form-group label-for="password_confirmation"
                        :invalid-feedback="errors.password_confirmation"
                        :label="$t('form.label.passwordConfirmation')">
              <input-password id="password_confirmation"
                              required
                              is-toggleable
                              v-model.trim="form.password_confirmation"
                              :placeholder="$t('form.placeholder.passwordConfirmation')"></input-password>
            </form-group>
          </div>
        </div>

        <button-wrapper block
                        class="mb-4"
                        icon="mdi-lock-reset"
                        id="reset-password"
                        type="submit"
                        :disabled="disabledSubmit"
                        :loading="loading">{{ $t('general.resetPassword') }}</button-wrapper>
      </form>
    </template>

    <router-link to="/login">
      <small>
        <strong>{{ $t('general.backToSignIn') }}</strong>
      </small>
    </router-link>
  </div>
</template>

<script lang="ts">
import { AxiosResponse } from 'axios';
import { Component, Mixins, Watch } from 'vue-property-decorator';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import FormGroup from '@/components/form/FormGroup.vue';
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputEmail from '@/components/form/InputEmail.vue';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';
import ResetPasswordModel from '@/models/ResetPasswordModel';

@Component({
  components: { ButtonWrapper, FormGroup, InputEmail, InputPassword, InputText }
})
export default class PasswordResetPage extends Mixins(FormMixin, FormValidationMixin) {
  emailPreFilled: boolean = false;
  form: ResetPasswordModel = {
    email: '',
    password: '',
    password_confirmation: '',
    token: ''
  };
  tokenPreFilled: boolean = false;

  mounted () {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email as string;
      this.emailPreFilled = true;
    }
    if (this.$route.query.token) {
      this.form.token = this.$route.query.token as string;
      this.tokenPreFilled = true;
    }
  }

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      const response: AxiosResponse = await AuthService.resetPassword(this.form);
      this.error = null;
      this.successMessage = response.data.message;
      this.dirty = false;
    } catch (e) {
      this.validateResponse(e);
      this.successMessage = null;
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  @Watch('form', { deep: true })
  onFormChange (form: ResetPasswordModel): void {
    this.dirty = true;
    this.disabledSubmit = !(form.email.length > 0 && form.password.length > 0 &&
      form.password_confirmation.length > 0 && form.token.length > 0);
  }
}
</script>
