<div class="margin20">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link('Home', '/'); ?></li>
        <li><a href="">Users</a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page margin20_left"><h3>Users</h3></div>
    <div class="add_and_search margin20_right margin20_top">
        <?php echo $this->Html->link('<i class="icon-plus-sign"></i> Add user', array('controller' => 'Users', 'action' => 'add'), array('class' => 'button small blue adduser', 'escape' => false));?>
        <div class="search">Search: <input id="text1" type="text" placeholder="Enter user data" /> <button class="small"><i class="icon-search"></i> Search</button></div>
    </div>
</div>

<div class='content_data'>
<div class="margin20">
    <table cellspaing="0" cellpadding="0">
    <thead>
        <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Group</th>
        <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
        <tr>
            <td>
                <?php echo $user['User']['username']; ?>
            </td><td>
                <?php echo $user['User']['first_name'] . " " . $user['User']['last_name']; ?>
            </td><td>
                <?php echo $user['User']['email']; ?>
            </td><td>
                <?php echo $user['Group']['name']; ?>
            </td><td class='right'>
            <ul class="button-bar">
                <li><?php echo $this->Js->link('<i class="icon-user"></i>Set permission', array('action' => 'userpermissions', $user['User']['id']), array('update' => '#update_container', 'buffer' => false, 'escape' => false)); ?></li>

                <li><?php echo $this->Js->link('<i class="icon-pencil"></i>Edit', array('action' => 'edit', $user['User']['id']), array('update' => '#update_container', 'buffer' => false, 'escape' => false)); ?></li>

                <li><?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('action' => 'delete', $user['User']['id']), array('update' => '#user_container', 'escape' => false, 'buffer' => false, 'confirm' => "Are you sure you want to delete this user?")); ?></li>
            </ul>
                <?php //echo $this->Html->link('<span class="glyphicon glyphicon-edit"> edit</span>', array('action'=>'edit', $user['User']['id']), array('escape' => false)); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</div>
</div>
<?php echo $this->start('script'); ?>
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
</script>
<?php echo $this->end(); ?>