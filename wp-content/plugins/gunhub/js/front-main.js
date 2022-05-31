;(function ($) {
  $(document).ready(function () {
    if (typeof blueimp !== undefined) {
      // todo - move to jQuery?
      const galleryItems = document.getElementById('gallery-items');
      if( galleryItems !== null ) {
        galleryItems.onclick = function (event) {
          event = event || window.event
          var target = event.target || event.srcElement
          var link = target.src ? target.parentNode : target
          var options = {index: link, event: event}
          var links = this.getElementsByTagName('a')
          blueimp.Gallery(links, options)
        }
      }
    }
    
    // remove empty select boxes and inputs from search url
    const $searchListingsForm = $('[gh-search-listings-form]');
    if( $searchListingsForm.length > 0 ) {
      $searchListingsForm.submit(function ( e ){
        $(this).find('select, input').each(function (){
          this.disabled = !($(this).val());
        })
      })
    }
  })
}(jQuery) )