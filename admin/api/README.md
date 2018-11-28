# API documentation

The api is based on REST principles.

* GET - get data
* POST - create new instance (or update)
* DELETE - delete data

## Managing modules

### modules

* GET - getModules - gets the overall config of managing structure within the components directory

## module

Common arguments:
* `id` - id of the modules (component name from `getModules`)

Methods:
* POST - installModule - install module
* DELETE - uninstallModule - uninstall module
