<template>
  <div>
    <action-panel>
      <router-link class="btn btn-secondary" role="button" to="/user-roles/add">
        {{ $t('page.title.userRoleAdd') }}
      </router-link>
    </action-panel>
    <datatable hide-utility-bar-bottom
               history-mode
               selectable
               table-id="users-roles"
               :actions="actions"
               :bulk-actions="bulkActions"
               :columns="columns"
               :filter-config="filters"
               :service="service"
               @datatable:action:delete="deleteRoles"
               @datatable:action:edit="editRole">
    </datatable>
  </div>
</template>

<script lang="ts">
import { VNode } from 'vue';
import { AxiosResponse } from 'axios';
import { Component, Mixins } from 'vue-property-decorator';

import { ActionMode } from '@/enum/ActionMode';
import { apiErrorsToList, checkPermissions } from '@/helpers';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import { EventBus } from '@/event-bus';
import { ToastVariant } from '@/enum/ToastVariant';

import ActionPanel from '@/components/ui/ActionPanel.vue';
import Datatable from '@/components/datatable/Datatable.vue';
import DeleteModalMixin from '@/mixins/DeleteModalMixin';
import RoleModel from '@/models/RoleModel';
import ToastMixin from '@/mixins/ToastMixin';
import UserRoleMixin from '@/mixins/configs/UserRoleMixin';
import UserRoleService from '@/services/UserRoleService';

@Component({
  components: { ActionPanel, Datatable }
})
export default class UsersPage extends Mixins(DeleteModalMixin, ToastMixin, UserRoleMixin) {
  actions: Array<DatatableActionModel> = [];
  bulkActions: Array<DatatableActionModel> = [];
  service: any = UserRoleService.all;

  created (): void {
    if (checkPermissions(['roles:write'])) {
      this.actions.push({
        label: this.$t('general.edit') as string,
        eventId: 'edit',
        icon: 'mdi-pencil'
      });
    }

    if (checkPermissions(['roles:delete'])) {
      this.actions.push({
        label: this.$t('general.delete') as string,
        eventId: 'delete',
        critical: true,
        icon: 'mdi-delete'
      });
      this.bulkActions.push({
        label: this.$t('general.delete') as string,
        eventId: 'delete',
        critical: true,
        icon: 'mdi-delete'
      });
    }
  }

  async deleteRoles (item: Array<RoleModel>|RoleModel, critical: boolean, mode: ActionMode): Promise<any> {
    let ids: Array<number> = mode === ActionMode.single
      ? [((item as RoleModel).id as number)]
      : (item as Array<RoleModel>).map((item): number => (item.id as number));

    let modal: any = await this.deleteModal(
      this.$tc('page.userRoles.deleteModalTitle', ids.length),
      this.$tc('page.userRoles.deleteModalText', ids.length),
      this.$tc('page.userRoles.deleteModalOk', ids.length)
    );

    if (!modal) {
      return;
    }

    try {
      let response: AxiosResponse = await UserRoleService.bulkDelete(ids);

      EventBus.$emit('datatable:refresh');
      this.toast(
        this.$tc('page.userRoles.deletedTitle', response.data.count),
        response.data.message,
        ToastVariant.success
      );
    } catch (e) {
      let errors: VNode|null = null;

      if (e.response.status === 422) {
        errors = apiErrorsToList(e.response.data.errors);
      }

      this.toast(
        this.$tc('page.userRoles.deletedTitleError', ids.length),
        this.$createElement('div', { staticClass: e.response.status }, [e.response.data.message, errors]),
        ToastVariant.danger
      );
    }
  }

  editRole (item: RoleModel): void {
    this.$router.push({ path: `/user-roles/${item.id}` });
  }
}
</script>
