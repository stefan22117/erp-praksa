<style type="text/css">
.loginform {
    margin-top: 0px;
}
.left {
    width: 1095px;
    margin: 50px auto;
}
.head-title {
    height: 65px;
}
.head-title img {
    width: 36px;
    float: left;
    margin: -4px 12px 0 0;
}
.main-info {
    margin-bottom: 20px;
}
ul.tabs li a{
    cursor: pointer;
}
.left {
    padding: 10px;
}
.tab-content {
    margin: 0;
    padding: 0;
    height: 0;
    border: none;
}
.holiday-type {
    margin-bottom: 15px;
    float: left;
}
.holiday-title {
    padding: 5px 0 5px 15px;
    font-size: 15px;
    font-weight: bold;
}
.worker {
    width: 215px;
    position: relative;
}
.worker-img {
    padding: 10px;
    float: left;
}
.worker-info {
    float: left;
}
.worker-name {
    width: 150px;
    margin: 20px 0px 0px 0px;
    font-size: 13px;
    white-space: nowrap;
}
.worker-name a {
    text-decoration: none;
    color: #000;
}
.worker-name a:hover {
    color: #4D99E0;
}
.info {
    color: #aaa;
    font-size: 10px;
    margin-top: -6px;
    width: 150px;
}
.bhat img {
    width: 25px;
    position: absolute;
    z-index: 999;
    left: 40px;
    top: -4px;
    transform: rotate(45deg);
}
#left {
    width: 660px;
    float: left;
}
#right {
    width: 410px;
    float: right;
}
#tasks {
    padding: 10px;
    border: 1px solid #eee;
    min-height: 500px;
}
#task-spinner {
    background: #eee;
    height: 500px;
    text-align: center;
    padding-top: 200px;
    border-radius: 5px;
}
#comment-spinner {
    height: 469px;
    background: #eee;
    text-align: center;
    padding-top: 200px;
}
</style>

<div id="container">

<?php if(isset($inventory_requests)){ ?>
    <?php foreach ($inventory_requests as $request) { ?>
        
    <div class="notice warning" style="width:95%; margin:10px;"><i class="icon-warning-sign icon-large"></i> 
    <?php 
        if ($request['InventoryApproveRequest']['status'] == 'on_hold') echo __('Na čekanju imate zahtev za odobrenje popisa '); 
        if ($request['InventoryApproveRequest']['verifying_status'] == 'on_hold') echo __('Na čekanju imate zahtev za knjiženje popisa '); 
    ?>
    <?php echo $this->Html->link($request['Inventory']['code'], array('controller' => 'InventoryApproveRequests', 'action' => 'index')); ?> 
        <a class="icon-remove" href="#close"></a></div>
    <?php } ?>
<?php } ?>
<?php $isERPFifthBirthday = false; ?>
<?php if(isset($iso_notification)){ ?>
<div class="notice success" style="width:95%; margin:10px;"><i class="icon-warning-sign icon-large"></i> 
<?php echo __('Na čekanju imate zahtev za odobrenje dokumenta '); ?>
<?php echo $this->Html->link($iso_notification['QmsDocument']['code'].' - '.$iso_notification['QmsDocumentVersion']['version'].' '.$iso_notification['QmsDocument']['name'], array('controller' => 'QmsDocumentVersions', 'action' => 'view', $iso_notification['QmsDocumentVersion']['id'])); ?> 
    <a class="icon-remove" href="#close"></a></div>
<?php } ?>
    <div class="loginform center">
        <?php 
        if(empty($auth_user)) {
            echo '<div style="margin-top: 100px">'.$this->element('../Users/login').'</div>'; ?>
        <script>
            $(document).ready(function() {
                var width = $(window).width();
                if (width < 1100) {
                    $('#frame, #container, .loginform, #footer').css('width', '100%');
                    $('#UserUsername, #UserPassword').removeClass('col_4');
                    $('.cake-sql-log').css('font-size', '8px');
                    $('#footer').css('padding', '10px');
                    $('.icon-reorder').hide();
                    $('#menu-bar h1').css('text-align', 'center');
                    $('#menu-edge').css('position', 'fixed');
                    $('#frame').css('padding-top', '50px');
                    $('.loginform').css('margin-top', '-60px');
                }         
            });
        </script>
        <?php } else { ?>
        <div class="left">
            <div class="head-title">
                <?php
                    echo $this->Html->link('<img src = "/img/users/avatars/mikroe-symbol-new.png">', '', array(
                        'escape' => false, 'title' => "MIKROE ERP"
                    ));
                ?>
                <h5><?php echo __('Dobro došli').', '. $this->Session->read('Auth.User.first_name'); ?></h5>
            </div>       
            <div class="main-info">
                <!-- Main info -->
                <table style="width: 550px; float: left; position: relative;">
                    <tr>
                        <td rowspan="3" width="150px">
                        <?php
                            if(empty($activeUser['User']['avatar_link'])) {
                                echo $this->Html->image('company/male.jpg', array('alt' => 'Default Avatar', 'style' => 'width:100px; height:100px;'));
                            } else {
                                echo '<img style="width:100px; height:100px;" src="/img/'.$activeUser['User']['avatar_link'].'"/>';
                            }
                        ?>
                        </td>
                        <th width="170px"><?php echo __('Korisničko ime'); ?></th>
                        <td><?php echo $activeUser['User']['username']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo __('Ime i prezime'); ?></th>
                        <td><?php echo $activeUser['User']['first_name'].' '.$activeUser['User']['last_name']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo __('E-mail adresa'); ?></th>
                        <td><?php echo $activeUser['User']['email']; ?></td>
                    </tr>
                </table>
                <div class="clear"></div>
            </div>
        <?php } ?>
    <div class="submit_loader">
        <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
        <h2>Molimo sačekajte...</h2>
    </div>
