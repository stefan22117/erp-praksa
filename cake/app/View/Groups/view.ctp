<ul class="breadcrumbs">
	<li><?php echo $this->Html->link('Početna', '/'); ?></li>
	<li><?php echo $this->Html->link('Groups', array('controller' => 'Groups', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Pregled'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Članovi grupe'); ?> <?php echo $group_name; ?></h3></div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">        
            <li class="first">
				<?php
					$mailingList = "";
					foreach ($users as $user) {
						if($user['User']['active']) {
							$mailingList .= $user['User']['email'] . '; ';
						}
					}
                	echo $this->Html->link('<i class="icon-envelope" style="color :#669E00"></i> '.__('Posalji grupni e-mail'), 'mailto:'.$mailingList, array('escape' => false));
				?>
            </li>
        </ul>
    </div>
</div>

<div class="content_data tight">
	<table>
		<tr>
			<th></th><th><?php echo __('Korisnik'); ?></th><th><?php echo __('Korisničko ime'); ?></th><th><?php echo __('E-mail'); ?></th>
		</tr>
	<?php foreach($users as $user) { ?>
		<?php if($user['User']['active']){ ?>
		<tr bgcolor="99FFCC">
		<?php }else{ ?>
		<tr bgcolor="EFEFEF">
		<?php } ?>
			<td><?php if(empty($user['User']['avatar_link'])){
					echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:50px; height:50px;'));
				}else{
					echo $this->Html->image(str_replace("\\", "/", $user['User']['avatar_link']), array('style' => 'width:50px; height:50px;'));
				}
				?>
            </td>
			<td><?php echo $user['User']['full_name']; ?> <?php echo $this->Html->link('<icon class="icon-edit"></icon>', array('controller' => 'Users', 'action' => 'edit', $user['User']['id']), array('escape' => false)); ?></td>
			<td><?php echo $user['User']['username']; ?></td>
			<td><?php echo $user['User']['email']; ?></td>
		</tr>
	<?php } ?>
	</table>
</div>