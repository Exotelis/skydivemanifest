<template>
  <nav>
    <ul>
      <navigation-item :start="this.dataStart" :depth="this.dataDepth"></navigation-item>
    </ul>
  </nav>
</template>

<script>
import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';
import NavigationItem from '@/components/navigation/NavigationItem.vue';

@Component({})
export default class NavigationGenerator extends Vue {

}

export default {
  name: 'NavigationGenerator',
  components: {
    NavigationItem
  },
  data: function () {
    return {
      dataStart: undefined,
      dataDepth: undefined
    };
  },
  props: ['start', 'depth'],
  created: function () {
    this.dataStart = this.$router.options.routes;
    this.dataDepth = this.depth || 0;
    // If start is defined, find it and set children as start. If it does not exist, use default
    if (this.start) {
      for (let i in this.$router.options.routes) {
        let route = this.$router.options.routes[i];
        if (this.start === route.path && Array.isArray(route.children) && route.children.length > 0) {
          this.dataStart = route.children;
          break; // leave loop on match
        }
      }
    }
  }
};
</script>
