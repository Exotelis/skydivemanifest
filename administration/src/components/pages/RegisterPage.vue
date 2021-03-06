<template>
  <div class="my-4">
    <template v-if="email && error === null">
      <h3>{{ $t('page.register.successTitle') }}</h3>

      <p v-html="$t('page.register.confirmation', { email: email })"></p>
      <p><router-link to="/login">{{ $t('general.signInNow') }}</router-link></p>
      <p><small><em>{{ $t('page.register.note') }} {{ $t('page.register.contactUs') }}</em></small></p>
    </template>

    <template v-else>
      <h3>{{ $t('general.signUp') }}</h3>

      <div v-if="error" role="alert" class="alert alert-danger">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" novalidate v-validate>
        <form-group label-for="email"
                    :invalid-feedback="errors.email"
                    :label="$t('form.label.email')">
          <input-email id="email"
                       required
                       v-model.trim="form.email"
                       :placeholder="$t('form.placeholder.email')"></input-email>
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

        <!-- Names row -->
        <div class="form-row">
          <div class="col-md-6">
            <form-group label-for="firstname"
                        :invalid-feedback="errors.firstname"
                        :label="$t('form.label.firstName')">
              <input-text id="firstname"
                          required
                          v-model.trim="form.firstname"
                          :placeholder="$t('form.placeholder.firstName')"></input-text>
            </form-group>
          </div>
          <div class="col-md-6">
            <form-group label-for="lastname"
                        :invalid-feedback="errors.lastname"
                        :label="$t('form.label.lastName')">
              <input-text id="lastname"
                          required
                          v-model.trim="form.lastname"
                          :placeholder="$t('form.placeholder.lastName')"></input-text>
            </form-group>
          </div>
        </div>

        <!-- date of birth and gender row -->
        <div class="form-row">
          <div class="col-md-6">
            <form-group label-for="dob"
                        :invalid-feedback="errors.dob"
                        :label="$t('form.label.dob')">
              <input-date id="dob"
                          required
                          v-model.trim="form.dob"
                          :placeholder="$t('form.placeholder.dob')"></input-date>
            </form-group>
          </div>
          <div class="col-md-6">
            <form-group label-for="gender"
                        :invalid-feedback="errors.gender"
                        :label="$t('form.label.gender')">
              <select-wrapper id="gender" v-model.trim="form.gender" :options="options"></select-wrapper>
            </form-group>
          </div>
        </div>

        <input-checkbox class="mb-3" id="tos" required :invalidFeedback="errors.tos" :name="tos" :value="true">
          <i18n path="page.register.tos">
            <template v-slot:tos><a @click.prevent="showTos()">{{ $t('general.tos') }}</a></template>
          </i18n>
        </input-checkbox>

        <button-wrapper block
                        class="mb-4"
                        icon="mdi-account-plus"
                        id="signup"
                        type="submit"
                        :disabled="disabledSubmit"
                        :loading="loading">{{ $t('general.signUp') }}</button-wrapper>
      </form>

      <div>
        <small><strong>{{ $t('page.register.back') }}&nbsp;</strong></small>
        <router-link to="/login"><small><strong>{{ $t('general.signIn') }}</strong></small></router-link>
      </div>
    </template>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import { AxiosResponse } from 'axios';
import { defaultLanguage } from '@/i18n';
import { Gender } from '@/enum/Gender';
import { Options } from '@/types/Options';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import FormGroup from '@/components/form/FormGroup.vue';
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputCheckbox from '@/components/form/InputCheckbox.vue';
import InputDate from '@/components/form/InputDate.vue';
import InputEmail from '@/components/form/InputEmail.vue';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';
import RegisterModel from '@/models/RegisterModel';
import SelectWrapper from '@/components/form/SelectWrapper.vue';

@Component({
  components: {
    ButtonWrapper,
    FormGroup,
    InputCheckbox,
    InputDate,
    InputEmail,
    InputPassword,
    InputText,
    SelectWrapper
  }
})
export default class RegisterPage extends Mixins(FormMixin, FormValidationMixin) {
  email: string|null = null;
  form: RegisterModel = {
    dob: '',
    email: '',
    firstname: '',
    gender: Gender.u,
    lastname: '',
    password: '',
    password_confirmation: '',
    tos: false
  };
  options: Options = [];
  tos: Array<boolean> = [];

  mounted () {
    this.options = [
      { value: Gender.u, text: this.$t('form.placeholder.gender') as string, disabled: true },
      { value: Gender.d, text: this.$t('general.gender.d') as string },
      { value: Gender.f, text: this.$t('general.gender.f') as string },
      { value: Gender.m, text: this.$t('general.gender.m') as string }
    ];

    // Set default locale as initial language. Can be changed in user settings.
    this.form.locale = defaultLanguage;

    // Get timezone of the host system. Can be changed in user settings later on
    this.form.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone ||
      (new Date().getTimezoneOffset() / -1).toString();
  }

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      const response: AxiosResponse = await AuthService.register(this.form);
      this.email = response.data.data.email;
      this.error = null;
      this.dirty = false;
    } catch (e) {
      this.validateResponse(e);
      this.error = e.response.data.message;
    }
    this.loading = false;
  }

  showTos () {
    // TODO open modal and show tos - query tos from backend - resolve this when upgrading or refactoring
  }

  @Watch('form', { deep: true })
  onFormChange (form: RegisterModel): void {
    this.dirty = true;
    this.disabledSubmit = !(form.dob.length > 0 && form.email.length > 0 && form.firstname.length > 0 &&
      form.lastname.length > 0 && form.password.length > 0 && form.password_confirmation.length > 0 && form.tos);
  }

  @Watch('tos', { deep: true })
  onTosChange (tos: Array<boolean>): void {
    this.form.tos = tos.length === 1 && tos[0];
  }
}
</script>
