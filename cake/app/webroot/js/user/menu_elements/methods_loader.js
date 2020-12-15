$(document).ready(function() {
	$("#MenuElementController" ).change(function() {
         $.ajax({
            dataType: "html",
            type: "POST",
            evalScripts: true,
            url: '',
            data: ({type:'original'}),
            success: function (data, textStatus){
                $("#div1").html(data);

            }
        });
	});			
});