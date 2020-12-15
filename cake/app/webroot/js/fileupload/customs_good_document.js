function basename(path, suffix) {
  //  discuss at: http://phpjs.org/functions/basename/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Ash Searle (http://hexmen.com/blog/)
  // improved by: Lincoln Ramsay
  // improved by: djmix
  // improved by: Dmitry Gorelenkov
  //   example 1: basename('/www/site/home.htm', '.htm');
  //   returns 1: 'home'
  //   example 2: basename('ecra.php?p=1');
  //   returns 2: 'ecra.php?p=1'
  //   example 3: basename('/some/path/');
  //   returns 3: 'path'
  //   example 4: basename('/some/path_ext.ext/','.ext');
  //   returns 4: 'path_ext'

  var b = path;
  var lastChar = b.charAt(b.length - 1);

  if (lastChar === '/' || lastChar === '\\') {
    b = b.slice(0, -1);
  }

  b = b.replace(/^.*[\/\\]/g, '');

  if (typeof suffix === 'string' && b.substr(b.length - suffix.length) == suffix) {
    b = b.substr(0, b.length - suffix.length);
  }

  return b;
}//~!

/* --- Remove document from list --- */
function removeDoc(element){
	if(element){
		//Get link
		var link = element.attr('href');

		//Remove from array
		var filelink = atob(link.replace("/CustomsStorageDocuments/remove/", ""));		

		//Remove from array
		var index = file_links.indexOf(filelink);
		if (index >= 0) file_links.splice(index, 1);

		//Check for directory separator
		var DS = "";
		if(filelink.indexOf("\\") > -1) DS = "\\";
		if(filelink.indexOf("/") > -1) DS = "/";

		//Remove only for temporary files
		if(customs_storage_good_id === null || filelink.indexOf(DS + "tmp" + DS) > -1){
			//Delete file on server
		    $.ajax({
		        dataType: "json",
		        post: "POST",
		        evalScripts: true,
		        url: element.attr("href"),		        
		        data: ({ status: current_status }),
		        success: function(deleted){
		        	if(deleted.result){
			            if(deleted.result.error){
							var content = '<div class="notice error">';			
							content += '<i class="icon-remove-sign icon-large"></i>';
							content += deleted.result.error;
							content += '<a class="icon-remove" href="#close"></a>';
				            content += '</div>';

							$("#files").append(content);				                	
			            }
		        	}
		        },
		        error: function(xhr){
		          var error_message = "Doslo je do greske: " + xhr.status + " " + xhr.statusText;
		          alert(error_message);
		        }
		    });
		}

	    //Remove from HTML
	    element.closest("li").fadeOut(function(e){
			//If array empty show message
			if(file_links.length == 0){
				var content = '<div class="notice warning">';			
				content += '<i class="icon-warning-sign icon-large"></i>';
				content += 'Trenutno nije definisan nijedan dokument!';				                    
	            content += '</div>';

				$("#files").html(content);
			}
	    });	
	}
}//~!

$("#container").ready(function() {	 
	$(function () {
		'use strict';
		$('#attachment').fileupload({
			url: '/CustomsStorageDocuments/upload/',
			dataType: 'json',			
			done: function (e, data) {			
				console.log(data.result);
				if(data.result.error){
					$("#upload_info").html('<span style="color:red;">Greška: '+data.result.error+'</span>');
				}else{			
					//Show message		
					$("#upload_info").html('<span style="color:blue;">Fajl je uspešno zakačen!</span>');

					//Add files to list
					file_links.push(data.result.filelink);

					//Show on page
					var html = '<ul class="alt">';
					file_links.forEach(function(link) {
					    html += '<li>'+basename(link)+'<a class="remove" href="/CustomsStorageDocuments/remove/'+btoa(link)+'" style="float:right; margin-right:5px;" title="Ukloni dokument"><i class="icon-remove" style="color:red;"></i></a></li>';
					});						
					html += '</ul>';
					$("#files").html(html);

					$(".remove").on("click", function(e) {				        						
						removeDoc($(this));
						return false;
					});
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

	//Load new file links on submit
	$('#attachment').bind('fileuploadsubmit', function (e, data) {
	    // The example input, doesn't have to be part of the upload form:
	    data.formData = {currentlinks: JSON.stringify(file_links), status: current_status};

	});

	//Check if there are any file links
	if(file_links.length > 0){
		$(".remove").on("click", function(e) {				        						
			removeDoc($(this));
			return false;
		});		
	}

	//Submit event
	$(".main_form").submit(function(event){
		setFileLinksInForm();
	});
});

function setFileLinksInForm(){
	var processed_file_links = JSON.stringify(file_links);
	$("#CustomsStorageGoodFileLinks").val(processed_file_links);
}