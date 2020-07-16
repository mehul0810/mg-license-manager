<?php
namespace MG\LicenseManager\Admin;

use MG\LicenseManager\Includes\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class LicenseManager
 *
 * @package MG\LicenseManager\Admin
 *
 * @since 1.0.0
 */
class LicenseManager {

	/**
	 * LicenseManager constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_mg_license_manager_activate_product', [ $this, 'activateLicense' ] );
		add_action( 'wp_ajax_mg_license_manager_deactivate_product', [ $this, 'deactivateLicense' ] );
	}

	/**
	 * Activate License.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function activateLicense() {
		$postData   = give_clean( $_POST );
		$licenseKey = ! empty( $postData['licenseKey'] ) ? $postData['licenseKey'] : false;
		$itemName   = ! empty( $postData['name'] ) ? $postData['name'] : false;
		$itemSlug   = ! empty( $postData['slug'] ) ? $postData['slug'] : false;
		$url        = add_query_arg(
			[
				'edd_action' => 'activate_license',
				'item_name'  => $itemName,
				'license'    => $licenseKey,
				'url'        => site_url(),
			],
			'https://mehulgohil.com'
		);

		$response     = wp_remote_get( $url );
		$responseBody = wp_remote_retrieve_body( $response );
		$responseCode = wp_remote_retrieve_response_code( $response );

		if ( 200 === $responseCode ) {
			$responseData = json_decode( $responseBody );

			if (
				isset( $responseData->success ) &&
				$responseData->success &&
				'valid' === $responseData->license
			) {
				$licenseKeySlug = Helpers::getLicenseKeySlug( $itemSlug );
				update_option( $licenseKeySlug, $licenseKey );
				update_option( "mg_license_manager_license_information_{$itemSlug}", (array) $responseData );
				wp_send_json_success( $responseData );
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Deactivate License.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function deactivateLicense() {
		$postData   = give_clean( $_POST );
		$licenseKey = ! empty( $postData['licenseKey'] ) ? $postData['licenseKey'] : false;
		$itemName   = ! empty( $postData['name'] ) ? $postData['name'] : false;
		$itemSlug   = ! empty( $postData['slug'] ) ? $postData['slug'] : false;
		$url        = add_query_arg(
			[
				'edd_action' => 'deactivate_license',
				'item_name'  => $itemName,
				'license'    => $licenseKey,
				'url'        => site_url(),
			],
			'https://mehulgohil.com'
		);

		$response     = wp_remote_get( $url );
		$responseBody = wp_remote_retrieve_body( $response );
		$responseCode = wp_remote_retrieve_response_code( $response );

		if ( 200 === $responseCode ) {
			$responseData = json_decode( $responseBody );

			if (
				isset( $responseData->success ) &&
				$responseData->success
			) {
				update_option( "mg_license_manager_license_information_{$itemSlug}", (array) $responseData );
				wp_send_json_success( $responseData );
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}
	}
}
