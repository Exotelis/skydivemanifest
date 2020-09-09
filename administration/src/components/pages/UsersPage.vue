<template>
  <div>
    <action-panel>
      <template v-if="!trashed">
        <router-link class="btn btn-secondary mdi mdi-delete" role="button" to="/users/trashed">
          {{ $t('page.title.usersTrashed') }}
        </router-link>
        <router-link class="btn btn-secondary mdi mdi-account-plus" role="button" to="/users/add">
          {{ $t('page.title.userAdd') }}
        </router-link>
      </template>
      <template v-if="trashed">
        <router-link class="btn btn-secondary mdi mdi-account" role="button" to="/users">
          {{ $t('page.title.users') }}
        </router-link>
      </template>
    </action-panel>
    <datatable hide-utility-bar-bottom
               history-mode
               selectable
               table-id="users"
               :actions="actions"
               :bulk-actions="bulkActions"
               :columns="columns"
               :filter-config="filters"
               :filters-lazy-loading="filtersLazyLoading"
               :service="service"
               @datatable:action:delete="deleteUsers"
               @datatable:action:restore="restoreUsers">
    </datatable>
  </div>
</template>

<script lang="ts">
import { AxiosResponse } from 'axios';
import { Component, Mixins } from 'vue-property-decorator';

import { ActionMode } from '@/enum/ActionMode';
import { checkPermissions } from '@/helpers';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import { EventBus } from '@/event-bus';
import { ToastVariant } from '@/enum/ToastVariant';

import ActionPanel from '@/components/ui/ActionPanel.vue';
import Datatable from '@/components/datatable/Datatable.vue';
import DeleteModalMixin from '@/mixins/DeleteModalMixin';
import ToastMixin from '@/mixins/ToastMixin';
import UserMixin from '@/mixins/configs/UserMixin';
import UserModel from '@/models/UserModel';
import UserService from '@/services/UserService';

@Component({
  components: { ActionPanel, Datatable }
})
export default class UsersPage extends Mixins(DeleteModalMixin, ToastMixin, UserMixin) {
  actions: Array<DatatableActionModel> = [];
  bulkActions: Array<DatatableActionModel> = [];
  service: any = UserService.all;
  trashed!: boolean;

  created (): void {
    this.loadAsyncFilters();
    this.trashed = this.$router.currentRoute.name === 'users-trashed';

    if (this.trashed) {
      this.service = UserService.trashed;
    }

    if (checkPermissions(['users:delete'])) {
      if (this.trashed) {
        this.actions.push(
          {
            label: this.$t('general.restore') as string,
            eventId: 'restore',
            icon: 'mdi-delete-restore'
          }, {
            label: this.$t('general.deletePermanently') as string,
            eventId: 'delete',
            critical: true,
            icon: 'mdi-delete'
          }
        );
        this.bulkActions.push(
          {
            label: this.$t('general.restore') as string,
            eventId: 'restore',
            icon: 'mdi-delete-restore'
          }, {
            label: this.$t('general.deletePermanently') as string,
            eventId: 'delete',
            critical: true,
            icon: 'mdi-delete'
          }
        );
      } else {
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
  }

  async deleteUsers (item: Array<UserModel>|UserModel, critical: boolean, mode: ActionMode): Promise<any> {
    let ids: Array<number> = mode === ActionMode.single
      ? [(item as UserModel).id]
      : (item as Array<UserModel>).map(item => item.id);

    const modalOk: string = this.trashed
      ? this.$tc('page.users.deleteModalOkPermanently', ids.length)
      : this.$tc('page.users.deleteModalOk', ids.length);
    const modalText: string = this.trashed
      ? this.$tc('page.users.deleteModalTextPermanently', ids.length)
      : this.$tc('page.users.deleteModalText', ids.length);
    const modalTitle: string = this.trashed
      ? this.$tc('page.users.deleteModalTitlePermanently', ids.length)
      : this.$tc('page.users.deleteModalTitle', ids.length);
    let modal: any = await this.deleteModal(
      modalTitle,
      modalText,
      modalOk
    );

    if (!modal) {
      return;
    }

    try {
      let response: AxiosResponse;
      if (this.trashed) {
        // Delete permanently
        response = await UserService.deletePermanently(ids);
      } else {
        // Soft delete
        response = await UserService.bulkDelete(ids);
      }

      EventBus.$emit('datatable:refresh');
      this.toast(this.$tc('page.users.deletedTitle', response.data.count), response.data.message, ToastVariant.success);
    } catch (e) {
      this.toast(
        this.$tc('page.users.deletedTitleError', ids.length),
        this.$tc('page.users.deletedError', ids.length),
        ToastVariant.danger
      );
    }
  }

  async restoreUsers (item: Array<UserModel>|UserModel, critical: boolean, mode: ActionMode): Promise<any> {
    let ids: Array<number> = mode === ActionMode.single
      ? [(item as UserModel).id]
      : (item as Array<UserModel>).map(item => item.id);

    try {
      let response: AxiosResponse = await UserService.restore(ids);
      EventBus.$emit('datatable:refresh');
      this.toast(
        this.$tc('page.users.restoredTitle', response.data.count),
        response.data.message,
        ToastVariant.success
      );
    } catch (e) {
      this.toast(this.$tc('page.users.restoredTitleError', ids.length), e.response.data.message, ToastVariant.danger);
    }
  }
}
</script>