</div>

<fieldset id="change_pass" style="width:390px; height:auto;">
    <legend id="item_name"><?php echo __('Promena lozinke'); ?></legend>
    <table class="tight">
        <tr>
            <th><?php echo __('Stara lozinka'); ?></th>
            <th><?php echo $this->Form->input('old_password', array('type' => 'password', 'class' => 'col_9', 'placeholder' => __('Stara lozinka'), 'required' => false, 'label' => false)); ?></th>
        </tr>
        <tr>
            <th><?php echo __('Nova lozinka'); ?></th>
            <th><?php echo $this->Form->input('new_password', array('type' => 'password', 'class' => 'col_9', 'placeholder' => __('Nova lozinka'), 'required' => false, 'label' => false)); ?></th>
        </tr>
        <tr>
            <th><?php echo __('Ponovi novu lozinku'); ?></th>
            <th><?php echo $this->Form->input('repeat_new_password', array('type' => 'password', 'class' => 'col_9', 'placeholder' => __('Ponovi lozinku'), 'required' => false, 'label' => false)); ?></th>
        </tr>
    </table>
    <?php echo $this->Html->link('<i class="icon-save"></i> '. __('Sačuvaj'), array(), array('class' => 'button small blue adduser', 'escape' => false, 'onclick' => 'changePassword(); return false;')) ;?>
    <?php echo $this->Html->link('<i class="icon-remove-sign"></i> '. __('Odustani'), array(), array('class' => 'button small grey adduser', 'escape' => false, 'onclick' => 'closePopUp(); return false;')) ;?>
</fieldset>

<script type="text/javascript">

$("#container").ready(function(){
    $(".submit_loader").hide();
});

$('#change_pass').popup({
    opacity: 0.3,
    transition: 'all 0.0s',
    scrolllock: true
});

function changePassword(){
    $.ajax({
        dataType: "json",
        type: "POST",
        evalScripts: true,
        data: ({ old_password:$("#old_password").val(), new_password:$("#new_password").val(), repeat_new_password:$("#repeat_new_password").val() }),
        url: "<?php echo Router::url(array('controller' => 'Users', 'action' => 'changePassword')) ?>",
        success: function (data){
            //alert(JSON.stringify(data.message));
            if(data.success){
                $('#my_popup').html('<div class="notice success margin20"><i class="icon-ok icon-large"></i>'+data.message+'</div>');
                $('#change_pass').popup('hide');
            }
            else{
                $('#my_popup').html('<div class="notice error margin20"><i class="icon-remove-sign icon-large"></i>'+data.message+'</div>');
            }
            $('#my_popup').popup('show');
            setTimeout(function(){ $('#my_popup').popup('hide'); }, 3000);
        },
        error: function(xhr){
            var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
            $('#my_popup').html('<div class="notice success margin20"><i class="icon-remove-sign icon-large"></i>'+xhr.responseText+'</div>');
            setTimeout(function(){ $('#my_popup').popup('hide'); }, 3000);
        }
    });
}

function closePopUp(){
    $("#change_pass").popup("hide");
    $("#old_password").val("");
    $("#new_password").val("");
    $("#repeat_new_password").val("");
}

/**
    * read notification
*/
function readNotification(id){
    $.ajax({
        dataType: "json",
        type: "POST",
        evalScripts: true,
        data: ({id:id}),
        url: "<?php echo Router::url(array('controller' => 'Notifications', 'action' => 'readNotification')); ?>",
        success: function(data){
            if (data['success']){
                $.ambiance({
                    message: data['success'],
                    type: 'success',
                    timeout: 4
                });
            }else{
                $.ambiance({
                    message: data['error'],
                    type: 'error',
                    timeout: 4
                });
            }
        },
        error: function(xhr){
                $.ambiance({
                    message: "An error occured: " + xhr.status + " " + xhr.statusText,
                    type: 'error',
                    timeout: 4
                });
        }
    });
}//~!

    $(document).ready(function(){                    
        // Code for tab switching
        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
            $('.tab-content').css('display', 'none');
            $("#"+tab_id).css('display', 'block');
        });
    });
</script>