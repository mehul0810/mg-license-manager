<?php
namespace MG\LicenseManager;

use MG\LicenseManager\Admin\LicenseManager;
use MG\LicenseManager\Admin;
use MG\LicenseManager\Admin\Menu;
use MG\LicenseManager\Includes\Helpers;
use MG\LicenseManager\Includes\PluginUpdater;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads and registers plugin functionality through WordPress hooks.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Registers functionality with WordPress hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register() {
		// Handle plugin activation and deactivation.
		register_activation_hook( MG_LICENSE_MANAGER_PLUGIN_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( MG_LICENSE_MANAGER_PLUGIN_FILE, [ $this, 'deactivate' ] );

		// Register services used throughout the plugin.
		add_action( 'plugins_loaded', [ $this, 'register_services' ] );

		// Load text domain.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Registers the individual services of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_services() {
		// Load Admin Menu.
		new Menu();

		// Load License Manager.
		new LicenseManager();

		add_action( 'after_setup_theme', [ $this, 'checkForUpdates' ] );
		add_filter( 'plugin_action_links_' . MG_LICENSE_MANAGER_PLUGIN_BASENAME, [ $this, 'pluginActionLinks' ] );
	}

	/**
	 * This function is used to add settings page link on plugins page.
	 *
	 * @param array $links List of links on plugin page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function pluginActionLinks( $links ) {

		$links['manage_licenses'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url_raw( admin_url( 'options-general.php?page=mg_license_manager' ) ),
			esc_html__( 'Manage Licenses', 'mg-mastercard-for-give' )
		);

		$links['donate'] = sprintf(
			'<a target="_blank" href="%1$s">%2$s</a>',
			esc_url_raw( 'https://mehulgohil.com/donations/buy-me-a-cup-of-coffee/' ),
			esc_html__( 'Donate', 'mg-mastercard-for-give' )
		);

		asort( $links );

		return $links;
	}

	public function checkForUpdates() {
		$plugins = Helpers::getAllSupportedPlugins();

		if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
			foreach ( $plugins as $slug => $data ) {
				$licenseKeySlug  = Helpers::getLicenseKeySlug( $data['ItemSlug'] );
				$licenseKey      = get_option( $licenseKeySlug );
				$pluginPath      = $data['Path'];

				// Call PluginUpdater Class to ensure that automatic updates are working.
				new PluginUpdater( 'https://mehulgohil.com', $pluginPath, [
					'version'   => $data['Version'],
					'license'   => $licenseKey,
					'item_name' => $data['ItemName'],
					'author'    => 'Mehul Gohil',
					'url'       => home_url(),
					'beta'      => false
				] );
			}
		}
	}

	/**
	 * Loads the plugin's translated strings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'mg-license-manager',
			false,
			dirname( plugin_basename( MG_LICENSE_MANAGER_PLUGIN_FILE ) ) . '/languages/'
		);
	}

	/**
	 * Handles activation procedures during installation and updates.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param bool $network_wide Optional. Whether the plugin is being enabled on
	 *                           all network sites or a single site. Default false.
	 *
	 * @return void
	 */
	public function activate( $network_wide = false ) {}

	/**
	 * Handles deactivation procedures.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function deactivate() {}
}
