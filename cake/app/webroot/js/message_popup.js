function showErrorPopup(error_msg){
    $("#message-popup-place").html('<div class="notice error margin20"><i class="icon-remove icon-large"></i>'+error_msg+'</div>');
    $("#message-popup-place").popup('show');
}
function showWarningPopup(warning_msg){
	var msg = warning_msg;
    $("#message-popup-place").html('<div class="notice warning margin20"><i class="icon-warning-sign icon-large"></i>'+msg+'</div>');
    $("#message-popup-place").popup('show');
}

function showSuccessPopup(success_msg){
    $('#message-popup-place').html('<div class="notice success margin20"><i class="icon-ok icon-large"></i>'+success_msg+'</div>');
    $('#message-popup-place').popup('show');
    setTimeout(function(){$('#message-popup-place').popup('hide');}, 2000);
}
