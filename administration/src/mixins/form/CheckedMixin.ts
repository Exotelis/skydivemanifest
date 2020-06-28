import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - checkbox radio

@Component({})
export default class CheckedMixin extends Vue {
  @Prop([Boolean]) readonly checked!: boolean;
}
