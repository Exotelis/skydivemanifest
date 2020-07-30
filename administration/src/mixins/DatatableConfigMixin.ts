import Vue from 'vue';
import { Component, Prop } from 'vue-property-decorator';

@Component
export default class DatatableConfigMixin extends Vue {
  @Prop({ required: true, type: String }) readonly tableId!: string;

  /**
   * Return datatable config from localStorage, null if empty, or config for a specific component.
   *
   * @param tableId
   * @param component Return config of a given component, default: whole config
   */
  getConfig (tableId: string, component: null|string = null): any|null {
    let config: string|null = localStorage.getItem('datatable_' + tableId);

    if (config === null) {
      return null;
    }

    if (component === null) {
      return JSON.parse(config);
    }

    let componentValue: any|undefined = JSON.parse(config)[component];

    if (componentValue === undefined) {
      return null;
    }

    return componentValue;
  }

  /**
   * Store the config of the datatable in the localStorage.
   *
   * @param tableId
   * @param component
   * @param value     A JSON.stringify()'d array|object or some other basic datatype
   */
  storeConfig (tableId: string, component: string, value: string): void {
    let config: any|null = this.getConfig(tableId);

    if (config === null) {
      localStorage.setItem('datatable_' + tableId, JSON.stringify({ [component]: value }));
      return;
    }

    config[component] = value;
    localStorage.setItem('datatable_' + tableId, JSON.stringify(config));
  }
}
