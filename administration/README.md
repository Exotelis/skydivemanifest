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
  + [Datatable](#datatable)
    * [Datatable actions](#datatable-actions)
    * [Datatable column selection](#datatable-column-selection)
    * [Datatable density](#datatable-density)
    * [Datatable filters](#datatable-filters)
    * [Datatable filters toggle](#datatable-filters-toggle)
    * [Datatable refresh](#datatable-refresh)
    * [Datatable rows per page selection](#datatable-rows-per-page-selection)
    * [Datatable sort mode](#datatable-sort-mode)
  + [Form components](#form-components)
    * [form-group](#form-group)
    * [input-date](#input-date)
    * [input-email](#input-email)
    * [input-hidden](#input-hidden)
    * [input-password](#input-password)
    * [input-text](#input-text)
    * [select-wrapper](#select-wrapper)
    * [button-wrapper](#button-wrapper)
  + [Form validation](#form-validation)
  + [Pagination](#pagination)
  + [Prevent from leaving route](#prevent-from-leaving-route)
  + [Layouts](#layouts)
  + [Permissions](#permissions)
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
[NavigationModel](src/models/NavigationModel.ts). The only required key of the NavigationModel is `type`.
You can choose one out of four [NavigationTypes](src/enum/NavigationType.ts). Using `Path` will
automatically load the route information matching the given path:
```
{ path: '/', type: NavigationType.Path }
```
The type `Submenuhandler` will generate a submenu. `Title` can be used to group the menu items. When using one of those
types, you should also provide a title for the menu item:
```
{ title: 'Submenutitel',
  type: NavigationType.Submenuhandler,
  children: [
    { title: 'User stuff',
      type: NavigationType.Title
      children: [
        { path: '/userroles', type: NavigationType.Path }
      ]
    }
  ]
}
```
As you can see in the example above, a `Submenuhandler` just makes sense with some children defined.

Note: When using the type `title`, all menu items that should belong to this group, must be placed in the children array
of the title object.

The type `Hidden` must not be defined manually, but will be set when the user doesn't have the required permissions.
When all menu items of a submenu have been hidden, the submenu will also be hidden. The same goes for the `Title` type,
if all children have been hidden the `Title` item will also be hidden.

Note: If `Submenuhandler` and `Title` elements have an empty `children` array, these elements will be hidden.

Finally, you can define an icon for each menu item. The icon must be the classname of an existing mdi icon:
```
{ icon: 'mdi-airplane', path: '/aircrafts', type: NavigationType.Path }
```
By default, the property `onlyOneSubmenu` is false. Setting this to `true` means any other open submenu will be closed,
when the user opens another submenu. Settings this to false allows the user to open multiple submenus at the same time.
```
<navigation-generator only-one-submenu :config="..."></navigation-generator>
```
It is possible to display a title, and a close button inside the submenus. Just add the attributes `show-submenu-close`
and/or `show-submenu-title`:
```
<navigation-generator show-submenu-close show-submenu-title :config="..."></navigation-generator>
```
The title of a submenu will be the text of the submenu handler (The menu item which opens the submenu).

If you want the submenu to be right aligned, in relation to the submenu handler you can set the attribute
`submenus-right`:
```
<navigation-generator submenus-right :config="..."></navigation-generator>
```

You can change the layout of the navigation by setting one of the supported bootstrap classes on the
`navigation-generator`:
```
<navigation-generator class="nav-pills" :config="..."></navigation-generator>
```
Please see the [bootstrap documentation](https://getbootstrap.com/docs/4.4/components/navs/) for more details.

Of course, you don't have to use the `NavigationGenerator` and its config, you could also directly use the
[NavigationItem](src/components/navigation/NavigationItem.vue) component. It also needs a config, but not as an array.

Example usage:
```
<navigation-generator class="flex-column" ref="mainNavigation" :config="mainNavigationConfig"></navigation-generator>
```
The ref is used to call the `closeAll` method of the `NavigationGenerator`, when the user clicks anywhere outside of the
navigation menu.

### Datatable
The [Datatable](src/components/datatable/Datatable.vue) is a very powerful component and requires some configuration.
So, let's go through the single options of the datatable.

First of all you need to set a `service` that is being called every time new data must be pulled from the REST api. The
service must be a callback function:
```
<datatable :service="service"></datatable>

service: any = UserService.all;
```

Next, you need to define the different columns that should be displayed in the datatable. The `columns` must be an array
of [DatatableColumnModel](src/models/datatable/DatatableColumnModel.ts) types. A definition could look like:
```
columns: Array<DatatableColumnModel> = [
  { label: i18n.t('page.users.id') as string, prop: 'id', notHideable: true, sortable: true },
  { label: i18n.t('page.users.lastname') as string, prop: 'lastname' },
  { label: i18n.t('page.users.middlename') as string, prop: 'middlename', hide: true },
  { label: i18n.t('page.users.email') as string, prop: 'email', classes: 'user-select-all' },
  { label: i18n.t('page.users.dob') as string, prop: 'dob', alignBody: Position.right, alignHead: Position.right },
  { label: i18n.t('page.users.role') as string, prop: 'role', sortable: true, sortKey: 'roleName' }
]
```
Ok, these are a lot of settings. See the table below for the explanation:
| setting     | required | description                                                                                                     | allowed values |
| ----------- | -------- | --------------------------------------------------------------------------------------------------------------- | -------------- |
| alignBody   |          | Aligns the content of the tbody cell                                                                            | Position.center , Position.left, Position.right |
| alignHead   |          | Aligns the content of the thead cell                                                                            | Position.center , Position.left, Position.right |
| classes     |          | Additional custom classes that will be set on the tbody cell                                                    | string |
| hide        |          | Hides the column by default                                                                                     | boolean |
| label       | *        | The lable that is being displayed in the thead cell                                                             | string |
| notHideable |          | Column is not hideable                                                                                          | boolean |
| prop        | *        | The key of the data returned by the api                                                                         | string |
| propCustom  |          | Customizes the output. Can be used to conditionally display icons or convert booleans to some meaningful output | function |
| sortable    |          | Makes the column sortable. By default the prop is used as sortKey                                               | boolean |
| sortKey     |          | If the sortKey is a different than the prop                                                                     | string |

The `propCustom` option might be the most powerful. Let's dive a bit more into detail. `propCustom` needs to be a
function. This function gets called every time the column gets rendered. This setting can be used to conditionally
display icons or convert booleans to some meaningful output. See the following examples to learn more:
```
propCustom: function (gender: string): string {
  let genderString: string = i18n.t('general.gender.' + gender) as string;
  if (gender === Gender.f) {
    return '<span class="female mdi mdi-gender-female"></span> ' + genderString;
  }

  return '';
}

propCustom: function (locale: string): string {
  return (locales as any)[locale].toLowerCase();
}

propCustom: function ({ name, color }: any): string {
  let fontColor: string = colorYiq(`${color}`);
  return `<span style="background-color: ${color};color: ` + fontColor + `" class="badge">${name}</span>`;
}
```
The last example expects an object as parameter. This is the case when the returned value of the `prop` is an object as
well. The `name` and `color` keys must exist on the returned object.

The third and last required setting is the `tableId`. The `tableId` will be used to store some data in the users local
storage to keep different settings even if the user leaves the page. For example, the visible columns or the sort mode
will be stored.

All other settings are optional but can add useful features to the datatable.

actions:
Actions can be performed on each record of the datatable. If `actions` are defined, a dropdown menu will be displayed in
the last column of the datable. The `actions` must be an array of the
[DatatableActionModel](src/models/datatable/DatatableActionModel.ts). Useful actions might be show, edit or delete:
```
actions: Array<DatatableActionModel> = [
  { label: 'Show', eventId: 'show', icon: 'mdi-eye' },
  { label: 'Edit', eventId: 'edit', icon: 'mdi-pencil' },
  { label: 'Delete', eventId: 'delete', critical: true, icon: 'mdi-delete' }
];
```
| setting  | required | description                                            | allowed values |
| -------- | -------- | ------------------------------------------------------ | -------------- |
| critical |          | Marks the action as critical                           | boolean        |
| eventId  | *        | The name of the event that will be fired               | string         |
| icon     |          | An icon that should be displayed in front of the label | string         |
| label    | *        | The text that is being displayed                       | string         |

When the user clicks an action an event will be fired that needs to be caught to perform the action. The base name of
the event will be `datatable:action:` followed by the `eventId` as suffix `datatable:action:delete`. The emitted event
consists of three parameters. The items, the critical state, and the event mode (single or bulk). You can listen in the
parent component of the datatable for the events:
```
<datatable @datatable:action:delete="deleteUser"> </datatable>

deleteUser (item: Array<object>|object, critical: boolean, mode: ActionMode): void {
  // Perform action
}
```

bulkActions:
Will do the same as the actions but will only be available if the datatable is in the `selectable` mode.

caption:
The subtitle of the table. By default, it's empty.

filterConfig:
Please see the [datatable-filters](#datatable-filters) section to learn more about how to define the `filterConfig`.

hideUtilityBarBottom / hideUtilityBarTop:
Will hide the top or bottom utility bar.

historyMode:
By default, the history mode is disabled. If it's enabled, it will catch any state change of the datatable (filter
updates, sort changes, etc.) and it's possible to use the back and forward buttons of the browser to navigate to the
last state.

perPage:
An array of numbers that will be selectable in the "records per page" component. Default is [10, 25, 50, 100, 250].

selectable:
By default, it's false. Enabling the `selectable` setting will add checkboxes to the datatable to select rows. This
feature is only useful when using bulk actions.

utilityBarBottomClasses / utilityBarTopClasses:
Adds the defined classes to the top or bottom utility bars.

---
Events:
- datatable:beforeRefresh - Is fired before new data will be loaded
- datatable:refreshed - Is fired after new data have been pulled
- datatable:selection - When the selection changed (With the current selection as parameter)
---
One word to the response of the api. The response must be of the type
[DatatableDataModel](src/models/datatable/DatatableDataModel.ts).  The REST api of the skydivemanifest will always have
this format.

#### Datatable actions
In a lot of situations it makes sense to perform a specific action on a single or multiple datatable records. The
[DatatableActions](src/components/datatable/DatatableActions.vue) component does exactly that. The datatable can run two
different action modes `single` and `bulk`. The single action will be performed on a single table row, the bulk action
on multiple selected rows. Therefore, it makes only sense to run the bulk mode with `selectable` enabled. The default
mode is `single`. The `DatatableActions` module expects two required parameters. The `actions` must be an array of
[DatatableActionModels](src/models/datatable/DatatableActionModel.ts) and the items must be an `object` or an array of `objects`.
The component renders a dropdown menu with a list of the defined actions. When the user clicks any of those actions, an
event will be emitted. Let's imagine having the following action config:
```
actions: Array<DatatableActionModel> = [
    { label: 'Show', eventId: 'show', icon: 'mdi-eye' },
    { label: 'Edit', eventId: 'edit', icon: 'mdi-pencil' },
    { label: 'Delete', eventId: 'delete', critical: true, icon: 'mdi-delete' }
];
```
As you can see you can define different options. The label will be the displayed action name. If critical is true, the
user will be notified, that the he or she is about to perform some critical action. The icon will be placed in front of
the label. The interesting part is indeed the `eventId`. This ID determines the full name of the emitted event. The
basic name of the event is `datatable:action:`, the `eventId` is going to be the suffix. The name of the `delete` event
would be `datatable:action:delete`. The parent component of the datatable should handle the event and perform the
intended action.

Note: The `DatatableActions` component can only be used in combination with the `Datatable` component.

#### Datatable column selection
The [DatatableColumnSelection](src/components/datatable/DatatableColumnSelection.vue) component makes it possible to
toggle the visibility of columns. 
You must pass the required attributes `columns` and `tableId`. Please see the section [Datatable](#datatable) to learn
more about those attributes:
```
<datatable-column-selection table-id="users" :columns="columns"></datatable-column-selection>
```
It must be at least one column visible. By default, the maximum of visible columns is 10, but you can change this by
defining the max attribute:
```
<datatable-column-selection table-id="users" :columns="columns" :max="20"></datatable-column-selection>
```
The attribute `visible` is a list of all visible columns. It must be an array including the property name of the column:
```
['id', 'firstname' ...]
``` 
To catch a selection change in the parent component, you have two options. The first is to sync the `visible` attribute:
```
<datatable-column-selection :visible.sync="visibleColumns"></datatable-column-selection>
```
or you could listen for the `datatable:columnToggle` event:
```
<datatable-column-selection @datatable:columnToggle="onColumnSelectionChange"></datatable-column-selection>
```

#### Datatable density
The [DatatableDensity](src/components/datatable/DatatableDensity.vue) component sends an `datatable:densityChanged`
event, when the density has been changed.
To catch a density change in the parent component, you have two options. The first is to sync the `density` attribute:
```
<datatable-density :density.sync="sortMode"></datatable-density>
```
or you could listen for the `datatable:densityChanged` event:
```
<datatable-density @datatable:densityChanged="onDensityChange"></datatable-density>
```

#### Datatable filters
With the [DatatableFilters](src/components/datatable/DatatableFilters.vue) component the user can filter the datatable
records. The component has no required attributes, but it would make sense to set the `filters`, to configure the
available filters. `Filters` must be an array of [DatatableBaseFilter](src/filters/DatatableBaseFilter.ts) types.
Currently, two different filter types are available. Those two extend the `DatatableBaseFilter` class. The
[DatatableExactFilter](src/filters/DatatableExactFilter.ts) can filter for specific values, let's say the exact age for
example. The [DatatableFromToFilter](src/filters/DatatableFromToFilter.ts) can filter for the exact value, values
greater than, lower than, or between to given values. The creation of a new filter could look like:
```
filters: Array<DatatableBaseFilter> = [
  new DatatableExactFilter('ID', { inputType: FilterInputTypes.text, prop: 'id' })
]
```
This would create a `Exact filter` with the legend `ID`. Each filter will be wrapped in a fieldset, and the legend will
display the defined name of the filter. The second attribute of the `DatatableExactFilter` constructor must be one of
the `DatatableFilterInputModel|DatatableFilterSelectModel` types. The definition can be found
[here](src/models/datatable/DatatableFilterModels.ts). As you can see, those objects can have a label and a value. If
you define the value, the datatable would filter for this value by default. The required field `prop` must be the name
of the filter defined in the api. Please see the api documentation for more information. You also need to define an
inputType. This setting defines the type of the rendered input field. The available types are:
```
FilterInputTypes.date
FilterInputTypes.email
FilterInputTypes.number
FilterInputTypes.text
FilterInputTypes.select
```
Note: The `select` type is only available for the `Exact filter` and not the `FromTo filter`. The
`DatatableFilterSelectModel` requires also the `options` setting. Please see the [select-wrapper](#select-wrapper)
section for more details.
The definition of the `FromToFilter` could look like:
```
new DatatableFromToFilter(
  'Age',
  { inputType: FilterInputTypes.number, prop: 'age', label: 'Age' },
  { inputType: FilterInputTypes.number, prop: 'age_eot', label: 'Older than' },
  { inputType: FilterInputTypes.number, prop: 'age_eyt',  label: 'Younger than' }
)
```
If you only want to use some of those fields you can replace the others with `undefined`:
```
new DatatableFromToFilter(
 'Age',
 undefined,
 { inputType: FilterInputTypes.number, prop: 'age_eot', label: 'Older than' }
)
```
In this case the user could only filter for people older than a given age.

#### Datatable filters toggle
The [DatatableFiltersToggle](src/components/datatable/DatatableFiltersToggle.vue) component sends an
`datatable:filtersToggle` event, when the user clicks the filters button. The toggle button should help to separate the
button from the filters row to provide a good UX.
To catch a filter visibility change in the parent component, you have two options. The first is to sync the `visible`
attribute:
```
<datatable-filters-toggle :visible="filtersVisible"></datatable-filters-toggle>
```
or you could listen for the `datatable:filtersToggle` event:
```
<datatable-filters-toggle @datatable:filtersToggle="onFiltersToggle"></datatable-filters-toggle>
```

#### Datatable refresh
The [DatatableRefresh](src/components/datatable/DatatableRefresh.vue) component sends an `datatable:refresh` event, when
the refresh button has been clicked. The event can be used to refresh the data of the table:
```
<datatable-refresh @datatable:refresh="onRefresh"></datatable-refresh>

onRefresh (): void {
    // Reload data
}
```

#### Datatable rows per page selection
The [DatatableRowsPerPage](src/components/datatable/DatatableRowsPerPage.vue) component sends an
`datatable:rowsPerPageChanged` event, when selection has been changed. With this component the user can define how many
rows should be displayed on a single page. To catch a mode change in the parent component, you have two options.
The first is to sync the `current` attribute:
```
<datatable-rows-per-page :current.sync="params.limit"></datatable-rows-per-page>
```
or you could listen for the `datatable:rowsPerPageChanged` event:
```
<datatable-rows-per-page @datatable:rowsPerPageChanged="onRowsPerPageChange"></datatable-rows-per-page>
```
The attribute `rows-per-page` can be set to define which numbers are allowed. It must be an array of numbers:
```
<datatable-rows-per-page :rows-per-page="[5, 10, 25, 50, 100]"></datatable-rows-per-page>
```

#### Datatable sort mode
The [DatatableSortMode](src/components/datatable/DatatableSortMode.vue) component sends an `datatable:sortModeChanged`
event, when the sort mode has been changed.
To catch a mode change in the parent component, you have two options. The first is to sync the `mode` attribute:
```
<datatable-sort-mode :mode.sync="sortMode"></datatable-sort-mode>
```
or you could listen for the `datatable:sortModeChanged` event:
```
<datatable-sort-mode @datatable:sortModeChanged="onSortModeChange"></datatable-sort-mode>
```

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

Any component with a form should extend the [FormMixin](src/mixins/FormMixin.ts), because it provides all important
variables such as the `disabledSubmit` and `loading`.

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
[_variables.scss](src/assets/scss/themes/default/_variables.scss#L39).
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

#### input-date
Creates an input element with type date. The format of min and max must be `YYYY-MM-DD`. the value of `step` must be
given in days. The default value of `step` is 1, indicating 1 day. In the example below, `max` is set to the current
date.

A full list of available attributes you can find in the [InputDate.vue component](src/components/form/InputDate.vue).

Example:
```
<input-date autofocus
            id="name"
            min="1980-10-23"
            required
            step="5"
            v-model="form.date"
            :max="new Date(Date.now()).toISOString().split('T')[0]"></input-date>
```

#### input-email
Creates an input element with type email. In the example below, only email addresses with the tld .org are allowed.

A full list of available attributes you can find in the [InputEmail.vue component](src/components/form/InputEmail.vue).

Example:
```
<input-email id="email"
             pattern=".*.org"
             required
             v-model.trim="form.email"
             :placeholder="$t('form.placeholder.email')"></input-email>
```

#### input-hidden
Creates an input element with type hidden. The attribute `value` must be set, to submit any data.

Example:
```
<input-hidden form="formId" id="hidden01" type="hidden" value="Some value"></input-hidden>
```

#### input-password
Creates an input element with type password. The attribute `:is-toggleable` is true, an icon will be displayed to toggle
the visibility of the password.

A full list of available attributes you can find in the
[InputPassword.vue component](src/components/form/InputPassword.vue).

Example:
```
<input-password id="password"
                is-toggleable
                placeholder="Your password"
                required
                v-model="form.password"></input-password>
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
            plaintext
            readonly
            required
            v-model="form.name"></input-text>
```

#### select-wrapper
Creates a select element. You can define options in two different ways or even mix them. The first way is to define the
`option` elements inside the `select-wrapper`:
```
<select-wrapper>
    <option value="female">Female</option>
    <option value="male">Male</option>
</select-wrapper>
```
The second way is to use the `options` attribute of the `select-wrapper`:
```
<select-wrapper :options="options"></select-wrapper>

// In typescript:
import { Options } from '@/types/Options';

export default class SomeClass extends Vue {
  options: Options = [
    { value: 'female', text: 'Female' },
    { value: 'male', text: 'Male' }
  ];
}
```
Note: If you mix both approaches, make sure the `value` is unique.

It is also possible to define `optgroups` in the typescript option. This is the structure of options and optgroups:
```
// Options
{ disabled?: boolean; text: string; value: string|number|boolean|object|null|Array<string|number|boolean|object>; }

// OptGroups
{ disabled?: boolean; label: string; options: Options[]; }
```

It is also possible to pre select values. Without multiple:
```
<select-wrapper v-model="selected">
    <option value="foo">Foo</option>
    <option value="bar">Bar</option>
</select-wrapper>

// In Typescript
selected = 'foo';
```
With multiple:
```
<select-wrapper multiple v-model="selected">
    <option value="foo">Foo</option>
    <option value="bar">Bar</option>
</select-wrapper>

// In Typescript
selected = ['foo', 'bar']';
```
Note: If you don't want to pre select some value, just leave the string or array empty.

When multiple is not set and no default value is defined, a placeholder option with the text
`-- Please select an option --` will be rendered. In some cases you might want to use a custom text. In this case you
can overwrite the placeholder:
```
<select-wrapper>
    <template #placeholder>Custom text</template>
</select-wrapper>
```
or with a translatable string:
```
<select-wrapper>
    <template #placeholder>{{ $t('form.placeholder.dob') }}</template>
</select-wrapper>
```

A full list of available attributes you can find in the [SelectWrapper.vue component](src/components/form/SelectWrapper.vue).

Full example:
```
<select-wrapper id="someId" v-model="selected" :options="options" required>
    <template #placeholder>-- Please select with custom text --></template>
    <option value="foo">Foo</option>
</select-wrapper>

// In typescript
selected = '';
options: Options = [
  { value: 'bar', text: 'Bar' },
  { value: 'baz', text: 'Baz' }
];
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
                    right-aligned
                    type="submit"
                    :disabled="disabledSubmit"
                    :loading="loading">{{ $t('login.signIn') }}</button-wrapper>
</div>
```

A full list of available attributes you can find in the [ButtonWrapper.vue component](src/components/form/ButtonWrapper.vue).

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
To check if the values of two fields are equal, the id of one of those fields must have the suffix `_confirmation`. The
ids of the two fields could look like:
```
id="password"
id="password_confirmation"
```

Note: For a full support of the validation, please use the [from-group component](#form-group) as a wrapper.

### Pagination
When you want to implement a feature that should have multiple pages, you can use the
[pagination component](src/components/ui/Pagination.vue). The datatable makes use of the pagination component for
example. To use the pagination, you have to import the component.

```
import Pagination from '@/components/ui/Pagination.vue';

@Component({
  components: { Pagination }
})
```
once this is done you can define the html element:
```
<pagination :current="params.page"
            :from="response.from"
            :last="response.last_page"
            :to="response.to"
            :total="response.total"
            @pagination:changed="onPageChange"></pagination>
```
The attributes `current`, `from`, `last`, `to` and `total` are required. To hide the `record` text, you can set the
`hideRecords` attribute:
```
<pagination hide-records></pagination>
```
To catch a page change in the parent component, you have two options. The first is to sync the `current` attribute:
```
<pagination :current.sync="params.page"></pagination>
```
or you could listen for the `pagination:changed` event:
```
<pagination @pagination:changed="onPageChange"></pagination>
```
in your typescript you can then handle the event:
```
onPageChange (page: number) {
  // Do something
}
```

### Prevent from leaving route
When a form has been manipulated, you can prevent the user from changing the route without saving the data. The only
thing you have to do is to set the `dirty` variable to `true` when implementing the `FormMixin`. The best place to set
`dirty` to `true` is a watcher:
```
@Watch('form', { deep: true })
onFormChange (form: RegisterModel): void {
    this.dirty = true;
    this.disabledSubmit = !(form.dob.length > 0 && form.email.length > 0 && form.firstname.length > 0 &&
      form.lastname.length > 0 && form.password.length > 0 && form.password_confirmation.length > 0);
}
```
After the form has been submitted successfully, you have to reset `dirty` before any route change:
```
async handleSubmit (): Promise<any> {
    try {
        // Some api request
        this.dirty = false;
    } catch (e) {
        // Error handling
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
layout, import and register your component in [main.ts](src/main.ts).

You can also style your layouts separately. Just add a file to the directory
[layouts](src/assets/scss/themes/default/layouts) and name it appropriate to your layout. If your layouts name is
`DefaultLayout` you should name your stylesheet `_default.scss`. Finally, you must either import or replace your
stylesheet in the [app.scss](src/assets/scss/app.scss).

Note: The path in the assets folder might be different, when not using the default theme.

### Permissions
When a user gets signed in, the users permissions will stored in the locale storage with some other information. You can
use the permission to deny to access to specific pages or hide elements.

For example the following route will only be accessible if the user has at least one of the defined permissions:
```
{
  path: '/aircrafts',
  name: 'aircrafts',
  meta: {
    title: 'page.title.aircrafts',
    permissions: ['aircrafts:delete', 'aircrafts:read', 'aircrafts:write'],
    requiresAuth: true
  }
}
```
Note: When you want make use of the permissions `requiresAuth` must be true.

If neither the `aircrafts:delete` nor the `aircrafts:read` nor the `aircrafts:write` will be part of the users
permissions, the page will not be accessible. When you use the
[NavigationGenerator](#navigationgenerator-and-navigationitems), all menu items without permissions will be hidden
automatically. You can use the helper `checkPermissions()`, to check manually for permissions:
```
checkPermissions(['permissionX::read'])
```

See the api [README](../api/README.md) to learn more about permissions.

## Internationalization
The internationalization support is enabled by default. You'll find all supported languages in the
[src/locales](src/locales) directory. All languages defined in [src/locales/locales.json](src/locales/locales.json) will
be available in the [languageSelector](src/components/ui/LanguageSelector.vue) The file [i18n.ts](src/i18n.ts) provides
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