import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component({})
export default class ListMixin extends Vue {
  @Prop({ default: null }) readonly list!: boolean;
}
