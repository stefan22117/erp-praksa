$("#container").ready(function() {	 			
	$(function () {
		'use strict';
		$('#MeetingTopicAttachmentAttachment').fileupload({
			url: '/MeetingTopicAttachments/upload/',
			dataType: 'json',
			done: function (e, data) {			
				if(data.result.error){
				    $("#MeetingTopicAttachmentFileLink").val("");
				    $("#MeetingTopicAttachmentOriginalFilename").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{					
					$("#MeetingTopicAttachmentFileLink").val(data.result.filepath);
					$("#MeetingTopicAttachmentOriginalFilename").val(data.result.original_filename);					
					$("#upload_info").html('<span style="color:blue;">Fajl je uspešno zakačen!</span>');
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