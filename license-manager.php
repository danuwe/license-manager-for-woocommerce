<?php
/**
 * Plugin Name: License Manager
 * Description: Sell software licenses through WooCommerce products.
 * Version: 1.0.0
 * Author: Dražen Bebić
 * Author URI: https://www.bebic.at/
 * Text Domain: lima
 * Domain Path: /lang
 * Requires at least: 4.7
 * Tested up to: 4.9
 */

namespace LicenseManager;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

// Define LM_PLUGIN_FILE.
if (!defined('LM_PLUGIN_FILE')) {
    define('LM_PLUGIN_FILE', __FILE__);
}


/**
 * Main instance of LicenseManager.
 *
 * Returns the main instance of SN to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return LicenseManager
 */
function licensemanager() {
    return \LicenseManager\Classes\Main::instance();
}

// Global for backwards compatibility.
$GLOBALS['licensemanager'] = licensemanager();