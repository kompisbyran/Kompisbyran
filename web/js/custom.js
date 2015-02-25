
/* Menu Slide JS  */

$(document).ready(function(){
  $(".menu-btn").on('click',function(e){
	  e.preventDefault();
		
		//Check this block is open or not..
	  if(!$(this).prev().hasClass("open")) {
		$(".header").slideDown(400);
		$(".header").addClass("open");
		$(this).find("i").removeClass().addClass("fa fa-chevron-up");
	  }
	  
	  else if($(this).prev().hasClass("open")) {
		$(".header").removeClass("open");
		$(".header").slideUp(400);
		$(this).find("i").removeClass().addClass("fa fa-chevron-down");
	  }
  });

}); 

/* PrettyPhoto for Recent Post */
/* ----------------------- */

$(".p-item-link").prettyPhoto({
   overlay_gallery: false, social_tools: false
});
        
/* *************************************** */  

/* Scroll to Top */

  $(".totop").hide();

  $(function(){
    $(window).scroll(function(){
      if ($(this).scrollTop()>300)
      {
        $('.totop').fadeIn();
      } 
      else
      {
        $('.totop').fadeOut();
      }
    });

    $('.totop a').click(function (e) {
      e.preventDefault();
      $('body,html').animate({scrollTop: 0}, 500);
    });

  });

/* Revolution Slider JS */

var api;
	jQuery(document).ready(function() {
		 api =  jQuery('.banner').revolution(
						{
							delay: 7000,
							
							startheight:650,
							
							hideThumbs:300,

							navigationType:"none",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off

							navOffsetHorizontal:0,
							navOffsetVertical:20,

							stopAtSlide:-1,
							stopAfterLoops:-1,

							shadow:0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"on"							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus
						});
	});


/* Feature Item */

$('.feature-item').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceIn');
}, { offset: '60%' });
	
/* Navigation Tab */
/* Tab navigation toggle */
$('#myTab a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
});


/* Pricing Table JS */

$('.p-one').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-two').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-three').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-four').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });


/* Tesimonial JS */

$('.t-one').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '75%' });

$('.t-two').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '75%' });

$('.t-three').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '75%' });


/* Inner Support page JS */

$("#slist a").click(function(e){
   e.preventDefault();
   $(this).next('p').toggle(200);
});

/* Inner Coming Soon Page JS */
/* Countdown */

$(function(){
	launchTime = new Date(); 
	launchTime.setDate(launchTime.getDate() + 365); 
	$("#countdown").countdown({until: launchTime, format: "dHMS"});
});



/* prettyPhoto Gallery */

jQuery(".prettyphoto").prettyPhoto({
   overlay_gallery: false, social_tools: false
});

/* Isotype */

// cache container
var $container = $('#portfolio');
// initialize isotope
$container.isotope({
  // options...
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});               


  