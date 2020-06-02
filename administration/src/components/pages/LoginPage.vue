<template>
  <div>
    <h3>{{ $t('pages.login.formHeader') }}</h3>

    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>

    <form @submit.prevent="login" novalidate v-validate>
      <form-group label-for="username"
                  :invalid-feedback="errors.username"
                  :label="$t('form.label.username')">
        <input-text autofocus
                    id="username"
                    required
                    v-model.trim="form.username"
                    :placeholder="$t('form.placeholder.username')"></input-text>
      </form-group>
      <form-group label-for="password"
                  :invalid-feedback="errors.password"
                  :label="$t('form.label.password')">
        <input-password id="password"
                        required
                        v-model.trim="form.password"
                        :is-toggleable="true"
                        :placeholder="$t('form.placeholder.password')"></input-password>
      </form-group>
      <div class="clearfix">
        <button-wrapper icon="mdi-login"
                        id="signin"
                        type="submit"
                        :disabled="disabledSubmit"
                        :loading="loading"
                        :right-aligned="true">{{ $t('general.signIn') }}</button-wrapper>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import { CredentialsModel } from '@/models/CredentialsModel';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import FormGroup from '@/components/form/FormGroup.vue';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';

interface FormElements {
  username: string;
  password: string;
}

@Component({
  components: {
    ButtonWrapper,
    FormGroup,
    InputPassword,
    InputText
  }
})
export default class LoginPage extends Mixins(FormValidationMixin) {
  disabledSubmit: boolean = true;
  error: string|null = null;
  form: FormElements = {
    username: '',
    password: ''
  };
  loading: boolean = false;

  async login (): Promise<any> {
    let credentials: CredentialsModel = { username: this.form.username, password: this.form.password };
    this.loading = true;

    try {
      await AuthService.login(credentials);
      await this.$router.push('/');
    } catch (e) {
      this.validateResponse(e.response);
      this.error = e.response.data.message;
    }
    this.loading = false;
  }

  @Watch('form', { deep: true })
  onFormChange (form: FormElements): void {
    this.disabledSubmit = !(form.username.length > 0 && form.password.length > 0);
  }
}
</script>
