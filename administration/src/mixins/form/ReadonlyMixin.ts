import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - date datetime-local email month number password search tel text time url week
// Also available on the textarea element

@Component({})
export default class ReadonlyMixin extends Vue {
  @Prop([Boolean]) readonly plaintext!: boolean;
  @Prop([Boolean]) readonly readonly!: boolean;
}
