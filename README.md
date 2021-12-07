# thinkshout/drupal-integrations

Add this project to any Drupal distribution based on drupal/core-composer-scaffold to enable it for use with ThinkShout.

This project enables the following useful things:

- TODO.

## Enabling this project

This project must be enabled in the top-level composer.json file, or it will be ignored and will not perform any of its functions.
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
        "thinkshout/drupal-integrations": "^9"
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
