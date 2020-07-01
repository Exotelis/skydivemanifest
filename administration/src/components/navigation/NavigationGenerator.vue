<template>
  <ul class="nav">
    <navigation-item v-for="(c, key) in config"
                     @close-all="closeAll"
                     @toggle-submenu="toggleSubmenu"
                     :key="key"
                     :config="c"
                     :portal="portal"
                     :showSubmenuClose="showSubmenuClose"
                     :showSubmenuTitle="showSubmenuTitle"
                     :submenusRight="submenusRight">
    </navigation-item>
  </ul>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import { NavigationGeneratorInterface } from '@/interfaces/NavigationGeneratorInterface';
import { NavigationModel } from '@/models/NavigationModel';
import NavigationItem from '@/components/navigation/NavigationItem.vue';

@Component({
  components: {
    NavigationItem
  }
})
export default class NavigationGenerator extends Vue implements NavigationGeneratorInterface {
  @Prop({ required: true }) readonly config!: Array<NavigationModel>;
  @Prop([Boolean]) readonly onlyOneSubmenu!: boolean;
  @Prop({ default: null }) readonly portal!: string;
  @Prop([Boolean]) readonly showSubmenuClose!: boolean;
  @Prop([Boolean]) readonly showSubmenuTitle!: boolean;
  @Prop([Boolean]) readonly submenusRight!: boolean;
  navigationItems: Array<Vue> = [];

  closeAll () {
    this.closeSubmenus();
  }

  toggleSubmenu (component: Vue) {
    if (component.$data.isSubmenuOpen) {
      if (this.onlyOneSubmenu) {
        this.closeSubmenus();
      }
      this.navigationItems.push(component);
    } else {
      this.navigationItems.splice(this.navigationItems.indexOf(component), 1);
    }
  }

  closeSubmenus () {
    const len: number = this.navigationItems.length;
    for (let i = 0; i < len; i++) {
      const comp = this.navigationItems.pop();
      comp!.$data.isSubmenuOpen = false;
    }
  }
}
</script>
