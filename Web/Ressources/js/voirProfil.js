$(document).ready(function(){
  if (typeof($('#btn-active')) != "undefined" && $('#btn-active') != null) {
    tippy('#btn-active', {
      content: "Cliquer pour publier"
    });
  } 
  if (typeof($('#btn-desactive')) != "undefined" && $('#btn-desactive') != null) {
    tippy('#btn-desactive', {
      content: "Cliquer pour cacher"
    });
  } 
    
  tippy('#pseudo-btn', {
    content: "Cliquer pour modifier le pseudo"
  }); 
});