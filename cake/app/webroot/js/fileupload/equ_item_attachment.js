$("#container").ready(function() {	 			
	$(function () {
		'use strict';
		$('#EquFileAttachment').fileupload({
			url: '/EquFiles/fileUpload/',
			dataType: 'json',
			done: function (e, data) {	
				if(data.result.error){
				    $("#EquFileFileLink").val("");
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{					
					$("#EquFileFileLink").val(data.result.filepath);
					$("#upload_info").html('<span style="color:blue;">Fajl je uspešno zakačen!</span>');
					//table to fill
					var tableSelector  = '';
					if(typeof($("#noFilesIndicator").val()) != "undefined" && $("#noFilesIndicator") !== null) {
					    tableSelector = 'noFilesTable';
					    //if there was no files, remove warnig and show file
						$("#noFilesWarning").hide();
						$("#noFilesTable").css("visibility", "visible");
					} else{
						tableSelector = 'equFilesTable';
					}
					var td = "";
					//diffrent icons for diffrent file extensions
					switch(data.result.file_type) {
					    case 'pdf':
					        td = '<i class="icon-file icon-2x"></i> ';
					        break;
					    case 'xls':
                        case 'xlsx':
					        td = '<i class="icon-table icon-2x"></i> ';
					        break;
				        case 'doc':
                        case 'docx':
                        	td = '<i class="icon-file-alt icon-2x"></i> ';
                        	break;
					    default:
					        td = '<i class="icon-camera-retro icon-2x pointer img_popup" data-preview="0" data-name="'+data.result.filename+'" data-path="'+data.result.filepath+'"></i>';
					        break;
					}
					//show flie type
					var file_type_td = "";
					if(data.result.file_type_id){
						file_type_td = '<td>' + 
							'<a href="/EquFileTypes/view/'+data.result.file_type_id+'" style="color:#'+data.result.file_type_color+';">'+
							data.result.file_type_name +'</a>'+
							'</td>';
					}
					else{
						file_type_td = '<td>Nema tip</td>';
					}
					$('#'+tableSelector+' > tbody > tr:first').before(
						'<tr id="'+data.result.file_id+'">'+
							'<td>' + 
								td+
								'<a href="/EquFiles/fileDownload/'+data.result.file_id+'" title="Preuzimanje">'+ 
									data.result.filename + 
								'</a>'+
							'</td>'+
							'<td>' + 
								data.result.file_size + ' KB'+
							'</td>'+
							file_type_td+
							'<td>' + 
								data.result.file_created +
							'</td>'+
							'<td class="right">' + 
								'<ul class="button-bar">'+
									'<li class="first">' +
										'<a href="/EquFiles/fileDownload/'+data.result.file_id+'" title="Preuzimanje">'+
											'<i class="icon-download-alt"></i>'+
										'</a>'+
									'</li>'+
									'<li>' +
										'<a href="/EquFiles/save/'+data.result.file_id+'" title="Izmena">'+
											'<i class="icon-edit"></i>'+
										'</a>'+
									'</li>'+
									'<li class="last">' +
										'<a href="/EquFiles/delete/'+data.result.file_id+
										'" class="deleteAjax" data-id="'+data.result.file_id+
										'" data-name="'+data.result.filename+
										'" data-controller="EquFiles" title="Brisanje">'+
											'<i class="icon-trash"></i>'+
										'</a>'+
									'</li>'+
								'</ul>'+
							'</td>'+
						'</tr>'
					);
				}
				$("#EquFileName").val("");
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