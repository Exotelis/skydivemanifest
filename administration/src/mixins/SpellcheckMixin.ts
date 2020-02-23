import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component({})
export default class SpellcheckMixin extends Vue {
  @Prop({ default: false }) readonly spellcheck!: boolean;
}
