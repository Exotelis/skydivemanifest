import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component({})
export default class ReadonlyMixin extends Vue {
  @Prop({ default: false }) readonly readonly!: boolean;
}
