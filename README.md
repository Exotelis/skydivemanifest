# Skydivemanifest
A tool to manage your dropzones manifest.

## Getting started
This project consists of different bundles. The `restapi` uses Laravel, which is a PHP framework.
The `administration` bundle is the frontend for the REST api, where the admin can manage the
manifest. It's based on the JavaScript framework Vue.js. Each bundle provides its own
documentation, because of the different technologies.

### Administration bundle
Please see the [REAMDE.md](administration/README.md) of the administration bundle for details.

## Roadmap
The first version is going to support a RESTful api as well as an administration interface to fill 
the api with data. Laravel will be used as framework for the api. The library Vue.js will be used
for the administration panel. Important functionalities are:
- User management
- Role management
- Permission handling
- Basic functions so have a working manifest app (adding aircrafts, skydiver, loads etc.)

In the second version the focus will be the UI and UX of the administration interface. Another
important feature will be the licensing. The administration panel should be able to check if the
product is registered.

## Contribution guidelines
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.