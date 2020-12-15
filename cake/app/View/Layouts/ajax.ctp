<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php $auth_msg = $this->Session->flash('auth'); ?>
<?php if(!empty($auth_msg)){ ?>
<div id="alert_auth" class="notice error margin20">
<i class="icon-remove-sign icon-large"></i>
<?php echo $auth_msg; ?>
<a class="icon-remove" href="#close"></a>
</div>
<?php } ?>
<?php echo $this->fetch('content'); ?>
<script type="text/javascript">
$(document).ready(function()
{
	setTimeout(function() {
	$(".alert").slideUp(500);
	}, 3000);
	setTimeout(function() {
	$("#alert_auth").slideUp(500);
	}, 5000);
});

//Check container load
$("#container").ready(function(){
	$('a.ajax').click(function(e) {
		href = $(this).attr("href");
		//Check if pushState is supported
		if(history.pushState){
		    // HISTORY.PUSHSTATE
		    history.pushState('', 'New URL: '+href, href);                                
		    e.preventDefault();
		}
	});

	//Set skip history push state flag
	var skip_history_push_state = false;
	<?php if(!empty($skip_history_push_state)){ ?>
		skip_history_push_state = true;
	<?php } ?>

	//Check if pushState is supported
	if(history.pushState && !skip_history_push_state){
	    // THIS EVENT MAKES SURE THAT THE BACK/FORWARD BUTTONS WORK AS WELL
	    window.onpopstate = function(event) {
			$(".submit_loader").show();
			$.ajax({
				dataType:"html",
				url:location.pathname,
				success:function (data, textStatus) {
					$("#container").html(data);
				}
			});
	    };              
	}
});

</script>