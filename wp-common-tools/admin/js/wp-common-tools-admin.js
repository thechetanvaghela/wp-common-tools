jQuery(document).ready(function(){

  /* add loader class */
  jQuery('#wpct-setting-container').addClass('loader'); 
  setTimeout(function(){
    jQuery('#wpct-setting-container').removeClass('loader');
  }, 1000);
  jQuery("#wp-common-tools-form-settings").click(function() {
    jQuery('#wpct-setting-container').addClass('loader'); 
  });

  /* Page loader options */
  jQuery("#wpct-loader-enable").click(function() {
      if(jQuery(this).is(":checked")) {
          jQuery(".wpct-loader-image-wrap").show(300);
      } else {
          jQuery(".wpct-loader-image-wrap").hide(200);
      }
  });

  /* Progress bar options */
  jQuery("#wpct-progress-bar-enable").click(function() {
      if(jQuery(this).is(":checked")) {
          jQuery(".wpct-scroll-progress-bar-option-wrap").show(300);
      } else {
          jQuery(".wpct-scroll-progress-bar-option-wrap").hide(200);
      }
  });

  /* Back to top options */
  jQuery("#wpct-back-to-top-enable").click(function() {
      if(jQuery(this).is(":checked")) {
          jQuery(".wpct-back-to-top-option-wrap").show(300);
      } else {
          jQuery(".wpct-back-to-top-option-wrap").hide(200);
      }
  });

  /* Remove loader image */
  jQuery(".remove-loader-img").click(function() {
      jQuery('.wpct-loader-img-preview-wrap').remove();
      jQuery('.wpct-loader-img-select-wrap').show();
  });

  /* Remove login image */
  jQuery(".remove-login-img").click(function() {
      jQuery('.wpct-login-img-preview-wrap').remove();
      jQuery('.wpct-login-img-select-wrap').show();
  });

  /* Tabs */
  if (localStorage.getItem("wpct-current-tab") != null) {
      var lct = localStorage.getItem('wpct-current-tab');
      if(jQuery('.wpct-tab-menu #'+lct).length > 0)
      {
          setTimeout(function(){
            jQuery('.wpct-tab-menu .wpct-tab-a#'+lct).trigger('click');
          }, 100);
      }
  }
  jQuery('.wpct-tab-a').click(function(){  
      jQuery(".wpct-tab").removeClass('wpct-tab-active');
      jQuery(".wpct-tab[data-id='"+jQuery(this).attr('data-id')+"']").addClass("wpct-tab-active");
      jQuery(".wpct-tab-a").removeClass('wpct-active-a');
      jQuery(this).parent().find(".wpct-tab-a").addClass('wpct-active-a');
      var ct = jQuery(this).attr('id');
      localStorage.setItem('wpct-current-tab', ct);
   });
});
