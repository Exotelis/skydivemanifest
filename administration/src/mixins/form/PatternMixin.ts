import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - email password search tel text url

@Component({})
export default class PatternMixin extends Vue {
  @Prop({ default: null }) readonly pattern!: string;
}
