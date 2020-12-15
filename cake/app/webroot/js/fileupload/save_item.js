var count = 0;
var links = new Array();
$("#container").ready(function() {	 
    $("input.focus").focus();
    $(".dropdown").select2();

	$(function () {
		'use strict';
		$('#ItemAttachment').fileupload({
			url: '/Items/upload/',
			dataType: 'json',
			done: function (e, data) {
				if(data.result.error){
				    $("#ItemImageLink").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{
					$("#ItemImageLink").val(data.result.filepath);
					$("#upload_info").html('<img alt="Slika artikla" src="/img/items/'+data.result.filename+'.'+data.result.extension+'?timestamp='+Math.round((new Date()).getTime())+'">');
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

	count = $('#links').children('span').length;
	for(var i = 0; i<count; i++){
		links.push(Array(i, $("#l"+i).html(), $("#lid"+i).val()));
	}
	$("#ItemLinks").val(JSON.stringify(links));
});

function addLink(id){
	count++;

	var link = $("#ItemLink").val();
	$("#ItemLink").val("");

	if(link.length > 0){
		links.push(Array(count, link, id));
		$("#links").append('<span id="link_'+count+'" onclick="removeLink('+count+')" style="width:700px; float:left;"><i class="icon-minus red pointer"></i> <span>'+link+'</span></span>');
		$("#ItemLinks").val(JSON.stringify(links));
	}
}

function removeLink(i){
	$("#link_"+i).remove();

	for(var j=0; j<links.length; j++) {
		if(links[j][0]===i){
			links.splice( j, 1 );
			j--;
		}
	}
	$("#ItemLinks").val(JSON.stringify(links));
}