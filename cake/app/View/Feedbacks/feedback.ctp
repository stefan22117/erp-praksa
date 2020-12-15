<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<?php echo $this->Html->script('ckeditor/adapters/jquery.js'); ?>
<div id="feedback" onclick="feedback();">
</div>
<div id="feedback_form" class="hide">
	<div class="content_text_input">
            <label for="Description"> Feedback</label>
            <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => false, 'class' => 'feedback_text', 'required' => false)); ?>
    </div>
    <ul class="button-bar" style="margin-top:10px; margin-left:220px;">
	<li class="first"><a href="" onclick="sendFeedback();return false;"><i class="icon-envelope" style="color:blue;"></i> Pošalji</a></li>
	<li class="last"><a href="" onclick="reverse();return false;"><i class=" icon-remove-circle" style="color:red;"></i> Odustani</a></li>
	</ul>
</div>
<div id="my_popup"></div>
<?php echo $this->Form->input('controller', array('type' => 'hidden', 'value' => $this->params['controller'])); ?>
<?php echo $this->Form->input('action', array('type' => 'hidden', 'value' => $this->params['action'])); ?>
<script type="text/javascript">
$("#container").ready(function(){
    $('#description').ckeditor( function() { /* callback code */ }, { height : '200px' } ); 
});
$('#my_popup').popup({
    opacity: 0.3,
    transition: 'all 0.3s'
});

function feedback(){
	$("#feedback").hide();
	$("#feedback_form").show();
}

function reverse(){
	$("#feedback").show();
	$("#feedback_form").hide();
	$("#description").css("border-style","solid");
	$("#description").css("border-width","1px");
	$("#description").css("border-color","#ccc");
	$("#description").val("");
}

function sendFeedback(){
	if ($("#description").val().length>0){
        $("#feedback").show();
        $("#feedback_form").hide();
        $("#description").css("border-style","solid");
        $("#description").css("border-width","1px");
        $("#description").css("border-color","#ccc");
        $(".submit_loader").show();
		$.ajax({
            dataType: "json",
            type: "POST",
            evalScripts: true,
            data: ({description:$("#description").val()}),
            url: "<?php echo Router::url(array('controller' => 'Feedbacks', 'action' => 'add')); ?>",
            success: function (data){
                reverse();
                if(data['success']){
                	$('#my_popup').html('<div class="notice success margin20"><i class="icon-remove-sign icon-large"></i>'+data['success']+'</div>');
                    $('#my_popup').popup('show');
                    setTimeout(function(){$('#my_popup').popup('hide');}, 2000);
                }else{
                    $('#my_popup').html('<div class="notice error margin20"><i class="icon-remove-sign icon-large"></i>'+data['error']+'</div>');
                    $('#my_popup').popup('show');
                    setTimeout(function(){$('#my_popup').popup('hide');}, 2000);
                }
                $(".submit_loader").hide();
            },
            error:function(xhr){
                reverse();
                $(".submit_loader").hide();
                var error_msg = "Došlo je do greške: " + xhr.status + " " + xhr.statusText;
                $('#my_popup').html('<div class="notice error margin20"><i class="icon-remove-sign icon-large"></i>'+error_msg+'</div>');
                $('#my_popup').popup('show');
                setTimeout(function(){$('#my_popup').popup('hide');}, 2000);
                
            }
        });
	}else{
		$("#description").css("border-style","solid");
		$("#description").css("border-width","3px");
		$("#description").css("border-color","red");
	}
}

</script>