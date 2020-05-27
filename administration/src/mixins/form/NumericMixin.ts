import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - date datetime-local month number range time week

@Component({})
export default class NumericMixin extends Vue {
  @Prop({ default: null }) readonly max!: string;
  @Prop({ default: null }) readonly min!: string;
  @Prop({ default: null }) readonly step!: number;
}
