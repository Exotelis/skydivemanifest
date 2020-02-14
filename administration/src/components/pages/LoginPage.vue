<template>
  <div class="welcome-container">
    <div class="welcome-left">
      <div class="welcome-textbox">
        <div class="welcome-title">{{ getTimeBasedTitle }}</div>
        <div class="welcome-subtitle">{{ $t('login.subtitle') }}</div>
        <div class="welcome-author">{{ $t('login.imageAuthor', { author: 'wesleyjharrison', platform: 'pixabay'}) }}</div>
      </div>
    </div>
    <div class="welcome-right">
      <h3>{{ $t('login.formHeader') }}</h3>

      <form @submit.prevent="login" novalidate>
        <text-input autofocus
                    id="username"
                    v-model="username"
                    :error-text="$t('error.required')"
                    :label="$t('login.username.label')"
                    :placeholder="$t('login.username.placeholder')"
                    :required="true"></text-input>
        <password-input id="password"
                        v-model="password"
                        :error-text="$t('error.required')"
                        :is-toggleable="true"
                        :label="$t('login.password.label')"
                        :placeholder="$t('login.password.placeholder')"
                        :required="true"></password-input>
        <div class="clearfix">
          <submit-button icon="mdi-login"
                         id="signin"
                         right-aligned
                         :disabled="disabledSubmit"
                         :loading="loading">{{ $t('login.signIn') }}</submit-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import PasswordInput from '@/components/form/PasswordInput';
import SubmitButton from '@/components/form/SubmitButton';
import TextInput from '@/components/form/TextInput';

export default {
  name: 'Login',
  data: function () {
    return {
      time: new Date().getHours(),
      username: '',
      password: '',
      loading: false,
      disabledSubmit: true
    };
  },
  components: {
    PasswordInput,
    SubmitButton,
    TextInput
  },
  computed: {
    getTimeBasedTitle: function () {
      if (this.time >= 5 && this.time < 12) {
        return this.$t('login.title.morning');
      }

      if (this.time >= 12 && this.time < 15) {
        return this.$t('login.title.noon');
      }

      if (this.time >= 15 && this.time < 18) {
        return this.$t('login.title.afternoon');
      }

      if (this.time >= 18 && this.time <= 23) {
        return this.$t('login.title.evening');
      }

      return this.$t('login.title.other');
    },
    usernamePassword: function () {
      return this.username + this.password;
    }
  },
  methods: {
    login: function (element) {
      const form = element.target;
      form.classList.add('was-validated');

      this.loading = true;
      setTimeout(() => {
        this.loading = false;
      }, 3000);
      // Todo Login logic
    }
  },
  watch: {
    usernamePassword: function () {
      this.username.trim() !== '' && this.password.trim() !== '' ? this.disabledSubmit = false
        : this.disabledSubmit = true;
    }
  }
};
</script>
