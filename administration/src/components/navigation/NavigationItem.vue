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
      <navigation-item v-for="(c, key) in config.children" v-on="$listeners" :key="key" :config="c">
      </navigation-item>
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

    <template v-if="config.type === 'hidden'"></template>
  </li>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop, Watch } from 'vue-property-decorator';
import { checkPermissions } from '@/helpers';
import { NavigationModel } from '@/components/navigation/NavigationModel';
import { NavigationType } from '@/components/navigation/NavigationType';
import { Route } from 'vue-router';
import { routesMap } from '@/router/routes';

// Todo Use portals for sub navigation in vue 3 to prevent the css workaround
@Component({ name: 'navigation-item' })
export default class NavigationItem extends Vue {
  @Prop({ required: true }) readonly config!: NavigationModel;
  isSubmenuOpen: boolean = false;
  route!: Route;

  created (): void {
    if (this.config.type === NavigationType.Path) {
      this.route = routesMap.get(this.config.path) as Route;

      // Hide element if not permissions for this route
      if (this.route.meta.permissions && !checkPermissions(this.route.meta.permissions)) {
        this.config.type = NavigationType.Hidden;
      }
    }

    // Hide empty submenus and empty title groups
    if (this.config.type === NavigationType.Submenuhandler || this.config.type === NavigationType.Title) {
      if (typeof this.config.children === 'undefined' || this.config.children.length === 0) {
        this.config.type = NavigationType.Hidden;
      }
    }
  }

  toggleSubmenu (): void {
    this.isSubmenuOpen = !this.isSubmenuOpen;
    this.$emit('toggle-submenu', this);
  }

  @Watch('config.children', { deep: true })
  onChildrenUpdate (children: Array<NavigationModel>): void {
    // Check if all children are hidden then hide this as well
    if (!children.some((child) => child.type !== NavigationType.Hidden)) {
      this.config.type = NavigationType.Hidden;
    }
  }
}
</script>
