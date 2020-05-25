document.addEventListener( 'DOMContentLoaded', () => {
	const licenseKeyTextFields = Array.from( document.querySelectorAll( 'input[name="licenseKey"]' ) );
	const activateLicenseBtns = Array.from( document.querySelectorAll( '.mg-activate-license-btn' ) );
	const deactivateLicenseBtns = Array.from( document.querySelectorAll( '.mg-deactivate-license-btn' ) );

	/**
	 * This section of JS will be used to ensure that the "Activate License" button
	 * is disabled until a license key is not entered into the license key textarea.
	 */
	licenseKeyTextFields.forEach( ( licenseKeyTextField ) => {
		licenseKeyTextField.addEventListener( 'keyup', ( e ) => {
			if ( 0 < e.target.value.length ) {
				licenseKeyTextField.nextElementSibling.removeAttribute( 'disabled' );
			} else {
				licenseKeyTextField.nextElementSibling.setAttribute( 'disabled', 'disabled' );
			}

		} );
	} );

	/**
	 * This section of JS is used to ensure that on clicking "Activate License" button,
	 * we call the license API to activate the license which will help in automatic
	 * updates of the plugin and accessing the support.
	 */
	activateLicenseBtns.forEach( ( activateLicenseBtn ) => {

		activateLicenseBtn.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			const parentElement = e.target.parentNode;
			const licenseKey = parentElement.querySelector( 'input[name="licenseKey"]' );
			const xhttp = new XMLHttpRequest();
			const data = new FormData();

			data.append( 'action', 'mg_license_manager_activate_product' );
			data.append( 'slug', licenseKey.getAttribute( 'data-slug' ) );
			data.append( 'name', licenseKey.getAttribute( 'data-name' ) );
			data.append( 'licenseKey', licenseKey.value );

			xhttp.onload = function() {
				const response = JSON.parse( xhttp.response );

				if ( xhttp.status === 200 && response.success ) {
					licenseKey.setAttribute( 'readonly', true );
					e.target.classList.add( 'mg-hidden' );
					e.target.nextElementSibling.classList.remove( 'mg-hidden' );

					parentElement.querySelector( '.mg-license-not-active' ).classList.add( 'mg-hidden' );
					parentElement.querySelector( '.mg-license-active' ).classList.remove( 'mg-hidden' );
				}
			};
			xhttp.open('POST', ajaxurl, true );
			xhttp.send( data );
		} );
	} );

	deactivateLicenseBtns.forEach( ( deactivateLicenseBtn ) => {
		deactivateLicenseBtn.addEventListener('click', (e) => {
			e.preventDefault();

			const xhttp = new XMLHttpRequest();
			const parentElement = e.target.parentNode;
			const licenseKey = parentElement.querySelector( 'input[name="licenseKey"]' );
			const data = new FormData();

			data.append( 'action', 'mg_license_manager_deactivate_product' );
			data.append( 'slug', licenseKey.getAttribute( 'data-slug' ) );
			data.append( 'name', licenseKey.getAttribute( 'data-name' ) );
			data.append( 'licenseKey', licenseKey.value );

			xhttp.onload = function () {
				const response = JSON.parse(xhttp.response);

				if (xhttp.status === 200 && response.success) {
					licenseKey.removeAttribute( 'readonly' );
					e.target.classList.add( 'mg-hidden' );
					e.target.previousElementSibling.classList.remove( 'mg-hidden' );

					parentElement.querySelector( '.mg-license-not-active' ).classList.remove( 'mg-hidden' );
					parentElement.querySelector( '.mg-license-active' ).classList.add( 'mg-hidden' );
				}
			};
			xhttp.open('POST', ajaxurl, true);
			xhttp.send(data);
		});
	} );
} );
