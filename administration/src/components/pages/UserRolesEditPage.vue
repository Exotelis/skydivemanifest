<template>
  <div>
    <action-panel back-button back-path="/user-roles" container>
      <button-wrapper id="deleteRoleButton"
                      type="button"
                      variant="secondary"
                      v-if="form.deletable"
                      :loading="loadingDelete"
                      @click.native="handleDelete">
        {{ $t('page.userRoles.delete') }}
      </button-wrapper>

      <!-- form is defined in the user-role-form component -->
      <button-wrapper form="userRoleForm"
                      id="updateRoleButton"
                      type="submit"
                      variant="secondary"
                      :loading="loadingEdit"
                      @click.native="handleSubmit">
        {{ $t('page.userRoles.edit') }}
      </button-wrapper>
    </action-panel>

    <user-role-form ref="userRoleForm"
                    v-if="roleLoaded"
                    :form-data="form"
                    :service="service"
                    :success-notification-message="successMessage"
                    :success-notification-title="successTitle"
                    @form:loading="updateLoading"
                    @form:dirty="dirty = $event">
    </user-role-form>
    <loading-spinner v-else></loading-spinner>
  </div>
</template>

<script lang="ts">
import { AxiosResponse } from 'axios';
import { Component, Mixins } from 'vue-property-decorator';

import { getErrorMessage } from '@/helpers';
import { ServiceModel } from '@/models/ServiceModel';
import { ToastVariant } from '@/enum/ToastVariant';

import ActionPanel from '@/components/ui/ActionPanel.vue';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import DeleteModalMixin from '@/mixins/DeleteModalMixin';
import DirtyModalMixin from '@/mixins/DirtyModalMixin';
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';
import PermissionModel from '@/models/PermissionModel';
import RoleModel from '@/models/RoleModel';
import ToastMixin from '@/mixins/ToastMixin';
import UserRoleForm from '@/components/form/UserRoleForm.vue';
import UserRoleService from '@/services/UserRoleService';

@Component({
  components: {
    ActionPanel,
    ButtonWrapper,
    LoadingSpinner,
    UserRoleForm
  }
})
export default class UserRolesEditPage extends Mixins(DeleteModalMixin, DirtyModalMixin, ToastMixin) {
  form: RoleModel = {};
  id!: number;
  loadingDelete: boolean = false;
  loadingEdit: boolean = false;
  roleLoaded: boolean = false;
  service: ServiceModel = UserRoleService.update;
  successMessage: string = '';
  successTitle: string = '';

  created (): void {
    this.id = parseInt(this.$route.params.id);
    this.successMessage = this.$t('page.userRoles.updatedMessage') as string;
    this.successTitle = this.$t('page.userRoles.updatedTitle') as string;

    this.getRole();
  }

  async getRole (): Promise<any> {
    try {
      let response: AxiosResponse = await UserRoleService.get(this.id);
      this.form = {
        color: response.data.color,
        deletable: response.data.deletable,
        editable: response.data.editable,
        id: response.data.id,
        name: response.data.name,
        permissions: response.data.permissions.map((permissions: PermissionModel) => permissions.slug)
      };
      this.roleLoaded = true;
    } catch (e) {
      let message = getErrorMessage(e);
      await this.$router.go(-1);
      this.toast(this.$t('page.userRoles.couldNotLoadRole') as string, message, ToastVariant.danger);
    }
  }

  async handleDelete (): Promise<any> {
    const modal: any = await this.deleteModal(
      this.$tc('page.userRoles.deleteModalTitle', 1),
      this.$tc('page.userRoles.deleteModalText', 1),
      this.$tc('page.userRoles.deleteModalOk', 1)
    );

    if (!modal) {
      return;
    }

    this.loadingDelete = true;

    try {
      const response: AxiosResponse = await UserRoleService.delete(this.id);
      await this.$router.push({ path: '/user-roles' });
      this.toast(this.$tc('page.userRoles.deletedTitle', 1), response.data.message, ToastVariant.success);
    } catch (e) {
      let message = getErrorMessage(e);
      this.toast(this.$tc('page.userRoles.deletedTitleError', 1), message, ToastVariant.danger);
    }

    this.loadingDelete = false;
  }

  handleSubmit (): void {
    (this.$refs.userRoleForm as any).handleSubmit();
  }

  updateLoading (state: boolean): void {
    this.loadingEdit = state;
  }
}
</script>
