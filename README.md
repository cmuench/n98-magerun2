# netz98 magerun CLI tools for Magento 2

![n98-magerun Logo](.github/doc/magerun-logo.png)

The n98 magerun CLI Tools provides some handy tools to work with Magento / Mage-OS / Adobe Commerce
from command line.

> The swiss army knife for Magento developers, sysadmins and devops

## Build Status

| **Latest Release**     | ![Tests](https://github.com/netz98/n98-magerun2/actions/workflows/magento_platform_tests.yml/badge.svg?branch=master) ![Maintenance Badge](https://img.shields.io/maintenance/yes/2025.svg) |
|------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Development Branch** | ![Tests](https://github.com/netz98/n98-magerun2/actions/workflows/magento_platform_tests.yml/badge.svg?branch=develop)                                                                                                                                                                      |

Development is done in **develop** branch.

This software is only running with Magento 2.

If you use Magento 1 (EOL) or OpenMage please use another software in a different repository
(<https://github.com/netz98/n98-magerun>).

## Compatibility

The tools will automatically be tested for multiple PHP versions. It's
currently running in various Linux distributions and Mac OS X. Microsoft
Windows is not fully supported (some Commands like `db:dump`
or `install` are excluded).

We support the following Magento Versions:

- Mage-OS 1.0.x
- 2.4.7 Open Source/Commerce
- 2.4.6 Open Source/Commerce
- 2.4.5 Open Source/Commerce
- 2.4.4 Open Source/Commerce (last compatible n98-magerun2 version is v7.5.0)
- 2.3.x Open Source/Commerce (last compatible n98-magerun2 version is v5.2.0)
- 2.2.x Open Source/Commerce (last compatible n98-magerun2 version is v3.2.0)

We support the following PHP Versions:

- PHP 8.3
- PHP 8.2
- PHP 8.1
- PHP 7.4 (last compatible version is v7.5.0)
- PHP 7.3 (last compatible version is v6.1.1)
- PHP 7.2 (last compatible version is v4.7.0)

## Installation

There are three ways to install the tools:

### Download and Install Phar File

Download the latest stable n98-magerun phar-file from the [file-server](https://files.magerun.net/):

```sh
wget https://files.magerun.net/n98-magerun2.phar
```

or if you prefer to use Curl:

```sh
curl -O https://files.magerun.net/n98-magerun2.phar
```

Verify the download by comparing the SHA256 checksum with the one on the
website:

```sh
shasum -a256 n98-magerun2.phar
```

It is also possible to verify automatically:

```sh
curl -sS -O https://files.magerun.net/n98-magerun2-latest.phar
curl -sS -o n98-magerun2-latest.phar.sha256 https://files.magerun.net/sha256.php?file=n98-magerun2-latest.phar
shasum -a 256 -c n98-magerun2-latest.phar.sha256
```

If it shows the same checksum as on the website, you downloaded the file
successfully.

Now you can make the phar-file executable:

```sh
chmod +x ./n98-magerun2.phar
```

The base-installation is now complete and you can verify it:

```sh
./n98-magerun2.phar --version
```

The command should execute successfully and show you the version number
of N98-Magerun like:

`n98-magerun2 version 4.8.0 by valantic CEC`

You now have successfully installed Magerun! You can tailor the
installation further like installing it system-wide and enable
autocomplete - read on for more information about these and other
features.

If you want to use the command system wide you can copy it to
`/usr/local/bin`.

```sh
sudo cp ./n98-magerun2.phar /usr/local/bin/
```

### Install the phar via Composer

We offer a special dist package to install the phar file via Composer.
See <https://packagist.org/packages/n98/magerun2-dist> for more details.
The main advantage of the dist package is that there are no package dependencies.

### Install with Composer (Source Package) - not recommended

The installation via Composer is **not recommended**,
because it's impossible to be compatible with all project and Magento core dependencies.
Please use the phar file instead of the Composer version. We are not able to provide
compatibility to all Magento versions anymore.

## Update

There is a `self-update` command available. This works only
for phar-distribution.

```sh
./n98-magerun2.phar self-update [--dry-run] <version>
```

With `--dry-run` option it is possible to download and test
the phar file without replacing the old one.

The version argument is optional and can be used to rollback to a specific
version of n98-magerun2. The version was introduced with v8.0.0. Older versions do not have the version argument.

## Autocompletion

Files for autocompletion with Magerun can be found inside the folder
`res/autocompletion`, In the following some more information
about a specific one (Bash), there are more (e.g. Fish, Zsh).

### Bash

Bash completion is available pre-generated, all commands and their
respective options are availble on tab. To get completion for an option
type two dashes (`--`) and then tab.

To install the completion files, copy `n98-magerun2.phar.bash` to your
bash compatdir folder for autocompletion.

On my Ubuntu system this can be done with the following command:

```sh
sudo cp res/autocompletion/bash/n98-magerun2.phar.bash /etc/bash_completion.d/
```

The concrete folder can be obtained via pkg-config:

```sh
pkg-config --variable=compatdir bash-completion
```

Detailed information is available in the bash-completions FAQ:
<https://github.com/scop/bash-completion#faq>

## Usage / Commands

> **NOTE** There are more commands available as documented here. Please use the list command to see all.

All commands try to detect the current Magento root directory. If you
have multiple Magento installations you must change your working
directory to the preferred installation.

You can list all available commands by:

```sh
n98-magerun2.phar list
```

If you don't have the .phar file installed system wide you can call it
with the PHP CLI interpreter:

```sh
php n98-magerun2.phar list
```

Global config parameters:

| Parameter                            | Description                                 |
|--------------------------------------|---------------------------------------------|
| `--root-dir`                         | Force Magento root dir. No auto detection.  |
| `--skip-config`                      | Do not load any custom config.              |
| `--skip-root-check`                  | Do not check if n98-magerun2 runs as root.  |
| `--skip-core-commands`               | Do not include Magento commands.            |
| `--skip-magento-compatibility-check` | Do not check Magento version compatibility. |

### Call Core Magento Commands

The tool can be used to run core Magento commands. We provide a internal *Proxy Command* which calls
the original Magento command via `bin/magento`.
All options and arguments are passed to the original command.

If you do not want to use the proxy command you can disable it with the `--skip-core-commands` option.

One of the big advantages of the proxy command is that you can run any command without having to change the working
directory to the Magento root directory
or to specify the path to `bin/magento` if your current working directory is inside the Magento installation.

If you are outside the Magento root directory you can run any command by specifying the Magento root directory with
the `--root-dir` option.
That is very useful if you have multiple Magento installations or if it is used in some kind of automation.

For core commands we filter environment variables to avoid problems with enabled xdebug extension.

### Open Shop in Browser

```sh
n98-magerun2.phar open-browser [store]
```

---

### Customer Info

Loads basic customer info by email address.

```sh
n98-magerun2.phar customer:info [email] [website]
```

### Create customer

Creates a new customer/user for shop frontend.

```sh
n98-magerun2.phar customer:create [email] [password] [firstname] [lastname] [website]
```

Example:

```sh
n98-magerun2.phar customer:create foo@example.com password123 John Doe base
```

You can add additional any number of custom fields, example:

```sh
n98-magerun2.phar customer:create foo@example.com passworD123 John Doe base taxvat DE12345678 prefix Mrs.
```

### List Customers

List customers. The output is limited to 1000 (can be changed by
overriding config). If search parameter is given the customers are
filtered (searchs in firstname, lastname and email).

```sh
n98-magerun2.phar customer:list [--format[="..."]] [search]
```

### Change customer password

```sh
n98-magerun2.phar customer:change-password [email] [password] [website]
```

- Website parameter must only be given if more than one websites are available.

### Create Customer Token for Webapi

```sh
n98-magerun2.phar customer:token:create <email>
```

### Delete customer

```sh
n98-magerun2.phar customer:delete [-f|--force] [-a|--all] [-r|--range] [--fuzzy] [--id=ID] [--website=ID] [--email=EMAIL] [--firstname=STRING] [--lastname=STRING]
```

Examples:

```sh
n98-magerun2.phar customer:delete --id 1                     # Will delete customer with Id 1
n98-magerun2.phar customer:delete --fuzzy --email=test       # Will delete all customers with email like "%test%"
n98-magerun2.phar customer:delete --all                      # Will delete all customers
n98-magerun2.phar customer:delete --range                    # Will prompt for start and end Ids for batch deletion
```

Deletes customer(s) by given id or a combination of the website id and email or website id and firstname and lastname.
In addition, you can delete a range of customer ids or delete all customers.


### Add customer address

```sh
n98-magerun2.phar customer:add-address [email] [website] [--firstname=STRING] [--lastname=STRING] [--street=STRING] [--city=STRING] [--country=STRING] [--postcode=STRING] [--telephone=STRING] [--default-billing] [--default-shipping]
```

Examples:

```sh
n98-magerun2.phar customer:add-address foo@example.com base --firstname="John" --lastname="Doe" --street="Pariser Platz" --city="Berlin" --country="DE" --postcode="10117" --telephone="1234567890"  # add address of brandenburger tor to customer with email "foo@example.com" in website "base"
n98-magerun2.phar customer:add-address foo@example.com base --firstname="John" --lastname="Doe" --street="Pariser Platz" --city="Berlin" --country="DE" --postcode="10117" --telephone="1234567890" --default-billing --default-shipping # add address of brandenburger tor to customer with email "foo@example.com" in website "base" as default billing and shipping
```

Adds a customer address to given customer defined by email and website
---

### Magento Installer

- Downloads Composer (if not already installed)
- Downloads Magento 2.
- Tries to create database if it does not exist.
- Installs Magento sample data.
- Starts Magento installer
- Sets rewrite base in .htaccess file

Interactive installer:

```sh
n98-magerun2.phar install
```

Unattended installation:

```sh
n98-magerun2.phar install [--magentoVersion[="..."]] [--magentoVersionByName[="..."]] [--installationFolder[="..."]] [--dbHost[="..."]] [--dbUser[="..."]] [--dbPass[="..."]] [--dbName[="..."]] [--installSampleData[="..."]] [--useDefaultConfigParams[="..."]] [--baseUrl[="..."]] [--replaceHtaccessFile[="..."]]
```

Example of an unattended Magento CE 2.0.0.0 dev beta 1 installation:

```sh
n98-magerun2.phar install --dbHost="localhost" --dbUser="mydbuser" --dbPass="mysecret" --dbName="magentodb" --installSampleData=yes --useDefaultConfigParams=yes --magentoVersionByName="magento-ce-2.0.0.0-dev-beta1" --installationFolder="magento2" --baseUrl="http://magento2.localdomain/"
```

Additionally, with `--noDownload` option you can install Magento working
copy already stored in `--installationFolder` on the given database.

---

### Magento System Info

Provides infos like the edition, version or the configured cache
backends, amount of data or installed packages.

```sh
n98-magerun2.phar sys:info
```

**Options:**

| Option   | Description        |
|----------|--------------------|
| `--sort` | Sort table by name |

---

### Magento Stores

Lists all store views.

```sh
n98-magerun2.phar sys:store:list [--format[="..."]]
```

### Magento Websites

Lists all websites.

```sh
n98-magerun2.phar sys:website:list [--format[="..."]]
```

---

### List Cronjobs

Lists all cronjobs defined in crontab.xml files.

```sh
n98-magerun2.phar sys:cron:list [--format[="..."]]
```

### Run Cronjobs

Runs a cronjob by code.

```sh
n98-magerun2.phar sys:cron:run [job]
```

If no `job` argument is passed you can select a job from a list.
See it in action: <http://www.youtube.com/watch?v=QkzkLgrfNaM>
If option schedule is present, cron is not launched, but just scheduled immediately in magento crontab.

### Kill a running job

```sh
n98-magerun2.phar sys:cron:kill [--timeout <seconds>] [job_code]
```

If no job is specified a interactive selection of all running jobs is shown.
Jobs can only be killed if the process runs on the same machine as n98-magerun2.

Default timeout of a process kill is 5 seconds.

### Cronjob History

Last executed cronjobs with status.

```sh
n98-magerun2.phar sys:cron:history [--format[="..."]] [--timezone[="..."]]
```

---

### Create app/etc/env.php

Create env file interactively.
If can also update existing files.
To update a single value you can use the command `config:env:set`.

```sh
n98-magerun2.phar config:env:create
```

### Set single value in env.php file

Set a single value in env.php by providing a key and an optional value.
The command will save an empty string as default value if no value is set.

Sub-arrays in config.php can be specified by adding a "." character to every array.

```sh
n98-magerun2.phar config:env:set <key> [<value>]
```

You can also choose to provide a json text argument as value, by using the optional `--input-format=json` flag.
This will allow you to add values that aren't a string but also other scalar types.

Examples:

```sh
n98-magerun2.phar config:env:set backend.frontName mybackend
n98-magerun2.phar config:env:set crypt.key bb5b0075303a9bb8e3d210a971674367
n98-magerun2.phar config:env:set session.redis.host 192.168.1.1
n98-magerun2.phar config:env:set 'x-frame-options' '*'

n98-magerun2.phar config:env:set --input-format=json queue.consumers_wait_for_messages 0
n98-magerun2.phar config:env:set --input-format=json directories.document_root_is_pub true
n98-magerun2.phar config:env:set --input-format=json cron_consumers_runner.consumers '["some.consumer", "some.other.consumer"]'
```

### Delete key from env.php file

Remove a configuration from the env.php file by providing a key.

Sub-arrays in config.php can be specified by adding a "." character to every array.

```sh
n98-magerun2.phar config:env:delete <key>
```

Examples:

```sh
n98-magerun2.phar config:env:delete system
n98-magerun2.phar config:env:delete cache.frontend.default.backend
n98-magerun2.phar config:env:delete cache.frontend.default.backend_options
```

### Show env.php settings

```sh
n98-magerun2.phar config:env:show [options] [<key>]
```

If no key is passed, the whole content of the file is displayed as table.

Examples:

```sh
n98-magerun2.phar config:env:show  # whole content
n98-magerun2.phar config:env:show backend.frontName
n98-magerun2.phar config:env:show --format=json
n98-magerun2.phar config:env:show --format=csv
n98-magerun2.phar config:env:show --format=xml
```

---

### Config Search

Search in the store config meta data (labels).
The output is a table with id, type and name of the config item.

Type can be one of:
- section
- group
- field

```sh
n98-magerun2.phar config:search [--format[="..."]] <search>
```


### Set Store Config

```sh
n98-magerun2.phar config:store:set [--scope[="..."]] [--scope-id[="..."]] [--encrypt] path value
```

**Arguments:**

- path - The config path value The config value

**Options:**

| Option       | Description                                                                            |
|--------------|----------------------------------------------------------------------------------------|
| `--scope`    | The config value's scope (default: `default`). Can be `default`, `websites`, `stores`) |
| `--scope-id` | The config value's scope ID (default: `0`)                                             |
| `--encrypt`  | Encrypt the config value using crypt key                                               |

### Get Store Config

```sh
n98-magerun2.phar config:store:get [--scope="..."] [--scope-id="..."] [--decrypt] [--format[="..."]] [path]
```

**Arguments:**

- path - The config path

**Options:**

| Option             | Description                                                   |
|--------------------|---------------------------------------------------------------|
| `--scope`          | The config value's scope (`default`, `websites`, `stores`)    |
| `--scope-id`       | The config value's scope ID or scope code                     |
| `--decrypt`        | Decrypt the config value using crypt key defined in `env.php` |
| `--update-script`  | Output as update script lines                                 |
| `--magerun-script` | Output for usage with `config:store:set`                      |
| `--format`         | Output as `json`, `xml` or `csv`                              |

**Help:**

If path is not set, all available config items will be listed. path may contain wildcards (`*`)

**Example:**

```sh
n98-magerun2.phar config:store:get web/* --magerun-script
```

### Delete Store Config

```sh
n98-magerun2.phar config:store:delete [--scope[="..."]] [--scope-id[="..."]] [--all] path
```

**Arguments:**

- path - The config path

**Options:**

| Option       | Description                                          |
|--------------|------------------------------------------------------|
| `--scope`    | The config value's scope (default, websites, stores) |
| `--scope-id` | The config value's scope ID                          |
| `--all`      | Delete all entries by path                           |


### Display ACL Tree

```sh
n98-magerun2.phar config:data:acl
```

**Help:**

Prints acl.xml data as table

### Print Dependency Injection Config Data

```sh
n98-magerun2.phar config:data:di <type>
```

**Arguments:**

- type - Type (class)

**Options:**

| Option         | Description                                                                                             |
|----------------|---------------------------------------------------------------------------------------------------------|
| `--scope` `-s` | Config scope (`global`, `adminhtml`, `frontend`, `webapi_rest`, `webapi_soap`, ...) (default: `global`) |

### Print MView Config

Print the data of all merged mview.xml files.

```sh
n98-magerun2.phar config:data:mview [options]
```

**Options:**

| Option         | Description                                                                                             |
|----------------|---------------------------------------------------------------------------------------------------------|
| `--scope` `-s` | Config scope (`global`, `adminhtml`, `frontend`, `webapi_rest`, `webapi_soap`, ...) (default: `global`) |
| `--tree` `-t`  | Print data as tree                                                                                      |
| `--format`     | Output as `json`, `xml` or `csv`                                                                        |

### Print Indexer Config

Print the data of all merged indexer.xml files.

```sh
n98-magerun2.phar config:data:indexer [options]
```

**Options:**

| Option         | Description                                                                                             |
|----------------|---------------------------------------------------------------------------------------------------------|
| `--scope` `-s` | Config scope (`global`, `adminhtml`, `frontend`, `webapi_rest`, `webapi_soap`, ...) (default: `global`) |
| `--tree` `-t`  | Print data as tree                                                                                      |
| `--format`     | Output as `json`, `xml` or `csv`                                                                        |

---

### List Magento cache status

```sh
n98-magerun2.phar cache:list
```

### Clean Magento cache

Cleans expired cache entries.

If you would like to clean only one cache type:

```sh
n98-magerun2.phar cache:clean [code]
```

If you would like to clean multiple cache types at once:

```sh
n98-magerun2.phar cache:clean [code] [code] ...
```

If you would like to remove all cache entries use `cache:flush`

Run `cache:list` command to see all codes.

### Remove all cache entries

```sh
n98-magerun2.phar cache:flush [code]
```

Keep in mind that `cache:flush` cleares the cache backend,
so other cache types in the same backend will be cleared as well.

### Remove entry by ID

The command is not checking if the cache id exists. If you want to check if the cache id exists
use the `cache:remove:id` command with the `--strict` option.

```sh
n98-magerun2.phar cache:remove:id [options[--strict] <id>
```

### List Magento caches

```sh
n98-magerun2.phar cache:list [--format[="..."]]
```

### Disable Magento cache

```sh
n98-magerun2.phar cache:disable [code]
```

If no code is specified, all cache types will be disabled. Run
`cache:list` command to see all codes.

### Enable Magento cache

```sh
n98-magerun2.phar cache:enable [code]
```

### Cache Report

This command let you investigate what's stored inside your cache. It prints out a table with cache IDs.

```sh
n98-magerun2.phar cache:report [-t|--tags] [-m|--mtime] [--filter-id[="..."]] [--filter-tag[="..."]] [--fpc]
```

### Cache View

Prints stored cache entry by ID.

```sh
n98-magerun2.phar cache:view [--unserialize] [--decrypt] [--fpc] id
```

If value is serialized you can force a pretty output with `--unserialize` option.
Some entries are encrypted and can be decrypted with `--decrypt` option.
The command uses the core cache by default.
If the FPC cache should be used, the `--fpc` option can be used.

### Flush Catalog Images Cache

Removes pre-generated catalog images and triggers `clean_catalog_images_cache_after` event which
should invalidate the full page cache.

```sh
n98-magerun2.phar cache:catalog:image:flush
```

---

If no code is specified, all cache types will be enabled. Run
`cache:list` command to see all codes.

### List admin users

```sh
n98-magerun2.phar admin:user:list [--format[="..."]]
```

### Change admin user password

```sh
n98-magerun2.phar admin:user:change-password [username] [password]
```

### Delete admin user

```sh
n98-magerun2.phar admin:user:delete [email|username] [-f]
```

ID can be e-mail or username. The command will attempt to find the user
by username first and if it cannot be found it will attempt to find the
user by e-mail. If ID is omitted you will be prompted for it. If the
force parameter `-f` is omitted you will be prompted for confirmation.

### Create Admin Token for Webapi

```sh
n98-magerun2.phar admin:token:create <username>
```

---

### Run a raw DB query

```sh
n98-magerun2.phar db:query <sql-query>
```

**Example:**

```sh
n98-magerun2.phar db:query "select * from store"
```

### Open MySQL Console

```sh
n98-magerun2.phar db:console [options]
```

**Options:**

| Option                         | Description                                                                                          |
|--------------------------------|------------------------------------------------------------------------------------------------------|
| `--use-mycli-instead-of-mysql` | Use `mycli` as the MySQL client instead of `mysql`                                                   |
| `--no-auto-rehash`             | Same as `-A` option to MySQL client to turn off auto-complete (avoids long initial connection time). |
| `--connection=CONNECTION`      | Select DB connection type for Magento configurations with several databases (default: `default`)     |

### Dump database

Dumps configured Magento database with `mysqldump` or `mydumper`.

- Requires MySQL CLI tools (either `mysqldump` or `mydumper`)

Arguments:

- filename - Dump filename

Options:

| Option                     | Description                                                                                |
|----------------------------|--------------------------------------------------------------------------------------------|
| `--add-routines`           | Include stored routines in dump (procedures & functions).                                  |
| `--add-time suffix`        | Adds time to filename (only if filename was provided). Requires value [suffix, prefix, no] |
| `--compression` `-c`       | Compress the dump file using one of the supported algorithms                               |
| `--dry-run`                | Do everything but the actual dump. Useful to test.                                         |
| `--exclude`                | Tables to exclude entirely from the dump (including structure)                             |
| `--force` `-f`             | Do not prompt if all options are defined                                                   |
| `--git-friendly`           | Use one insert statement, but with line breaks instead of separate insert statements.      |
| `--human-readable`         | Use a single insert with column names per row.                                             |
| `--include`                | Tables to include entirely to the dump (default: all tables are included)                  |
| `--keep-definer`           | Do not replace DEFINER in dump with CURRENT_USER                                           |
| `--keep-column-statistics` | Retains `column statistics` table in `mysqldump`                                           |
| `--mydumper`               | Use mydumper instead of mysqldump for potentially faster dumps                             |
| `--no-single-transaction`  | Do not use single-transaction (not recommended, this is blocking)                          |
| `--no-tablespaces`         | Use this option if you want to create a dump without having the PROCESS privilege.         |
| `--only-command`           | Print only mysqldump/mydumper command. Does not execute.                                   |
| `--print-only-filename`    | Execute and prints not output except the dump filename                                     |
| `--set-gtid-purged-off`    | Adds --set-gtid-purged=OFF to mysqlqump                                                    |
| `--stdout`                 | Dump to stdout                                                                             |
| `--strip`                  | Tables to strip (dump only structure of those tables)                                      |

```sh
n98-magerun2.phar db:dump
```

Only the dump command:

```sh
n98-magerun2.phar db:dump --only-command [filename]
```

Or directly to stdout:

```sh
n98-magerun2.phar db:dump --stdout
```

Use compression (gzip cli tool has to be installed):

```sh
n98-magerun2.phar db:dump --compression="gzip"
```

Use mydumper for faster dumps:
```sh
n98-magerun2.phar db:dump --mydumper
```

#### Stripped Database Dump

Dumps your database and excludes some tables. This is useful for
development or staging environments where you may want to provision a
restricted database.

Separate each table to strip by a space. You can use wildcards like `*` and `?` in the table names to strip multiple
tables.
In addition, you can specify pre-defined table groups, that start with an @ sign.

Example: `dataflow_batch_export unimportant_module_* @log`

```sh
n98-magerun2.phar db:dump --strip="@stripped"
```

Available Table Groups:

| Table Group           | Description                                                                                                                          |
|-----------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| `@2fa`                | 2FA tables. These tables are used for storing 2FA information for admin users.                                                       |
| `@admin`              | Admin users, roles, sessions, etc.                                                                                                   |
| `@aggregated`         | Aggregated tables used for generating reports, etc.                                                                                  |
| `@dotmailer`          | Dotmailer data(`email_abandoned_cart` `email_automation` `email_campaign` `email_contact`).                                          |
| `@customers`          | Customer data (and company data from the B2B extension).                                                                             |
| `@development`        | Removes logs, sessions, trade data and admin users so developers do not have to work with real customer data or admin user accounts. |
| `@dotmailer`          | Dotmailer module tables                                                                                                              |
| `@ee_changelog`       | Changelog tables of new indexer since EE 1.13                                                                                        |
| `@idx`                | Tables with `_idx` suffix and index event tables.                                                                                    |
| `@klarna`             | Klarna tables containing information about klarna payments and their quotes/orders.                                                  |
| `@log`                | Log tables.                                                                                                                          |
| `@mailchimp`          | Mailchimp tables.                                                                                                                    |
| `@newrelic_reporting` | New Relic reporting tables. These tables provide production metric data for New Relic.                                               |
| `@oauth`              | OAuth sessions, tokens, etc.                                                                                                         |
| `@quotes`             | Cart (quote) data and B2B quotes.                                                                                                    |
| `@replica`            | Replica tables, these are generated from Magento Staging functionality.                                                              |
| `@sales`              | Sales data (orders, invoices, creditmemos etc).                                                                                      |
| `@search`             | Search related tables (catalogsearch\_).                                                                                             |
| `@sessions`           | Database session tables.                                                                                                             |
| `@stripped`           | Standard definition for a stripped dump (logs and sessions).                                                                         |
| `@trade`              | Current trade data (customers, orders and quotes). You usually do not want those in developer systems.                               |
| `@temp`               | Indexer __temp tables.                                                                                                               |

---

### Import database

- Requires MySQL CLI tools

Arguments:

- filename - Dump filename

Options:

| Option                                | Description                                                                        |
|---------------------------------------|------------------------------------------------------------------------------------|
| `--connection=CONNECTION`             | Select DB connection type for Magento configurations with several databases        |
| `-c`, `--compression=COMPRESSION`     | The compression of the specified file                                              |
| `--drop`                              | Drop and recreate database before import                                           |
| `--drop-tables`                       | Drop tables before import                                                          |
| `--force`                             | Continue even if an SQL error occurs                                               |
| `--only-command`                      | Print only mysql command. Do not execute                                           |
| `--only-if-empty`                     | Imports only if database is empty                                                  |
| `--optimize`                          | Convert verbose INSERTs to short ones before import (not working with compression) |
| `--skip-authorization-entry-creation` | Add default entry to authorization_role and authorization_rule tables.             |

```sh
n98-magerun2.phar db:import
```

### Fix empty authorization tables

If you run `db:dump` with stripped option and `@admin` group, the authorization_rule and authorization_role tables are
empty.
This blocks the creation of admin users.

You can re-create the default entries by running the command:

```sh
n98-magerun2.phar db:add-default-authorization-entries
```

If you are using the `db:import` command to import the stripped SQL dump, then this command will be implicitly called.

### Clear static view files

```sh
n98-magerun2.phar dev:asset:clear [--theme="..."]
```

Options:

| Option    | Description                    |
|-----------|--------------------------------|
| `--theme` | The specific theme(s) to clear |

To clear assets for all themes:

```sh
n98-magerun2.phar dev:asset:clear
```

To clear assets for specific theme(s) only:

```sh
n98-magerun2.phar dev:asset:clear --theme=Magento/luma
```

### List Themes

```sh
n98-magerun2.phar dev:theme:list
```

### Build Hyva Theme CSS

```sh
n98-magerun2.phar dev:theme:build-hyva [--production] <theme-name>
```

Example: `n98-magerun2.phar dev:theme:build-hyva "Hyva/default"`

The command starts in watch mode by default, as it is primarily designed for developers.

If no theme is specified, an interactive mode allows you to select a theme from a list.

If the `--production` flag is set, the command does not run in watch mode and will stop after the theme is built.

---

### Create Module Skeleton

Creates an empty module and registers it in current Magento shop.

```sh
n98-magerun2.phar dev:module:create [-m|--minimal] [--add-blocks] [--add-helpers] [--add-models] [--add-setup] [--add-all] [-e|--enable] [--modman] [--add-readme] [--add-composer] [--add-strict-types] [--author-name [AUTHOR-NAME]] [--author-email [AUTHOR-EMAIL]] [--description [DESCRIPTION]] [-h|--help] [-q|--quiet] [-v|vv|vvv|--verbose] [-V|--version] [--ansi] [--no-ansi] [-n|--no-interaction] [--root-dir [ROOT-DIR]] [--skip-config] [--skip-root-check] [--skip-core-commands [SKIP-CORE-COMMANDS]] [--skip-magento-compatibility-check] [--] <command> <vendorNamespace> <moduleName>
```

---

### Detect Composer Dependencies in Module

The source code of one or more modules can be scanned for dependencies.

```sh
n98-magerun2.phar dev:module:detect-composer-dependencies [--only-missing] <directory>
```

The `--only-missing` option will filter the output so that only the missing dependencies are listed.

---

### Translations

Enable/disable inline translation feature for Magento Admin:

```sh
n98-magerun2.phar dev:translate:admin [--on] [--off]
```

Enable/disable inline translation feature for shop frontend:

```sh
n98-magerun2.phar dev:translate:shop [--on] [--off] <store>
```

Set a translation (saved in translation table)

```sh
n98-magerun2.phar dev:translate:set <string> <translate> [<store>]
```

Export inline translations

```sh
n98-magerun2.phar dev:translate:export [--store=<storecode>] <locale> <filename>
```

---

### DI (Dependency Injection)

List Preferences:

```sh
n98-magerun2.phar dev:di:preferences:list [--format [FORMAT]] [<area>]
```

`area` is one of [global, adminhtml, frontend, crontab, webapi_rest, webapi_soap, graphql, doc, admin] 

Format can be `csv`, `json`, `xml` or `yaml`.

---

### List modules

```sh
n98-magerun2.phar dev:module:list [--vendor [VENDOR]] [-e|--only-enabled] [-d|--only-disabled] [--format [FORMAT]]
```

Lists all installed modules. If `--vendor` option is set, only modules of the given vendor are listed.
If `--only-enabled` option is set, only enabled modules are listed.
If `--only-disabled` option is set, only disabled modules are listed.
Format can be `csv`, `json`, `xml` or `yaml`.

### Encryption

Encrypt the given string using Magentos crypt key

```sh
n98-magerun2.phar dev:encrypt <string>
```

Decrypt the given string using Magentos crypt key

```sh
n98-magerun2.phar dev:decrypt <encrypted string>
```

---

### List Observers

```sh
n98-magerun2.phar dev:module:observer:list [--sort] <event> [<area>
```

### List Routes

```sh
n98-magerun2.phar route:list [-a|--area] [-m|--module] [-f|--format]
```

---

### EAV Attributes

View the data for a particular attribute:

```sh
n98-magerun2.phar eav:attribute:view [--format[="..."]] entityType attributeCode
```

---

### Generate Gift Card Pool

Generates a new gift card pool.

```sh
n98-magerun2.phar giftcard:pool:generate
```

### Create a Gift Card

```sh
n98-magerun2.phar giftcard:create [--website[="..."]] [--expires[="..."]] [amount]
```

You may specify a website ID or use the default. You may also optionally
add an expiration date to the gift card using the
`--expires` option. Dates should be in `YYYY-MM-DD` format.

### View Gift Card Information

```sh
n98-magerun2.phar giftcard:info [--format[="..."]] [code]
```

### Remove a Gift Card

```sh
n98-magerun2.phar giftcard:remove [code]
```

---

### Compare Setup Versions

Compares module version with saved setup version in
`setup_module` table and displays version mismatchs if
found.

```sh
n98-magerun2.phar sys:setup:compare-versions [--ignore-data] [--log-junit="..."] [--format[="..."]]
```

- If a filename with `--log-junit` option is set the tool generates an XML file and no output to *stdout*.

### Change Setup Version

Changes the version of a module. This command is useful if you want to
re-run an upgrade script again possibly for debugging. Alternatively you
would have to alter the row in the database manually.

```sh
n98-magerun2.phar sys:setup:change-version module version
```

---

### Downgrade Setup Versions

Downgrade the versions in the database to the module version from its
xml file if necessary. Useful while developing and switching branches
between module version changes.

```sh
n98-magerun2.phar sys:setup:downgrade-versions
```

### List all configured store URLs

The default behavior is to show the base URL of all stores except the admin store.
If you want to show the base URL of the admin store as well, use the `--with-admin-store` option.
If you want to show the admin login URL as well, use the `--with-admin-login-url` option.
The options `--with-admin-store` and `--with-admin-login-url` cannot be combined, because both print a url for the same store.

```sh
n98-magerun2.phar sys:store:config:base-url:list [--with-admin-store] [--with-admin-login-url] [--format[="..."]]
```

---

### Dump Media folder

Creates a ZIP archive with media folder content.

```sh
n98-magerun2.phar media:dump [--strip] [filename]
```

---

### Integrations (Webapi Access Tokens)

There are four commands to create, show, list, delete integrations (access tokens).
This commands are very useful for developers.

#### List all existing integrations

```sh
n98-magerun2.phar integration:list
```

#### Create a new integration

```sh
n98-magerun2.phar integration:create [options] [--] <name> [<email> [<endpoint>]]
```

Options:

| Option                                      | Description                                              |
|---------------------------------------------|----------------------------------------------------------|
| `--consumer-key=CONSUMER-KEY`               | Consumer Key (length 32 chars)                           |
| `--consumer-secret=CONSUMER-SECRET`         | Consumer Secret (length 32 chars)                        |
| `--access-token=ACCESS-TOKEN`               | Access-Token (length 32 chars)                           |
| `--access-token-secret=ACCESS-TOKEN-SECRET` | Access-Token Secret (length 32 chars)                    |
| `--resource=RESOURCE` `-r`                  | Defines a granted ACL resource (multiple values allowed) |

If no ACL resource is defined the new integration token will be created with FULL ACCESS.

If you do not want that, please provide a list of ACL resources by using the `--resource` option.

Example:

```sh
n98-magerun2.phar integration:create "My new integration 10" foo@example.com https://example.com -r Magento_Catalog::catalog_inventory -r Magento_Backend::system_other_settings
```

To see all available ACL resources, please run the command `config:data:acl`.

#### Show infos about existing integration

```sh
n98-magerun2.phar integration:show --format[=FORMAT] <name_or_id> [key]
```

Example (print only Access Key):

```sh
n98-magerun2.phar integration:show 1 "Access Key"
```

#### Delete integration

```sh
n98-magerun2.phar integration:delete <name_or_id>
```

---

### Github

(experimental) Commands

### Pull Requests

Gets infos about Github Pull Requests.
If no Github Repository is defined by `---repository` (-r) option the default
Magento 2 Github Repository `magento/magento2` is used.
For the [Mage-OS](https://github.com/mage-os/mageos-magento2) repository we provide a shortcut option `--mage-os`.

If the command is executed without any options it will show infos about the PR.

```sh
# Magento 2 Open Source
n98-magerun2.phar github:pr:patch <pr-number>

# Mage-OS
n98-magerun2.phar github:pr:patch --mage-os <pr-number>
```

*Create a patch file from PR:*

```sh
n98-magerun2.phar github:pr:patch --patch <pr-number>
```

*Directly apply the patch:*

```sh
# Magento 2 Open Source
n98-magerun2.phar github:pr:patch --patch --apply <pr-number>

# for Mage-OS
n98-magerun2.phar github:pr:patch --mage-os --patch --apply <pr-number>
```

Files of the magento2-base and magento2-ee-base and b2b base packages are currently not handled by the command.

**List only the raw diff:**

```sh
n98-magerun2.phar github:pr:patch --diff <pr-number>
```

---

### Interactive Development Console

Opens PHP interactive shell with initialized Magento Admin-Store.

```sh
n98-magerun2.phar dev:console [--area=AREA] <arg>
```

Optional an area code can be defined. If provided, the configuration
(di.xml, translations) of the area are loaded.

Possible area codes are:

- `adminhtml`
- `crontab`
- `frontend`
- `graphql`
- `webapi_xml`
- `webapi_rest`

Variable `$di` is made available with a
`Magento\Framework\ObjectManagerInterface` instance to allow creation of
object instances.

Variable `$dh` provides convenient debugging functions.
Type `$dh->` and press Tab for a list.

Example:

```bash
n98-magerun2 dev:console --area=adminhtml
    // show name of category 123 in default store
    $dh->debugCategoryById(123)['name']; 
    // show name of product id 123
    $dh->debugProductById(123)['name']; 
```

The interactive console works as
[REPL](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop).
It's possible to enter any PHP code. The code will be executed immediately.
The interactive console also comes with a lot of embedded scommands.

It's possible to add initial commands to the interactive console.
Commands should be delimited by a semicolon. You can mix PHP-Code with
embedded interactive console commands.

**Example:**

```sh
n98-magerun2.phar dev:console "$a = 1; call cache:flush; ls;"
```

The interactive console comes with a extendable code generator tool to
create i.e. modules, cli commands, controllers, blocks, helpers etc.

The console can be in a module context which allows you to generate code
for a selected module.

The basic idea of the stateful console was developed by [Jacques
Bodin-Hullin](https://github.com/jacquesbh) in this great tool
[Installer](https://github.com/jacquesbh/installer).

### n98-magerun Script

Run multiple commands from a script file.

```sh
n98-magerun2.phar script [-d|--define[="..."]] [--stop-on-error] [filename]
```

**Example:**

```sh
# Set multiple config
config:store:set "web/cookie/cookie_domain" example.com

# Set with multiline values with `\n`
config:store:set "general/store_information/address" "First line\nSecond line\nThird line"

# This is a comment
cache:flush
```

Optionally you can work with unix pipes.

```sh
echo "cache:flush" | n98-magerun2.phar script
```

```sh
n98-magerun2.phar script < filename
```

It is even possible to create executable scripts:

Create file `test.magerun` and make it executable `chmod +x test.magerun`:

```sh
#!/usr/bin/env n98-magerun2.phar script

config:store:set "web/cookie/cookie_domain" example.com
cache:flush

# Run a shell script with "!" as first char
! ls -l

# Register your own variable (only key = value currently supported)
${my.var}=bar

# Let magerun ask for variable value - add a question mark
${my.var}=?

! echo ${my.var}

# Use resolved variables from n98-magerun in shell commands
! ls -l ${magento.root}/code/local
```

**Pre-defined variables:**

| Variable             | Description                                |
|----------------------|--------------------------------------------|
| `${magento.root}`    | Magento Root-Folder                        |
| `${magento.version}` | Magento Version i.e. 2.0.0.0               |
| `${magento.edition}` | Magento Edition -> Community or Enterprise |
| `${magerun.version}` | Magerun version i.e. 2.1.0                 |
| `${php.version}`     | PHP Version                                |
| `${script.file}`     | Current script file path                   |
| `${script.dir}`      | Current script file dir                    |

Variables can be passed to a script with "--define (-d)" option.

Example:

```sh
n98-magerun2.phar script -d foo=bar filename

# This will register the variable ${foo} with value bar.
```

It's possible to define multiple values by passing more than one
option.

Environment variables can be used in a script by using the `env.` prefix.

Example:

```bash
!echo "My current working directory is: ${env.PWD}"
!echo "Path: ${env.PATH}"
```

### Toggle CMS Block status

Toggles the status for a CMS block based on the given Block identifier.

```sh
n98-magerun2.phar cms:block:toggle [blockId]
```

### Change Admin user status

Changes the admin user based on the options, the command will toggle
the status if no options are supplied.

```sh
n98-magerun2.phar admin:user:change-status [user] [--activate] [--deactivate]
```

*Note: It is possible for a user to exist with a username that matches
the email of a different user. In this case the first matched user will be changed.*

### Add Sales Sequences for a given store

Create sales sequences in the database if they are missing, this will recreate profiles to.

```sh
n98-magerun2.phar sales:sequence:add [store] 
```

If store is omitted, it'll run for all stores.

*Note: It is possible a sequence already exists, in this case nothing will happen, only missing tables are created.*

### Remove Sales Sequences for a given store

Remove sales sequences from the database, warning, you cannot undo this, make sure you have database backups.

```sh
n98-magerun2.phar sales:sequence:remove [store] 
```

If store is omitted, it'll run for all stores. When the option `no-interaction` is given, it will run immediately
without any interaction.
Otherwise it will remind you and ask if you know what you're doing and ask for each store you are running it on.

*Note: .*

### Script Repository

You can organize your scripts in a repository.
Simply place a script in folder `/usr/local/share/n98-magerun2/scripts` or in your home dir
in folder `<HOME>/.n98-magerun2/scripts`.

Scripts must have the file extension *.magerun*.

After that you can list all scripts with the *script:repo:list* command.
The first line of the script can contain a comment (line prefixed with #) which will be displayed as description.

```sh
n98-magerun2.phar script:repo:list [--format[="..."]]
```

If you want to execute a script from the repository this can be done by *script:repo:run* command.

```sh
n98-magerun2.phar script:repo:run [-d|--define[="..."]] [--stop-on-error] [script]
```

Script argument is optional. If you don't specify any you can select one from a list.

### Composer Redeploy Base Packages

If files are missing after a Magento updates it could be that new files were added to the files map in the base packages
of Magento. The `composer:redeploy-base-packages` command can fix this issue.

```sh
n98-magerun2.phar composer:redeploy-base-packages
```

## Development

We have more information on the wiki page:

<https://github.com/netz98/n98-magerun2/wiki>

### Included Commands for Plugin Developers

We offer two command to debug the configuration.

The `magerun:config:info` can display all loaded config files.

```sh
$> n98-magerun2.phar magerun:config:info

+------+----------------------------------+-------------------------------------------------+
| type | path                             | note                                            |
+------+----------------------------------+-------------------------------------------------+
| dist |                                  | Shipped in phar file                            |
| user | /home/cmuench/.n98-magerun2.yaml | Configuration in home directory of current user |
+------+----------------------------------+-------------------------------------------------+
```
The `magerun:config:dump` command can dump the merged configuration as highlighted yaml.

```sh
$> n98-magerun2.phar magerun:config:dump
```
