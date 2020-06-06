<template>
  <span>{{ name }}</span>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

@Component({})
export default class RegisterPage extends Vue {
  name: string = 'RegisterPage';
}
</script>
