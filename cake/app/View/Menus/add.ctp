<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Menus', array('controller' => 'Menus', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List menus", this.url); $(document).attr("title", "MikroERP - List menus");')); ?></li>
    <li class="last"><a href="" onclick="return false">Add</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add Menu</h3></div>
    <div class="add_and_search">
        
        <div class="search"><?php echo $this->Form->create('Menu', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search', 'placeholder' => 'Enter search data'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'Menus', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
</div>


<div class="content_data">
<div class="formular">

<?php echo $this->Form->create('Menu'); ?>

<?php foreach($controllers as $controller => $methods):        	
		$arrController[$controller] = $controller;
	       endforeach;    ?>

    <div class="content_text_input">
        <label for="MenuParentId"> Select parent menu</label>
	    <?php echo $this->Form->input('parent_id', array('label' => false, 'options' => $menuparents, 'class'=>'col_9', 'placeholder' => 'Parent', 'empty' => '(Choose parent)')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuTitle"> Menu title</label>
	    <?php echo $this->Form->input('title', array('label' => false, 'class' => 'col_9', 'placeholder' => 'Title')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuController"> Select controller</label>
	    <?php echo $this->Form->input('controller', array('label' => false, 'options' => $arrController, 'empty' => '(Choose Controller)', 'class' => 'col_9','disabled' => 'disabled')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuAction"> Select action</label>
	    <?php echo $this->Form->input('action', array('label' => false, 'options' => 0, 'empty' => '(Choose Method)', 'class' => 'col_9', 'disabled' => 'disabled')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuGroupId"> Select group</label>
	    <?php echo $this->Form->input('group_id', array('label' => false, 'class' => 'col_9', 'placeholder' => 'Group', 'empty' => '(Choose group)')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuUserId"> Select user</label>
	    <?php echo $this->Form->input('user_id', array('label' => false, 'class' => 'col_9', 'placeholder' => 'User', 'empty' => '(Choose user)')); ?>
	</div>

	<div class="content_text_input">
        <label for="MenuIcon"> Icon</label>
	    <?php echo $this->Form->input('icon', array('label' => false, 'class' => 'col_9', 'placeholder' => 'Icon class')); ?>
	</div>
    
	<div class="content_text_input">
		<?php
		$options = array('1' => 'Allowed', '0' => 'Deny');
		$attributes = array('legend' => false, 'separator'=>' &nbsp;&nbsp;', 'error' => false);
		echo $this->Form->radio('allowed', $options, $attributes);
		?>
        <?php if($this->Form->isFieldError('allowed')){ ?>
            <?php echo $this->Form->error('allowed'); ?>
        <?php } ?>
	</div>

	<div class="buttons_box">
        <div class="button_box">
        <?php
            echo $this->Js->submit('Add menu', array(
                'update' => '#container',
                'div' => false,
                'class' => "button blue",
                'style' => "margin:20px 0 20px 0;",
                'buffer' => false,
                'before' => '$(".submit_loader").show();',
                'success' => 'history.pushState(null, "MikroERP - List menus", "'.Router::url(array('controller' => 'menus', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List menus");'
            ));?>
        </div>
        <div class="button_box margin5_left">
        <?php
            echo $this->Js->link('Cancel', array('controller' => 'Menus', 'action' => 'index'), array('update' => '#container', 'buffer' => false,'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Menus", this.url); $(document).attr("title", "MikroERP - Menus");'));
        ?>
        <?php echo $this->Form->end(); ?>
    	</div>
	</div>

</div>
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<?php echo $this->Html->script('user/menus/switcher'); ?>

<script type="text/javascript">
    function getActions(){
        $('#MenuAction').find('option').remove().end();
         $.ajax({
            dataType: "json",
            type: "POST",
            evalScripts: true,
            url: "<?php echo Router::url(array('controller' => 'menus', 'action' => 'getByController'));?>",
            data: ({controller:$("#MenuController").val()}),
            success: function (data, textStatus){
                var result = $.map(data, function(k, v) {
                    return [k];
                });
                var length = result.length,
                    element = null;
                if(length > 0){
                    $('#MenuAction').append($("<option>Choose Method</option>"));
                    for (var i = 0; i < length; i++) {
                      element = result[i];
                      $('#MenuAction').append($("<option></option>").attr("value",element).text(element));
                    }                
                    $('#MenuAction').prop('disabled', false);
                }else{
                    $('#MenuAction').prop('disabled', true);
                }
            }
        });        
    }
    $(document).ready(function() {
        $(".submit_loader").hide();
    	$("#MenuController").change(function() {
            getActions();
    	});		
        if($('#MenuController').val().length > 0){
            getActions();
        }

        $("#MenuParentId").change(function(){
            if($('#MenuParentId').val().length > 0)
            {
                $('#MenuController').prop('disabled', false);
                if($('#MenuController').val().length > 0){
                    $('#MenuAction').prop('disabled', false);
                }
            }
            else
            {
                $('#MenuController').prop('disabled', true);
                $('#MenuAction').prop('disabled', true);                
            }
        });
    });
</script> 
