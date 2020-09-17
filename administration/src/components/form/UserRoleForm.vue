<template>
  <form class="container" id="userRoleForm" novalidate v-validate @change="setDirty" @submit.prevent="handleSubmit">
    <form-group label-for="name"
                :invalid-feedback="errors.name"
                :label="$t('form.label.userRoleName')">
      <input-text id="name"
                  required
                  v-model.trim="form.name"
                  :placeholder="$t('form.placeholder.userRoleName')"></input-text>
    </form-group>

    <form-group label-for="color"
                :label="$t('form.label.color')">
      <input-color id="color"
                   v-model.trim="form.color"></input-color>
    </form-group>

    <label v-show="Object.keys(permissions).length > 0">{{ $t('general.permissions') }}</label>
    <div class="table-responsive" v-show="Object.keys(permissions).length > 0">
      <table id="permissionsTable" class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th class="w-25" scope="col"></th>
            <th class="text-center w-25"
                scope="col"
                v-for="(type, index) in ['read', 'write', 'delete']"
                :key="index">
              <span class="pointer" @click="selectColumn(index + 1)">{{ $t('general.' + type) }}</span>
            </th>
          </tr>
        </thead>
        <tbody class="tbody-light" v-if="permissions">
          <tr v-for="(permissionGroup, name, index) in permissions" :key="index">
            <th class="text-nowrap" scope="row">
              <span class="pointer" @click="selectRow(index + 1)">
                {{ $t('permissions.' + name) }}
              </span>
            </th>

            <!-- Permission that are either read- write- and deletable at the same time or nothing -->
            <td class="pointer text-center"
                colspan="3"
                v-if="permissionGroup.all !== undefined"
                @click="selectCell($event.target)">
              <input-checkbox :aria-label="$t('permissions.' + name) + ': ' + $t('general.read') + ', ' +
                                           $t('general.write') + ', ' + $t('general.read')"
                              :disabled="permissionGroup.all.is_default || form.editable === false"
                              :id="name"
                              :name="form.permissions"
                              :value="permissionGroup.all.slug">
              </input-checkbox>
            </td>

            <template v-else>
              <td class="pointer text-center"
                  v-for="type in ['read', 'write', 'delete']"
                  :key="type"
                  @click="selectCell($event.target)">
                <input-checkbox v-if="permissionGroup[type] !== undefined"
                                :aria-label="$t('permissions.' + name) + ': ' + $t('general.' + type)"
                                :disabled="permissionGroup[type].is_default || form.editable === false"
                                :id="name + '-' + type"
                                :name="form.permissions"
                                :value="permissionGroup[type].slug">
                </input-checkbox>
                <span v-else class="font-weight-bolder">-</span>
              </td>
            </template>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</template>

<script lang="ts">
import { AxiosResponse } from 'axios';
import { Component, Mixins, Prop } from 'vue-property-decorator';

import { getErrorMessage } from '@/helpers';
import { ServiceModel } from '@/models/ServiceModel';
import { ToastVariant } from '@/enum/ToastVariant';

import FormGroup from '@/components/form/FormGroup.vue';
import FormInterface from '@/interfaces/FormInterface';
import FormMixin from '@/mixins/FormMixin';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputCheckbox from '@/components/form/InputCheckbox.vue';
import InputColor from '@/components/form/InputColor.vue';
import InputText from '@/components/form/InputText.vue';
import PermissionModel from '@/models/PermissionModel';
import PermissionService from '@/services/PermissionService';
import RoleModel from '@/models/RoleModel';
import ToastMixin from '@/mixins/ToastMixin';

@Component({
  components: { FormGroup, InputCheckbox, InputColor, InputText }
})
export default class UserRoleForm extends Mixins(FormMixin, FormValidationMixin, ToastMixin) implements FormInterface {
  @Prop({ required: true }) readonly formData!: RoleModel;
  @Prop({ required: true }) readonly service!: ServiceModel;
  @Prop({ required: true }) readonly successNotificationMessage!: string;
  @Prop({ required: true }) readonly successNotificationTitle!: string;

