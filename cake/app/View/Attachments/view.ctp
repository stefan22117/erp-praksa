<?php echo $this->Html->css('fileupload/jquery.fileupload.css'); ?>
<?php echo $this->Html->script('fileupload/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.iframe-transport.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.fileupload.js'); ?>
<div id="closeButton" style="position: absolute; top: 10px; right: 10px; cursor: pointer;"><i class="icon-remove-sign"></i></div>
<div style="width: 98%;">
    <h4 style="float: left"><i class="icon-paper-clip" style="margin-right: 10px;"></i><?php echo __('Prilozi'); ?></h4>
    <div style="float:right; margin: 15px 0 0 0;">
        <?php
            if(!isset($buttonShow)) {
                $buttonShow = array(
                    'view' => true,
                    'save' => true,
                    'delete' => true,
                    'download' => true,
                );
            }
        ?>
        <?php if($buttonShow['save']): ?>
        <ul class="button-bar">
            <li class="first last">
                <?php echo $this->Html->link('<i class="icon-upload-alt" style="color:green"></i> '.__("Dodaj novi prilog"), array(), array('escape' => false, 'onclick' => 'return false', 'id' => 'AddAttachmentButton', 'style' => 'cursor: pointer;', 'title' => __('Upload'), 'data-fkey' => $foreignKey, 'data-model' => $model)); ?>
            </li>
        </ul>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
	<div id='alertAttachment'><?php echo $this->Session->flash(); ?></div>
	<?php if(empty($attachments)): ?>
	    <div class="notice warning">
	        <i class="icon-warning-sign icon-large"></i>
	            <?php echo __('Nema priloga'); ?>
	        <a class="icon-remove" href="#close"></a>
	    </div>
        <?php $attachments = array(); ?>
	<?php else: ?>
	    <table>
	    	<tr>
	    		<th><?php echo __('Naziv') ?></th>
                <th><?php echo __('Tip') ?></th>
                <th><?php echo __('Ime fajla') ?></th>
                <th><?php echo __('Prilog dodao') ?></th>
                <th><?php echo __('Datum priloga') ?></th>
	    		<th width="100"></th>
	    	</tr>
	    	<?php foreach($attachments as $attachment): ?>
			<tr>
				<td><?php echo $attachment['Attachment']['name']; ?></td>
                <td><?php echo $attachment['AttachmentType']['type']; ?></td>
                <td><?php echo $attachment['Attachment']['file_name']; ?></td>
                <td><?php echo $attachment['User']['first_name'].' '.$attachment['User']['last_name']; ?></td>
                <td>
                    <?php
                        if (!empty($attachment['Attachment']['date'])) {
                            echo $this->Time->format('d. M Y.', $attachment['Attachment']['date']);
                        }
                    ?>
                </td>
				<td>
					<ul class="button-bar">
                        <?php if($buttonShow['download']): ?>
		                <li>
                            <?php echo $this->Html->link('<i class="icon-download-alt" style="color :green"></i>', array('controller' => 'Attachments', 'action' => 'download', $attachment['Attachment']['id']), array('title' => __('Preuzmi fajl'), 'escape' => false)); ?>
		                </li>
                        <?php endif; ?>
                        <?php if($buttonShow['delete']): ?>
		                <li>
		                    <?php echo $this->Html->link('<i class="icon-trash" style="color :#880000"></i>', array(), array('title' => __('Brisanje'), 'escape' => false, 'class' => 'DeleteAttachmentButton', 'onclick' => 'return false', 'data-id' => $attachment['Attachment']['id'], 'data-fkey' => $attachment['Attachment']['foreign_key'], 'data-model' => $model)); ?>
		                </li>
                        <?php endif; ?>
		            </ul>
				</td>
			</tr>
	    	<?php endforeach; ?>
		</table>
	<?php endif; ?>
    <div class="clear"></div>
</div>
<!-- Custom javascript -->
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){$('#alertAttachment').fadeOut();}, 2000);
    	var model = $('#AttachmentUpload').attr('data-model');
        var fkey = $('#AttachmentUpload').attr('data-fkey');

        //Set update counter
        var attachment_counter = <?php echo count($attachments); ?>;
        if($("#attachment_counter").length > 0) {
          $("#attachment_counter").text(attachment_counter);
        }

        //Set custom counter if exists
        var custom_model_name = '<?php echo $model; ?>';
        var custom_foreign_key = '<?php echo $foreignKey; ?>';
        var custom_counter_id = custom_model_name+custom_foreign_key+'Counter';
        if($("#"+custom_counter_id).length > 0) {
          $("#"+custom_counter_id).text(attachment_counter);
        }

        var container_id = 'ajaxAttachment';
        <?php if(!empty($container_id)){ ?>
            container_id = '<?php echo $container_id; ?>';
        <?php } ?>

		$('#AttachmentUpload').fileupload({
            url: '<?php echo $this->Html->url(array('controller' => 'Attachments', 'action' => 'save')); ?>/' + model + '/' + fkey + '?container_id='+container_id,
            success: function(response) {
                $('#'+container_id).html(response);
            }
		});

        $('#AddAttachmentButton').click(function(){
            var model = $(this).attr('data-model');
            var fkey = $(this).attr('data-fkey');
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'Attachments', 'action' => 'save')); ?>/' + model + '/' + fkey + '?container_id='+container_id,
                success: function(response) {
                    $('#'+container_id).html(response);
                }
            });
        });
        
        $('.DeleteAttachmentButton').click(function(){
            if(confirm('Da li ste sigurni da zelite da izbrisete prilog?')) {
            	var model = $(this).attr('data-model');
                var fkey = $(this).attr('data-fkey');
                var id = $(this).attr('data-id');
                var callback = 'afterDeleteAttachment';
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'Attachments', 'action' => 'delete')); ?>/' + model + '/' + fkey + '/' + id + '/' + callback + '?container_id='+container_id,
                    success: function(response) {
                        $('#'+container_id).html(response);
                    }
                });
            }
        });
    });
</script>
<?php echo $this->Js->writeBuffer(); ?>