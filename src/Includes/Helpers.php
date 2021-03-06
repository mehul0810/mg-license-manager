<?php
namespace MG\LicenseManager\Includes;

use function get_plugins;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Helpers
 *
 * @package MG\LicenseManager\Includes
 *
 * @since 1.0.0
 */
trait Helpers {

	/**
	 * Get All Supported Plugins List.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function getAllSupportedPlugins() {
		$plugins             = get_plugins();
		$mg_plugins          = [];
		$active_plugin_paths = (array) get_option( 'active_plugins', [] );

		if ( is_multisite() ) {
			$network_activated_plugin_paths = array_keys( get_site_option( 'active_sitewide_plugins', [] ) );
			$active_plugin_paths            = array_merge( $active_plugin_paths, $network_activated_plugin_paths );
		}

		foreach ( $plugins as $plugin_path => $plugin_data ) {
			// Is plugin active?
			if ( in_array( $plugin_path, $active_plugin_paths ) ) {
				$plugins[ $plugin_path ]['Status'] = 'active';
			} else {
				$plugins[ $plugin_path ]['Status'] = 'inactive';
			}

			$dirname                         = strtolower( dirname( $plugin_path ) );
			$plugins[ $plugin_path ]['Dir']  = $dirname;
			$plugins[ $plugin_path ]['Path'] = $plugin_path;

			$author = false !== strpos( $plugin_data['Author'], ',' )
				? array_map( 'trim', explode( ',', $plugin_data['Author'] ) )
				: array( $plugin_data['Author'] );

			if (
				false !== strpos( $dirname, 'mg-' )
				&& (
					false !== strpos( $plugin_data['PluginURI'], 'mehulgohil.com' ) ||
					array_intersect( $author, array( 'Mehul Gohil' ) )
				)
			) {

				$mg_plugins[ $plugin_path ] = $plugin_data;
				$mg_plugins[ $plugin_path ]['ItemSlug'] = substr( $mg_plugins[ $plugin_path ]['TextDomain'], 3 );
				$mg_plugins[ $plugin_path ]['ItemName'] = substr( $mg_plugins[ $plugin_path ]['Title'], 5 );

				$mg_plugins[ $plugin_path ]['Dir']  = $dirname;
				$mg_plugins[ $plugin_path ]['Path'] = $plugin_path;

				// Does a valid license exist?
				$mg_plugins[ $plugin_path ]['License'] = 'invalid';

			}
		}

		return $mg_plugins;
	}

	/**
	 * Get License Key Slug.
	 *
	 * @param string $slug Unique Plugin Slug.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function getLicenseKeySlug( $slug ) {
		return "mg_license_manager_license_key_{$slug}";
	}

	/**
	 * Get License Information.
	 *
	 * @param string $slug Plugin Slug.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function getLicenseInformation( $slug ) {
		return get_option( "mg_license_manager_license_information_{$slug}", [] );
	}
}
