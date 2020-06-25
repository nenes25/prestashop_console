PrestashopConsole 1.6.1
=======================

* [`help`](#help)
* [`list`](#list)

**admin:**

* [`admin:user:change-password`](#adminuserchange-password)
* [`admin:user:create`](#adminusercreate)
* [`admin:user:list`](#adminuserlist)

**cache:**

* [`cache:clean`](#cacheclean)
* [`cache:clearAll`](#cacheclearAll)
* [`cache:flush`](#cacheflush)
* [`cache:media`](#cachemedia)
* [`cache:smarty:clear`](#cachesmartyclear)
* [`cache:smarty:configure`](#cachesmartyconfigure)

**configuration:**

* [`configuration:delete`](#configurationdelete)
* [`configuration:get`](#configurationget)
* [`configuration:getAll`](#configurationgetAll)
* [`configuration:mass`](#configurationmass)
* [`configuration:set`](#configurationset)

**console:**

* [`console:create:command`](#consolecreatecommand)
* [`console:self-upgrade`](#consoleself-upgrade)

**db:**

* [`db:export`](#dbexport)
* [`db:import`](#dbimport)
* [`db:query`](#dbquery)

**dev:**

* [`dev:add-index-files`](#devadd-index-files)
* [`dev:anonymize:customer`](#devanonymizecustomer)
* [`dev:clean`](#devclean)
* [`dev:cron:list`](#devcronlist)
* [`dev:cron:run`](#devcronrun)
* [`dev:ide-class-names`](#devide-class-names)
* [`dev:list-overrides`](#devlist-overrides)
* [`dev:mode`](#devmode)

**hook:**

* [`hook:list`](#hooklist)
* [`hook:modules`](#hookmodules)

**images:**

* [`images:export`](#imagesexport)
* [`images:generate:categories`](#imagesgeneratecategories)
* [`images:generate:manufacturers`](#imagesgeneratemanufacturers)
* [`images:generate:products`](#imagesgenerateproducts)
* [`images:generate:stores`](#imagesgeneratestores)
* [`images:generate:suppliers`](#imagesgeneratesuppliers)

**install:**

* [`install:info`](#installinfo)
* [`install:install`](#installinstall)

**module:**

* [`module:disable`](#moduledisable)
* [`module:enable`](#moduleenable)
* [`module:generate:controller`](#modulegeneratecontroller)
* [`module:generate:model`](#modulegeneratemodel)
* [`module:generate:module`](#modulegeneratemodule)
* [`module:generate:upgrade`](#modulegenerateupgrade)
* [`module:hook:add`](#modulehookadd)
* [`module:hook:list`](#modulehooklist)
* [`module:hook:remove`](#modulehookremove)
* [`module:install`](#moduleinstall)
* [`module:list`](#modulelist)
* [`module:reset`](#modulereset)
* [`module:tab:add`](#moduletabadd)
* [`module:tab:list`](#moduletablist)
* [`module:tab:remove`](#moduletabremove)
* [`module:uninstall`](#moduleuninstall)

**parameters:**

* [`parameters:generate:htaccess`](#parametersgeneratehtaccess)
* [`parameters:generate:robots`](#parametersgeneraterobots)

**preferences:**

* [`preferences:cmscategory`](#preferencescmscategory)
* [`preferences:cmspage`](#preferencescmspage)
* [`preferences:maintenance`](#preferencesmaintenance)
* [`preferences:override`](#preferencesoverride)
* [`preferences:search:index`](#preferencessearchindex)
* [`preferences:urlrewrite`](#preferencesurlrewrite)

`help`
------

Displays help for a command

### Usage

* `help [--format FORMAT] [--raw] [--] [<command_name>]`

The help command displays help for a given command:

  php console_dev.php help list

You can also output the help in other formats by using the --format option:

  php console_dev.php help --format=xml list

To display the list of available commands, please use the list command.

### Arguments

#### `command_name`

The command name

* Is required: no
* Is array: no
* Default: `'help'`

### Options

#### `--format`

The output format (txt, xml, json, or md)

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `'txt'`

#### `--raw`

To output raw command help

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`list`
------

Lists commands

### Usage

* `list [--raw] [--format FORMAT] [--] [<namespace>]`

The list command lists all commands:

  php console_dev.php list

You can also display the commands for a specific namespace:

  php console_dev.php list test

You can also output the information in other formats by using the --format option:

  php console_dev.php list --format=xml

It's also possible to get raw list of commands (useful for embedding command runner):

  php console_dev.php list --raw

### Arguments

#### `namespace`

The namespace name

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--raw`

To output raw command list

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--format`

The output format (txt, xml, json, or md)

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `'txt'`

`admin:user:change-password`
----------------------------

Change admin user password

### Usage

* `admin:user:change-password`

Change admin user password

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`admin:user:create`
-------------------

Create new admin user

### Usage

* `admin:user:create [--email [EMAIL]] [--password [PASSWORD]] [--firstname [FIRSTNAME]] [--lastname [LASTNAME]]`

Create new admin user

### Options

#### `--email`

Admin email

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--password`

Admin password

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--firstname`

firstname

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--lastname`

lastname

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`admin:user:list`
-----------------

List admin users

### Usage

* `admin:user:list`

List admin users registered in employee table

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:clean`
-------------

Clean cache

### Usage

* `cache:clean [<key>]`

Clean cache

### Arguments

#### `key`

key name | default *

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:clearAll`
----------------

Clear all cache

### Usage

* `cache:clearAll`

Clear all cache

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:flush`
-------------

Flush cache

### Usage

* `cache:flush`

Flush cache

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:media`
-------------

Clean media cache directory

### Usage

* `cache:media`

Clean media cache directory

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:smarty:clear`
--------------------

Clear smarty cache

### Usage

* `cache:smarty:clear`

Clear smarty cache

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`cache:smarty:configure`
------------------------

Configure Smarty cache

### Usage

* `cache:smarty:configure <name> <value>`

Configure Smarty cache

### Arguments

#### `name`

configuration name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `value`

configuration value

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`configuration:delete`
----------------------

delete configuration value

### Usage

* `configuration:delete <name>`

delete configuration value

### Arguments

#### `name`

configuration name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`configuration:get`
-------------------

get configuration value

### Usage

* `configuration:get <name>`

get configuration value

### Arguments

#### `name`

configuration name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`configuration:getAll`
----------------------

get all configuration values

### Usage

* `configuration:getAll`

get all configuration values

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`configuration:mass`
--------------------

Mass operation configured in yaml file

### Usage

* `configuration:mass [--config CONFIG]`

Mass operation configured in yaml file

### Options

#### `--config`

Yaml definition file

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`configuration:set`
-------------------

set configuration value

### Usage

* `configuration:set <name> <value>`

set configuration value

### Arguments

#### `name`

configuration name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `value`

configuration value

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`console:create:command`
------------------------

Create a new command skeleton

### Usage

* `console:create:command`

Create a new command skeleton

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`console:self-upgrade`
----------------------

Upgrade console to last version (phar only)

### Usage

* `console:self-upgrade`

Upgrade console to last version (phar only)

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`db:export`
-----------

Create db export 

### Usage

* `db:export [-t|--type [TYPE]] [-g|--gzip [GZIP]] [-f|--filename [FILENAME]]`

This command will export current prestashop database using mysqldump shell command

### Options

#### `--type|-t`

allowed values all|customers|orders|catalog

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'all'`

#### `--gzip|-g`

compress export in gzip

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--filename|-f`

custom file name for export

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`db:import`
-----------

Import db dump 

### Usage

* `db:import [-f|--file FILE] [-g|--gzip [GZIP]]`

This command will import dumb (gziped or not ) in current prestashop database using mysql shell command

### Options

#### `--file|-f`

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `NULL`

#### `--gzip|-g`

gzip 

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`db:query`
----------

Run sql query on prestashop db

### Usage

* `db:query [-s|--query QUERY]`

This command will exec db query using the prestashop Db class, its only allow SELECT queries

### Options

#### `--query|-s`

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:add-index-files`
---------------------

Add missing index.php files in directory

### Usage

* `dev:add-index-files <dir>`

Add missing index.php files in directory

### Arguments

#### `dir`

directory to fill ( relative to ps root path)

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:anonymize:customer`
------------------------

Anonymize Customer information

### Usage

* `dev:anonymize:customer [--type [TYPE]] [--exclude-emails [EXCLUDE-EMAILS]] [--names [NAMES]]`

This command will anonymize customer related data (lastname,firstname,email ) without erasing them

### Options

#### `--type`

allowed values all|customers|addresses|newsletter

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--exclude-emails`

emails to exclude separated by commas

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--names`

anonymize names (default none ) use only for customers

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:clean`
-----------

Clean existing datas with module PsCleaner

### Usage

* `dev:clean <type>`

Clean existing datas with module PsCleaner

### Arguments

#### `type`

data types. Possibles values all, catalog, sales

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:cron:list`
---------------

List cron tasks configured with the module cronjobs

### Usage

* `dev:cron:list`

List cron tasks configured with the module cronjobs

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:cron:run`
--------------

Run cron task configured with the module cronjobs

### Usage

* `dev:cron:run <id_cronjob>`

Run cron task configured with the module cronjobs

### Arguments

#### `id_cronjob`

cron job id ( use command dev:cron:list to get it )

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:ide-class-names`
---------------------

Download class names index to resolve autocompletion in IDE

### Usage

* `dev:ide-class-names`

Download class names index to resolve autocompletion in IDE

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:list-overrides`
--------------------

List overrides of classes and controllers in the project

### Usage

* `dev:list-overrides`

List overrides of classes and controllers in the project

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`dev:mode`
----------

Enable / Disable debug mode (to display errors).

### Usage

* `dev:mode <state>`

Enable / Disable debug mode (to display errors).

### Arguments

#### `state`

enable or disable debug mode ( possible values : enable,disable,toggle)
Be sure to include "include(__DIR__. '/debug_mode.php');" in config/defines.inc.php for this feature to run.

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`hook:list`
-----------

List all hooks registered in database

### Usage

* `hook:list`

List all hooks registered in database

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`hook:modules`
--------------

List all hooks with hooked modules

### Usage

* `hook:modules`

List all hooks with hooked modules

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:export`
---------------

Export images

### Usage

* `images:export [-t|--type [TYPE]] [-i|--interactive [INTERACTIVE]] [-a|--archive [ARCHIVE]]`

Export images

### Options

#### `--type|-t`

type of file to export

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--interactive|-i`

Interactive Mode

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--archive|-a`

Archive format

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'tar.gz'`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:generate:categories`
----------------------------

Regenerate categories thumbnails

### Usage

* `images:generate:categories [-f|--force] [--] [<format>]...`

Regenerate categories thumbnails

### Arguments

#### `format`

images formats separated by a space

* Is required: no
* Is array: yes
* Default: `array (  0 => 'all',)`

### Options

#### `--force|-f`

Force delete of currents thumbnails

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:generate:manufacturers`
-------------------------------

Regenerate manufacturers thumbnails

### Usage

* `images:generate:manufacturers [-f|--force] [--] [<format>]...`

Regenerate manufacturers thumbnails

### Arguments

#### `format`

images formats separated by a space

* Is required: no
* Is array: yes
* Default: `array (  0 => 'all',)`

### Options

#### `--force|-f`

Force delete of currents thumbnails

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:generate:products`
--------------------------

Regenerate products thumbnails

### Usage

* `images:generate:products [-f|--force] [--] [<format>]...`

Regenerate products thumbnails

### Arguments

#### `format`

images formats separated by a space

* Is required: no
* Is array: yes
* Default: `array (  0 => 'all',)`

### Options

#### `--force|-f`

Force delete of currents thumbnails

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:generate:stores`
------------------------

Regenerate stores thumbnails

### Usage

* `images:generate:stores [-f|--force] [--] [<format>]...`

Regenerate stores thumbnails

### Arguments

#### `format`

images formats separated by a space

* Is required: no
* Is array: yes
* Default: `array (  0 => 'all',)`

### Options

#### `--force|-f`

Force delete of currents thumbnails

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`images:generate:suppliers`
---------------------------

Regenerate suppliers thumbnails

### Usage

* `images:generate:suppliers [-f|--force] [--] [<format>]...`

Regenerate suppliers thumbnails

### Arguments

#### `format`

images formats separated by a space

* Is required: no
* Is array: yes
* Default: `array (  0 => 'all',)`

### Options

#### `--force|-f`

Force delete of currents thumbnails

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`install:info`
--------------

prestashop install info

### Usage

* `install:info [--raw] [--format FORMAT] [--] [<namespace>]`

prestashop install info

### Arguments

#### `namespace`

The namespace name

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--raw`

To output raw command list

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--format`

The output format (txt, xml, json, or md)

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `'txt'`

`install:install`
-----------------

install prestashop

### Usage

* `install:install [--psVersion [PSVERSION]] [--domainName [DOMAINNAME]] [--dbname [DBNAME]] [--dbuser [DBUSER]] [--dbpassword [DBPASSWORD]] [--contactEmail [CONTACTEMAIL]] [--adminpassword [ADMINPASSWORD]] [--directory [DIRECTORY]]`

install prestashop

### Options

#### `--psVersion`

Prestashop version

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--domainName`

domainName

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--dbname`

dbname

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--dbuser`

dbuser

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--dbpassword`

dbpassword

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--contactEmail`

contactEmail

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--adminpassword`

Adminpassword

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--directory`

Install directory

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:disable`
----------------

Disable module

### Usage

* `module:disable <name> (<name>)...`

Disable module

### Arguments

#### `name`

module name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:enable`
---------------

Enable module

### Usage

* `module:enable <name> (<name>)...`

Enable module

### Arguments

#### `name`

module name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:generate:controller`
----------------------------

Generate module controller file

### Usage

* `module:generate:controller [-t|--template TEMPLATE] [--] <moduleName> <controllerName> <controllerType>`

Generate module controller file

### Arguments

#### `moduleName`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `controllerName`

controller name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `controllerType`

controller type

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--template|-t`

generate template

* Accept value: yes
* Is value required: yes
* Is multiple: no
* Default: `true`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:generate:model`
-----------------------

Generate module model object

### Usage

* `module:generate:model <moduleName> [<objectClass>]`

Generate module model object

### Arguments

#### `moduleName`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `objectClass`

object class

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:generate:module`
------------------------

Generate module default file

### Usage

* `module:generate:module [-i|--interactive [INTERACTIVE]] [-a|--author [AUTHOR]] [-dn|--displayName [DISPLAYNAME]] [-d|--description [DESCRIPTION]] [-l|--hookList [HOOKLIST]] [-w|--widget [WIDGET]] [-t|--templates [TEMPLATES]] [--] <name>`

Generate module default file

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--interactive|-i`

Interactive Mode

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--author|-a`

Module author name

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'hhennes'`

#### `--displayName|-dn`

Display Name

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'Your module display name'`

#### `--description|-d`

Description

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'Your module description'`

#### `--hookList|-l`

Comma separated hook List

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--widget|-w`

Implement widget interface

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--templates|-t`

Generate hook templates

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:generate:upgrade`
-------------------------

Generate module upgrade file

### Usage

* `module:generate:upgrade <moduleName> <moduleVersion>`

Generate module upgrade file

### Arguments

#### `moduleName`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `moduleVersion`

module version

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:hook:add`
-----------------

Add module to one or several hooks

### Usage

* `module:hook:add <name> <hooks> (<hooks>)...`

Add module to one or several hooks

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `hooks`

hooks name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:hook:list`
------------------

Get modules list

### Usage

* `module:hook:list <name>`

Get modules list

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:hook:remove`
--------------------

Remove module to one or several hooks

### Usage

* `module:hook:remove <name> <hooks> (<hooks>)...`

Remove module to one or several hooks

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `hooks`

hooks name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:install`
----------------

Install module

### Usage

* `module:install <name> (<name>)...`

Install module

### Arguments

#### `name`

module name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:list`
-------------

Get modules list

### Usage

* `module:list [--active] [--no-active] [--installed] [--no-installed]`

Get modules list

### Options

#### `--active`

List only active modules

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-active`

List only not active modules

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--installed`

List only installed modules

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-installed`

List only not installed modules

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:reset`
--------------

Reset module: hard = remove data and reinstall, soft(default) = keep data and reinstall

### Usage

* `module:reset [--type [TYPE]] [--] <name> (<name>)...`

Reset module: hard = remove data and reinstall, soft(default) = keep data and reinstall

### Arguments

#### `name`

module name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--type`

hard|soft(default)

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:tab:add`
----------------

Add module admin tab

### Usage

* `module:tab:add [-p|--parentTab [PARENTTAB]] [-i|--icon [ICON]] [--] <name> <tab> <label>`

Allow to add a new admin tab (controller )

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `tab`

tab class name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `label`

tab label

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--parentTab|-p`

Parent tab

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `'DEFAULT'`

#### `--icon|-i`

Tab icon

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:tab:list`
-----------------

list module admin tab

### Usage

* `module:tab:list <name>`

list module admin tab

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:tab:remove`
-------------------

remove module admin tab

### Usage

* `module:tab:remove <name> <tab>`

remove module admin tab

### Arguments

#### `name`

module name

* Is required: yes
* Is array: no
* Default: `NULL`

#### `tab`

tab class name

* Is required: yes
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`module:uninstall`
------------------

Uninstall module

### Usage

* `module:uninstall <name> (<name>)...`

Uninstall module

### Arguments

#### `name`

module name ( separate multiple with spaces )

* Is required: yes
* Is array: yes
* Default: `array ()`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`parameters:generate:htaccess`
------------------------------

Generate the .htaccess file

### Usage

* `parameters:generate:htaccess`

Generate the .htaccess file

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`parameters:generate:robots`
----------------------------

Generate the robots.txt file

### Usage

* `parameters:generate:robots [-e|--executeHook [EXECUTEHOOK]]`

Generate the robots.txt file

### Options

#### `--executeHook|-e`

Generate actionAdminMetaBeforeWriteRobotsFile hook ?

* Accept value: yes
* Is value required: no
* Is multiple: no
* Default: `NULL`

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:cmscategory`
-------------------------

Disable or enable a specific cms category

### Usage

* `preferences:cmscategory <id> [<action>]`

Disable or enable a specific cms category

### Arguments

#### `id`

cms category id

* Is required: yes
* Is array: no
* Default: `NULL`

#### `action`

enable|disable(default)

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:cmspage`
---------------------

Disable or enable a specific cms page

### Usage

* `preferences:cmspage <id> [<action>]`

Disable or enable a specific cms page

### Arguments

#### `id`

cms page id

* Is required: yes
* Is array: no
* Default: `NULL`

#### `action`

enable|disable(default

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:maintenance`
-------------------------

Disable or enable shop

### Usage

* `preferences:maintenance [<type>]`

Disable or enable shop

### Arguments

#### `type`

enable|disable(default)

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:override`
----------------------

Disable or enable Override

### Usage

* `preferences:override [<type>]`

Disable or enable Override

### Arguments

#### `type`

enable|disable(default)

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:search:index`
--------------------------

Add missing products to the index or re-build the entire index (default)

### Usage

* `preferences:search:index [<type>]`

Add missing products to the index or re-build the entire index (default)

### Arguments

#### `type`

add|rebuild(default)

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

`preferences:urlrewrite`
------------------------

Disable or enable Url Rewrite

### Usage

* `preferences:urlrewrite [<type>]`

Disable or enable Url Rewrite

### Arguments

#### `type`

enable|disable(default)

* Is required: no
* Is array: no
* Default: `NULL`

### Options

#### `--help|-h`

Display this help message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--quiet|-q`

Do not output any message

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--verbose|-v|-vv|-vvv`

Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--version|-V`

Display this application version

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--ansi`

Force ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-ansi`

Disable ANSI output

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`

#### `--no-interaction|-n`

Do not ask any interactive question

* Accept value: no
* Is value required: no
* Is multiple: no
* Default: `false`