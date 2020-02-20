import { inputmodeValidator } from '@/validators';

export default {
  props: {
    inputmode: { default: null, type: String, validator: inputmodeValidator }
  }
};
