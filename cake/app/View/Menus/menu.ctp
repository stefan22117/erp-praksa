<ul class="menu menu_no_border">
<?php if(!empty($auth_user)) { ?>
<?php foreach ($menus_parent_app as $parent) { ?>
	<?php //echo '<li><a href="#" onclick="return false;">'.__d('menu', $parent['Menu']['full_title']).'</a>'; ?>  
	<li><a href="#" onclick="return false;"><?php echo $parent['Menu']['icon'].' '.__d('menu', $parent['Menu']['title']); ?></a>
	<ul>	
	<?php foreach ($parent['Menu']['children'] as $child) { ?>
	    <?php 
	        $show = false;
	        if(!empty($child['Menu']['allowed'])){
	          if(!empty($child['Menu']['group_id']) && $child['Menu']['group_id'] == $auth_user['group_id']) $show = true;
	          if(!empty($child['Menu']['user_id']) && $child['Menu']['user_id'] == $auth_user['id']) $show = true;
	        }
	    ?>	
	    <?php if($show){ ?>
		<li>
			<?php echo $this->Html->link($child['Menu']['icon'].' '.__d('menu', $child['Menu']['title']), array('controller' => $child['Menu']['controller'], 'action' => $child['Menu']['action']), array('escape' => false)); ?>
			<?php if(!empty($child['Menu']['elements'])){ ?>
			<ul>
				<?php foreach ($child['Menu']['elements'] as $element) { ?>
					<li><?php echo $this->Html->link(__d('menu', $element['MenuElement']['title']), array('controller' => $element['MenuElement']['controller'], 'action' => $element['MenuElement']['action']), array('escape' => false)); ?></li>
				<?php } ?>
			</ul>
			<?php } ?>    
		</li>
		<?php } ?>
	<?php } ?>

	</ul>
	</li>
<?php } ?>
	<li class="right mainLi"><span><?php echo $this->element('../Users/logout'); ?></span></li>
	<li class="right usernameList"><?php echo $auth_user['username']; ?></li>
<?php }else{ ?>
	<center><li><a href="#" onclick="return false" class="cursor"><b>mikroERP</b></a></li></center>
<?php } ?>
</ul>