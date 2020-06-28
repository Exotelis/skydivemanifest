<template>
  <div class="col-xxs-auto">
    <div class="dropdown">
      <button class="btn btn-link dropdown-toggle default-font-size mdi mdi-account" type="button"
              id="user-panel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="d-none d-md-inline-block">{{ user.firstname }}</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user-panel">
        <a class="dropdown-item disabled" href="#" tabindex="-1" >
          {{ $t('general.accountSettings', { email: user.email }) }}
        </a>
        <a class="dropdown-item menu-item-danger" href="#" tabindex="-1"  v-logout>
          {{ $t('general.signOut') }}
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { getUser } from '@/helpers';
import Logout from '@/directives/LogoutDirective';
import UserShortModel from '@/models/UserShortModel';

@Component({
  directives: { Logout }
})
export default class UserMenu extends Vue {
  user: UserShortModel = getUser();
  // TODO pull user information from api as soon as the users/:id resource exist.
  // TODO pull on login and when the user changed.
}
</script>
