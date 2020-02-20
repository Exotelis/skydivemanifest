import { iconValidator } from '@/validators';

export default {
  props: {
    disabled: { default: false, type: Boolean },
    form: { default: null },
    icon: { default: null, type: String, validator: iconValidator },
    id: { required: true },
    loading: { default: false, type: Boolean },
    rightAligned: { default: false, type: Boolean },
    tabindex: { default: null }
  }
};
