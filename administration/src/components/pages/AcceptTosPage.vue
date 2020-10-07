<template>
  <div class="my-4">
    <h3>{{ $t('page.title.acceptTos') }}</h3>

    <template v-if="error === null && successMessage != null">
      <p>{{ successMessage }} {{ $t('general.continueLink') }}</p>

      <router-link to="/">
        <small>
          <strong>{{ $t('general.nextPage') }}</strong>
        </small>
      </router-link>
    </template>
    <template v-else>
      <p>{{ $t('page.acceptTos.description') }}</p>

      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" novalidate v-validate>
        <input-checkbox class="mb-3" id="tos" required :invalidFeedback="errors.tos" :name="tos" :value="true">
          <i18n path="page.register.tos">
            <template v-slot:tos><a @click.prevent="showTos()">{{ $t('general.tos') }}</a></template>
          </i18n>
        </input-checkbox>

        <button-wrapper block
                        class="mb-4"
                        icon="mdi-file-document-edit-outline"
                        id="accept-tos"
                        type="submit"
                        :disabled="disabledSubmit"
                        :loading="loading">{{ $t('page.acceptTos.button') }}</button-wrapper>
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
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputCheckbox from '@/components/form/InputCheckbox.vue';
import Logout from '@/directives/LogoutDirective';

@Component({
  components: { ButtonWrapper, InputCheckbox },
  directives: { Logout }
})
export default class AcceptTosPage extends Mixins(FormMixin, FormValidationMixin) {
  tos: Array<boolean> = [];

  async handleSubmit (): Promise<any> {
    if (this.hasValidationError()) {
      return;
    }
    this.loading = true;

    try {
      let response: AxiosResponse = await AuthService.acceptTos(this.tos[0]);

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

  showTos () {
    // TODO open modal and show tos - query tos from backend - resolve this when upgrading or refactoring
  }

  @Watch('tos', { deep: true })
  onTosChange (tos: Array<boolean>): void {
    this.disabledSubmit = !(tos.length === 1 && tos[0]);
  }
}
</script>
