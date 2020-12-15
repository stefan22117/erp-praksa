<style type="text/css">
input[class*="col_"], select[class*="col_"], label[class*="col_"] {
    border-color: #999;
}
.select2-drop { z-index: 999999; }
#margin20 { margin: 0px; }
</style>
<?php echo $this->Html->css('fileupload/jquery.fileupload.css'); ?>
<?php echo $this->Html->script('fileupload/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.iframe-transport.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.fileupload.js'); ?>
<div style="width: 98%;">
    <h4 style="float: left"><i class="icon-upload-alt" style="margin-right: 10px;"></i><?php echo __('Prilog'); ?></h4>
    <div style="float:right; margin: 10px 0 0 0;">
        <ul class="button-bar">
            <li class="first last">
                <?php echo $this->Html->link('<i class="icon-arrow-left" style="color:gray"></i> '.__('Nazad na pregled'), array(), array('escape' => false, 'onclick' => 'return false', 'id' => 'AttachmentBackButton', 'data-fkey' => $foreignKey, 'data-model' => $model)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>

    <!-- Messages -->
    <div id='alertAttachment'><?php echo $this->Session->flash(); ?></div>

    <fieldset style="margin:0;">
    	<?php echo $this->Form->create('Attachment', array('type' => 'file')); ?>
    	<?php
    		if($action == 'edit')
    		echo $this->Form->input('id');
    	?>
        <div class="col_12">
            <span class="red">*</span>
            <i style="font-size: 11px;">
                <sup>
                    <?php echo __('Polja označena zvezdicom su obavezna'); ?>
                </sup>
            </i>
        </div>
        <div class="clear"></div>
        <div class="col_12">
            <?php echo $this->Form->label('file_name', __('Naziv priloga')); ?>
            <i style="font-size: 11px;">
                <sup>
                    <?php echo __('Ukoliko se ne definiše, podrazumevani naziv je naziv datoteke koja se prilaže'); ?>
                </sup>
            </i>
            <?php echo $this->Form->input('file_name', array('type' => 'text', 'label' => false, 'class' => 'col_12', 'placeholder' => __('Unesite naziv priloga'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_12">
            <?php echo $this->Form->label('attachment_type_id', __('Tip priloga').' <span class="red">*</span>'); ?>
            <?php echo $this->Form->input('attachment_type_id', array('class' => 'col_12 dropdown', 'label' => false, 'style' => 'margin: 5px;', 'empty' => __('Izaberite tip priloga...'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_12">
            <?php echo $this->Form->label('date', __('Datum priloga').' ('.__('dokumenta').')'); ?>
            <?php echo $this->Form->input('date', array('type' => 'text', 'label' => false, 'class' => 'col_12', 'placeholder' => __('Izaberite datum priloga'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_12">
            <?php echo $this->Form->label('description', __('Opis')); ?>
            <?php echo $this->Form->input('description', array('type' => 'text', 'rows' => 3, 'label' => false, 'class' => 'col_12')); ?>
        </div>
        <div class="clear"></div>
        <div class="col_12">
            <span class="btn btn-success fileinput-button" style="margin: 25px 25px 25px 5px;">
                <span>Odaberi datoteku i sačuvaj kao prilog</span>
                <?php echo $this->Form->input('link', array('label' => false, 'class' => 'hide', 'div' => false, 'error' => false)); ?>
                <?php echo $this->Form->input('upload', array('label' => false, 'type' => 'file', 'div' => false, 'data-fkey' => $foreignKey, 'data-model' => $model)); ?>
            </span>
        </div>
        <div class="col_12">
            <p style="background: #ddd; padding: 10px 20px; font-size: 11px;">
            Podržani tipovi datoteka:
            <?php
                $types = implode(', ', array_keys($allowedFileTypes));
                echo $types;
            ?>
            </p>
        </div>
    	<div class="clear"></div>
    </fieldset>
</div>
<!-- Custom javascript -->
<script type="text/javascript">
	$(document).ready(function(){
        $("#AttachmentAttachmentTypeId").select2();
        $('input[type=text]').attr('autocomplete', 'off');

        $.datepicker.setDefaults($.datepicker.regional['rs']);
        $('#AttachmentDate').datepicker({
            dateFormat: "yy-mm-dd",
            showWeek: true,
            changeMonth: true,
            changeYear: true,
            yearRange: "-15:+0"
        });

        var model = $('#AttachmentUpload').attr('data-model');
        var fkey = $('#AttachmentUpload').attr('data-fkey');
        var id = $('#AttachmentId').val();
        var callback = 'afterSaveAttachment';

        var container_id = 'ajaxAttachment';
        <?php if(!empty($container_id)){ ?>
            container_id = '<?php echo $container_id; ?>';
        <?php } ?>

		$('#AttachmentUpload').fileupload({
            url: '<?php echo $this->Html->url(array('controller' => 'Attachments', 'action' => 'save')); ?>/' + model + '/' + fkey + '/' + id +'/' + callback + '?container_id='+container_id,
            success: function(response) {
                $('#'+container_id).html(response);
            }
		});
		$('#AttachmentBackButton').click(function(){
        	var model = $(this).attr('data-model');
            var fkey = $(this).attr('data-fkey');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'Attachments', 'action' => 'view')); ?>/' + model + '/' + fkey + '?container_id='+container_id,
                success: function(response) {
                    $('#'+container_id).html(response);
                }
            });
		});
        
        $('#ajaxAttachment').removeAttr('tabindex');
	});
</script>
