# Api Bundle
Welcome to the skydivemanifest api bundle. You can follow the documentation for development or to build the bundle, to
use it in production.

## Table of contents
- [Project setup](#project-setup)
  + [Install the dependencies](#install-the-dependencies)
  + [Create an .env file and change settings](#create-an-env-file-and-change-settings)
  + [Directory and file permissions](#directory-and-file-permissions)
  + [Create a database](#create-a-database)
  + [Running migrations](#running-migrations)
  + [Setting up OAuth 2.0](#setting-up-oauth-20)
  + [Security](#security)
    * [Server HTTP-Header](#server-http-header)
    * [X-Powered-By HTTP-Header](#x-powered-by-http-header)
  + [Task scheduling](#task-scheduling)
  + [Config caching](#config-caching)
  + [Create an admin user](#create-an-admin-user)
  + [Imprint in emails](#imprint-in-emails)
  + [Update to a newer version](#update-to-a-newer-version)
- [Developers guide](#developers-guide)
  + [Running tests](#running-tests)
  + [API documentation](#api-documentation)
  + [Authorization options](#authorization-options)
  + [Frontend requirements](#frontend-requirements)
  + [Permissions](#permissions)
  + [OAuth client credentials](#oauth-client-credentials)
  + [Create a migration](#create-a-migration)
  + [Create a seeder](#create-a-seeder)

## Project setup
Requirements:
- PHP 7.2.5 or higher
- PHP extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - Intl
- Database management system
- Hypertext Transfer Protocol Secure (https)
- Cronjobs (optional - for queues and task scheduling)
- Supervisor (optional - for queues)

### Install the dependencies
Install the dependencies for the api bundle by running:
```
composer install
```
If composer is not installed on your system, download the latest `composer.phar` from the
[composer website](https://getcomposer.org/) and copy it to the api directory. Now, you should be able to run:
```
php composer.phar install
```
After the installation succeeded you should rebuild the autoloader by running:
```
composer dump-autoload
```
or
```
php composer.phar dump-autoload
```

### Create an .env file and change settings
After installing the dependencies, you should create a `.env` file by copying the [.env.example](.env.example). Once
this is done, you should adapt the settings to your needs. If you want to use the project in production, you should
define an app key. Typically, the key should be a 32 characters long random string. If the application key isn't being
set, your user encrypted data won't be secure! Also set the `debug` mode to `false`.

To set the app key just run:
```
php artisan key:generate
```
___
The `APP_URL_FRONTEND` setting is important for redirects (in particular in mails). Please add the url to your frontend.
___
The `APP_DELETE_INACTIVE_USERS` setting determines after how many months of inactivity a user will be deleted. Only non
admin users can be automatically deleted. Decisively is the date of the last log in, or the date where the account has
been modified last. This setting might be important to match different legal specifications, for example how long you
are allowed to store personal information of your users or how long you must store invoices. However, latter will store
the necessary data anyway. The default value is 10 years.
___
The `APP_DELETE_UNVERIFIED_USERS` setting is pretty similar to the previous. It will delete unverified user accounts
after the given number in days. This guarantees that a user entered a valid email address. The default value is 1 week.
___
You may want to define a mail for support requests, then just update the `MAIL_SUPPORT_ADDRESS=` setting. This email
address will be attached to every email that is being sent by your application.
___
If you want to speed up your application's response time, you could enable queuing in your `.env` file. For example:
```
QUEUE_CONNECTION=database
```
There are more options than saving the jobs on the database, but you have to configure those services manually.

Once you enabled queuing, you have to make sure that `php artisan queue:work` is running. The best way would be, to
install and configure a process monitor like Supervisor on your system. To learn more about Supervisor, please see the
[Supervisor Configuration](https://laravel.com/docs/7.x/queues#supervisor-configuration). If you don't have root access
on your platform, you could also run the `queue:work` command as a cron job. Since the `queue:work` listen for new jobs,
it will not stop after processing the queue. That's why you have to add the `--stop-when-empty` option, when running
the command as a cron job:
```
php artisan queue:work --stop-when-empty
```
We store every kind of notification in different queues. Emails for example will be queued in mail, short messages
instead in sms etc. To listen to any queue, you must set the `--queue=` option:
```
php artisan queue:work --queue=default,mail,sms,listeners
```
To fully disable the queue, just set the `QUEUE_CONNECTION=` setting to `sync` in your `.env` file.

Note: A cron job could that runs every two minutes could look like:
```
2 * * * * cd <path-to-your-project> && php artisan queue:work --queue=default,mail,sms,messanger,listeners --stop-when-empty >> /dev/null 2>&1
```
___

### Directory and file permissions
TODO as soon as the first project is in production.

### Create a database
When you've updated all settings in your `.env` file, in particular the database settings, it's time to create a
database you can work with. Make sure the name of the database is the same as the one in your `.env` file. Also, see the
[config/database.php](config/database.php) to choose the correct `charset` and `collation`.

### Running migrations
Migrations are like version control for your database. They will add the database tables, constraints and so on so
forth. You can also rollback the migrations to get to an earlier state. When you have to run migrations during an
upgrade, you should always backup your database. You can run migrations with the command:
```
php artisan migrate
```
___
If you're running MariaDB or older versions of MySQL you may hit this error (or similar) when trying to run migrations:
`1709 Index column size too large`. To fix this, just add the following line to your `.env` file.
```
DB_DEFAULT_STRING_LENGTH=191
```
and rerun the migrations with the `php artisan migrate:fresh` command.
Note: This command will drop all tables, you should only run this command when installing the application.
___
You can rollback migrations with the command.
```
php artisan migrate:rollback
php artisan migrate:rollback --step=5
```
If you don't want to define the `countries`, `regions` and `currencies` manually, you might want to run the default
seeder, which will fill those tables with entries.
```
php artisan db:seed
```
Note: In production you need to add the option `--force`.

For more information see [Database: Migrations](https://laravel.com/docs/7.x/migrations).

### Setting up OAuth 2.0
In this project we are using OAuth 2.0 for authorization. On the one hand we use it to provide access tokens for the
users, on the other hand tokens can be used by other applications to be able to communicate with the Skydivemanifest
api. After you ran the migrations, you already created all necessary database tables. Before you can use OAuth, you need
to create encryption keys and the default clients:
```
php artisan passport:install
```
As already said, this command will create the encryption keys and print the client ids and secret keys on the console.
You must copy this information to your `.env` file. This is an example, please replace the ids and keys:
```
OAUTH_PERSONAL_CLIENT_ID=90881e95-e53c-4284-90db-4d1086e4dfa4
OAUTH_PERSONAL_CLIENT_SECRET=8Bti7LdqRMexGZxEAgJvqKSb0Zi4tGbWp1NGQofo
OAUTH_PASSWORD_CLIENT_ID=90881e95-f052-48f0-b2b4-5efb5f5b0984
OAUTH_PASSWORD_CLIENT_SECRET=cXedZFpn3Ara6BGVijlFaOWo5Fbjo0amPC795rhL
```
___
When you run into the error `There are no commands defined in the "passport" namespace.` when trying to run the
`php artisan passport:install` command, export an environment variable:
```
export APP_RUNNING_IN_CONSOLE=true
```
and re-run `php artisan passport:install`. After installing passport you can unset the variable by running:
```
unset APP_RUNNING_IN_CONSOLE
```

If you don't have the permissions to define environment variables, just add the following line temporarily to the
[artisan](artisan) file:
```
#!/usr/bin/env php
<?php

putenv('APP_RUNNING_IN_CONSOLE=TRUE');
```
If this doesn't fix the problem, please get in contact with us on
[GitHub](https://github.com/exotelis/skydivemanifest/issues).

### Security
We implemented as many security feature as possible, but some settings have to be changed on your server directly.

#### Server HTTP-Header
You should reduce the `Server` header to the minimum on a productive web server. Just add the following lines to your
apache config:
```
# Reduce Server HTTP Header to the minimum:
ServerTokens Prod

# Remove the footer from error pages, which details the version numbers:
ServerSignature Off
```
Please see [Removing Headers](https://scotthelme.co.uk/hardening-your-http-response-headers/#removingheaders), when you
don't use apache.

#### X-Powered-By HTTP-Header
You should disable the `X-Powered-By` header in your `php.ini`:
```
expose_php = off
```
Note: You can also completely turn of the `X-Powered-By` header. Just add the following lines to your apache config:
```
Header always unset "X-Powered-By"
Header unset "X-Powered-By"
```

### Task scheduling
The Skydivemanifest api bundle includes a couple of tasks that should run frequently. The `Task scheduler` will clean up
the database for example. It will delete expired or revoked tokens, inactive users and so on and so forth. If you want
to make use of the `Task scheduler` add the following line to your cron file:
```
* * * * * cd <path-to-the-api-folder> && php artisan schedule:run >> /dev/null 2>&1
```

### Config caching
Only in production you should run:
```
php artisan config:cache
```
This caches all configuration files and will increase the performance of your application. Keep in mind to clear the
cache when you change any setting or update the application:
```
php artisan config:clear
```

### Create an admin user
Finally, the last missing peace is to create the first user for your application. Run:
```
php artisan make:admin
```
and follow the instructions.

After this step succeeded you can start using the Skydivemanifest.

### Imprint in emails
If you want or have to add your legal information to your emails, just create a file `imprint.blade.php` and
`imprint_plain.blade.php` to the [resources/views/mails](resources/views/mails) folder. In the `imprint.blade.php` you
can use html tags and the `imprint_plain.blade.php` is for text mails. The imprint will then be added automatically to
your emails.
```
<b>Skydivemanifest | Street | Postal City</b>
```
and
```
Skydivemanifest
Street
Postal City
```

### Update to a newer version
If you want to update to a newer version, you have to run a couple of commands to make sure everything is working as
expected. First you want to update the dependencies by running:
```
composer install
composer dump-autoload
```
or
```
php composer.phar install
php composer.phar composer dump-autoload
```
After this succeeded continue with:
```
php artisan migrate
php artisan optimize:clear
php artisan config:cache
```
The first command is going to install new migrations, the second command is going to clear caches, the third command
creates a new cache with all configs.

Note: You should always backup your application, before performing an update.

## Developers guide
This section should help any developer to get started.

### Running tests
To start the tests run:
```
php artisan test
```
or with less output:
```
vendor/bin/phpunit
```
To run a single suite or method you can simply add the filename, or the filter option:
```
php artisan test --debug --filter testRegister tests/Feature/Auth/AuthResourcesTest.php
vendor/bin/phpunit --debug --filter testRegister tests/Feature/Auth/AuthResourcesTest.php
```
The `--debug` flag will also display all `echo` or `print` output.
___
To be able to create a coverage report, you need to install `xdebug`. Run the following commands:
```
php artisan tinker
phpinfo();
```
Copy and paste the output of `phpinfo()` to the [xdebug wizard](https://xdebug.org/wizard) and follow the instructions.

### API documentation
TODO

### Authorization options
The api bundle supports two different kinds of authentication. The first and easier one is by sending the
`Authorization` header. We recommend using this approach for m2m communication. All you need to do is, attaching your
access token to the `Authorization` header:
```
Authorization: Bearer <access_token>
```
For web based apps we recommend using the two cookies approach. The api bundle sends two cookies with any response that
has the header `X-Requested-With` set to `XMLHttpRequest`. For example, if you use axios do:
```
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```
As already said, the api bundle sends two cookies. The first cookie is called `AUTH-TOKEN` and consists of the access
token signature, the second cookie `XSRF-TOKEN` consists of the header and payload of the access token. The `XSRF-TOKEN`
can be read by JavaScript. Any request you send MUST have the value of the `XSRF-TOKEN` attached to the header
`X-XSRF-TOKEN`:
```
X-XSRF-TOKEN: <header.payload>
```
with axios:
```
axois.defaults.xsrfCookieName = 'XSRF-TOKEN'; // Default value
```
The two cookie approach is much safer, when you have to store the cookie in a public context, like the users browser.
Storing the token just in the local storage makes it vulnerable to XSS attacks. Storing it just in a cookie, makes it
vulnerable to CSRF attacks. By sending one part of the token in a cookie, and the other part in a header mitigates those
kinds of attacks.

### Frontend requirements
If your are about to develop a new frontend, you have to make sure, that some routes exist to make use of the full
functionality of the backend.

The first route is the `forgot-password` route, where a user can request a new password.
```
https://<path-to-your-frontend>/forgot-password
```
The second route is the `reset-password` route, where a user should be able to set a new password for the account. The
full URL could look like:
```
https://<path-to-your-frontend>/reset-password
```
The third route is the `change-password` route, where a user gets forced to set a new password. After the user signed
in, the payload of the access token can be decrypted. If the `password_change` value is true, the user must set a new
password.
```
https://<path-to-your-frontend>/reset-password
```
Without having those routes, you cannot make use of those features.

Apart from those routes, you also have to check if the parameter `email-token` was submitted. If it was submitted you
should handle it and call the `/auth/email/confirm` resource. This does not require a separate route. A better UX would
be to give the user feedback by displaying a modal dialog.

### Permissions
When adding new tables and models to the project, you might want to specify permissions to restrict or grant access to
the resources. By default, the project is shipped with the two default roles `Administrator` and `User` and the
permissions `permissions:read` `personal` `roles:delete` `roles:read` `roles:write`. The default roles are neither
editable nor deletable. The permissions are read-only as well, because only developers should take care of adding the
permissions for their resources. That's why permissions should be added in the migration, which creates the new table.
Here is an example from the `countries` migration:
```
// Add country permissions
Permission::insert([
    ['slug' => 'countries:delete', 'name' => 'Delete countries',],
    ['slug' => 'countries:read',   'name' => 'Read countries',],
    ['slug' => 'countries:write',  'name' => 'Add/Edit countries',],
]);

// Add country permissions to admin group
Role::find(adminRole())->permissions()->attach(['countries:delete', 'countries:read', 'countries:write']);
// Add country permissions to user group
Role::find(defaultRole())->permissions()->attach(['countries:read']);
```
Note: The id of the `Administrator` role is always `1` and the id of the `User` role is always `2`. This can be changed
in the [config/app.php](config/app.php) file.

As you can see in the example above, the three permissions `countries:delete` `countries:read` `countries:write` will be
created. We assign all of those permissions to the `Administrator` group and just the `countries:read` permissions to
the `User` group. 

To protect a specific action/route, just open the controller and add the middleware to the constructor:
```
$this->middleware('scopes:users:read', ['only' => ['index']]);
```

### OAuth client credentials
Client credential tokens are another way of authentication. These tokens are not assigned to a user and are made for
machine to machine communication. Any `client credential` client is only allowed to have one active token at a time. So,
if you need more than just one client credential token, you have to create another `client credential` client before.
To create a `client credential` client, just run:
```
php artisan passport:client --client
```
Note: Authorization with those tokens is not yet implemented!!!

### Create a migration
To create a new migration file, just run:
```
php artisan make:migration <migration-name>
php artisan make:migration create_users_table
```
This will add a new migration file in the [database/migrations](database/migrations) directory.

For a list of available column types, please see
[Creating Columns](https://laravel.com/docs/7.x/migrations#creating-columns).

### Create a seeder
To create a seeder, just run:
```
php artisan make:seeder <seeder-name>
php artisan make:seeder UsersTableSeeder
```
Once you have written your seeder, you may need to regenerate Composer's autoloader using the dump-autoload command:
```
composer dump-autoload
```
Now you may use the db:seed Artisan command to seed your database. By default, the db:seed command runs the
DatabaseSeeder class, which may be used to call other seed classes. However, you may use the --class option to specify a
specific seeder class to run individually:
```
php artisan db:seed
php artisan db:seed --class=UsersTableSeeder
```
You may also seed your database using the `migrate:fresh` command, which will drop all tables and re-run all of your
migrations. This command is useful for completely re-building your database:
```
php artisan migrate:fresh --seed
```
To learn more about seeder, please see [Database: Seeding](https://laravel.com/docs/7.x/seeding).
