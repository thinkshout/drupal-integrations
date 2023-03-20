# thinkshout/drupal-integrations

Add this project to any Drupal distribution based on [drupal/core-composer-scaffold](https://github.com/drupal/core-composer-scaffold) to enable it for use with ThinkShout.

This project enables the following useful things:

- ThinkShout styleguide on dev & test sites at /styleguide (see assets/web/modules/thinkshout/ts_styleguide)
- Behat testing infrastructure (see assets/behat/README.md)
- visual regression testing infrastructure (see assets/.ci/test/visual-regression/README.md)
- github issue templates (see assets/.github/)
- config_split (see assets/config-local) for local development to enable:
  - config_suite (automatic configuration export)
  - stage_file_proxy (automatic image/file downloads from live site instance)
- site settings customizations (see assets/web/sites/default/):
  - settings.php customizations:
    - pantheon settings file compatibility (includes)
    - includes settings.ts.php and settings.local.php if present
  - settings.ts.php customizations:
    - Pull in PRESSFLOW_SETTINGS from `robo configure` using phpdotenv for Drupal "hash_salt"
    - Enabling of the "local" config_split settings if not on Pantheon
    - Enforces use of https on Pantheon instances of the site
    - includes a "settings.dev.php" file if present
  - services.dev.yml
    - enables debugging helpers for twig:
      - Automatically recompiles changes so you don't have to clear cache
      - Includes the template hints in html comments
      - Enables caching, the first step in [setting up debugging for twig files](https://library.thinkshoutlabs.com/articles/twig-debugging-and-cache).

## Enabling this project

This project must be enabled in the top-level composer.json file, or it will be ignored and will not perform any of its functions.

Add the "repositories" and "extra" entries below to composer.json then run `composer require thinkshout/drupal-integrations ^1.0@alpha`. (You'll end up with the "require" entry automatically.)
```
{
    "repositories": [
        {
            "type": "vcs",
            "name": "thinkshout/drupal-integrations",
            "url": "git@github.com:thinkshout/drupal-integrations.git"
        }
    ]
    ...
    "require": {
        "thinkshout/drupal-integrations": "^1.0@alpha"
    },
    ...
    "extra": {
        "drupal-scaffold": {
            "allowed-packages": [
                "thinkshout/drupal-integrations"
            ]
        }
    }
}
```
