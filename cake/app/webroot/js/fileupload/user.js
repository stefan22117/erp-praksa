//Init app
$("#container").ready(function() {
	//Avatar uploader		
	$(function () {
		'use strict';
		$('#UserAvatar').fileupload({
			url: '/Users/upload/',
			dataType: 'json',
			done: function (e, data) {
				if(data.result.error){
				    $("#UserAvatarLink").val("");
					$("#avatar_upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{
					console.log(data.result.filepath);
					$("#UserAvatarLink").val(data.result.filepath);
					var preview = "/img/"+data.result.filepath.replace(/\\/g, '/');
					$("#avatar").attr("src", preview);
				}
				$("#avatar_upload_info").removeClass("hide");
				$("#avatar_progress").addClass("hide");
				$("#remove_avatar").show();
			},
			send: function (e, data) {
				$("#avatar_upload_info").addClass("hide");
				$("#avatar_progress").removeClass("hide");
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#avatar_progress .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});
	//Signature uploader
	$(function () {
		'use strict';
		$('#UserSignature').fileupload({
			url: '/Users/upload/',
			dataType: 'json',
			done: function (e, data) {
				if(data.result.error){
				    $("#UserSignatureLink").val("");
					$("#signature_upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{
					$("#UserSignatureLink").val(data.result.filepath);
					var preview = "/img/"+data.result.filepath.replace(/\\/g, '/');
					$("#signature").attr("src", preview);
				}
				$("#signature_upload_info").removeClass("hide");
				$("#signature_progress").addClass("hide");
				$("#remove_signature").show();
			},
			send: function (e, data) {
				$("#signature_upload_info").addClass("hide");
				$("#signature_progress").removeClass("hide");
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#signature_progress .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});
	//Handle removing avatar
	$("#remove_avatar").click(function() {
		$("#UserAvatarLink").val("");
		var preview = "/img/company/avatar_default.png";
		$("#avatar").attr("src", preview);
		$("#remove_avatar").hide();
		return false;
   	});	
	//Handle removing signature
	$("#remove_signature").click(function() {
		$("#UserSignatureLink").val("");
		var preview = "/img/users/signature_not_defined.png";
		$("#signature").attr("src", preview);
		$("#remove_signature").hide();
		return false;
   	});
});