# Administration Bundle
Welcome to the skydivemanifest administration bundle. You can follow the documentation for
development or to build the bundle, to use it in production.

## Project setup
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

## Developer guide
This section should help any developer to implement new features or fix bugs.

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
will also be always the fallback language, if the configured default language couldn't be loaded or some string is not
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