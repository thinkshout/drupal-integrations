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

$settings['install_profile'] = 'minimal';

// Require HTTPS.
// Check if Drupal is running via command line
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['HTTPS'] === 'OFF') &&
  (php_sapi_name() != "cli")) {
  if (!isset($_SERVER['HTTP_USER_AGENT_HTTPS']) ||
    (isset($_SERVER['HTTP_USER_AGENT_HTTPS']) && $_SERVER['HTTP_USER_AGENT_HTTPS'] != 'ON')) {
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
