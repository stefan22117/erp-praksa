<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('html-kickstart-master/kickstart');
    	echo $this->Html->css('main');
    	echo $this->Html->css('jquery-ui');
    	echo $this->Html->css('timepicker');
    	echo $this->Html->css('select2');
    	echo $this->Html->script('jquery');
    	echo $this->Html->script('jquery-ui');
    	echo $this->Html->script('jquery-ui-timepicker-addon');
		echo $this->Html->script('html-kickstart-master/kickstart');
		echo $this->Html->script('number/jquery.number');
		echo $this->Html->script('format_prices');
		echo $this->Html->script('select2');
		echo $this->Html->script('select2_locale_rs');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
<!-- Showing only html -->
<?php echo $this->fetch('content'); ?>
</body>
</html>