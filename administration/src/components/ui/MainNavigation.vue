<template>
  <nav id="main-navigation">
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link to="/" class="nav-link">
          <span></span>
          <span>{{ $t('pages.title.dashboard') }}</span>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/settings" class="nav-link">
          <span></span>
          <span>{{ $t('pages.title.settings') }}</span>
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
