/**
 * INSPINIA - Responsive Admin Theme
 * 2.0
 *
 * Custom scripts
 */

$(document).ready(function () {

    // Append config box / Only for demo purpose
    /*$.get("views/skin-config.html", function (data) {
        $('body').append(data);
    });*/

    // Full height of sidebar
    function fix_height() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

        var navbarHeigh = $('nav.navbar-default').height();
        var wrapperHeigh = $('#page-wrapper').height();

        if(navbarHeigh > wrapperHeigh){
            $('#page-wrapper').css("min-height", navbarHeigh + "px");
        }

        if(navbarHeigh < wrapperHeigh){
            $('#page-wrapper').css("min-height", $(window).height()  + "px");
        }

    }


    $(window).bind("load resize scroll", function() {
        if(!$("body").hasClass('body-small')) {
            fix_height();
        }
    })

    // Move right sidebar top after scroll
    $(window).scroll(function(){
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav') ) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });


    setTimeout(function(){
        fix_height();
    })

    /*$("body").addClass('mini-navbar');
    SmoothlyMenu();*/




	function SmoothlyMenu() {
		if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
			// Hide menu in order to smoothly turn on when maximize menu
			$('#side-menu').hide();
			// For smoothly turn on menu
			setTimeout(
					function () {
						$('#side-menu').fadeIn(500);
					}, 100);
		} else if ($('body').hasClass('fixed-sidebar')){
			$('#side-menu').hide();
			setTimeout(
					function () {
						$('#side-menu').fadeIn(500);
					}, 300);
		} else {
			// Remove all inline style from jquery fadeIn function to reset menu state
			$('#side-menu').removeAttr('style');
		}
	}


	// Minimalize menu when screen is less than 768px
	$(function() {
		$(window).bind("load resize", function() {
			if ($(this).width() < 769) {
				$('body').addClass('body-small')
			} else {
				$('body').removeClass('body-small')
			}
		})
	})



});