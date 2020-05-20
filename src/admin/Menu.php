<?php
namespace MG\LicenseManager\Admin;

use MG\LicenseManager\Includes\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Menu
 *
 * @package MG\LicenseManager\Admin
 *
 * @since 1.0.0
 */
class Menu {

	/**
	 * Menu constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'registerMenu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'registerScripts' ] );
	}

	/**
	 * Register Admin Scripts.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function registerScripts() {
		wp_enqueue_script( 'admin', MG_LICENSE_MANAGER_PLUGIN_URL . 'assets/dist/js/admin.js' );
		wp_enqueue_style( 'admin', MG_LICENSE_MANAGER_PLUGIN_URL . 'assets/dist/css/admin.css' );
	}

	/**
	 * Register Menu.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function registerMenu() {
		add_submenu_page(
			'edit.php?post_type=give_forms',
			esc_html__( 'MG - Licenses', 'mg-license-manager' ),
			esc_html__( 'MG - Licenses', 'mg-license-manager' ),
			'manage_options',
			'mg_license_manager',
			[ $this, 'licenseManager' ]
		);
	}

	/**
	 * License manager callback.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function licenseManager() {
		$plugins = Helpers::getAllSupportedPlugins();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'MG - License Manager', 'mg-ipay88-for-give' ); ?></h1>
			<div class="mg-license-manager-wrap">
				<div class="mg-license-manager-inner">
					<?php
					if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
						foreach ( $plugins as $slug => $data ) {
							$licenseKeySlug  = Helpers::getLicenseKeySlug( $data['ItemName'] );
							$licenseKey      = get_option( $licenseKeySlug );
							$isLicenseActive = ! empty( $licenseKey );
							?>
							<div class="mg-licenses-plugin-row">
								<div class="mg-license-plugin-header">
									<div class="mg-license-key-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Key', 'mg-licenses' ); ?>
										</h3>
										<input
											class="mg-full-width mg-license-text-input"
											type="text"
											name="licenseKey"
											data-name="<?php echo $data['ItemName']; ?>"
											data-slug="<?php echo $data['ItemSlug']; ?>"
										/>
										<input disabled class="mg-activate-license-btn mg-full-width button button-primary" type="button" value="<?php esc_html_e( 'Activate License', 'mg-licenses' ); ?>"/>
										<input class="mg-deactivate-license-btn mg-full-width button button-primary mg-hidden" type="button" value="<?php esc_html_e( 'Deactivate License', 'mg-licenses' ); ?>"/>
										<div class="mg-license-status">
											<div class="mg-license-not-active">
												<span class="dashicons dashicons-no"></span>
												<?php esc_html_e( 'Please activate your license key.', 'mg-licenses' ); ?>
											</div>
											<div class="mg-license-active">
												<span class="dashicons dashicons-yes"></span>
												<?php esc_html_e( 'You are receiving updates and support.', 'mg-licenses' ); ?>
											</div>
										</div>
										<div class="mg-license-errors"></div>
									</div>
									<div class="mg-license-information-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Information', 'mg-licenses' ); ?>
										</h3>
									</div>
									<div class="mg-license-actions-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Actions', 'mg-licenses' ); ?>
										</h3>
										<p class="mg-license-manage-license-wrap">
											<a href="#" title="<?php esc_html_e( 'Manage License', 'mg-licenses' ); ?>">
												<?php esc_html_e( 'Manage License', 'mg-licenses' ); ?>
											</a>
										</p>
										<p class="mg-license-access-support-wrap">
											<a href="#" title="<?php esc_html_e( 'Access Support', 'mg-licenses' ); ?>">
												<?php esc_html_e( 'Access Support', 'mg-licenses' ); ?>
											</a>
										</p>
									</div>
								</div>
								<div class="mg-license-plugin-footer">
									<div class="mg-license-plugin-footer-left">
										<h3 class="mg-license-heading">
											<?php  echo $data['Title']; ?>
										</h3>
										<span class="mg-license-version">
											<?php
											echo sprintf(
												'%1$s: %2$s',
												esc_html__( 'Version', 'mg-licenses' ),
												$data['Version']
											);
											?>
										</span>
									</div>
									<div class="mg-license-plugin-footer-right">

									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				<div class="mg-license-manager-upsells">
				</div>
			</div>
		</div>
		<?php
	}

}
