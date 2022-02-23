# Emigrate module

This module exports all content of a Drupal site in a hierarchy of portable JSON files. It is still in development.

# Prerequisites

- Drush 9
- TOML (installed with composer)

## How to use this module

At the root of your drupal installation, type

```shell
drush emigrate:init
```

It will create a emigrate directory with a TOML configuration file inside it. You can then type

```shell
drush emigrate:export
```

All nodes, users and taxonomy terms will be exported as individual json files.