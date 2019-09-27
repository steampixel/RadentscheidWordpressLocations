
/*
  requestJSON
*/
function requestJSON(url, data, success, error, method){

  if(method==undefined){
    method = 'GET';
  }

  var request = new XMLHttpRequest();
  request.open(method, url, true);

  request.onload = function() {
    if (this.status >= 200 && this.status < 400) {
      // Success!

      var data = JSON.parse(this.response);

      success(data)

    } else {
      // We reached our target server, but it returned an error
      error()
    }
  };

  request.onerror = function() {
    // There was a connection error of some sort
    error()
  };

  request.send(data)

}

/*
  getGeolocation
*/
function getGeolocation (success, error) {
  if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(function(position){

      success(position)

    }, function(){

      error('Cannot get location')

    });
  } else {
    error('Geolocation not supported')
  }
}

/*
  Modals
*/
function openModal(id){
  window.location.hash = id;
}

function closeModal(){
  window.location.hash = '';
}

window.addEventListener('hashchange', function(){

  // Close all currently opened modals
  const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)
  if ($modals.length > 0) {
    // For each modal
    $modals.forEach( function ($modal) {
      $modal.classList.remove('sp-is-active');
    })
  }

  // Reset the body state
  document.body.classList.remove('sp-modal-is-active');

  // Open modal
  if(window.location.hash.length>1){
    // if(window.location.hash.includes('modal-')){
      var id = window.location.hash.substring(1)
      var $modal = document.getElementById(id);
      if($modal){
        $modal.classList.add('sp-is-active');
        document.body.classList.add('sp-modal-is-active');
        spLoadImagesInside($modal);
      }
      // spToggleModal(window.location.hash.substring(1))
    // }
  }

});

document.addEventListener('DOMContentLoaded', function () {

  // Get the initial hash id
  var initialId = window.location.hash.substring(1)

  const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)

  if ($modals.length > 0) {

    // For each element
    $modals.forEach( function ($modal) {

      // We found an initial active modal
      if($modal.classList.contains('sp-is-active')){
        openModal($modal.id)
      }

      // Move the element to the body to avoid zindex problems
      document.body.appendChild($modal);

      // Add click on modal background
      (function($modal){
        $modal.addEventListener('click', function (event) {

          if(event.target == event.currentTarget) { // Ignore child clicks
            closeModal($modal.id)
          }

        });
      })($modal);

      // Add click on close button
      var $close = $modal.querySelectorAll(".sp-modal-close")[0];
      (function($close){
        $close.addEventListener('click', function () {
          closeModal($modal.id)
        });
      })($close);

    });

  }

  if(initialId!=''){
    openModal(initialId)
  }

})

/*
  Lazy load images
*/
function spLoadImagesInside($element){
  var $images = $element.querySelectorAll("img[data-src]");
  $images.forEach( function ($image) {
    $image.src = $image.dataset.src;
  })
}

/*
  Toggle elements
*/
function toggleDisplay(selector){
  const $elements = Array.prototype.slice.call(document.querySelectorAll(selector), 0);

  if ($elements.length > 0) {
    $elements.forEach( function ($element) {
      $element.classList.toggle('sp-hidden');
    })
  }
}
