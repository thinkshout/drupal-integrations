Follow the instructions at http://dev-ts-docs.pantheonsite.io/articles/drupal-setup-documentation to create a new
project using this repo.

# :::::::::::::::::::::: Customize code below ::::::::::::::::::::::

## Development set-up

This is a Drupal 8 site built using the [robo taskrunner](http://robo.li/). As such, it does not require separate `~/Project` and `~/Sites` folders. Install the repo directly into `~/Sites` using `git clone` and you will be ready to begin.  

This site uses PHP 7.4: make sure your local environment is running PHP 7.4 (you can run `php --version` to verify).

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

`brew install composer`

Next add `./vendor/bin` to your PATH, at the beginning of your PATH variable, if it is not already there.

Check with:
`echo $PATH`

Update with:
`export PATH=./vendor/bin:$PATH`

You can also make this change permanent by editing your `~/.zshrc` file:
`export PATH="./vendor/bin:...`

### Initial build (existing repo) -- do this once

From within your `~/Sites` directory run:

```
git clone git@github.com:thinkshout/new-project-name.git
cd new-project-name
composer install
```

### Building

Running the `robo configure` command will read the .env.dist, cli arguments and
your local environment (`DEFAULT_PRESSFLOW_SETTINGS`) to generate a .env file. This file will be used to set
the database and other standard configuration options. If no database name is provided, the project name and the git branch name will be used. If no database name is provided, the project name and the git branch name will be used. Note the argument to pass to robo configure can include: --db-pass; --db-user; --db-name; --db-host.

```
robo configure
# Use an alternate DB password
robo configure --db-pass=<YOUR LOCAL DATABASE PASSWORD>
# Use an alternate DB name
robo configure --db-name=<YOUR DATABASE NAME>
# Use an alternate release/production/live branch name
robo configure --prod-branch=<YOUR BRANCH NAME>
```

The structure of `DEFAULT_PRESSFLOW_SETTINGS` if you want to set it locally is:

```
DEFAULT_PRESSFLOW_SETTINGS_={"databases":{"default":{"default":{"driver":"mysql","prefix":"","database":"","username":"root","password":"root","host":"localhost","port":3306}}},"conf":{"pressflow_smart_start":true,"pantheon_binding":null,"pantheon_site_uuid":null,"pantheon_environment":"local","pantheon_tier":"local","pantheon_index_host":"localhost","pantheon_index_port":8983,"redis_client_host":"localhost","redis_client_port":6379,"redis_client_password":"","file_public_path":"sites\/default\/files","file_private_path":"sites\/default\/files\/private","file_directory_path":"site\/default\/files","file_temporary_path":"\/tmp","file_directory_temp":"\/tmp","css_gzip_compression":false,"js_gzip_compression":false,"page_compression":false},"hash_salt":"","config_directory_name":"sites\/default\/config","drupal_hash_salt":""}
```

### Configure Drush

Drush options can be configured in the `.env` file. For example, to set a default uri for commands like `drush uli`, add this:

```
DRUSH_OPTIONS_URI="https://web.new-project-name.localhost"
```

[Drush configuration docs](https://github.com/drush-ops/drush/blob/master/docs/using-drush-configuration.md)

### Installing -- do this regularly (after changing branches especially)

Running the robo install command will run composer install to add all required
dependencies and then install the site and import the exported configuration.

```
robo install
```

### Committing code -- do this regularly

Each time you start a new task, you should create a branch from `production` with the github ticket number and a short
description of the ticket's goal in the branch:

```
git checkout production
git pull
git checkout -b issue-123-short-description
```

As a best practice, you should now re-install

```
robo install
```

Now work on your changes. Make sure configuration changes are saved in the codebase. If you make a config change
and it doesn't seem to affect your codebase (i.e. it doesn't make any changes to the `config` folder), run a `drush cex`.

Once you're happy with your changes, create a pull request from your branch to the `develop` branch in github
and tag the tech lead or PM in the PR. Also move your ticket into the "Tech Review" column on [Zenhub](https://github.com/thinkshout/hsus#workspaces/).
Someone will then review the PR for potential improvements or fixes, and merge, and move the ticket into the "Ready to deploy"
column in [Zenhub](https://github.com/thinkshout/hsus#workspaces/). This ticket is now ready to go out with
the next release.

### Testing -- do as needed

Test are run automatically on CircleCI, but can be run locally as well with:

```
robo test
```

We're also running the Drupal Code Sniffer on our custom module and theme directories. If you submit a PR with some
coding standard errors, your build will fail on circle. You can get your local site machine to run this same code
sniffer by running these commands:


```
composer code-sniff
```

If you have errors, you can run this command to fix most of those errors automagically:

```
composer code-sniff-fix
```

This site also runs Visual Regression tests both during the PR phase on backstopjs and nightly on the develop multidev.
More info about the backstopjs, including how to run locally, can be found in the `.ci/test/visual-regression` README.

If you need to apply patches (depending on the project being modified, a pull
request is often a better solution), you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module "foobar" insert the patches section in the `extra`
section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL to patch"
        }
    }
}
```

### Updating Drupal Core

This project will attempt to keep all of your Drupal Core files up-to-date; the
project [drupal-composer/drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold)
is used to ensure that your scaffold files are updated every time drupal/core is
updated. If you customize any of the "scaffolding" files (commonly .htaccess),
you may need to merge conflicts if any of your modified files are updated in a
new release of Drupal core.

Follow the steps below to update your core files.

1. Run `composer update drupal/core "drupal/core-*" --with-all-dependencies` to update Drupal Core and its dependencies.
