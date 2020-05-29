import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

// Available on input with type - email file | the select element

@Component({})
export default class MultipleMixin extends Vue {
  @Prop({ default: false }) readonly multiple!: boolean;
}
