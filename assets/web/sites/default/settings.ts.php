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
if (!(getenv('DRUPAL_INSTALL') || $is_installer_url) && defined('PANTHEON_ENVIRONMENT') && !empty($conf['redis_client_host'])) {
  // Include the Redis services.yml file. Adjust the path if you installed to a contrib or other subdirectory.
  $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

  // PhpRedis is built into the Pantheon application container.
  $settings['redis.connection']['interface'] = 'PhpRedis';
  // These are dynamic variables handled by Pantheon.
  $settings['redis.connection']['host']      = $_ENV['CACHE_HOST'];
  $settings['redis.connection']['port']      = $_ENV['CACHE_PORT'];
  $settings['redis.connection']['password']  = $_ENV['CACHE_PASSWORD'];

  $settings['cache']['default'] = 'cache.backend.redis'; // Use Redis as the default cache.
  $settings['cache_prefix']['default'] = 'pantheon-redis';

  $settings['redis_compress_length'] = 100;
  $settings['redis_compress_level'] = 1;

  // Set Redis to not get the cache_form (no performance difference).
  $settings['cache']['bins']['form']      = 'cache.backend.database';
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
