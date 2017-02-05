!function($){$(function(){
  
  var $window = $(window);
  
  $('.tip-top').tooltip();
  $('.tip-bottom').tooltip({placement: 'bottom'});
  $('.tip-left').tooltip({placement: 'left'});
  $('.tip-right').tooltip({placement: 'right'});
  
  $('.pop-right').popover();
  $('.pop-top').popover({placement: 'top'});
  $('.pop-bottom').popover({placement: 'bottom'});
  $('.pop-left').popover({placement: 'left'});
  
  $('.pop-tag').popover({trigger:'hover'});
  $('.pop-eg, .pop-logo-url, .reg-pop').popover({trigger:'focus'});
  $('.reg-tip').tooltip({placement: 'right', trigger: 'focus'});
    
})}(window.jQuery)

function urlencode(a) {
  a = (a + "").toString();
  return encodeURIComponent(a).replace(/!/g, "%21").replace(/'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
}
function siteReload(){ location.reload(); } // Reload entire site