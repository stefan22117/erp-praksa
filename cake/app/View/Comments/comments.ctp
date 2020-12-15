<div id="alert-comment"><?php echo $this->Session->flash(); ?></div>
<div id="comment_box" hidden>
	<?php echo $this->Form->create('Comment', array('id' => 'CommentForm')); ?>
	<?php echo $this->Form->input('model_id', array('type' => 'hidden', 'value' => $model_id)); ?>
	<?php echo $this->Form->input('model', array('type' => 'hidden', 'value' => $model)); ?>
	<?php echo $this->Form->input('comment', array('label' => false, 'style' => array('height: 60px;', 'width: 350px; display: inline-block;'))); ?><br/>
	<ul class="button-bar">
		<li class="first"><?php echo $this->Html->link('<i class="icon-save"></i> '.__('Snimi komentar'), array(), array('escape' => false, 'onclick' => 'saveComment(); return false;')); ?></li>
		<li class="last"><?php echo $this->Html->link('<i class="icon-reply"></i> '.__('Odustani'), array(), array('escape' => false, 'onclick' => 'quitComment(); return false;')); ?></li>
	</ul>
	<?php echo $this->Form->end(); ?>
</div>
<div id="add_comment_button">
	<ul class="button-bar">
		<li class="first last">
			<?php echo $this->Html->link('<i class="icon-comment"></i> '.__('Dodaj komentar'), array(), array('escape' => false, 'onclick' => 'addComment(); return false;')); ?>
		</li>
	</ul>
</div>

<div id="comments_preview" style="margin-top: 10px;">
	<table class="tight">
		<thead>
			<tr>
				<th class="center" width="100px"><?php echo __('Korisnik'); ?></th>
				<th class="left"><?php echo __('Komentar'); ?></th>
				<th class="right"><?php echo __('Postavljen'); ?></th>
			</tr>
		</thead>
		<tbody id="list_comments">
			<?php if ( !empty( $comments ) ){ ?>
				<?php foreach ($comments as $comment) { ?>
				<tr class="comment_row">
					<td class="center" width="120px"><?php echo $this->Html->image(str_replace("\\", "/", $comment['User']['avatar_link']), array('alt' => $comment['User']['username'], 'style' => 'width:50px; height:50px;'));?><br/><?php echo $comment['User']['first_name']; ?></td>
					<td class="left" valign="top"><?php echo $comment['Comment']['comment']; ?></td>
					<td class="right"><?php echo date('d.M.Y H:i', strtotime($comment['Comment']['created'])); ?></td>
				</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>

<script>
$("#container").ready(function(){
	// CK editor
	$('#CommentComment').ckeditor( function() { /* callback code */ }, { height : '100px', width:'500px' } );
});

<?php if ( empty( $comments ) ){ ?>
	$("#comments_preview").hide();
<?php } ?>

function addComment(){
	$("#add_comment_button").slideUp();
	$("#comment_box").slideDown();
}

function quitComment(){
	$("#comment_box").slideUp("slow");
	$("#add_comment_button").slideDown("slow");
}//~!

function saveComment(){
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		data: $("#CommentForm").serialize(),
		url:"\/Comments\/saveComment",
		success: function (data){
			if ( data.success ){
				quitComment();

				$.ambiance({
					message: data.message,
					type: "success",
					timeout: 3,
				});

				$("#comments_preview").show();
				$("#list_comments").prepend(
					$("<tr class='comment_row'>"
						+"<td class='center' width='120px'><img style='width:50px; height:50px;' alt='"+data.User.username+"' src='/img/"+data.User.avatar_link+"'><br/>"+data.User.first_name+"</td>"
						+"<td class='left' valign='top'>"+data.Comment.comment+"</td>"
						+"<td class='right'>"+data.Comment.created_beautify+"</td>"
					+"</tr>").show('slow')
				);
				$("#CommentComment").val("");
				$("#number_of_comments").html( $(".comment_row").length );
			}else{
				$.ambiance({
					message: data.message,
					type: "error",
					timeout: 3,
				});
			}
		},
		error: function(xhr){
			var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
			$.ambiance({
				message: error_msg,
				type: "error",
				timeout: 3,
			});
		}
	});
}//~!
</script>