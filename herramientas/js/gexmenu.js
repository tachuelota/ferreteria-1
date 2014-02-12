
$.fn.gexmenu = function(options){
	var settings = {
		interval	 		: 250     	// animation time (ms)
	}
	$(".menu").prepend("<li class='showhide'><span>MENU</span><i class='icon-reorder'></i></li>");
	
	screenSize();
	
	$(window).resize(function() {
		screenSize();
	});
	
	function screenSize(){
		$(".menu").find("li").unbind();
		$(".menu").find("ul").hide(0);
		if(window.innerWidth <= 768){
			showCollapse();
			bindClick();
		}
		else{
			hideCollapse();
			bindHover();
		}
	}
	
	function bindHover(){
		$(".menu li").bind("mouseover", function(){
			$(this).children("ul").fadeIn(settings.interval);
		}).bind("mouseleave", function(){
			$(this).children("ul").fadeOut(settings.interval);
		});
	}
	
	function bindClick(){
		$(".menu > li").bind("click", function(){
			if($(this).children("ul").css("display") == "none"){
				$(this).find("ul").slideDown(settings.interval);
			}
			else{
				$(this).children("ul").slideUp(settings.interval);
			}
		});
	}
	
	$(".menu a").bind("mouseenter", function(){
		$(this).stop().fadeOut(0).fadeIn(500);
	});
	
	function showCollapse(){
		$(".menu > li:not(.showhide)").hide(0);
		$(".menu > li.showhide").show(0);
		$(".menu > li.showhide").bind("click", function(){
			if($(".menu > li").is(":hidden")){
				$(".menu > li").slideDown(300);
			}
			else{
				$(".menu > li:not(.showhide)").slideUp(300);
				$(".menu > li.showhide").show(0);
			}
		});
	}
	
	function hideCollapse(){
		$(".menu > li").show(0);
		$(".menu > li.showhide").hide(0);
	}	
}
















