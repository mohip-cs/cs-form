function csFormOnSubmit(token) {
	console.log('form data');
	
	jQuery(document).ready(function (event) {
		// event.preventDefault();
		debugger;
		const fieldName = document.getElementById('cs-form-button');
		const formData = jQuery('#contactForm').serializeArray();
		console.log('form data', formData);
		jQuery.ajax({
			url: csFormSubmitAjax.ajaxUrl,
			type: "POST",
			dataType: "JSON",
			data: {
				formData,
				Nonce: csFormSubmitAjax.nonce,
				action: "cs_form_submit",
			},
			success(response) {
				debugger;
				document.getElementById("contactForm").submit();
				console.log(response);
				if (response.success) {
					jQuery(".recaptcha-success").css("display", "block");
				}
			},
			error(error) {
				debugger;
				console.log(error, 'error');
			}
		});
	})
}


// jQuery(document).ready(function () {
	
// 	jQuery("#cs-form-button").on("click", function (event) {
// 		console.log( event,'click');
// 		event.preventDefault();
// 		const fieldName = document.getElementById('cs-form-button');
// 		const name = jQuery("input[name=cs-form-fields]").val();
// 		console.log(name, 'fieldName');
// 		const decode = atob(name);
// 		console.log(decode, 'fieldName');
// 		const formData = jQuery('#contactForm').serializeArray();
// 		console.log('form data', formData);
// 		debugger;
// 		alert('test')
// 		return false;
// 		jQuery.ajax({
// 			url: csFormSubmitAjax.ajaxUrl,
// 			type: "POST",
// 			dataType: "JSON",
// 			data: {
// 				formData,
// 				Nonce: csFormSubmitAjax.nonce,
// 				action: "cs_form_submit",
// 			},
// 			success(response) {
// 				debugger;
// 				event.preventDefault();
// 				console.log(response);
// 				if (response.success) {
// 					jQuery(".recaptcha-success").css("display", "block");
// 				}
// 			},
			
// 		});
// 	})
	
// })