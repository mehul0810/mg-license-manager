document.addEventListener( 'DOMContentLoaded', () => {
	const licenseKeyTextFields = Array.from( document.querySelectorAll( 'input[name="licenseKey"]' ) );
	const activateLicenseBtns = Array.from( document.querySelectorAll( '.mg-activate-license-btn' ) );
	const deactivateLicenseBtns = Array.from( document.querySelectorAll( '.mg-deactivate-license-btn' ) );
	const spinner = document.querySelector( '.spinner' );

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

			// spinner.style.visibility = 'visible';

			const parentElement = e.target.parentNode;
			const licenseKey = e.target.parentNode.querySelector( 'input[name="licenseKey"]' );
			const xhttp = new XMLHttpRequest();
			const data = new FormData();

			data.append( 'action', 'mg_licenses_activate_product' );
			data.append( 'slug', licenseKey.getAttribute( 'data-slug' ) );
			data.append( 'name', licenseKey.getAttribute( 'data-name' ) );
			data.append( 'licenseKey', licenseKey.value );

			xhttp.onload = function() {
				const response = JSON.parse( xhttp.response );
console.log(response);
				if ( xhttp.status === 200 && response.success ) {

				} else {

				}

				spinner.style.visibility = 'hidden';
			};
			xhttp.open('POST', ajaxurl, true );
			xhttp.send( data );
		} );
	} );

	deactivateLicenseBtns.forEach( ( deactivateLicenseBtn ) => {
		deactivateLicenseBtn.addEventListener('click', (e) => {
			e.preventDefault();

			spinner.style.visibility = 'visible';

			const xhttp = new XMLHttpRequest();
			const parentElement = e.target.parentNode;
			const data = new FormData();

			data.append('action', 'mg_ipay88_deactivate_license');

			xhttp.onload = function () {
				const response = JSON.parse(xhttp.response);

				if (xhttp.status === 200 && response.success) {
					parentElement.classList.add('give-hidden');
					parentElement.previousElementSibling.classList.remove('give-hidden');
					document.querySelector('.mggi88-license-connected').classList.add('give-hidden');
					document.querySelector('.mggi88-license-disconnected').classList.remove('give-hidden');
					licenseErrors.innerHTML = `<p class="notice notice-success">${response.data.message}</p>`;
				} else {
					licenseErrors.innerHTML = `<p class="notice notice-error">${response.data.message}</p>`;
				}

				spinner.style.visibility = 'hidden';
			};
			xhttp.open('POST', ajaxurl, true);
			xhttp.send(data);
		});
	} );
} );