  form: RoleModel = {
    permissions: []
  };
  permissions: any = {};

  created (): void {
    this.form = Object.assign({}, this.form, this.formData);
    this.getPermissions();
  }

  async getPermissions (): Promise<any> {
    try {
      let response: AxiosResponse = await PermissionService.all();
      let permissions: any = {};

      for (let key in response.data) {
        let permission: PermissionModel = response.data[key];
        let splitSlugs: Array<string> = permission.slug.split(':');

        // Set defaults in model
        if (permission.is_default) {
          if (!this.form.permissions?.includes(permission.slug)) {
            this.form.permissions?.push(permission.slug);
          }
        }

        if (!permissions[splitSlugs[0]]) {
          permissions[splitSlugs[0]] = {};
        }

        if (splitSlugs.length === 1) {
          permissions[splitSlugs[0]]['all'] = permission;
          continue;
        }

        permissions[splitSlugs[0]][splitSlugs[1]] = permission;
      }

      this.permissions = Object.assign({}, permissions);
    } catch (e) {
      let message: string = getErrorMessage(e);
      this.toast(this.$t('page.userRoles.couldNotLoadPermissions') as string, message, ToastVariant.danger);
    }
  }

  async handleSubmit (): Promise<any> {
    this.$emit('validate');
    if (this.hasValidationError()) {
      return;
    }

    this.loading = true;
    this.$emit('form:loading', true);

    try {
      this.form.id ? await this.service(this.form.id, this.form) : await this.service(this.form);
      this.unsetDirty();
      await this.$router.push({ path: '/user-roles' });
      this.toast(this.successNotificationTitle, this.successNotificationMessage, ToastVariant.success);
    } catch (e) {
      this.validateResponse(e);

      if (!this.hasValidationError()) {
        let message: string = getErrorMessage(e);
        this.toast(this.$t('general.couldNotSave') as string, message, ToastVariant.danger);
      }
    }

    this.$emit('form:loading', false);
  }

  /**
   * Utility
   */
  getTable (): HTMLTableElement|null {
    return this.$el.querySelector('#permissionsTable');
  }

  selectCell (element: HTMLElement): void {
    const inputs: Array<HTMLInputElement> = Array.from(element.getElementsByTagName('input'));
    inputs.forEach((element: HTMLInputElement) => { element.click(); });
  }

  selectColumn (columnNr: number): void {
    const table: HTMLTableElement|null = this.getTable();
    if (table === null) {
      return;
    }

    let inputs: Array<HTMLInputElement> = [];
    for (let i = 1; i < table.rows.length; i++) {
      let cellLen: number = table.rows[i].cells.length;

      if (cellLen === table.rows[0].cells.length && columnNr < cellLen) {
        let result: HTMLCollectionOf<HTMLInputElement> = table.rows[i].cells[columnNr].getElementsByTagName('input');

        // Push input that should be clicked
        if (result.length > 0) {
          if (!result[0].disabled) {
            inputs.push(result[0]);
          }
        }
      }
    }

    inputs.filter((element: HTMLInputElement) => !element.disabled);
    this.toggleCheckboxes(inputs);
  }

  selectRow (rowNr: number): void {
    const table: HTMLTableElement|null = this.getTable();
    if (table === null) {
      return;
    }

    const inputs: Array<HTMLInputElement> = Array.from(table.rows[rowNr].getElementsByTagName('input'))
      .filter((element: HTMLInputElement) => !element.disabled);
    this.toggleCheckboxes(inputs);
  }

  toggleCheckboxes (inputs: Array<HTMLInputElement>): void {
    const unchecked: Array<HTMLInputElement> = inputs.filter((element: HTMLInputElement) => !element.checked);

    if (unchecked.length > 0) {
      unchecked.forEach((element: HTMLInputElement) => { element.click(); });
    } else {
      inputs.forEach((element: HTMLInputElement) => { element.click(); });
    }
  }
}
</script>
