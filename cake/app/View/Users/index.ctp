<?php 
$this->Paginator->options(array(
	'url' => array('action' => 'index'),
	'update' => '#container', 
	'evalScripts' => true,
	'before' => '$(".submit_loader").show();'
	)); ?>
<ul class="breadcrumbs margin20">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li class="last"><a href="" onclick="return false">Users</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Users</h3></div>
    <div class="add_and_search">
        <?php echo $this->Js->link('<i class="icon-plus-sign"></i> Add user', array('controller' => 'Users', 'action' => 'add'), array('update' => '#container', 'htmlAttributes' => array('class' => 'button small blue adduser', 'escape' => false), 'buffer' => false,'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add User", this.url); $(document).attr("title", "MikroERP - Add User");')) ;?>
        <div class="search"> <?php echo $this->Form->create('User', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' =>'Podaci za pretragu'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'users', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
</div>
<?php echo $this->element('paginator'); ?>
<div class='content_data'>
    <table>
    <thead>
        <tr>
        <th>Image</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Group</th>
        <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
        <?php if($user['User']['active']){ ?>
        <tr bgcolor="99FFCC">
        <?php }else{ ?>
        <tr bgcolor="EFEFEF">
        <?php } ?>
            <td>
                <?php if(empty($user['User']['avatar_link'])){
                    echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:50px; height:50px;'));
                }else{
                    echo $this->Html->image(str_replace("\\", "/", $user['User']['avatar_link']), array('style' => 'width:50px; height:50px;'));
                }
                ?>
            </td>
            <td>
                <?php echo $user['User']['username']; ?>
            </td><td>
                <?php echo $user['User']['first_name'] . " " . $user['User']['last_name']; ?>
            </td><td>
                <?php echo $user['User']['email']; ?>
            </td><td>
                <?php echo $user['Group']['name']; ?>
            </td>
            <td class='right'>
                <?php if(!empty($user['cake_session']['id'])){ ?>
                    <ul class="button-bar">
                        <li class="first last">
                            <?php 
                                echo $this->Html->link('<i class="icon-signout"></i>', 
                                    array('controller' => 'Users', 'action' => 'force_logout', $user['User']['id']),
                                    array('title' => __('User logout'), 'escape' => false),
                                    __("Are you sure you want to logout this user?")
                                );
                            ?>
                        </li>
                    </ul>                   
                <?php } ?>        
                <ul class="button-bar">
                    <li class="first">
                        <?php 
                            echo $this->Js->link('<i class="icon-edit"></i> Edit', array(
                                'action' => 'edit', $user['User']['id']
                            ), 
                            array(
                                'update' => '#container', 
                                'buffer' => false, 
                                'escape' => false, 
                                'before' => '$(".submit_loader").show();', 
                                'success' => 'history.pushState(null, "MikroERP - Edit User", this.url); $(document).attr("title", "MikroERP - Edit User");'
                            )); 
                        ?>
                    </li>
                    <li class="last">
                        <?php 
                            echo $this->Js->link('<i class="icon-trash"></i> Delete', array(
                                'action' => 'delete', $user['User']['id']
                            ),
                            array(
                                'update' => '#container',
                                'escape' => false,
                                'buffer' => false,
                                'before' => '$(".submit_loader").show();', 'confirm' => "Are you sure you want to delete this user?",
                            ));
                        ?>
                    </li>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</div>
<?php echo $this->element('paginator'); ?>
<div class="clear"></div>
<div class="submit_loader">
  <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
  <h2>Molimo sa??ekajte...</h2>
</div>
<?php echo $this->Js->writeBuffer(); ?>


<script type="text/javascript">
$(document).ready(function()
{
    $("#json_load").click(function() {
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (data) {
                $("#json_response").html('<pre>' + JSON.stringify(data, null, 4) + '</pre>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var error_msg = "Status: " + xhr.status + "Error: " + thrownError;
                alert(error_msg);
            }
        });         
        return false;
    });
}); 

$('#container').ready(function(){
	$(".submit_loader").hide();
	<?php if(!empty($isAjax)){ ?>
		var url = "<?php echo Router::url(array('controller' => 'Users', 'action' => 'index')); ?>";
		history.pushState(null, "MikroERP - Korisnici", url); $(document).attr("title", "MikroERP - Korisnici");
	<?php } ?>
});

</script>

