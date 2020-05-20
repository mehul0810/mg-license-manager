<?php
namespace MG\LicenseManager\Admin;

class LicenseManager {

	public function __construct() {
		add_action( 'wp_ajax_mg_license_manager_activate_product', [ $this, 'ActivateLicense' ] );
	}

	public function ActivateLicense() {
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
				update_option( "mg_licenses_my_license_key_{$itemSlug}", $licenseKey );
				update_option( "mg_licenses_my_license_information_{$itemSlug}", $licenseKey );
				wp_send_json_success( $responseData );
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}

		die();
	}

}
