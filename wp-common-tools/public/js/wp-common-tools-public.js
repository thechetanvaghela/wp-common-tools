jQuery(document).ready(function() {

	 wpCTscrollProgressBar();
   
  /* Back to top */
  if(jQuery('#wpct-backtotop').length > 0)
  {
    	var btn = jQuery('#wpct-backtotop');
    	jQuery(window).scroll(function() {
    	    if (jQuery(window).scrollTop() > 300) {
    	        btn.addClass('show')
    	    } else {
    	        btn.removeClass('show')
    	    }
    	});
    	btn.on('click', function(e) {
    	    e.preventDefault();
    	    jQuery('html, body').animate({
    	        scrollTop: 0
    	    }, '300')
    	});
  }

});

/* Page Loader */
document.onreadystatechange = function() {
    if(document.querySelectorAll('#wpct-preload').length > 0)
    {
          if (document.readyState !== "complete") {
              document.querySelector(
                "body").style.visibility = "hidden";
              document.querySelector(
                "#wpct-preload").style.visibility = "visible";
          } else {
            setTimeout(function(){
              document.querySelector(
                "#wpct-preload").style.display = "none";
              document.querySelector(
                "body").style.visibility = "visible";
            }, 100);
          }
      }
};


/* Show Scroll progress in circle */
const updateScrollPercentage = function() {
  if(document.querySelectorAll('#wpct-percentage-value').length > 0)
  {
     const heightOfWindow = window.innerHeight,
     contentScrolled = window.pageYOffset,
     bodyHeight = document.body.offsetHeight,
     percentage = document.querySelector("[data-scrollPercentage] .wpct-percentage")
     percentageVal = document.querySelector("#wpct-percentage-value")
     
     	if(bodyHeight - contentScrolled <= heightOfWindow) {
     		percentageVal.textContent = percentage.style.width = "100%"
     	}
     	else 
     	{
    	 const total = bodyHeight - heightOfWindow,
    	 got = contentScrolled,
    	 percent = parseInt((got/total) * 100)
    	 percentageVal.textContent = percentage.style.width = percent + "%"
     	}
    }
 }
 window.addEventListener('scroll', updateScrollPercentage)

/* Top Scroll Progress Bar*/
 function wpCTscrollProgressBar() {
  var wpctgetMax = function () {
    return jQuery(document).height() - jQuery(window).height();
  };

  var wpctgetValue = function () {
    return jQuery(window).scrollTop();
  };

  var wpctprogressBar = jQuery(".wpct-progress-bar"),
    wpctmax = wpctgetMax(),
    wpctvalue,
    wpctwidth;

  var wpctgetWidth = function () {
    wpctvalue = wpctgetValue();
    wpctwidth = (wpctvalue / wpctmax) * 100;
    wpctwidth = wpctwidth + "%";
    return wpctwidth;
  };

  var wpctsetWidth = function () {
    wpctprogressBar.css({ width: wpctgetWidth() });
  };

  jQuery(document).on("scroll", wpctsetWidth);
  jQuery(window).on("resize", function () {
    wpctmax = wpctgetMax();
    wpctsetWidth();
  });
}
