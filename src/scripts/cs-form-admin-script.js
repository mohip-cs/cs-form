console.log("admin js");
jQuery(document).ready(function () {
	jQuery("#recaptcha-submit").on("click", function () {
		const formData = {
			siteKey: jQuery("#recaptcha-site-key").val(),
			secretKey: jQuery("#recaptcha-secret-key").val(),
		};
		if ("" !== formData.siteKey && "" !== formData.secretKey) {
			jQuery(".recaptcha-error").css("display", "none");
			jQuery.ajax({
				url: csFormAjax.ajaxUrl,
				type: "POST",
				dataType: "JSON",
				data: {
					formData,
					Nonce: csFormAjax.nonce,
					action: "cs_form_recaptcha",
				},
				success(response) {
					console.log(response);
					if (response.success) {
						jQuery(".recaptcha-success").css("display", "block");
					}
				},
			});
		} else {
			jQuery(".recaptcha-error").css("display", "block");
			jQuery(".recaptcha-success").css("display", "none");
		}
	});
});
