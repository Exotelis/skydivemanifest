import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - checkbox date datetime-local email file month number password radio search tel text
//                                time url week
// Also available on the select and textarea elements

@Component({})
export default class RequiredMixin extends Vue {
  @Prop({ default: false }) readonly required!: boolean;
}
