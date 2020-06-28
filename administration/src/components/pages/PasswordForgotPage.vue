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
        <form-group label-for="username"
                    :invalid-feedback="errors.username"
                    :label="$t('form.label.usernameEmail')">
          <input-text id="username"
                      required
                      v-model.trim="username"
                      :placeholder="$t('form.placeholder.usernameEmail')"
                      :plaintext="usernamePreFilled"
                      :readonly="usernamePreFilled"></input-text>
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
import InputText from '@/components/form/InputText.vue';

@Component({
  components: { ButtonWrapper, FormGroup, InputText }
})
export default class PasswordForgotPage extends Mixins(FormMixin, FormValidationMixin) {
  username: string = '';
  usernamePreFilled: boolean = false;

  mounted () {
    if (this.$route.query.email) {
      this.username = this.$route.query.email as string;
      this.usernamePreFilled = true;
    }
  }

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      const response: AxiosResponse = await AuthService.forgotPassword(this.username);
      this.error = null;
      this.successMessage = response.data.message;
    } catch (e) {
      this.validateResponse(e.response);
      this.successMessage = null;
      this.error = e.response.data.message;
    }

    this.loading = false;
  }

  @Watch('username')
  onUsernameChange (username: string): void {
    this.disabledSubmit = !(username.length > 0);
  }
}
</script>
