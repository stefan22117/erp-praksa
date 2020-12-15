$('#container').ready(function() {
	var s=$("#MenuUserId").val();
	var d=$("#MenuGroupId").val();
	if(s){
		$('#MenuGroupId').attr("disabled", true);
	}
	if(d){
		$('#MenuUserId').attr("disabled", true);
	}

	$("#MenuUserId").change(function() {
		var s=$("#MenuUserId").val();
		if(s!=="")
			$('#MenuGroupId').attr("disabled", true);
		else
			$('#MenuGroupId').attr("disabled", false);
	});	
	$("#MenuGroupId").change(function() {
		var s=$("#MenuGroupId").val();
		if(s!=="")
			$('#MenuUserId').attr("disabled", true);
		else
			$('#MenuUserId').attr("disabled", false);
	});			
});