<template>
  <div>
    <action-panel back-button back-path="/user-roles" container>
      <!-- form is defined in the user-role-form component -->
      <button-wrapper form="userRoleForm"
                      id="createRoleButton"
                      type="submit"
                      variant="secondary"
                      :loading="loading"
                      @click.native="handleSubmit">
        {{ $t('page.userRoles.create') }}
      </button-wrapper>
    </action-panel>

    <user-role-form ref="userRoleForm"
                    :form-data="form"
                    :service="service"
                    :success-notification-message="successMessage"
                    :success-notification-title="successTitle"
                    @form:loading="updateLoading"
                    @form:dirty="dirty = $event">
    </user-role-form>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';

import { ServiceModel } from '@/models/ServiceModel';

import ActionPanel from '@/components/ui/ActionPanel.vue';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import DirtyModalMixin from '@/mixins/DirtyModalMixin';
import RoleModel from '@/models/RoleModel';
import UserRoleForm from '@/components/form/UserRoleForm.vue';
import UserRoleService from '@/services/UserRoleService';

@Component({
  components: {
    ActionPanel,
    ButtonWrapper,
    UserRoleForm
  }
})
export default class UserRolesAddPage extends Mixins(DirtyModalMixin) {
  form: RoleModel = {
    color: '#' + [...Array(6)].map(() => Math.floor(Math.random() * 16).toString(16)).join(''),
    permissions: []
  };
  loading: boolean = false;
  service: ServiceModel = UserRoleService.add;
  successMessage: string = '';
  successTitle: string = '';

  created (): void {
    this.successMessage = this.$t('page.userRoles.createdMessage') as string;
    this.successTitle = this.$t('page.userRoles.createdTitle') as string;
  }

  handleSubmit (): void {
    (this.$refs.userRoleForm as any).handleSubmit();
  }

  updateLoading (state: boolean): void {
    this.loading = state;
  }
}
</script>
