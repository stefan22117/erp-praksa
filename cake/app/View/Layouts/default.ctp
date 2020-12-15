<?php
/**
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

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('html-kickstart-master/kickstart');
    	echo $this->Html->css('main.css?ver='.filemtime(WWW_ROOT . "/css/main.css"));
    	echo $this->Html->css('jquery-ui');
    	echo $this->Html->css('timepicker');
	 	echo $this->Html->css('select2'); 
	 	echo $this->Html->css('treeview'); 
		 echo $this->Html->css('dropit');
		 echo $this->Html->css('edgemenu.css?ver='.filemtime(WWW_ROOT . "/css/edgemenu.css"));

    	echo $this->Html->script('jquery');
    	echo $this->Html->script('jquery-ui');
    	echo $this->Html->script('jquery-ui-timepicker-addon');
		echo $this->Html->script('html-kickstart-master/kickstart');
		echo $this->Html->script('number/jquery.number');
		echo $this->Html->script('format_prices');
		echo $this->Html->script('message_popup');
		echo $this->Html->script('datesqlformat');
		echo $this->Html->script('select2');
		echo $this->Html->script('select2_locale_rs');
		echo $this->Html->script('popup/jquery.popupoverlay');
		echo $this->Html->script('jscolor');
		echo $this->Html->script('dropit');
		echo $this->Html->script('edgemenu.js?ver='.filemtime(WWW_ROOT . "/js/edgemenu.js"));

		// Pop up notification
		echo $this->Html->css('ambiance'); 
    	echo $this->Html->script('ambiance');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		 
	?>
</head>
<body>
<?php $user_id = $this->Session->read('Auth.User.id'); ?>
<?php  if($user_id>0) { ?>
<?php echo $this->element("../Feedbacks/feedback");	?>
<?php } ?>
		<div id='frame'>
			<div id="menu-bar">
				<h1 style="">MIKRO<span>ERP</span>
				<?php if($user_id): ?>
				<div style="font-size: 18px; float: right; transform: scaleY(1.6); margin: -1px -30px;"><?php echo $this->Html->link('<i class="icon-bell"></i>', array('controller' => 'Notifications', 'action' => 'index'), array('escape' => false, 'onclick' => 'return false', 'id' => 'urNotification')) ?><div id="unreadCount" style="position: relative; background: #a00; color: #fff; padding: 0px 5px; border-radius: 20px; top: -15px; left: 9px; font-size: 11px; text-align: center; line-height: 18px; margin-bottom: -21px;"><?php echo $unreadNotificationCount; ?></div></div>
				<?php endif; ?>
				</h1>
			</div>
			<?php if (isset($unreadNotificationCount) && !$unreadNotificationCount): ?>
			<style>#unreadCount{display:none;}</style>
			<?php endif; ?>
			<div id="erp-menu">
				<?php echo $this->element("../MenuItems/menu");	?>
			</div>
		  <?php $flash_msg = $this->Session->flash(); ?>
		  <?php if(!empty($flash_msg)){ ?>
		    <?php echo $flash_msg; ?>
		  <?php } ?>
			  <?php echo $this->element("../Notifications/notificationList");	?>
		  <?php $auth_msg = $this->Session->flash('auth'); ?>
		  <?php if(!empty($auth_msg)){ ?>
		  <div id="alert_auth" class="notice error margin20">
		  <i class="icon-remove-sign icon-large"></i>
		  <?php echo $auth_msg; ?>
		  <a class="icon-remove" href="#close"></a>
		  </div>

		  <script type="text/javascript">
		  $(document).ready(function()
		  {
		  setTimeout(function() {
		  $(".alert").slideUp(500);
		  }, 2000);
		  setTimeout(function() {
		  $("#alert_auth").slideUp(500);
		  }, 2000);
		  });
		  </script>
		  <?php } ?>
		<div id="container">
			<div id="message-popup-place"></div>
			    <?php echo $this->fetch('content'); ?>
		</div>
			<div id="footer">
				<!-- 2013.  &#169; mikroElektronika -->
				Copyright &#169; 2013 - <?php echo date('Y'); ?>. MikroElektronika<br/>
				<span class="grey"><?php echo __("Ono što je teško uradićemo sad, a za ono što je nemoguće trebaće nam neko vreme."); ?></span>
			</div>
		<?php echo $this->Js->writeBuffer(); ?>

		<div onclick="topFunction()" id="backToTopBtn" title="Idi na vrh strane"></div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
<script type="text/javascript">
  $('#message-popup-place').popup({
	    opacity: 0.3,
	    transition: 'all 0.3s'
	});
	// add a handler for the double click
	$('.click_once').click( function(event) {					    				    
		//Check if class disabled
		if(!$(this).hasClass('disabled')){
			//Add disabled class
			$(this).addClass('disabled');
		}else{
			// prevent default action of the link - this is not really necessary as the link has no "href"-attribute
			event.preventDefault();
		}    
	});

	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function() {scrollFunction()};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("backToTopBtn").style.display = "block";
		} else {
			document.getElementById("backToTopBtn").style.display = "none";
		}
	}

	// When the user clicks on the button, scroll to the top of the document
	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}

	$(document).mouseup(function(e) {
		var container = $("#notification");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			$('#notificationList').hide();
		}
		var container = $("#urNotification");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			$('#notificationListAlt').hide();
		}
	});

	$('#menu-bar h1').css('width', $(window).width());
</script>