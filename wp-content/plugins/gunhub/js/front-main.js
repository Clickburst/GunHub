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
    
    // todo - not active yet
    if( $.fn.select2 !== undefined ) {
      $('.select2').select2()
    }
    
    $('[gh-seller-remove-listing]').click(function(e){
        e.preventDefault();
        const $this = $(this),
        listingId = $this.data('listing-id')
      
      if( ! window.confirm( 'Are you sure you want to delete listing ?' ) ) {
        return;
      }

      $.ajax({
        type: 'POST',
        url: gunhub.ajaxurl,
        data: {
          action: 'remove_listing',
          listing_id: listingId,
          nonce: gunhub.ajaxnonce
        },
        dataType: 'JSON',
      }).success(function (response) {
        if( response.success ) {
          $this.parents('[gunhub-listing-wrapper]').remove();
          return
        }
        
        alert(response.data.message);
      }).fail(function() {
        alert('error occurred, please contact site administrator');
      })
    })

    $('[gunhub-hide-parent]').click(function (e) {
      e.preventDefault()

      $(this).parent().hide()

    })
  })
}(jQuery) )