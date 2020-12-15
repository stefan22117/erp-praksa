<div class="loginform">
    <?php echo $this->Form->create('User', array('action' => 'login')); ?>
    <h4 class="form-signin-heading"><?php echo __('Prijavite se'); ?></h4>
    <p><?php echo $this->Form->input('username', array('label' => false, 'class' => 'col_4', 'required' => false, 'placeholder' => __('Korisničko ime'), 'div' => false)); ?></p>
    <p><?php echo $this->Form->input('password', array('label' => false, 'class' => 'col_4', 'required' => false, 'placeholder' => __('Lozinka'), 'div' => false)); ?></p>
    <p><?php $options = array('label' => __('Prijavi se'), 'class' => 'blue', 'div' => false); 
    echo $this->Form->end($options); ?></p>
<div id="capsLockNote" class="red bold" style="visibility:hidden"><?php echo __('Caps Lock je uključen.'); ?></div> 
</div>
<script language="Javascript">

$('#container').ready(function(){
	$('input[id=UserUsername]').focus();
	
	$('#UserPassword').keypress(function(e) { 
		var s = String.fromCharCode( e.which );
		if ( s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) {
			document.getElementById('capsLockNote').style.visibility = 'visible';
		}else{
			document.getElementById('capsLockNote').style.visibility = 'hidden';
		}
	});
});
</script>