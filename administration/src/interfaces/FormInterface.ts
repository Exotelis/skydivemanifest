export default interface FormInterface {
  disabledSubmit: boolean;
  error: string|null;
  loading: boolean;

  handleSubmit (): Promise<any>;
}
