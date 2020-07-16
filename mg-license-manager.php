<?php
/**
 * MG - License Manager WordPress Plugin
 *
 * @package           MG - License Manager
 * @author            Mehul Gohil
 * @copyright         2020 Mehul Gohil <hello@mehulgohil.com>
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 *
 * Plugin Name:       MG - License Manager
 * Plugin URI:        https://mehulgohil.com/plugins/mg-license-manager/
 * Description:       This WordPress plugin will help you manage licenses and automatic updates for the premium plugins developed by Mehul Gohil.
 * Version:           1.0.1
 * Requires at least: 4.8
 * Requires PHP:      5.6
 * Author:            Mehul Gohil
 * Author URI:        https://mehulgohil.com
 * Text Domain:       mg-license-manager
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace MG\LicenseManager;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/config/constants.php';

// Automatically loads files used throughout the plugin.
require_once MG_LICENSE_MANAGER_PLUGIN_DIR . 'vendor/autoload.php';

// Initialize the plugin.
$plugin = new Plugin();
$plugin->register();
