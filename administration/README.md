# Administration Bundle
Welcome to the skydivemanifest administration bundle. You can follow the documentation for
development or to build the bundle, to use it in production.

## Table of contents
- [Project setup](#project-setup)
  + [Install the dependencies](#install-the-dependencies)
  + [Compiles and hot-reloads for development](#compiles-and-hot-reloads-for-development)
  + [Compiles and bundles for production](#compiles-and-bundles-for-production)
  + [Test your app](#test-your-app)
  + [Lints and fixes files](#lints-and-fixes-files)
- [Developers guide](#developers-guide)
  + [NavigationGenerator and NavigationItems](#navigationgenerator-and-navigationitems)
  + [Form components](#form-components)
    * [form-group](#form-group)
  + [Form validation](#form-validation)
  + [Layouts](#layouts)
- [Internationalization](#internationalization)
  + [Change the default language](#change-the-default-language)
  + [Disable prefetch](#disable-prefetch)
- [Testing](#testing)
- [Configuration](#configuration)
- [Troubleshooting](#troubleshooting)
  + [404 error when accessing a route directly](#404-error-when-accessing-a-route-directly)
  + [Module linking in Jetbrains products](#module-linking-in-jetbrains-products)

## Project setup
### Install the dependencies
Install the dependencies for the administration bundle by running:
```
npm install
```

### Compiles and hot-reloads for development
You can serve the bundle in development mode by running:
```
npm run serve
```
This command will refresh the package any time your change you source files

### Compiles and bundles for production
To use the administration bundle in production, you should adapt the `vue.config.js` to your needs.
See [Configuration Reference](https://cli.vuejs.org/config/) for details.

Once this is done just run:
```
npm run build
```
Note: You should always lint before building

### Test your app
To test you source code, simply run:
```
npm run test:unit
```
Please see [Testing Single-File Components with Jest](https://vue-test-utils.vuejs.org/guides/#testing-single-file-components-with-jest)
for more details on how to write tests.

### Lints and fixes files
```
npm run lint
```

## Developers guide
This section should help any developer to implement new features or fix bugs.

### NavigationGenerator and NavigationItems
To automatically generate a navigation menu, you can use the
[NavigationGenerator](src/components/navigation/NavigationGenerator.vue) component. The component requires just a single
property `config: Array<NavigationModel>`. Please see the definition of the
[NavigationModel](src/components/navigation/NavigationModel.ts). The only required key of the NavigationModel is `type`.
You can choose one out of three [NavigationTypes](src/components/navigation/NavigationType.ts). Using `Path` will
automatically load the route information matching the given path:
```
{ path: '/', type: NavigationType.Path }
```
The type `Submenuhandler` will generate a submenu. `Title` can be used to group the menu items, since title is not
clickable. When using one of those type, you should also provide a title for the menu item:
```
{ title: 'Submenutitel',
  type: NavigationType.Submenuhandler,
  children: [
    { title: 'User stuff', type: NavigationType.Title },
    { path: '/userroles', type: NavigationType.Path }
  ]
}
```
As you can see in the example above, a `Submenuhandler` just makes sense with some children defined. Finally, you can
define an icon for each menu item. The icon must be the classname of an existing mdi icon:
```
{ icon: 'mdi-airplane', path: '/aircrafts', type: NavigationType.Path }
```
By default the property `onlyOneSubmenu` is true. That means that any other open submenu will be closed, when the user
opens another one. Settings this to false allows the user to open multiple submenus.

Of course, you don't have to use the `NavigationGenerator` and its config, you could also directly use the
[NavigationItem](src/components/navigation/NavigationItem.vue) component. It also needs a config, but not as an array.

Example usage:
```
<navigation-generator class="flex-column" ref="mainNavigation" :config="mainNavigationConfig"></navigation-generator>
```
The ref is used to call the `closeAll` method of the `NavigationGenerator`, when the user clicks anywhere outside of the
navigation menu.

### Form components
From components are more or less a wrapper for from elements such as input fields, select boxes and submit buttons. They
should guarantee that the html structure is unified.

All existing form components are located in the [form](src/components/form/) directory. As you might have noticed, we
also defined some mixins. Since some attributes are not available on the different form elements or input types, it was
easier to create different mixins to share the props. So if some specification will change in the future, it'll be
easier to adapt the props. To learn more about the available attributes please see
[the input documentation](https://developer.mozilla.org/de/docs/Web/HTML/Element/Input).

Example Code:
```
<form @submit.prevent="login" novalidate>
  <text-input id="username" :label="$t('login.username.label')" :required="true" v-model="username"></text-input>
  <password-input id="password" :is-toggleable="true" :label="$t('login.password.label')" :required="true" v-model="password"></password-input>
  <div class="clearfix">
    <button-wrapper right-aligned>{{ $t('login.signIn') }}</button-wrapper>
  </div>
</form>
```
Every wrapper component needs to be imported and defined in the component decorator:
```
// Other imports
import FormGroup from '@/components/form/FormGroup.vue';

@Component({
  components: {
    // Other components
    FormGroup
  }
})
export default class ExampleClass extends Vue {}
```

These are the available form element wrapper:

#### form-group
The `form-group` is the surrounding element of almost every form element, except buttons. The `form-group` can be used
to add a label, validation text, or a description. It can also be used the change the look of the form elements. Usually
the label would be placed above the form element, but if you set the `horizontal` attribute, the label and the form
element will be on the same row. Responsiveness is another important thing in modern web-app. If you set the horizontal
attribute, it would also make sense to use the `labelColXs, labelColSm, labelColMd, labelColLg, labelColXl, labelColXxl,
labelColXxxl` attributes. Those `labelCol` attributes are the grid. You can choose a number out of 1 to 12. The
available space of the form will be divided by 12, the label will then take the space of x parts of it, depending on
what number you chose. The `Xs, Sm, Md, Lg, Xl, Xxl and Xxxl` identifier indicate the with of the browser in px. To see
for what width which identifier stands for, see the
[_variables.scss](https://github.com/Exotelis/skydivemanifest/blob/api/administration/src/assets/scss/themes/default/_varibales.scss#L39).
```
<form-group label="Example"
            label-for="exampleId"
            label-col-md="4"
            description="Please enter some example text"
            invalid-feedback="Something went wrong"
            valid-feedback="Ok!"
            :horizontal="true">
<!-- your form element wrapper goes here -->
</form-group>
```

#### input-text
Creates an input element with type text. If the attributes `plaintext` and `readonly` are both true, the styling of the
form element will be removed.

A full list of available attributes you can find in the [InputText.vue component](src/components/form/InputText.vue).

Example:
```
<input-text autofocus
            id="name"
            placeholder="Your name"
            required
            v-model="form.name"
            :plaintext="true"
            :readonly="true"></input-text>
```

#### input-password
Creates an input element with type password. The attribute `:is-toggleable` is true, an icon will be displayed to toggle
the visibility of the password.

A full list of available attributes you can find in the
[InputPassword.vue component](src/components/form/InputPassword.vue).

Example:
```
<input-password id="password"
                placeholder="Your password"
                required
                v-model="form.password"
                :is-toggleable="true"></input-password>
```

#### input-hidden
Creates an input element with type hidden. The attribute `value` must be set, to submit any data.

Example:
```
<input-hidden form="formId" id="hidden01" type="hidden" value="Some value">
```

#### button-wrapper
Creates a button. By setting the `variant` attribute, you can choose the color scheme of the button. The `:loading`
attribute can be used to disable the button as long as a request is pending. If you want to right align a button, you
can set the attribute `:right-aligned` to `true`. Note that this requires to wrap the button in a clearfix `div`.

Example:
```
<div class="clearfix">
    <button-wrapper icon="mdi-login"
                    id="signin"
                    type="submit"
                    :disabled="disabledSubmit"
                    :loading="loading"
                    :right-aligned="true">{{ $t('login.signIn') }}</button-wrapper>
</div>
```

### Form validation
The form validation in this project covers both, the client and the server side validation. To make use of the
validation, you have to import and extend the `FormValidationMixin`:
```
import FormValidationMixin from '@/mixins/FormValidationMixin';

@Component({})
export default class YourClass extends Mixins(FormValidationMixin) {
```
This will add all required functionalities to your component. To validate all elements of form you can add the
`v-validate` directive to this specific form element.
```
<form v-validate novalidate>
```
Note that the `novalidate` attribute suppresses the build in HTML5 validation. By adding the `v-validate` directive all
form elements with one or more of the already existing attributes `type, required, min, max, minlength, maxlength, step,
pattern` will be checked. You MUST also add an id attribute to every form element:
```
<input type="email" id="usermail">
```
This will already validate your form correctly, but will neither display any error nor style the form element correctly.
However, if the validation fails it will add a class `is-invalid` to the form element. You could either add your
customized css or make use of the [form components](#form-components). We recommend:
```
<input-email id="usermail" required>
```
If the validation fails, the error will be stored in the `errors` object. You can either define your own element to
display the error message:
```
<!-- This approach is not recommended, please see the form-group -->
<input-email id="usermail" required>
<span v-if="errors.usermail">{{ errors.usermail }}</span>
```
or make use of the `form-group` component, which is recommended.
```
<form-group :invalid-feedback="errors.usermail">
    <input-email id="usermail" required>
</form-group>
```
As you might have noticed already, the `id` of the form element will be the key in the errors object.

Once this is done, the form element will be validated when the user focuses another element, or when the user stops
typing for some while. You can also validate the form, when the user clicks the submit button. You need to catch the
`submit` event and handle the validation in your typescript code:
```
<form @submit.prevent="handleSubmit" v-validate novalidate>

handleSubmit (): void {
    this.$emit('validate');
}
```
Broadcasting the `validate` event will check all form elements for errors. In the `handleSubmit` method, you can also
validate the server response. If the validation fails on the server side, the response code MUST be 422, and the
response body must have the following structure:
```
data: {
  'message': 'The given data was invalid.',
  'errors': {
    'usermail': [
      'An account with this email address already exists.'
    ]
  }
}
```
Note that the key is once again the id of the form element. The server response must be validated after broadcasting the
`validate` event, otherwise it would not work:
```
handleSubmit (): void {
    let response = // Get response from server
    this.$emit('validate');
    this.validateResponse(response);
}
```
Last but not least you can also check for errors before handling the response or sending a request:
```
handleSubmit (): void {
    let response = // Get response from server
    this.$emit('validate');
    this.validateResponse(response);
    if (this.hasValidationError()) {
        // Do something
    }
}
```

### Layouts
Different routes can have different layouts. To set a layout for a specific route just add the meta field `layout` with
the name of the layout component you want to use:
```
meta: {
  layout: 'Default', // Use proper case for the layout (Default instead of default)
  ...
}
```
Browse the directory [layout](src/components/layouts) to see which layouts does exist.

If you want to add your own layouts, just add a new component in the directory mentioned above. To start your work, just
copy the content of the [DefaultLayout.vue](src/components/layouts/DefaultLayout.vue). Note that the line
`<slot><router-view></router-view></slot>` is very important to display the component of each route. Once you added your
layout, import and register your component in [main.js](src/main.js).

You can also style your layouts separately. Just add a file to the directory
[layouts](src/assets/scss/themes/default/layouts) and name it appropriate to your layout. If your layouts name is
`DefaultLayout` you should name your stylesheet `_default.scss`. Finally, you must either import or replace your
stylesheet in the [app.scss](src/assets/scss/app.scss).

Note: The path in the assets folder might be different, when not using the default theme.

## Internationalization
The internationalization support is enabled by default. You'll find all supported languages in the
[src/locales](src/locales) directory. All languages defined in [src/locales/locales.json](src/locales/locales.json) will
be available in the [languageSelector](src/components/ui/LanguageSelector.vue) The file [i18n.js](src/i18n.js) provides
the `VueI18n` object and a function `loadLanguageAsync` that lazy loads files.

By running `npm run i18n:report` you'll get an overview about which entries are missing and which translations are not
used.

Please see [Formatting](https://kazupon.github.io/vue-i18n/guide/formatting.html) if you want to learn more about how to
use internationalization in your components.

### Change the default language
You or the user can change the default language. The first option would be to add the environment variable
`VUE_APP_I18N_LOCALE=` to one of your `.env*` files. For example:
```
VUE_APP_I18N_LOCALE=de
```
To learn more about the `.env` files, see [Configuration](#configuration)

The second option is to set the parameter `locale` in the users localStorage. A language changer component could take
care of this, for example.

If neither in the [.env](.env) nor the `localStorage` a default language is set, english will be the default. English
will also be the fallback language always, if the configured default language couldn't be loaded or some string is not
translated.

Note: The `localStorage.locale` has a higher priority than the `.env*` files.

### Disable prefetch
By default all translation files will be prefetched by the browser (if the users browser supports prefechting). That
means that if the browser has nothing else to load, it will load any language file to the users browser cache. If you
want to disable this feature, you can add those lines to your [vue.config.js](vue.config.js):
```
chainWebpack: config => {
  config.plugin('prefetch').tap(options => {
    options[0].fileBlacklist = options.fileBlacklist || [];
    options[0].fileBlacklist.push(/(lang-)(.){2,}(-json)(.*)\.js$/);
    return options;
  });
}
```

Note: Prefetching does not only support the language files, in general all chunks will be prefetched by default.

## Testing
The test environment we use is jest. You can run the tests with the command:
```
npm run test:unit
```
Every file and component should be tested. The tests should be placed in a `__tests__` directory right next to the code
being tested. We aim for a test coverage over 90%. That means, that pull request with a lower coverage will most likely
be rejected. Once the tests have been executed, a directory `coverage` will be created in the administration directory.
There you'll find the coverage report.

Note: Tests can also be placed in the [tests](tests) directory, but then the directory structure should match the
structure in `src`. The tests directory ist the perfect place for general tests.

## Configuration
You can define global variables in the [.env](.env) file. Note that only variables that start with `VUE_APP_` will be
embedded into the client bundle:
```
VUE_APP_TITLE=Skydivemanifest Administration
```

You can access env variables in your application code:
```
process.env.VUE_APP_TITLE
```

Sometimes you might have env variables that should not be committed into the codebase, for example URL to your API. In
that case you should use an .env.local file instead. Local env files are ignored in .gitignore by default.

See [Environment Variables](https://cli.vuejs.org/guide/mode-and-env.html#environment-variables) for more information.

## Troubleshooting
### 404 error when accessing a route directly
When using history mode, you will get a 404 error if you access `http://oursite.com/dashboard` directly in your browser.
This will happen without a proper server configuration. To fix the issue, all you need to do is add a simple catch-all
fallback route to your server. If the URL doesn't match any static assets, it should serve the same index.html page that
your app lives in.

Adapt the [publicPath in the vue.config.js](vue.config.js#L2) that it match the path your app lives in. For example, if
your `index.html` is stored under `https://yoururl.de/subdir/dist/index.html` your `publicPath` should be
`/subdir/dist`. If your are using Apache, you have to do the same for the
[RewriteBase in the .htaccess file](public/.htaccess#L3).

If you use another Webserver, please see [Example Server Configurations](https://router.vuejs.org/guide/essentials/history-mode.html#example-server-configurations)
for more information.

### Module linking in Jetbrains products
If you want to be able to `Ctrl + left click` imports, you need to select the correct webpack file in your IDEA
settings. Go to `File > Settings > Languages and Frameworks > JavaScript > Webpack` and select the following webpack
config:
```
<projectdir>/administration/node_modules/@vue/cli-service/webpack.config.js
```