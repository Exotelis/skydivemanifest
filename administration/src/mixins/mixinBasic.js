import { autocompleteValidator } from '@/validators';

export default {
  props: {
    autocomplete: { default: null, validator: autocompleteValidator },
    autofocus: { default: false, type: Boolean },
    description: { default: null, type: String },
    disabled: { default: false, type: Boolean },
    errorText: { default: null, type: String },
    form: { default: null },
    id: { required: true },
    label: { required: true, type: String },
    required: { default: false, type: Boolean },
    tabindex: { default: null },
    value: { default: null }
  }
};
