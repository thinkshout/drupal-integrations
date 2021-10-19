<?php

// If we have a default hash salt value (from PRESSFLOW_SETTINGS) use it.
if (!empty($drupal_hash_salt)) {
  $settings['hash_salt'] = $drupal_hash_salt;
}

$settings['config_sync_directory'] = '../config';

if (defined('PANTHEON_ENVIRONMENT')) {
  $config['config_split.config_split.local']['status'] = FALSE;
}
else {
  $config['config_split.config_split.local']['status'] = TRUE;
}

// If we are not installing and have a redis host defined, setup redis.
if (!(getenv('DRUPAL_INSTALL') || $is_installer_url) && !empty($conf['redis_client_host'])) {

  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.redis.yml';

  $settings['redis.connection']['interface'] = 'PhpRedis'; // Can be "Predis".
  $settings['redis.connection']['host'] = $conf['redis_client_host'];
  $settings['redis.connection']['port'] = $conf['redis_client_port'];
  $settings['redis.connection']['password'] = $conf['redis_client_password'];
  $settings['cache']['default'] = 'cache.backend.redis';
  // Set a cache_prefix per https://github.com/md-systems/redis/issues/8
  $env_name = (defined('PANTHEON_ENVIRONMENT')) ? PANTHEON_ENVIRONMENT : getenv('TERMINUS_ENV');
  $settings['cache_prefix'] = 'SITE_' . $env_name;

  // Always set the fast backend for bootstrap, discover and config, otherwise
  // this gets lost when redis is enabled.
  $settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
  $settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
  $settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
}

$settings['install_profile'] = 'minimal';

// Require HTTPS.
// Check if Drupal is running via command line
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['HTTPS'] === 'OFF') &&
  (php_sapi_name() != "cli")) {
  if (!isset($_SERVER['HTTP_X_SSL']) ||
    (isset($_SERVER['HTTP_X_SSL']) && $_SERVER['HTTP_X_SSL'] != 'ON')) {
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}

/**
 * If there is a dev settings file, include it - but only when not on pantheon.
 */
if (!defined('PANTHEON_ENVIRONMENT')) {
  $dev_settings = __DIR__ . "/settings.dev.php";
  if (file_exists($dev_settings)) {
    include $dev_settings;
  }
}
