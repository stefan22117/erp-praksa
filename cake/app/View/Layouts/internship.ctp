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
	<?php 
		//Set default class
		$class = 'testing';
		//Check if app is in production
		$debug = Configure::read('debug');
		if(empty($debug)){
			//Set production class
			$class = 'production';
		}
	?>
	<div class="<?php echo $class; ?>">
		<div id='frame'>
			<div id="menu-bar">
				<h1 style="">MIKRO<span>ERP</span></h1>
			</div>
			<div id="erp-menu">				
				<style>
					#frame {
						position: relative;
					}					
					.menu li {
						font-size: 15px;
					}
				</style>
				<?php if(!empty($intern)){ ?>
					<ul class="menu" style="border-left:0; border-right:0;">
						<li class="right">
							<?php 
								echo $this->Html->link('<i class="icon-signout"></i> '.__d('intern', 'Odjava'), array(
										'controller' => 'ImInterns', 'action' => 'internship', 
										'?' => array('logout' => 1)
									), 
									array('escape' => false)
								); 
							?>
						</li>
					</ul>
				<?php }else{ ?>
					<ul class="menu menu_no_border">
						<center><li><a href="#" onclick="return false" class="cursor"><b>mikroERP</b></a></li></center>
					</ul>    
				<?php } ?>
			</div>
		  <?php $flash_msg = $this->Session->flash(); ?>
		  <?php if(!empty($flash_msg)){ ?>
		    <?php echo $flash_msg; ?>
		  <?php } ?>
		  <?php $auth_msg = $this->Session->flash('auth'); ?>
		  <?php if(!empty($auth_msg)){ ?>
			<div id="alert_auth" class="notice error margin20">
				<i class="icon-remove-sign icon-large"></i>
				<?php echo $auth_msg; ?>
				<a class="icon-remove" href="#close"></a>
			</div>
			<script type="text/javascript">
			$(document).ready(function(){
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
				<span class="grey"><?php echo __d('intern', "Ono što je teško uradićemo sad, a za ono što je nemoguće trebaće nam neko vreme."); ?></span>
			</div>
			<?php echo $this->Js->writeBuffer(); ?>
			<div onclick="topFunction()" id="backToTopBtn" title="<?php echo __d('intern', "Idi na vrh strane"); ?>"></div>
			<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
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
	$('#menu-bar h1').css('width', $(window).width());
</script>