<?php echo $this->Html->css('fileupload/jquery.fileupload.css'); ?>
<?php echo $this->Html->script('fileupload/jquery.ui.widget.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.iframe-transport.js'); ?>
<?php echo $this->Html->script('fileupload/jquery.fileupload.js'); ?>
<?php echo $this->Html->script('fileupload/user.js?ver='.filemtime(WWW_ROOT . "/js/fileupload/user.js")); ?>
<ul class="breadcrumbs margin20">
    <li><?php echo $this->Html->link('Pocetna', '/'); ?></li>
    <?php if($this->data['User']['id'] != $this->Session->read('Auth.User.id')){ ?>
    <li><?php echo $this->Js->link('Users', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");')); ?></li>
    <?php } ?>
    <li class="last"><a href="" onclick="return false">Izmena</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Edit user <?php echo (" (" . $this->data['User']['username'].")"); ?></h3></div>
    <div class="add_and_search">
        <div class="search"><?php echo $this->Form->create('User', array('action' => 'search')); ?>
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

<div class="content_data">
<div class="formular">
<?php echo $this->Form->create('User', array('type' => 'post')); ?>

    <?php echo $this->Form->input('id'); ?>
        <div class="content_text_input">
            <label for="UserFirstName"> Ime</label>
            <?php echo $this->Form->input('first_name', array('class' => 'col_9', 'placeholder' => 'First name', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserLastName"> Prezime</label>
            <?php echo $this->Form->input('last_name', array('class' => 'col_9', 'placeholder' => 'Last name', 'required' => false, 'label' => false)); ?>
        </div>
        <?php if($auth_user['group_id'] == 1){ ?>
        <div class="content_text_input">
            <label for="UserUsername"> Korisnicko ime</label>
            <?php echo $this->Form->input('username', array('class' => 'col_9', 'placeholder' => 'Username', 'required' => false, 'label' => false)); ?>
        </div>
        <?php } ?>
        <div class="content_text_input">
            <label for="UserPassword"> Lozinka</label>
            <?php echo $this->Form->input('password', array('class' => 'col_9', 'placeholder' => 'Password', 'required' => false, 'value' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserEmail"> E-mail</label>
            <?php echo $this->Form->input('email', array('class' => 'col_9', 'placeholder' => 'E-mail', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserActive"> Aktivan</label>
            <?php echo $this->Form->input('active', array('class' => 'col_9', 'placeholder' => 'E-mail', 'required' => false, 'label' => false)); ?>
        </div>
        <?php if($auth_user['group_id'] == 1){ ?>
        <div class="content_text_input">
            <label for="UserGroupId"> Group</label>
            <?php echo $this->Form->input('group_id', array('options' => $groups, 'empty' => '(Choose group)', 'class'=>'col_9', 'required' => false, 'label' => false)); ?>
        </div>
        <?php } ?>  
        <!-- AVATAR -->
        <div class="content_text_input clearfix">        
            <div class="col_3">
                <?php echo $this->Form->label('avatar', 'Avatar Korisnika'); ?>
            </div>
            <div class="col_9">        
            <span class="btn btn-success fileinput-button">
                <span>Promeni</span>                    
                <?php 
                    echo $this->Form->input('avatar_link', array(
                        'label' => false, 'class' => 'hide', 'div' => false, 'error' => false
                    )); 
                ?>
                <?php echo $this->Form->input('avatar', array('label' => false, 'type' => 'file', 'div' => false)); ?>
            </span>      
            </div>  
            <div class="clear"></div>        
            <div class="col_9 center holder">
                <?php if(empty($this->request->data['User']['avatar_link'])){ ?>         
                    <a href="#" class="button small" title="Ukloni avatar" style="float:left; display:none;" id="remove_avatar">
                        <i class="icon-remove" style="color:red;"></i>
                    </a>                
                    <?php 
                        echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'id' => 'avatar')); 
                    ?>
                <?php }else{ ?>
                    <a href="#" class="button small" title="Ukloni avatar" style="float:left;" id="remove_avatar">
                        <i class="icon-remove" style="color:red;"></i>
                    </a>
                    <?php 
                        echo $this->Html->image(str_replace("\\", "/", $this->request->data['User']['avatar_link']), 
                            array('alt' => $this->request->data['User']['username'], 'id' => 'avatar')
                        ); 
                    ?>
                <?php } ?>   
            </div>                             
            <div class="col_9">
                <div id="avatar_progress" class="progress hide">
                    <div class="progress-bar progress-bar-success"></div>
                </div>  
                <p id="avatar_upload_info" style="margin:2px 0 0 0;">
                    <?php if(empty($this->data['User']['avatar_link'])){ ?>
                        <?php if ($this->Form->isFieldError('User.avatar_link')){ ?>                              
                            <?php 
                                $error_msg = $this->Form->error('User.avatar_link', array('avatar_link-rule' => 'Fajl ne postoji!'),
                                array('wrap' => false, 'class' => false));
                            ?>
                        <?php } ?>                 
                        <?php if(!empty($error_msg)){ ?><span style="color:red;"><?php echo $error_msg; ?></span><?php } ?>
                    <?php } ?>
                </p>                      
            </div>
        </div>
        <div class="clear"></div>
        <!-- SIGNATURE -->
        <div class="content_text_input clearfix">        
            <div class="col_3">
                <?php echo $this->Form->label('signature', 'Potpis Korisnika'); ?>
            </div>
            <div class="col_9">        
            <span class="btn btn-success fileinput-button">
                <span>Promeni</span>                    
                <?php 
                    echo $this->Form->input('signature_link', array(
                        'label' => false, 'class' => 'hide', 'div' => false, 'error' => false
                    )); 
                ?>
                <?php echo $this->Form->input('signature', array('label' => false, 'type' => 'file', 'div' => false)); ?>                      
            </span>      
            </div>  
            <div class="clear"></div>        
            <div class="col_9 center holder">
                <?php if(empty($this->request->data['User']['signature_link'])){ ?>
                    <a href="#" class="button small" title="Ukloni potpis" style="float:left; display:none;" id="remove_signature">
                        <i class="icon-remove" style="color:red;"></i>
                    </a>                
                    <?php 
                        echo $this->Html->image('users/signature_not_defined.png', array(
                            'alt' => 'Default Signature', 'id' => 'signature', 'style' => 'width:335px;'
                        ));
                    ?>
                <?php }else{ ?>
                    <a href="#" class="button small" title="Ukloni potpis" style="float:left;" id="remove_signature">
                        <i class="icon-remove" style="color:red;"></i>
                    </a>                
                    <?php 
                        echo $this->Html->image(str_replace("\\", "/", $this->request->data['User']['signature_link']), array(
                            'alt' => $this->request->data['User']['username'], 'id' => 'signature', 'style' => 'width:335px;'
                        ));
                    ?>
                <?php } ?>   
            </div>                             
            <div class="col_9">
                <div id="signature_progress" class="progress hide">
                    <div class="progress-bar progress-bar-success"></div>
                </div>  
                <p id="signature_upload_info" style="margin:2px 0 0 0;">
                    <?php if(empty($this->data['User']['signature_link'])){ ?>
                        <?php if ($this->Form->isFieldError('User.signature_link')){ ?>                              
                            <?php 
                                $error_msg = $this->Form->error('User.signature_link', array(
                                    'signature_link-rule' => 'Fajl ne postoji!'), array('wrap' => false, 'class' => false
                                )); 
                            ?>
                        <?php } ?>                 
                        <?php if(!empty($error_msg)){ ?><span style="color:red;"><?php echo $error_msg; ?></span><?php } ?>
                    <?php } ?>
                </p>
            </div>
        </div>
        <div class="col_9" style="border:1px solid #ccc; background-color:#eee; padding:12px;">
            <p><strong>Podržani tipovi fajlova:</strong> *.JPG, *.PNG, *.BMP</p>
            <?php 
                $max_upload = (int)(ini_get('upload_max_filesize'));
                $max_post = (int)(ini_get('post_max_size'));
                $memory_limit = (int)(ini_get('memory_limit'));
                $upload_mb = min($max_upload, $max_post, $memory_limit);
            ?>
            <?php if($upload_mb >= 0){ ?>
            <p><strong>Najveća veličina fajla:</strong> <?php echo $upload_mb; ?> MB</p>            
            <?php }else{ ?>
            <p><strong>Najveća veličina fajla:</strong> Neograničena</p>            
            <?php } ?>
        </div>     
        <div class="clear"></div>        
        <div class="buttons_box">
            <div class="button_box">
            <?php 
            if($this->data['User']['id'] != $this->Session->read('Auth.User.id')){
                if(empty($is_ajax)){
                    echo $this->Form->button('Submit', array(
                         'type' => 'submit', 
                         'div' => false, 
                         'class' => "button blue", 
                         'style' => "margin:20px 0 20px 0;"
                    ));
                }else{
                    echo $this->Js->submit('Submit', array(
                        'update' => '#container',
                        'div' => false,
                        'class' => "button blue",
                        'style' => "margin:20px 0 20px 0;",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - List users", "'.Router::url(array('controller' => 'users', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List users");'
                    ));
                }
            }
            else{
                if(empty($is_ajax)){
                    echo $this->Form->button('Submit', array(
                         'type' => 'submit', 
                         'div' => false, 
                         'class' => "button blue", 
                         'style' => "margin:20px 0 20px 0;"
                    ));
                }else{
                    echo $this->Js->submit('Sacuvaj', array(
                        'update' => '#container',
                        'div' => false,
                        'class' => "button blue",
                        'style' => "margin:20px 0 20px 0;",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - List users", "'.Router::url(array('controller' => 'pages', 'action' => 'display', 'home')).'"); $(document).attr("title", "MikroERP - List users");'
                    ));
                }

            }
            ?>
            </div>
            <div class="button_box margin5_left">
            <?php
                if(empty($is_ajax)){
                    echo $this->Html->link('Odustani', '/', array(
                        'class' => 'button', 
                        'div' => false,
                        'style' => 'margin:20px 0 20px 0;'));
                }else{
                    if($this->data['User']['id'] != $this->Session->read('Auth.User.id')){
                    echo $this->Js->link('Odustani', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false,'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");'));
                    }else{
                        echo $this->Html->link('Odustani', array('controller' => 'pages', 'action' => 'display', 'home'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'));
                    }

                }
            ?>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>

</div>
</div>
<div class="clear"></div>
<div class="submit_loader">
  <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
  <h2>Molimo sacekajte...</h2>
</div>
<script>
$('#container').ready(function(){
    $(".submit_loader").hide();
    <?php if(!empty($isAjax)){ ?>
        var url = "<?php echo Router::url(array('controller' => 'Users', 'action' => 'edit', $this->request->data['User']['id'])); ?>";
        history.pushState(null, "MikroERP - Korisnici", url); $(document).attr("title", "MikroERP - Korisnici");
    <?php } ?>
});
</script>