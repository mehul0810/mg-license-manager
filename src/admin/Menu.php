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
		add_options_page(
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
			<h1><?php esc_html_e( 'MG - License Manager', 'mg-license-manager' ); ?></h1>
			<div class="mg-license-manager-wrap">
				<div class="mg-license-manager-inner">
					<?php
					if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
						foreach ( $plugins as $slug => $data ) {
							$licenseKeySlug       = Helpers::getLicenseKeySlug( $data['ItemSlug'] );
							$licenseKey           = get_option( $licenseKeySlug );
							$licenseInformation   = Helpers::getLicenseInformation( $data['ItemSlug'] );
							$isLicenseValid       = ! empty( $licenseInformation['license'] ) && 'valid' === $licenseInformation['license'];
							$isLicenseDeactivated = ! empty( $licenseInformation['license'] ) && 'deactivated' === $licenseInformation['license'];
							$activateBtnClass     = $isLicenseValid ? 'mg-hidden' : '';
							$deactivateBtnClass   = $isLicenseValid ? '' : 'mg-hidden';
							$licenseExpiryDate    = ! empty( $licenseInformation['expires'] ) ?
									date_i18n( 'dS F Y', strtotime( $licenseInformation['expires'] ) ) :
									false;
							$isActivationLeft   = ! empty( $licenseInformation['activations_left'] );
							$activationsLeft    = $isActivationLeft ?
									sprintf(
										'%1$s %2$s',
											$licenseInformation['activations_left'],
											esc_html__( 'activations remaining', 'mg-license-manager' )
									) :
									esc_html__( 'No activations remaining', 'mg-license-manager' );
							?>
							<div class="mg-licenses-plugin-row">
								<div class="mg-license-plugin-header">
									<div class="mg-license-key-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Key', 'mg-license-manager' ); ?>
										</h3>
										<input
											class="mg-full-width mg-license-text-input"
											type="text"
											name="licenseKey"
											data-name="<?php echo $data['ItemName']; ?>"
											data-slug="<?php echo $data['ItemSlug']; ?>"
											value="<?php echo $isLicenseValid ? $licenseKey : ''; ?>"
											<?php echo $isLicenseValid ? 'readonly' : ''; ?>
										/>
										<input disabled class="mg-activate-license-btn mg-full-width button button-primary <?php echo $activateBtnClass; ?>" type="button" value="<?php esc_html_e( 'Activate License', 'mg-license-manager' ); ?>"/>
										<input class="mg-deactivate-license-btn mg-full-width button button-primary <?php echo $deactivateBtnClass; ?>" type="button" value="<?php esc_html_e( 'Deactivate License', 'mg-license-manager' ); ?>"/>
										<div class="mg-license-status">
											<div class="mg-license-not-active <?php echo $isLicenseValid ? 'mg-hidden' : ''; ?>">
												<span class="dashicons dashicons-no"></span>
												<?php esc_html_e( 'Please activate your license key.', 'mg-license-manager' ); ?>
											</div>
											<div class="mg-license-active <?php echo ! $isLicenseValid ? 'mg-hidden' : ''; ?>">
												<span class="dashicons dashicons-yes"></span>
												<?php esc_html_e( 'You are receiving updates and support.', 'mg-license-manager' ); ?>
											</div>
										</div>
										<div class="mg-license-errors"></div>
									</div>
									<div class="mg-license-information-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Information', 'mg-license-manager' ); ?>
										</h3>
										<?php
										if ( empty( $licenseKey ) ) {
											?>
											<p>
												<?php esc_html_e( 'No License Information exists. Please activate license to display license information.', 'mg-license-manager' ); ?>
											</p>
											<?php
										}

										if ( $licenseExpiryDate ) {
											?>
											<p class="mg-license-renewal-date">
												<span class="dashicons dashicons-calendar-alt"></span>
												<strong>
													<?php esc_html_e( 'Renews:', 'mg-license-manager' ); ?>
												</strong>
												<?php echo $licenseExpiryDate; ?>
											</p>
											<?php
										}
										?>
									</div>
									<div class="mg-license-actions-wrap mg-license-plugin-header-column">
										<h3 class="mg-license-heading">
											<?php esc_html_e( 'License Actions', 'mg-license-manager' ); ?>
										</h3>
										<p class="mg-license-manage-license-wrap">
											<a href="https://mehulgohil.com/my-account" title="<?php esc_html_e( 'Manage License', 'mg-license-manager' ); ?>" target="_blank">
												<?php esc_html_e( 'Manage License', 'mg-license-manager' ); ?>
											</a>
										</p>
										<p class="mg-license-access-support-wrap">
											<a href="https://mehulgohil.com/my-account" title="<?php esc_html_e( 'Access Support', 'mg-license-manager' ); ?>" target="_blank">
												<?php esc_html_e( 'Access Support', 'mg-license-manager' ); ?>
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
												esc_html__( 'Version', 'mg-license-manager' ),
												$data['Version']
											);
											?>
										</span>
									</div>
									<div class="mg-license-plugin-footer-right">
										<?php
										if ( $isLicenseValid ) {
											?>
											<div class="mg-license-badge mg-license-green">
												<?php esc_html_e( 'Activated', 'mg-license-manager' ); ?>
											</div>
											<?php
										} else if ( $isLicenseDeactivated ) {
											?>
											<div class="mg-license-badge mg-license-red">
												<?php esc_html_e( 'Deactivated', 'mg-license-manager' ); ?>
											</div>
											<?php
										} else {
											?>
											<div class="mg-license-badge mg-license-grey">
												<?php esc_html_e( 'Installed', 'mg-license-manager' ); ?>
											</div>
											<?php
										}
										?>
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
