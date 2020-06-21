<template>
  <div>
    <h3>{{ $t('page.title.passwordForgot') }}</h3>

    <template v-if="error === null && successMessage != null">
      <p>{{ successMessage }}</p>
    </template>

    <template v-else>
      <p>{{ $t('page.passwordForgot.infoText') }}</p>

      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" novalidate v-validate>
        <form-group label-for="email"
                    :invalid-feedback="errors.email"
                    :label="$t('form.label.email')">
          <input-email id="email"
                       required
                       v-model.trim="email"
                       :placeholder="$t('form.placeholder.email')"
                       :plaintext="emailPreFilled"
                       :readonly="emailPreFilled"></input-email>
        </form-group>

        <button-wrapper block
                        class="mb-4"
                        icon="mdi-lock-reset"
                        id="forgot-password"
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

@Component({
  components: { ButtonWrapper, FormGroup, InputEmail }
})
export default class PasswordForgotPage extends Mixins(FormMixin, FormValidationMixin) {
  email: string = '';
  emailPreFilled: boolean = false;

  mounted () {
    if (this.$route.query.email) {
      this.email = this.$route.query.email as string;
      this.emailPreFilled = true;
    }
  }

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      const response: AxiosResponse = await AuthService.forgotPassword(this.email);
      this.error = null;
      this.successMessage = response.data.message;
    } catch (e) {
      this.validateResponse(e.response);
      this.successMessage = null;
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  @Watch('email')
  onEmailChange (email: string): void {
    this.disabledSubmit = !(email.length > 0);
  }
}
</script>
