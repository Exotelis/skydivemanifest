<template>
  <div class="align-items-center d-flex justify-content-center">
    <!-- Single mode -->
    <div class="dropdown" v-if="mode === actionModes.single">
      <button aria-haspopup="true"
              aria-expanded="false"
              class="btn btn-link btn-sm datatable_btn-inline-action dropdown-toggle"
              data-toggle="dropdown"
              type="button"
              :id="'datatable_actions-' + uuid">
        <span class="align-middle mdi mdi-dots-horizontal"></span>
      </button>
      <div class="dropdown-menu" :aria-labelledby="'datatable_actions-' + uuid">
        <a class="dropdown-item"
           v-for="(action, key) in actions"
           :class="action.critical ? 'menu-item-danger' : ''"
           :key="key"
           @click="onActionClick(action)">
          <i v-if="action.icon" :class="['mdi', action.icon]"></i> {{ action.label }}
        </a>
      </div>
    </div>

    <!-- Bulk mode -->
    <template>
      <div class="btn-group" v-if="mode === actionModes.bulk">
        <button type="button"
                :class="['btn btn-sm', actions[0].critical ? 'btn-danger' : 'btn-primary']"
                :disabled="items.length === 0"
                @click="onActionClick(actions[0])">
          {{ actions[0].label }}
        </button>
        <button aria-haspopup="true"
                aria-expanded="false"
                class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"
                data-reference="parent"
                data-toggle="dropdown"
                type="button"
                v-if="actions.length > 1"
                :class="[
                  'btn btn-sm dropdown-toggle dropdown-toggle-split',
                  actions[0].critical ? 'btn-danger' : 'btn-primary'
                ]"
                :disabled="items.length === 0">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" v-if="actions.length > 1">
          <a class="dropdown-item"
             v-for="(action, key) in actions.slice(1)"
             :class="action.critical ? 'menu-item-danger' : ''"
             :key="key"
             @click="onActionClick(action)">
            <i v-if="action.icon" :class="['mdi', action.icon]"></i> {{ action.label }}
          </a>
        </div>
      </div>
      <span class="ml-2" v-if="items.length > 0">
        {{ $t('component.datatableActions.selected', { selected: items.length }) }}
      </span>
    </template>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import { ActionMode } from '@/enum/ActionMode';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import UuidMixin from '@/mixins/UuidMixin';

@Component
export default class DatatableActions extends Mixins(UuidMixin) {
  @Prop({ required: true, type: Array }) readonly actions!: Array<DatatableActionModel>;
  @Prop({ required: true }) readonly items!: object|Array<object>;
  @Prop({ default: ActionMode.single }) readonly mode!: ActionMode;

  actionModes: any = ActionMode;

  onActionClick (action: DatatableActionModel): void {
    this.$parent.$emit('datatable:action:' + action.eventId, this.items, action.critical, this.mode);
  }
}
</script>
