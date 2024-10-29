if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  function cate (x){

    x.parentElement.firstElementChild.style.display=''; 
    x.remove()
    
    }