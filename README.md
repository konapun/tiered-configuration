# Tiered Configuration
A cascading configuration file parser and validator

Often, you will have multiple configurations for multiple environments but most of the configuration options may be shared between environments. Rather than having to sync changes in each configuration file,
it would be better to allow configurations to cascade, allowing like options to be defined in one place and optionally be overridden by more specific configurations. That's what this does.

## Configuration Formats
Any file type can be used for configuration, as long as it has an adapter. Currently, the only available adapters are for **json** and **flatfiles** (files where each line specifies a key=value pair and comments are given by lines starting with #), but creating adapters is simple -- just create a class that implements
`configuration\adapter\IAdapter` and write the implementation for `buildConfigurationTree`. You can also cascade configuration files of multiple formats.

### Example

Contrived **global** configuration file:
```json
{
  "project": {
    "root": "./lib",
    "name": "Tiered Configuration"
  }
}
```

Contrived **specific** configuration file:
```json
{
  "environment": {
    "name": "my environment"
  },
  "project": {
    "name": "Overridden name!"
  }
}
```

Cascading the configurations:
```php
include_once('lib/TieredConfiguration.php');
use configuration\adapter\JSONAdapter as JSONAdapter;
use configuration\TieredConfiguration as TieredConfiguration;

$config = new TieredConfiguration(array(new JSONAdapter('global.json'), new JSONAdapter('specific.json')));
$root = $config->getValue('root'); // "./lib" - from global.json
$projectName = $config->getValue('name'); // "Overridden name!" - from specific.json
```

You can get the configuration as a PHP array where each value is the appropriate cascaded value using `TieredConfiguration::flatten`:
```php
//Continuing example above
$flattened = $config->flatten();
/*
$flattened value =>

array(
  'project' => array(
    'root' => "./lib",
    'name' => "Overridden name!"
  ),
  'environment' => array(
    'name' => "my environment"
  )
)
*/
```
You can cascade any number of configuration files you want.

### Pending Tasks
  * **Scoping:** Configuration should support "sections", like "environment" and "project" above, to which config variables are scoped

## Validator
In order to enforce a certain configuration format, you may write an onus file which the validator compares against the loaded configuration. An onus file can be any format listed above (Configuration Formats)
and supports the following attributes:

  * **type** (each configuration value in the onus file must list a type)
    * **string**: a string value
    * **integer**: an integer value
    * **boolean**: true or false
    * **limit**: an array of allowed values
    * **range**: an inclusive range of allowed values
  * **required** (true|false - default **true**): Whether or not the configuration value is required to pass validation
  * **comment**: A free form comment used to describe the property for a self-documenting onus file

### Example
Sample onus file in JSON format
```json
{
    "environment": {
        "name": {
            "type": "string"
        }
    },
    "project": {
        "root": {
            "type": "string"
        },
        "name": {
            "type": "string"
        },
        "debug": {
            "type": "boolean",
            "required": false,
            "comment": "A boolean (true/false) type. Since it's optional it won't be required in the final configuration being checked against this onus file"
        },
        "test-limited": {
            "type": {
                "limit": [
                    0,
                    1,
                    "null"
                ]
            },
            "comment": "This shows an example of limiting input to values described which are, in this case, 0, 1, or the string value 'null'"
        },
        "test-ranged": {
            "type": {
                "range": [
                    0,
                    10
                ]
            },
            "comments": "This shows an example of using the range type from 0 (inclusive) to 10 (inclusive)"
        }
    },
    "test-optional": {
        "type": "string",
        "required": false,
        "comment": "An optional thing"
    }
}
```

### Validating a configuration
```php
include_once('lib/TieredConfiguration.php');
use configuration\adapter\JSONAdapter as JSONAdapter;
use configuration\TieredConfiguration as TieredConfiguration;
use configuration\Validator as Validator;

$config = new TieredConfiguration(array(new JSONAdapter('global.json'), new JSONAdapter('specific.json'))); // load the cascading configuration
$validator = new Validator(new JSONAdapter('onus.json')); // load the onus file to validate against
if (!$validator->validate($config)) {
  die("Validation of configuration files failed");
}
else {
  $root = $config->getValue('root'); // "./lib" - from global.json
  $projectName = $config->getValue('name'); // "Overridden name!" - from specific.json
}
```
