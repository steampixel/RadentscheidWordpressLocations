
/*
  Request Json from Server
*/
function spRequestJSON(url, data, success, error, method){

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
function spGetGeolocation (success, error) {
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
  Ready function
  http://youmightnotneedjquery.com/
*/
function spReady (fn) {
  if (document.readyState != 'loading'){
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

/*
  Shorten strings
*/
function spTruncate( str, n, useWordBoundary ){
  if (str.length <= n) { return str; }
  const subString = str.substr(0, n-1); // the original check
  return (useWordBoundary
    ? subString.substr(0, subString.lastIndexOf(" "))
    : subString) + "&hellip;";
};

/*
  Hash state manager
*/
// function spHashManager () {
//
//   var self = this;
//
//   this.data = {};
//   this.oldData = {};
//
//   this.getCurrentHash = function () {
//     if(window.location.hash.length>1) {
//       return window.location.hash.substring(1);
//     }
//     return '';
//   }
//
//   this.hashToData = function (hash) {
//     var data = {};
//     var _data = hash.split(';');
//     for (i = 0; i < _data.length; i++) {
//       var __data = _data[i].split(':');
//       if(__data[0] !== undefined && __data[1] !== undefined){
//         data[__data[0]] = __data[1];
//       }
//     }
//     return data;
//   }
//
//   this.dataToHash = function (data) {
//     var hash = '';
//     for (var key in data) {
//       var hash = hash+key+':'+data[key]+';'
//     }
//     window.location.hash = hash;
//   }
//
//   this.has = function (key) {
//     if(this.data[key] === undefined){
//       return false;
//     }
//     return true;
//   }
//
//   this.get = function (key) {
//     if(this.has(key)) {
//       return this.data[key];
//     }
//     return false;
//   }
//
//   this.set = function (key, value) {
//     this.data[key] = value;
//     this.dataToHash(this.data);
//   }
//
//   this.delete = function (key) {
//     delete this.data[key];
//     this.dataToHash(this.data);
//   }
//
//   this.listeners = {};
//   this.on = function (key, callback) {
//     this.listeners[key] = [];
//     this.listeners[key].push(callback);
//   }
//
//   this.invokeListeners = function (key, data) {
//     if(this.listeners[key] !== undefined){
//       for(var i of this.listeners[key]){
//         i(data);
//       }
//     }
//   }
//
//   // Init the hash data
//   this.data = this.hashToData(this.getCurrentHash());
//   this.oldData = this.hashToData(this.getCurrentHash());
//
//   window.addEventListener('hashchange', function () {
//
//     // Set the new data form the new hash
//     self.data = self.hashToData(self.getCurrentHash());
//
//     // Compare the new data with the old data
//     for (var key in self.data) {
//       if (self.oldData[key] === undefined) {
//         // New data was detected
//         self.invokeListeners(key, self.data[key]);
//       } else {
//         if (self.oldData[key] !== self.data[key]) {
//           // A change was detected
//           self.invokeListeners(key, self.data[key]);
//         }
//       }
//     }
//
//     // Compare the old data with the new data
//     for (var key in self.oldData) {
//       if (self.data[key] === undefined) {
//         // Data was removed
//         console.log('remove data');
//         self.invokeListeners(key, null);
//       }
//     }
//
//     // Now update the old hash
//     self.oldData = self.hashToData(self.getCurrentHash());
//
//   });
//
// }
//
// var myHashmanager = new spHashManager();

/*
  Hash based Modals
*/
// function openModalViaHash(id){
//   myHashmanager.set('modal',id);
// }
//
// function closeModalViaHash(){
//   myHashmanager.set('modal', '');
// }
//
// function openModal(id){
//   var $modal = document.getElementById(id);
//   if($modal){
//     $modal.classList.add('sp-is-active');
//     document.body.classList.add('sp-modal-is-active');
//     spLoadImagesInside($modal);
//   }
// }
//
// function closeModal(id){
//   var $modal = document.getElementById(id);
//   if($modal){
//     document.body.classList.remove('sp-modal-is-active');
//     $modal.classList.remove('sp-is-active');
//   }
// }
//
// document.addEventListener('DOMContentLoaded', function () {
//
//   myHashmanager.on('modal', function(id){
//
//     // Close all currently opened modals
//     const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)
//     if ($modals.length > 0) {
//       // For each modal
//       $modals.forEach( function ($modal) {
//         closeModal($modal.id);
//       })
//     }
//
//     // If something has changed or if modal data was added to the hash
//     if(id){
//       openModal(id);
//     }
//
//   });
//
//   // Is there a modal defined inside the hash?
//   var foundInitialHashModal = false;
//   if(myHashmanager.get('modal')) {
//     foundInitialHashModal = true;
//     openModal(myHashmanager.get('modal'))
//   }
//
//   // Init
//   // Find all modals
//   const $modals = Array.prototype.slice.call(document.querySelectorAll('.sp-modal'), 0)
//   if ($modals.length > 0) {
//
//     // For each element
//     $modals.forEach( function ($modal) {
//
//       // Check if the modal should use the hash manager to save its state
//       var useHashManager = false;
//       if(foundInitialHashModal&&$modal.classList.contains('sp-use-hash')){
//         useHashManager = true;
//       }
//
//       // We found an initial active modal inside the dom
//       // Add it to the hash if no modal was found inside the hash
//       if(!foundInitialHashModal&&$modal.classList.contains('sp-is-active')) {
//
//         if(useHashManager) {
//           openModalViaHash($modal.id);
//         } else {
//           openModal($modal.id);
//         }
//
//       }
//
//       // Move the element to the body to avoid zindex problems
//       document.body.appendChild($modal);
//
//       // Add click on modal background
//       (function($modal){
//         $modal.addEventListener('click', function (event) {
//
//           if(event.target == event.currentTarget) { // Ignore child clicks
//             if(useHashManager) {
//               closeModalViaHash($modal.id);
//             } else {
//               closeModal($modal.id);
//             }
//           }
//
//         });
//       })($modal);
//
//       // Add click on close button
//       var $close = $modal.querySelectorAll(".sp-modal-close")[0];
//       (function($close){
//         $close.addEventListener('click', function () {
//           if(useHashManager) {
//             closeModalViaHash($modal.id);
//           } else {
//             closeModal($modal.id);
//           }
//         });
//       })($close);
//
//     });
//
//   }
//
// });

/*
  Lazy load images
*/
// function spLoadImagesInside($element){
//   var $images = $element.querySelectorAll("img[data-src]");
//   $images.forEach( function ($image) {
//     $image.src = $image.dataset.src;
//   })
// }

/*
  Toggle elements
*/
// function toggleDisplay(selector){
//   const $elements = Array.prototype.slice.call(document.querySelectorAll(selector), 0);
//
//   if ($elements.length > 0) {
//     $elements.forEach( function ($element) {
//       $element.classList.toggle('sp-hidden');
//     })
//   }
// }

/*
  Validate form element
*/
// function validateFormElement(id){
//   var $formElement = document.getElementById(id);
//   var $hintElement = document.getElementById(id+'_hint');
//   $formElement.classList.remove('sp-is-invalid');
//   $hintElement.classList.add('sp-hidden');
//   if(!$formElement.checkValidity()){
//     $formElement.classList.add('sp-is-invalid');
//     $hintElement.classList.remove('sp-hidden');
//   }
// }

/*
  Location Map
*/
spReady(function() {

  // Function for loading markers from the API
  function loadMapMarkers(key, callback) {

    var layer = new L.LayerGroup();


    $now = Math.floor((Date.now() / 60000) % (60 * 24 * 356));      // minutes per year
    spRequestJSON('/api/locations?type='+key+'&r='+$now, {}, function (locations) {

      // Now add the requested markers to the map
      for (var key in locations) {
        if( locations.hasOwnProperty( key ) ) {

          if(locations[key]['marker'] != undefined) {

            // Create icon markup
            var icon = L.divIcon({
              className: '',
              iconSize:null,
              html:'<div class="sp-map-marker sp-map-marker-'+locations[key]['type']+'" title="'+locations[key]['title']+'"><img style="height:100%;width:auto;" src="'+locations[key]['marker']['icon']+'"></div>'
            });

            // Create new marker
            var marker = L.marker([locations[key]['lat'], locations[key]['lng']], {icon: icon});

            // Create bubble
            var bubbleHtml = '<b>'+locations[key]['title']+'</b><br>';
            // Add the first image to bubble
            if(locations[key]['images'] != undefined) {
              if(locations[key]['images'][0] != undefined) {
                bubbleHtml+= '<img class="" src='+locations[key]['images'][0]['thumbnails'][300]+'><br>';
              }
            }
            if(locations[key]['description'] != undefined) {
              bubbleHtml+= spTruncate(locations[key]['description'], 100, true);
            }
            bubbleHtml+= ' <a href="'+locations[key]['url']+'" target="_blank">Details</a><br>';

            marker.bindPopup(bubbleHtml);

            // Add marker to layer
            layer.addLayer(marker);

          }

        }
      }

      callback(layer);

    });

  }

  // Function for loading geojsons from the API
  function loadMapGeojson(key, callback) {

    var layer = new L.LayerGroup();

    // Get all the locations from the API endpoint
    spRequestJSON('/api/geojsons?key='+key, {}, function (geojsons) {

      // Now add the requested data to the map
      for (var key in geojsons) {
        if( geojsons.hasOwnProperty( key ) ) {

          var style = {};
          if(geojsons[key]['color'] != undefined) { style['color'] = geojsons[key]['color']; }
          if(geojsons[key]['weight'] != undefined) { style['weight'] = geojsons[key]['weight']; }
          if(geojsons[key]['opacity'] != undefined) { style['opacity'] = geojsons[key]['opacity']; }

          var geojson = L.geoJSON(geojsons[key]['geojson'], {
            style: style
          }).addTo(layer);

          // Create bubble
          var bubbleHtml = '<b>'+geojsons[key]['title']+'</b><br>';

          if(geojsons[key]['description'] != undefined) {
            bubbleHtml+= spTruncate(geojsons[key]['description'], 300, true);
          }

          if(geojsons[key]['url'] != undefined) {
            bubbleHtml+= '<a href="'+geojsons[key]['url']+'" target="_blank">Details</a>'
          }

          geojson.bindPopup(bubbleHtml);

        }
      }

      callback(layer);

    });

  }

  var mapElements = document.querySelectorAll(".sp-map");

  for (var i = 0; i < mapElements.length; ++i) {

    // Isolate the element scope
    (function(mapElement) {

      // Find leaflet target
      var mapLeafletElement = mapElement.querySelector(".sp-map-leaflet");

      // Init the map
      var mymap = L.map(mapLeafletElement);

      // Add attribution
      mymap.attributionControl.addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');

      // Get dataset options
      var initialLat = mapElement.dataset.lat;
      var initialLng = mapElement.dataset.lng;
      var initialZoom = mapElement.dataset.zoom;
      var clusterIcon = mapElement.dataset.clusterIcon;

      // Set initial map position
      mymap.setView([initialLat, initialLng], initialZoom);

      // Add tile layer to map
      L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {}).addTo(mymap);

      // Add a marker cluster layer to the map
      var markerClusterGroup = L.markerClusterGroup({
      	spiderfyOnMaxZoom: true,
      	showCoverageOnHover: false,
      	zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        iconCreateFunction: function(cluster) {
      		return L.divIcon({ html: '<div class="sp-map-marker sp-map-marker-cluster"><img style="height:100%;width:auto;" src="'+clusterIcon+'"><div class="sp-map-marker-info">' + cluster.getChildCount() + '</div></div>' });
      	}
      });

      // Add the cluster layer to the map
      mymap.addLayer(markerClusterGroup);

      // Add a new geojson layer group
      var geojsonLayer = new L.LayerGroup();
      mymap.addLayer(geojsonLayer);

      // Find filters
      var mapFilters = mapElement.getElementsByClassName("sp-map-filter");

      // For each filter
      for (var f = 0; f < mapFilters.length; ++f) {
        (function(mapFilter) {

          var checkbox = mapFilter.querySelector(".sp-map-filter-checkbox");
          var type = checkbox.dataset.type;
          var key = checkbox.dataset.key;

          if(type=='marker') {

            loadMapMarkers(key, function (layer) {
              // Add click function to filters
              checkbox.addEventListener("click", function () {
                // Enable / Disbale filter
                if (checkbox.checked == true) {
                  markerClusterGroup.addLayer(layer);
                  mapFilter.classList.remove('disabled');
                } else {
                  markerClusterGroup.removeLayer(layer);
                  mapFilter.classList.add('disabled');
                }
              });

              // Initially Enable / Disable filter
              if (checkbox.checked == true) {
                markerClusterGroup.addLayer(layer);
                mapFilter.classList.remove('disabled');
              } else {
                markerClusterGroup.removeLayer(layer);
                mapFilter.classList.add('disabled');
              }
            })

          }

          if(type=='geojson') {

            loadMapGeojson(key, function(layer) {

              // Add click function to filters
              checkbox.addEventListener("click", function () {
                // Enable / Disbale filter
                if (checkbox.checked == true) {
                  geojsonLayer.addLayer(layer);
                  mapFilter.classList.remove('disabled');
                } else {
                  geojsonLayer.removeLayer(layer);
                  mapFilter.classList.add('disabled');
                }
              });

              // Initially Enable / Disable filter
              if (checkbox.checked == true) {
                geojsonLayer.addLayer(layer);
                mapFilter.classList.remove('disabled');
              } else {
                geojsonLayer.removeLayer(layer);
                mapFilter.classList.add('disabled');
              }

            });

          }

        })(mapFilters[f]);
      }

      // Find Categorys
      var mapCategories = mapElement.getElementsByClassName("sp-map-filter-category");

      // For each filter
      for (var c = 0; c < mapCategories.length; ++c) {
        (function(mapCategory) {

          var header = mapCategory.querySelector(".sp-map-filter-category-header");

          // Add click function to header
          header.addEventListener("click", function () {

            // Toggle all filters
            var checkboxes = mapCategory.getElementsByClassName("sp-map-filter-checkbox");
            for (var c = 0; c < checkboxes.length; ++c) {
              checkboxes[c].click();
            }

          });

        })(mapCategories[c])
      }

      var mapFilterButton = mapElement.querySelector(".sp-map-filter-button");
      var mapFilters = mapElement.querySelector(".sp-map-filters");

      // Add filter button
      mapFilterButton.addEventListener('click', function () {
        mapFilters.classList.toggle('active');
        mapFilterButton.classList.toggle('active');
      });

      // Add click to filter close button
      mapFilters.addEventListener('mouseleave', function () {
        mapFilters.classList.remove('active');
        mapFilterButton.classList.add('active');
      });

    })(mapElements[i]); // scope container

  } // for

});

/*
  Location picker
  This location picker provides an interactive map and adress selection
*/
function spLocationPicker(locationPickerElement) {

  var mapElement = locationPickerElement.querySelector(".sp-location-picker-map");
  var mapHintElement = locationPickerElement.querySelector(".sp-location-picker-map-hint");
  var inputStreetElement = locationPickerElement.querySelector(".sp-location-picker-input-street");
  var inputHouseNumberElement = locationPickerElement.querySelector(".sp-location-picker-input-house-number");
  var inputPostcodeElement = locationPickerElement.querySelector(".sp-location-picker-input-postcode");
  var inputPlaceElement = locationPickerElement.querySelector(".sp-location-picker-input-place");
  var inputSuburbElement = locationPickerElement.querySelector(".sp-location-picker-input-suburb");
  var inputLatElement = locationPickerElement.querySelector(".sp-location-picker-input-lat");
  var inputLngElement = locationPickerElement.querySelector(".sp-location-picker-input-lng");

  // Get dataset attributes
  var initialLat = locationPickerElement.dataset.lat;
  var initialLng = locationPickerElement.dataset.lng;
  var initialZoom = locationPickerElement.dataset.zoom;
  var markerIcon = locationPickerElement.dataset.markerIcon;

  function updateAddressFromCoordinates (lat, lng) {
    spRequestJSON('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng, {}, function(data){

      inputLatElement.value = lat;
      inputLngElement.value = lng;
      mapHintElement.classList.remove('sp-is-active');

      if(data.address != undefined) {

          if(data.address.city != undefined) {
            inputPlaceElement.value = data.address.city;
          }else{
            if(data.address.village != undefined) {
              inputPlaceElement.value = data.address.village;
            }
            else{
              if(data.address.town != undefined) {
                inputPlaceElement.value = data.address.town;
              }
              else{
                inputPlaceElement.value = '';
              }
            }
          }

          if(data.address.house_number != undefined) {
            inputHouseNumberElement.value = data.address.house_number;
          }else{
            inputHouseNumberElement.value = '';
          }

          if(data.address.road != undefined) {
            inputStreetElement.value = data.address.road;
          }else{
            if(data.address.pedestrian != undefined) {
              inputStreetElement.value = data.address.pedestrian;
            }else{
              inputStreetElement.value = '';
            }
          }

          if(data.address.postcode != undefined) {
            inputPostcodeElement.value = data.address.postcode;
          }else{
            inputPostcodeElement.value = '';
          }

          if(data.address.suburb != undefined) {
            inputSuburbElement.value = data.address.suburb;
          }else{
            inputSuburbElement.value = '';
          }

        } else {
          // Error
        }

    }, 'GET');
  }

  function updateCoordinatesFromAddress(){

    var postcode = inputPostcodeElement.value;
    var street = inputStreetElement.value;
    var place = inputPlaceElement.value;
    var house_number = inputHouseNumberElement.value;

    spRequestJSON('https://nominatim.openstreetmap.org/search?format=jsonv2&city='+place+'&postalcode='+postcode+'&street='+house_number+' '+street, {}, function(data){

      if(data[0] != undefined) {

        var lat = data[0].lat;
        var lng = data[0].lon;

        mymap.setView([lat, lng])
        mymarker.setLatLng([lat, lng]);

        // Update input fields
        inputLatElement.value = lat;
        inputLngElement.value = lng;
        mapHintElement.classList.remove('sp-is-active');
      }

    });

  }

  // Add blur events to input fields
  inputStreetElement.addEventListener("blur", updateCoordinatesFromAddress );
  inputHouseNumberElement.addEventListener("blur", updateCoordinatesFromAddress );
  inputPostcodeElement.addEventListener("blur", updateCoordinatesFromAddress );
  inputPlaceElement.addEventListener("blur", updateCoordinatesFromAddress );

  // Create the map
  mymap = L.map(mapElement);

  // Add map attribution
  mymap.attributionControl.addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');

  // Default coordinates
  mymap.setView([initialLat, initialLng], initialZoom);

  // Add tile layer
  L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {}).addTo(mymap);

  // Create map icon
  var icon = L.divIcon({
     className: 'map-marker',
     iconSize:null,
     html:'<div class="sp-map-marker"><img style="height:100%;width:auto;" src="'+markerIcon+'"></div>'
   });

  // Add the marker
  mymarker = L.marker([0, 0], {icon: icon}).addTo(mymap);

  // Update center coordinates on click
  mymap.on('click', function (e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;
    mymap.setView([lat, lng]);
    mymarker.setLatLng([lat, lng]);
    updateAddressFromCoordinates(lat, lng);
  });

  if (inputLatElement.value == '') {
    // Auto get coordinates from browser
    spGetGeolocation(function (position) {
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      mymap.setView([lat, lng], 13)
      mymarker.setLatLng([lat, lng]);
      updateAddressFromCoordinates(lat, lng);
    }, function (error) {
      mapHintElement.classList.add('sp-is-active');
    })
  }
  else {
    var lat = inputLatElement.value;
    var lng = inputLngElement.value;
    mymap.setView([lat, lng]);
    mymarker.setLatLng([lat, lng]);
  }
}
spReady(function () {

  var elements = document.querySelectorAll(".sp-location-picker");

  for (var i = 0; i < elements.length; ++i) {
    new spLocationPicker(elements[i]);
  }

});

/*
  File Uploader
  This is a simple file uploader
  It provides a simple drag / drop functionality and an image preview
*/
function spFileUploader(fileUploaderElement){

  var fileInputElement = fileUploaderElement.querySelector(".sp-file-uploader-input");
  var fileLabelElement = fileUploaderElement.querySelector(".sp-file-uploader-label");
  var filePreviewElement = fileUploaderElement.querySelector(".sp-file-uploader-image");

  // Drag over
  fileUploaderElement.addEventListener("dragover", function (event){
    event.currentTarget.classList.add('dragover');
    event.preventDefault();
  } );

  // Drag leave
  fileUploaderElement.addEventListener("dragleave", function (event){
    event.currentTarget.classList.remove('dragover');
    event.preventDefault();
  } );

  // Drop files
  fileUploaderElement.addEventListener("drop", function (ev) {

    ev.preventDefault();

    event.currentTarget.classList.remove('dragover');

    // Copy the files to the file input
    // So the XHR Form later can grab the data from there
    fileInputElement.files = ev.dataTransfer.files;

    if (ev.dataTransfer.items) {
      // Use DataTransferItemList interface to access the file(s)
      for (var i = 0; i < ev.dataTransfer.items.length; i++) {
        // If dropped items aren't files, reject them
        if (ev.dataTransfer.items[i].kind === 'file') {
          file = ev.dataTransfer.items[i].getAsFile();
          previewFile(file);
          // console.log('... file[' + i + '].name = ' + file.name);
        }
      }
    } else {
      // Use DataTransfer interface to access the file(s)
      for (var i = 0; i < ev.dataTransfer.files.length; i++) {
        // console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
        file = ev.dataTransfer.files[i];
        previewFile(file);
      }
    }

  } );

  // On change
  fileInputElement.addEventListener("change", function () {
    file = fileInputElement.files[0]; // This is the file we need in the submit method later
    previewFile(file);
  });

  function previewFile(file) {

    // This code will create a base 64 preview representation of the image
    if (file) {
      var reader = new FileReader();
      reader.onload = function(e) {
        filePreviewElement.src = e.target.result;
        fileLabelElement.classList.add('sp-hidden');
      }
      // Start the reader job - read file as a data url (base64 format)
      reader.readAsDataURL(file);
    }
  }

}
spReady(function () {

  var elements = document.querySelectorAll(".sp-file-uploader");

  for (var i = 0; i < elements.length; ++i) {
    new spFileUploader(elements[i]);
  }

});

/*
  XHR Forms
  This form module will automatically gather form data, validate it and create JSON requests to API endpoints
  It also provides client side RSA encryption of sensitive form data
*/
function spXhrForm(formElement) {

  // Define some important elements
  var submitButton = formElement.querySelector(".sp-xhr-form-submit");
  var formInputs = formElement.querySelectorAll(".sp-xhr-form-data");

  var formFieldsContainer = formElement.querySelector(".sp-xhr-form-fields");

  var waitMessage = formElement.querySelector(".sp-xhr-form-wait");
  var successMessage = formElement.querySelector(".sp-xhr-form-success");
  var errorMessage = formElement.querySelector(".sp-xhr-form-error");
  var validationErrorMessage = formElement.querySelector(".sp-xhr-form-validation-error");

  var nonceElement = document.getElementById("_wpnonce");

  // Get some form attributes
  var action = formElement.action;
  var method = formElement.method;

  // Enable RSA encryption
  var enableRsa = false;
  var rsaPublicKey = false;
  var encrypt = false;
  if(formElement.dataset.enableRsaEncryption != undefined) {
    enableRsa = true;
    rsaPublicKey = formElement.dataset.rsaPublicKey;
    encrypt = new JSEncrypt();
    encrypt.setPublicKey(rsaPublicKey);
  }

  // Define the submit routine
  function submit () {

    // Create form data object
    var formData = new FormData();

    // For each input
    for (var i = 0; i < formInputs.length; ++i) {

      // Isolate scope
      (function(formInput){

        // Verify the data
        var hint = formElement.querySelector(".sp-xhr-form-hint[data-input='"+formInput.name+"']");
        var verified = true;
        if(hint) {
          if(!formInput.checkValidity()){
            hint.classList.remove('sp-hidden');
            verified = false;
          } else {
            hint.classList.add('sp-hidden');
            verified = true;
          }
        }

        if(verified) {
          if(formInput.type=='file') {
            // Add files
            formData.append(formInput.name, formInput.files[0], formInput.files[0].name);
          } else {
            // Add other form fields
            // Encrypt the data
            if(enableRsa&&formInput.dataset.encrypt != undefined) {
              // Encrypt the data and append it
              formData.append(formInput.name, encrypt.encrypt(formInput.value));
            } else {
              // Simply append the data without encryption
              formData.append(formInput.name, formInput.value);
            }
          }
        }

      })(formInputs[i]);

    }

    // Check for validality
    if(formElement.checkValidity()) {

      // Append the encryption key
      if(enableRsa) {
        formData.append('rsa_public_key', rsaPublicKey);
      }

      // Append the wp nonce
      formData.append('_wpnonce', nonceElement.value);

      // Hide the form
      formFieldsContainer.classList.add('sp-hidden');

      // Show wait message
      waitMessage.classList.remove('sp-hidden');

      // Hide the validation error
      validationErrorMessage.classList.add('sp-hidden');

      // Submit request to server
      spRequestJSON(
        action,
        formData,
        function(data) { // on success
          waitMessage.classList.add('sp-hidden'); // Hide wait message
          successMessage.classList.remove('sp-hidden'); // Show success message
          errorMessage.classList.add('sp-hidden'); // Hide error message
        },
        function(data) { // on error
          waitMessage.classList.add('sp-hidden'); // Hide wait message
          errorMessage.classList.remove('sp-hidden'); // Show error message
          formElement.classList.remove('sp-hidden'); // Show form
        },
        method
      );

    } else {

      // Show the general validation error
      validationErrorMessage.classList.remove('sp-hidden');

    }

  }

  // Submit via Enter
  formElement.addEventListener("submit", function (e) {
    e.preventDefault();
    submit();
  });

  // Submit via Button
  submitButton.addEventListener("click", function (e) {
    e.preventDefault();
    submit();
  });

}
spReady(function () {

  var forms = document.querySelectorAll(".sp-xhr-form");

  for (var i = 0; i < forms.length; ++i) {
    new spXhrForm(forms[i]);
  }

});

/*
  Enlarge on click
*/
function spEnlargeOnClick(element) {

  var enlargeSrc = element.dataset.enlargeSrc;

  element.addEventListener("click", function (e) {
    e.preventDefault();
    var overlay = document.createElement('div');
    overlay.classList.add('enlarge-on-click-overlay');
    overlay.style.cssText = 'background-image:url('+enlargeSrc+');';
    overlay.addEventListener("click", function (e) {
      overlay.remove();
    });
    document.body.appendChild(overlay);
  });

}
spReady(function () {

  var elements = document.querySelectorAll(".enlarge-on-click");

  for (var i = 0; i < elements.length; ++i) {
    new spEnlargeOnClick(elements[i]);
  }

});

/*
  Minimap
*/
function spMiniMap(mapElement) {

  var lat = mapElement.dataset.lat;
  var lng = mapElement.dataset.lng;
  var title = mapElement.dataset.title;
  var type = mapElement.dataset.type;
  var icon = mapElement.dataset.icon;
  var zoom = mapElement.dataset.zoom;

  var mymap = L.map(mapElement).setView([lat, lng], zoom);

  mymap.attributionControl.addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');

  L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {

  }).addTo(mymap);

  var mapicon = L.divIcon({
     className: 'map-marker',
     iconSize:null,
     html:'<div class="sp-map-marker sp-map-marker-'+type+'" title="'+title+'"><img style="height:100%;width:auto;" src="'+icon+'"></div>'
   });

  // Add marker
  mymarker = L.marker([lat, lng], {icon: mapicon}).addTo(mymap);

};
spReady(function () {

  var elements = document.querySelectorAll(".sp-mini-map");

  for (var i = 0; i < elements.length; ++i) {
    new spMiniMap(elements[i]);
  }

});
// Avoid passing unnecessary form fields
function spOnReviewSubmit(path) {
    var action = document.getElementById("action").value;
    if (action == 'update') return true;
    window.location.href = path + '&action=' + action;
    return false;
}
