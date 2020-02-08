<template>
  <nav id="main-navigation">
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link to="/" class="nav-link font-weight-bold mdi mdi-speedometer">
          {{ $t('pages.title.dashboard') }}
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/settings" class="nav-link font-weight-bold mdi mdi-settings-outline">
          {{ $t('pages.title.settings') }}
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'MainNavigation'
};
</script>
