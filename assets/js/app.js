
/*
  Request Json from Server
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
  Get location from browser
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
  Hasg state manager
*/
function HashManager () {

  var self = this;

  this.data = {};
  this.oldData = {};

  this.getCurrentHash = function () {
    if(window.location.hash.length>1) {
      return window.location.hash.substring(1);
    }
    return '';
  }

  this.hashToData = function (hash) {
    var data = {};
    var _data = hash.split(';');
    for (i = 0; i < _data.length; i++) {
      var __data = _data[i].split(':');
      if(__data[0] !== undefined && __data[1] !== undefined){
        data[__data[0]] = __data[1];
      }
    }
    return data;
  }

  this.dataToHash = function (data) {
    var hash = '';
    for (var key in data) {
      var hash = hash+key+':'+data[key]+';'
    }
    window.location.hash = hash;
  }

  this.has = function (key) {
    if(this.data[key] === undefined){
      return false;
    }
    return true;
  }

  this.get = function (key) {
    if(this.has(key)) {
      return this.data[key];
    }
    return false;
  }

  this.set = function (key, value) {
    this.data[key] = value;
    this.dataToHash(this.data);
  }

  this.delete = function (key) {
    delete this.data[key];
    this.dataToHash(this.data);
  }

  this.listeners = {};
  this.on = function (key, callback) {
    this.listeners[key] = [];
    this.listeners[key].push(callback);
  }

  this.invokeListeners = function (key, data) {
    if(this.listeners[key] !== undefined){
      for(var i of this.listeners[key]){
        i(data);
      }
    }
  }

  // Init the hash data
  this.data = this.hashToData(this.getCurrentHash());
  this.oldData = this.hashToData(this.getCurrentHash());

  window.addEventListener('hashchange', function () {

    // Set the new data form the new hash
    self.data = self.hashToData(self.getCurrentHash());

    // Compare the new data with the old data
    for (var key in self.data) {
      if (self.oldData[key] === undefined) {
        // New data was detected
        self.invokeListeners(key, self.data[key]);
      } else {
        if (self.oldData[key] !== self.data[key]) {
          // A change was detected
          self.invokeListeners(key, self.data[key]);
        }
      }
    }

    // Compare the old data with the new data
    for (var key in self.oldData) {
      if (self.data[key] === undefined) {
        // Data was removed
        console.log('remove data');
        self.invokeListeners(key, null);
      }
    }

    // Now update the old hash
    self.oldData = self.hashToData(self.getCurrentHash());

  });

}

var myHashmanager = new HashManager();

/*
  Hash based Modals
*/
function openModalViaHash(id){
  myHashmanager.set('modal',id);
}

function closeModalViaHash(){
  myHashmanager.set('modal', '');
}

function openModal(id){
  var $modal = document.getElementById(id);
  if($modal){
    $modal.classList.add('sp-is-active');
    document.body.classList.add('sp-modal-is-active');
    spLoadImagesInside($modal);
  }
}

function closeModal(id){
  var $modal = document.getElementById(id);
  if($modal){
    document.body.classList.remove('sp-modal-is-active');
    $modal.classList.remove('sp-is-active');
  }
}

document.addEventListener('DOMContentLoaded', function () {

  myHashmanager.on('modal', function(id){

    // Close all currently opened modals
    const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)
    if ($modals.length > 0) {
      // For each modal
      $modals.forEach( function ($modal) {
        closeModal($modal.id);
      })
    }

    // If something has changed or if modal data was added to the hash
    if(id){
      openModal(id);
    }

  });

  // Is there a modal defined inside the hash?
  var foundInitialHashModal = false;
  if(myHashmanager.get('modal')) {
    foundInitialHashModal = true;
    openModal(myHashmanager.get('modal'))
  }

  // Init
  // Find all modals
  const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)
  if ($modals.length > 0) {

    // For each element
    $modals.forEach( function ($modal) {

      // Check if the modal should use the hash manager to save its state
      var useHashManager = false;
      if(foundInitialHashModal&&$modal.classList.contains('sp-use-hash')){
        useHashManager = true;
      }

      // We found an initial active modal inside the dom
      // Add it to the hash if no modal was found inside the hash
      if(!foundInitialHashModal&&$modal.classList.contains('sp-is-active')) {

        if(useHashManager) {
          openModalViaHash($modal.id);
        } else {
          openModal($modal.id);
        }

      }

      // Move the element to the body to avoid zindex problems
      document.body.appendChild($modal);

      // Add click on modal background
      (function($modal){
        $modal.addEventListener('click', function (event) {

          if(event.target == event.currentTarget) { // Ignore child clicks
            if(useHashManager) {
              closeModalViaHash($modal.id);
            } else {
              closeModal($modal.id);
            }
          }

        });
      })($modal);

      // Add click on close button
      var $close = $modal.querySelectorAll(".sp-modal-close")[0];
      (function($close){
        $close.addEventListener('click', function () {
          if(useHashManager) {
            closeModalViaHash($modal.id);
          } else {
            closeModal($modal.id);
          }
        });
      })($close);

    });

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

/*
  Validate form element
*/
function validateFormElement(id){
  var $formElement = document.getElementById(id);
  var $hintElement = document.getElementById(id+'_hint');
  $formElement.classList.remove('sp-is-invalid');
  $hintElement.classList.add('sp-hidden');
  if(!$formElement.checkValidity()){
    $formElement.classList.add('sp-is-invalid');
    $hintElement.classList.remove('sp-hidden');
  }
}
