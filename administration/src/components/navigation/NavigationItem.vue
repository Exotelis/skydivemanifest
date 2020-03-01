<template>
  <li class="nav-item">
    <template v-if="config.type === 'path' && this.route">
      <router-link class="nav-link font-weight-bold"
                   @click.native="$emit('close-all')"
                   :to="route.path"
                   :class="[config.icon ? 'mdi ' + config.icon : '']">
        {{ $t(route.meta.title) }}
      </router-link>
    </template>

    <template v-if="config.type === 'title'">
      <span class="nav-link font-weight-bold menu-subtitle"
            :class="[config.icon ? 'mdi ' + config.icon : '']">
        {{ $t(config.title) }}
      </span>
    </template>

    <template v-if="config.type === 'submenuhandler'">
      <a class="nav-link font-weight-bold"
         href=""
         @click.prevent="toggleSubmenu()"
         :class="[config.icon ? 'mdi ' + config.icon : '']">
        {{ $t(config.title) }}
      </a>
      <ul class="nav flex-column submenu" :class="{ 'open': isSubmenuOpen }">
        <li><span class="submenu-title">{{ $t(config.title) }}</span></li>
        <li><a class="mdi mdi-close" @click.prevent="toggleSubmenu()" href=""></a></li>
        <navigation-item v-for="(c, key) in config.children" v-on="$listeners" :key="key" :config="c">
        </navigation-item>
      </ul>
    </template>
  </li>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { NavigationModel } from '@/components/navigation/NavigationModel';
import { routesMap } from '@/router/routes';

// Todo Use portals for sub navigation in vue 3 to prevent the css workaround
@Component({ name: 'navigation-item' })
export default class NavigationItem extends Vue {
  @Prop({ required: true }) readonly config!: NavigationModel;
  isSubmenuOpen: boolean = false;
  route!: object;

  created (): void {
    if (this.config.type === 'path') {
      this.route = routesMap.get(this.config.path) as object;
    }
  }

  toggleSubmenu (): void {
    this.isSubmenuOpen = !this.isSubmenuOpen;
    this.$emit('toggle-submenu', this);
  }
}
</script>
