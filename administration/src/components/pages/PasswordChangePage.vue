<template>
  <div class="my-4">
    <h3>{{ $t('page.title.passwordChange') }}</h3>

    <template v-if="error === null && successMessage != null">
      <p>{{ successMessage }} {{ $t('general.continueLink') }}</p>

      <router-link to="/">
        <small>
          <strong>{{ $t('general.nextPage') }}</strong>
        </small>
      </router-link>
    </template>
    <template v-else>
      <p>{{ $t('general.passwordExpired') }}</p>

      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" novalidate v-validate>
        <form-group label-for="password"
                    :invalid-feedback="errors.password"
                    :label="$t('form.label.oldPassword')">
          <input-password id="password"
                          is-toggleable
                          required
                          v-model.trim="form.password"
                          :placeholder="$t('form.placeholder.oldPassword')"></input-password>
        </form-group>
        <form-group label-for="new_password"
                    :invalid-feedback="errors.new_password"
                    :label="$t('form.label.newPassword')">
          <input-password id="new_password"
                          is-toggleable
                          required
                          v-model.trim="form.new_password"
                          :placeholder="$t('form.placeholder.newPassword')"></input-password>
        </form-group>
        <form-group label-for="new_password_confirmation"
                    :invalid-feedback="errors.new_password_confirmation"
                    :label="$t('form.label.newPasswordConfirmation')">
          <input-password id="new_password_confirmation"
                          is-toggleable
                          required
                          v-model.trim="form.new_password_confirmation"
                          :placeholder="$t('form.placeholder.newPasswordConfirmation')"></input-password>
        </form-group>

        <button-wrapper block
                        class="mb-4"
                        icon="mdi-lock-reset"
                        id="change-password"
                        type="submit"
                        :disabled="disabledSubmit"
                        :loading="loading">{{ $t('general.changePassword') }}</button-wrapper>
      </form>
      <a href="#" v-logout><small>{{ $t('general.signInAnotherAccount') }}</small></a>
    </template>
  </div>
</template>

<script lang="ts">
import { AxiosResponse } from 'axios';
import { Component, Mixins, Watch } from 'vue-property-decorator';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import ChangePasswordModel from '@/models/ChangePasswordModel';
import FormGroup from '@/components/form/FormGroup.vue';
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputPassword from '@/components/form/InputPassword.vue';
import Logout from '@/directives/LogoutDirective';

@Component({
  components: { ButtonWrapper, FormGroup, InputPassword },
  directives: { Logout }
})
export default class PasswordChangePage extends Mixins(FormMixin, FormValidationMixin) {
  form: ChangePasswordModel = {
    password: '',
    new_password: '',
    new_password_confirmation: ''
  };

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      let response: AxiosResponse = await AuthService.changePassword(this.form);

      // After request is sent, try to get new token with updated value
      let refreshToken: null|string = AuthService.refreshToken;
      if (refreshToken !== null) {
        await AuthService.refresh(refreshToken);
      } else {
        await AuthService.logout();
      }

      this.error = null;
      this.successMessage = response.data.message;
    } catch (e) {
      this.validateResponse(e);
      this.successMessage = null;
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  @Watch('form', { deep: true })
  onFormChange (form: ChangePasswordModel): void {
    this.disabledSubmit = !(form.password.length > 0 && form.new_password.length > 0 &&
      form.new_password_confirmation.length > 0);
  }
}
</script>
