PrestashopConsole 2.0.0
=======================

* [`help`](#help)
* [`list`](#list)

**install:**

* [`install:info`](#installinfo)
* [`install:install`](#installinstall)

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