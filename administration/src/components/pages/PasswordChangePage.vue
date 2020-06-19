<template>
  <div class="my-4">
    <h3>{{ $t('page.title.passwordChange') }}</h3>
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
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import { ChangePasswordModel } from '@/models/ChangePasswordModel';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
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
      await AuthService.changePassword(this.form);
      await AuthService.logout();
      await this.$router.push('/login');
    } catch (e) {
      this.validateResponse(e.response);
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
