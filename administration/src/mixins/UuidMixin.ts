import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { uuidv4 } from '@/helpers';

@Component
export default class UuidMixin extends Vue {
  uuid: string = uuidv4();
}
