# Tiered Configuration
A cascading configuration file parser and library

Often, you will have multiple configurations for multiple environments but most of the configuration options may be shared between environments. Rather than having to sync changes in each configuration file,
it would be better to allow configurations to cascade, allowing like options to be defined in one place and optionally be overridden by more specific configurations. That's what this does.

## Configuration Formats
Any file type can be used for configuration, as long as it has an adapter. Currently, the only available adapter is for **json**, but creating adapters is simple -- just create a class that implements
`configuration\adapter\IAdapter` and write the implementation for `buildConfigurationTree`. You can also cascade configuration files of multiple formats.

## Example

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

You can cascade any number of configuration files you want

## Pending Tasks
  * **Scoping:** Configuration should support "sections", like "environment" and "project" above, to which config variables are scoped
  
