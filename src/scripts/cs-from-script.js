function csFormOnSubmit(token) {
	
	jQuery(document).ready(function (event) {
		let names = [];
		let values = [];
		let error = false;
		
		jQuery("form#contactForm :input").each(function(){
			const input = jQuery(this);
			let name = input[0].name;
			console.log(input[0].className,  'field class');
			
			if(!names.includes(name) && name ) {
				let className = input[0].className;
				if('checkbox' === input[0].type ) {
					let checkbox = jQuery(`input[name=${name}]:checked`).val();
					if( !checkbox && className.includes("required") ) {
						jQuery( this ).parent().addClass('show_error');
						error = true;
					} else {
						jQuery( this ).parent().removeClass('show_error')
					}
					values.push(checkbox);
				} else if ('radio' === input[0].type) {
					let radio = jQuery(`input[name=${name}]:checked`).val();
					if( !radio && className.includes("required") ) {
						jQuery( this ).parent().addClass('show_error');
						error = true;
					} else {
						jQuery( this ).parent().removeClass('show_error')
					}
					values.push(radio);
				} else {
					let value = document.getElementsByName(name);
					if( '' === value[0].value && className.includes("required")) {
						jQuery( this ).parent().addClass('show_error');
						error = true;
					} else {
						jQuery( this ).parent().removeClass('show_error')
					}
					values.push(value[0].value);
				}
				names.push(name);
			}
		});
		if (error) {
			return false;
		}
		document.getElementById("contactForm").submit();

		// jQuery.ajax({
		// 	url: csFormSubmitAjax.ajaxUrl,
		// 	type: "POST",
		// 	dataType: "JSON",
		// 	data: {
		// 		// formData,
		// 		Nonce: csFormSubmitAjax.nonce,
		// 		action: "cs_form_submit",
		// 	},
		// 	success(response) {
		// 		console.log(response);
		// 		debugger;
		// 		// document.getElementById("contactForm").submit();
		// 		if (response.success) {
		// 			jQuery(".recaptcha-success").css("display", "block");
		// 		}
		// 	},
		// 	error(error) {
		// 		debugger;
		// 		console.log(error, 'error');
		// 	}
		// });
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