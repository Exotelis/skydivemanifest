<template>
  <b-modal body-class="my-3"
           centered
           header-border-variant="bottom-0"
           header-text-variant="white"
           hide-footer
           id="confirm-email-modal"
           :header-bg-variant="error ? 'danger' : 'success'"
           @hide="hideModal">
    <template v-slot:modal-header="{ close }">
      <span :class="['mdi mdi-8rem mx-auto', error ? 'mdi-close-circle-outline' : 'mdi-check-circle-outline' ]"></span>
      <button type="button" aria-label="Close" class="close text-white" @click="closeModal">×</button>
    </template>

    <div class="font-weight-normal text-center mx-4">
      <h3>{{ $t('component.confirmEmailModal.title') }}</h3>

      <template v-if="error">
        <p>{{ $t('component.confirmEmailModal.errorMsg', { reason: message }) }}</p>
        <p>{{ $t('component.confirmEmailModal.solution') }}</p>
        <button-wrapper id="close-confirm-email-modal" variant="secondary" @click.native="closeModal">
          {{ $t('general.close') }}
        </button-wrapper>
      </template>

      <template v-else>
        <p>{{ $t('component.confirmEmailModal.successMsg') }}</p>
        <button-wrapper icon="mdi-arrow-right"
                        id="close-confirm-email-modal"
                        variant="secondary"
                        @click.native="closeModal">
          {{ $t('general.continueUsing') }}
        </button-wrapper>
      </template>
    </div>
  </b-modal>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { ModalPlugin } from 'bootstrap-vue';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';

Vue.use(ModalPlugin);

@Component({
  components: { ButtonWrapper }
})
export default class ConfirmEmailModal extends Vue {
  error: boolean = false;
  message: string|null = null;

  async mounted () {
    if (this.$route.query['email-token']) {
      try {
        const response = await AuthService.confirmEmail(this.$route.query['email-token'] as string);
        this.message = response.data.message;
      } catch (e) {
        this.error = true;
        this.message = e.response.data.message;
      }

      // Show dialog
      this.$bvModal.show('confirm-email-modal');
    }
  }

  closeModal (): void {
    this.$bvModal.hide('confirm-email-modal');
  }

  hideModal (): void {
    let query = Object.assign({}, this.$route.query);
    delete query['email-token'];
    this.$router.replace({ query });
  }
}
</script>
