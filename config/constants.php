<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin version in SemVer format.
if ( ! defined( 'MG_LICENSE_MANAGER_VERSION' ) ) {
	define( 'MG_LICENSE_MANAGER_VERSION', '1.0.0' );
}

// Define plugin root File.
if ( ! defined( 'MG_LICENSE_MANAGER_PLUGIN_FILE' ) ) {
	define( 'MG_LICENSE_MANAGER_PLUGIN_FILE', dirname( dirname( __FILE__ ) ) . '/mg-license-manager.php' );
}

// Define plugin basename.
if ( ! defined( 'MG_LICENSE_MANAGER_PLUGIN_BASENAME' ) ) {
	define( 'MG_LICENSE_MANAGER_PLUGIN_BASENAME', plugin_basename( MG_LICENSE_MANAGER_PLUGIN_FILE ) );
}

// Define plugin directory Path.
if ( ! defined( 'MG_LICENSE_MANAGER_PLUGIN_DIR' ) ) {
	define( 'MG_LICENSE_MANAGER_PLUGIN_DIR', plugin_dir_path( MG_LICENSE_MANAGER_PLUGIN_FILE ) );
}

// Define plugin directory URL.
if ( ! defined( 'MG_LICENSE_MANAGER_PLUGIN_URL' ) ) {
	define( 'MG_LICENSE_MANAGER_PLUGIN_URL', plugin_dir_url( MG_LICENSE_MANAGER_PLUGIN_FILE ) );
}
