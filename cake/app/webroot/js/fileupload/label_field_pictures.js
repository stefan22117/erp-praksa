$("#container").ready(function() {	 			
	$(function () {
		'use strict';
		$('#LabelFieldAttachment').fileupload({
			url: '/erp/LabelFields/upload/',
			dataType: 'json',
			done: function (e, data) {			
				console.log(data.result);
				if(data.result.error){
				    $("#LabelFieldFileLink").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{					
					$("#LabelFieldFileLink").val(data.result.filepath);
					$("#upload_info").html('<span style="color:blue;">Slika je uspešno zakačena!</span>');
					console.log($("#LabelFieldFileLink").val());
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