<div class="center paginator">
	<?php echo $this->Paginator->first(__('prva strana'), array());?>
	<?php if($this->Paginator->hasPrev()){ ?>
	<?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
	<?php echo $this->Paginator->numbers();?>
	<?php if($this->Paginator->hasNext()){ ?>
	<?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
	<?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>
</div>