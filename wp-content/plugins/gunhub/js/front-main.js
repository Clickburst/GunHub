;(function ($) {
  $(document).ready(function () {


    if (typeof blueimp !== undefined) {
      document.getElementById('gallery-items').onclick = function (event) {
        event = event || window.event
        var target = event.target || event.srcElement
        var link = target.src ? target.parentNode : target
        var options = {index: link, event: event}
        var links = this.getElementsByTagName('a')
        blueimp.Gallery(links, options)
      }
    }
  })

}(jQuery) )