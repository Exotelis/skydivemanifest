import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - color date datetime-local email month number range search tel text time url week

@Component({})
export default class ListMixin extends Vue {
  @Prop({ default: null }) readonly list!: string;
}
