$("#container").ready(function() {	 			
	$(function () {
		'use strict';
		$('#ShippingTypeAttachment').fileupload({
			url: '/ShippingTypes/upload/',
			dataType: 'json',
			done: function (e, data) {						
				if(data.result.error){
				    $("#ShippingTypeCourierLogoImgPath").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{					
					$("#ShippingTypeCourierLogoImgPath").val(data.result.filepath);
					$("#upload_info").html('<img alt="Tip isporuke" src="/img/couriers/'+data.result.filename+'.'+data.result.extension+'?timestamp='+Math.round((new Date()).getTime())+'">');
				}
				$("#upload_info").removeClass("hide");
				$("#progress").addClass("hide");												
			},
			send: function (e, data) {
				$("#upload_info").addClass("hide");
				$("#progress").removeClass("hide");
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});

});