$(document).ready(function() {
	var width = $(window).width();
	if (width < 1100){
		$('#erp-menu').addClass('menu-edge')
		$('#erp-menu ul').removeClass('menu');
		$('#erp-menu ul').removeClass('menu_no_border');
		$('#erp-menu ul').addClass('edge-nav');

		var menuEdge 	= $('[class^="menu-edge"]');
		var gClass 		= 'grid-menu-edge';
		var classes 	= [
						'grid-menu-edge',
						'grid-menu-edge-top',
						'grid-menu-edge-right',
						'grid-menu-edge-bottom'	
						];
		
		// int setup
		menuEdge.wrapInner('<div class="menu-content"></div>')
		.append('<i class="icon-reorder"></i>');
		
		menuEdge.on('click', function(e){
			e.stopPropagation();
		});
		
		menuEdge.find('.icon-reorder').on('click', function(e){
			e.stopPropagation();
			
			// toggle the menu open or closed
			$(this).parent().toggleClass('open');
			$('.menu-content').css('left', '0');
			
			// hide the other menus
			menuEdge.not($(this).parent()).removeClass('open');
			
			// append non-scrolling to the body
			if($(this).parent().hasClass('open')){
				$('html,body').addClass('noscroll');
				$('#feedback').hide();
			}else{
				$('html,body').removeClass('noscroll');
				$('#feedback').show();
				$('.menu-content').css('left', '-30px');
			}

			menuEdge.find('.edge-nav li').one('click', function(e){
				$(this).find('ul:first').removeAttr('style');
			});

			// close the submenus
			closeSubMenus();
			
			// find what direction we are opening the menu
			gClass = 'grid-'+$(this).parent().attr('class');
			$.each(classes, function(index){
				if(gClass != classes[index]){
					$('.grid').removeClass(classes[index]);
				}
			});
			$('.grid').toggleClass(gClass);
			
		});
		
		function closeSubMenus(){
			menuEdge.find('.edge-nav ul').slideUp();
		}
		
		// click outside menu - hide the menu
		$(document).on('click', function(e){
			$('.menu-content').css('left', '-30px');
			menuEdge.removeClass('open');
			$('html,body').removeClass('noscroll');
			closeSubMenus();
			$.each(classes, function(index){
				$('.grid').removeClass(classes[index]);
			});
			$('#feedback').show();
		});
		
		// click a submenu item with children
		// expand collapse the submenu
		menuEdge.find('.edge-nav li').on('click', function(e){
			e.stopPropagation();
			$(this).find('ul:first').slideToggle();
		});

		// prevent link on arrow
		menuEdge.find('.edge-nav li a .arrow').on('click', function(e){
			e.preventDefault();
		});

	} else {
		$('#top-bar').removeClass('top-bar');
	}
	$('.edge-nav li.last').removeAttr('style');
	$('#erp-menu').show();
});
