$("#container").ready(function() {	 			
	$(function () {
		'use strict';
		$('#ComplaintItemAttachmentAttachment').fileupload({
			url: '/ComplaintItemAttachments/upload/',
			dataType: 'json',
			done: function (e, data) {			
				console.log(data.result);
				if(data.result.error){
				    $("#ComplaintItemAttachmentFileLink").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{					
					$("#ComplaintItemAttachmentFileLink").val(data.result.filepath);
					$("#upload_info").html('<span style="color:blue;">Fajl je uspešno zakačen!</span>');
					console.log($("#ComplaintItemAttachmentFileLink").val());
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