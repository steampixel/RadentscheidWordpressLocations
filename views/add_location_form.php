
<form id="add_location_form">

  <div class="sp-columns sp-has-margin-bottom-2">
    <div class="sp-column is-full">

      <div class="sp-has-margin-bottom-2">
        <label for="title"><?=$name_label ?></label>
        <input required minlength="1" maxlength="200" placeholder="<?=$name_placeholder ?>" class="sp-form-input" type="text" name="title" id="title">
        <div class="sp-hint sp-has-text-red sp-hidden" id="title_hint">Bitte gib einen Titel ein!</div>
      </div>

      <div class="sp-has-margin-bottom-2">
        <?php
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $upload_mb = min($max_upload, $max_post, $memory_limit);
        ?>
        <label for="upload_file"><?=$file_label ?> (<?=($require_image?'':'optional ') ?>max <?=$upload_mb ?>MB)</label>
        <label class="file-upload" id="file" ondragover="dragOver(event)" ondragleave="dragLeave(event)" ondrop="dropFiles(event);">
          <input id="upload_file" class="file-upload-input" type="file" accept="image/*" onChange="handleFileUpload()">
          <span id="upload_file_label" class="file-upload-label">
            <?=$file_placeholder ?>
          </span>
          <img class="file-upload-image" id="upload_preview">
        </label>
        <div class="sp-hint sp-has-text-red sp-hidden" id="file_hint">Bitte wähle ein Bild aus!</div>
      </div>
    </div>
  </div>

  <div class="sp-has-margin-bottom-2">
    <label for="street">Gib die Adresse ein oder setze die Position auf der Karte</label>
    <div class="sp-columns enable-wrap">

      <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet sp-has-margin-bottom-2">
        <div id="submitmap" style="height:300px;"></div>
        <div  id="submitmap_hint" class="sp-hint sp-has-text-red sp-hidden">
          Die Koordinaten konnten scheinbar nicht automatisch ermittelt werden. Bitte setze die Position auf der Karte selbst.
        </div>
      </div>

      <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">

        <?PHP
        // This is a simple honeypot
        // The form will only simulate the success if this field gets filled
        ?>
        <label class="sp-fg56bn67">
          Email
          <input placeholder="Deine Mail" class="sp-form-input" type="email" name="mail" id="mail">
        </label>

        <div class="sp-has-margin-bottom-2">
          <label for="street">Straße und Hausnummer<?=($require_address?'':' (optional)') ?></label>
          <div class="sp-columns">
            <div class="sp-column is-two-third">
              <input <?=($require_address?'required':'') ?> minlength="1" maxlength="200" placeholder="Straße" class="sp-form-input" type="text" name="street" id="street" onblur="updateCoordinatesFromAddress()">
            </div>
            <div class="sp-column is-one-third">
              <input <?=($require_address?'required':'') ?> minlength="1" maxlength="10" placeholder="Hausnummer" class="sp-form-input" type="text" name="house_number" id="house_number" onblur="updateCoordinatesFromAddress()">
            </div>
          </div>
          <div class="sp-hint sp-has-text-red sp-hidden" id="street_hint">Bitte gib eine Straße ein!</div>
          <div class="sp-hint sp-has-text-red sp-hidden" id="house_number_hint">Bitte gib eine Hausnummer ein!</div>
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="postcode">Postleitzahl und Ort<?=($require_address?'':' (optional)') ?></label>
          <div class="sp-columns">
            <div class="sp-column is-one-third">
              <input <?=($require_address?'required':'') ?> placeholder="Postleitzahl" class="sp-form-input" type="number" name="postcode" id="postcode" onblur="updateCoordinatesFromAddress()">
            </div>
            <div class="sp-column is-two-third">
              <input <?=($require_address?'required':'') ?> minlength="1" maxlength="200" placeholder="Ort" class="sp-form-input" type="text" name="place" id="place" onblur="updateCoordinatesFromAddress()">
            </div>
          </div>
          <div class="sp-hint sp-has-text-red sp-hidden" id="postcode_hint">Bitte gib eine Postleitzahl an!</div>
          <div class="sp-hint sp-has-text-red sp-hidden" id="place_hint">Bitte gib einen Ort ein!</div>
        </div>

      </div>

    </div>
  </div>

  <?PHP if($show_opening_hours){ ?>
    <div class="sp-columns">
      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          <label for="opening_hours">Öffnungszeiten</label>
          <textarea required minlength="1" maxlength="200" placeholder="Montag - Freitag 9.00-16.00" class="sp-form-input" name="opening_hours" id="opening_hours"></textarea>
          <div class="sp-hint sp-has-text-red sp-hidden" id="opening_hours_hint">Bitte gib an, wann man vorbeischauen kann!</div>
        </div>

      </div>
    </div>
  <?PHP } ?>

  <input type="hidden" id="type" name="type" value="<?=$selected_type ?>">

  <?PHP if($show_description){ ?>

    <div class="sp-columns enable-wrap">

      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          <label for="description">Beschreibe kurz, was dich stört (optional)</label>
          <textarea minlength="1" maxlength="2000" placeholder="Mich stört, dass..." class="sp-form-input" name="description" id="description"></textarea>
          <div class="sp-hint sp-has-text-red sp-hidden" id="description_hint">Bitte beschreibe kurz, was dich stört!</div>
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="solution">Wie könnte das Problem gelöst werden? (optional)</label>
          <textarea minlength="1" maxlength="2000" placeholder="Ich denke, dass..." class="sp-form-input" name="solution" id="solution"></textarea>
          <div class="sp-hint sp-has-text-red sp-hidden" id="solution_hint">Beschreibe, wie man das Problem lösen könnte!</div>
        </div>

      </div>
    </div>
  <?PHP } ?>

  <div class="sp-columns">
    <div class="sp-column is-full">

      <div class="sp-has-margin-bottom-2">
        Bitte gib an, wen wir bezüglich weiterer Fragen zu diesem Ort kontaktieren können (Diese Daten sind nicht öffentlich)
      </div>

      <div class="sp-has-margin-bottom-2">
        <label for="contact_person">Name<?=($require_personal_data?'':' (optional)') ?></label>
        <input <?=($require_personal_data?'required':'') ?> minlength="1" maxlength="200" placeholder="Name und Nachname" class="sp-form-input" type="text" name="contact_person" id="contact_person">
        <div class="sp-hint sp-has-text-red sp-hidden" id="contact_person_hint">Bitte gib den Namen einer Kontaktperson an!</div>
      </div>

    </div>
  </div>

  <div class="sp-columns enable-wrap">

    <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet">
      <div class="sp-has-margin-bottom-2">
        <label for="email">Email<?=($require_personal_data?'':' (optional)') ?></label>
        <input <?=($require_personal_data?'required':'') ?> minlength="1" maxlength="200" placeholder="Email" class="sp-form-input" type="email" name="email" id="email">
        <div class="sp-hint sp-has-text-red sp-hidden" id="email_hint">Bitte gib eine Mail für die Kontaktperson an!</div>
      </div>
    </div>

    <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">
      <div class="sp-has-margin-bottom-2">
        <label for="telephone">Telefonnummer<?=($require_personal_data?'':' (optional)') ?></label>
        <input <?=($require_personal_data?'required':'') ?> minlength="1" maxlength="200" placeholder="Telefonnummer" class="sp-form-input" type="text" name="telephone" id="telephone">
        <div class="sp-hint sp-has-text-red sp-hidden" id="telephone_hint">Bitte gib an, wie wir die Kontaktperson telefonisch erreichen können!</div>
      </div>
    </div>

  </div>

  <div class="sp-columns sp-has-margin-bottom-2">
    <div class="sp-column is-full">

      <label class="sp-has-margin-bottom-2">
        <input type="checkbox" required id="privacy">
        <span class="sp-hint">
          Ich bestätige hiermit, dass die von mir eingegebenen Daten zur Location auf der Website veröffentlicht werden dürfen. Meine persönlichen Kontaktinformationen wie Name, Mail und Telefonnummer dienen lediglich der Kontaktaufnahme durch das Radentscheid-Team.
          Sie sind nicht öffentlich auf der Website einsehbar. Ich bestätige außerdem, dass ich die Rechte an dem evenuell hochgeladenen Bild besitze. Ich habe außerdem die Datenschutzbestimmungen gelesen und verstanden.
        </span>
        <div class="sp-hint sp-has-text-red sp-hidden" id="privacy_hint">Bitte bestätige die Datenschutzbestimmungen!</div>
      </label>

      <?php wp_nonce_field( 'sp_location_add' ); ?>

      <input id="submit_button" type="button" value="<?=$submit_value ?>" onClick="submitForm()">

      <div id="submit_form_errors" class="sp-hint sp-has-text-red sp-hidden">
        Es ist ein Fehler aufgetreten. Bitte schau dir das Formular noch einmal an.
      </div>

    </div>
  </div>

</form>

<div id="submit_form_message" class="sp-hidden">
  <img src="<?=plugins_url().'/sp-locations/assets/img/ajax-loader.gif' ?>" >
  Bitte warte einen Moment. Deine Daten werden hochgeladen.
</div>

<div id="submit_form_error" class="sp-hidden sp-has-text-red">
  Es ist leider ein Problem aufgetreten, welches wir jetzt nicht lösen können. Bitte nimm Kontakt mit uns auf.
</div>

<div id="submit_form_success" class="sp-hidden sp-has-text-green">
  Vielen Dank! Wir prüfen deine Daten und setzen uns so schnell wie möglich mit dir in Verbindung.
  Danach schalten wir den Ort auf unserer interaktiven Karte frei.
</div>

<script>

var lat = null;
var lng = null;

var suburb = null;

var mymap = null;
var mymarker = null;

var file = null;

function updateAddressFromCoordinates () {
  requestJSON('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng, {}, function(data){
    // console.log(data);

    if(data.address != undefined) {

        if(data.address.city != undefined) {
          document.getElementById('place').value = data.address.city;
        }else{
          if(data.address.village != undefined) {
            document.getElementById('place').value = data.address.village;
          }
          else{
            if(data.address.town != undefined) {
              document.getElementById('place').value = data.address.town;
            }
            else{
              document.getElementById('place').value = '';
            }
          }
        }

        if(data.address.house_number != undefined) {
          document.getElementById('house_number').value = data.address.house_number;
        }else{
          document.getElementById('house_number').value = '';
        }

        if(data.address.road != undefined) {
          document.getElementById('street').value = data.address.road;
        }else{
          if(data.address.pedestrian != undefined) {
            document.getElementById('street').value = data.address.pedestrian;
          }else{
            document.getElementById('street').value = '';
          }
        }

        if(data.address.postcode != undefined) {
          document.getElementById('postcode').value = data.address.postcode;
        }else{
          document.getElementById('postcode').value = '';
        }

        if(data.address.suburb != undefined) {
          suburb = data.address.suburb;
        }else{
          suburb = '';
        }

      } else {
        // Error
      }

  }, 'GET');
}

function updateCoordinatesFromAddress(){

  var postcode = document.getElementById('postcode').value;
  var street = document.getElementById('street').value;
  var place = document.getElementById('place').value;
  var house_number = document.getElementById('house_number').value;

  requestJSON('https://nominatim.openstreetmap.org/search?format=jsonv2&city='+place+'&postalcode='+postcode+'&street='+house_number+' '+street, {}, function(data){
    // console.log(data);

    if(data[0] != undefined) {
      lat = data[0].lat;
      lng = data[0].lon;
      mymap.setView([lat, lng])
      mymarker.setLatLng([lat, lng]);
    }

  });

}

function dropFiles(ev) {

  ev.preventDefault();

  if (ev.dataTransfer.items) {
    // Use DataTransferItemList interface to access the file(s)
    for (var i = 0; i < ev.dataTransfer.items.length; i++) {
      // If dropped items aren't files, reject them
      if (ev.dataTransfer.items[i].kind === 'file') {
        file = ev.dataTransfer.items[i].getAsFile();
        previewFile();
        // console.log('... file[' + i + '].name = ' + file.name);
      }
    }
  } else {
    // Use DataTransfer interface to access the file(s)
    for (var i = 0; i < ev.dataTransfer.files.length; i++) {
      // console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
      file = ev.dataTransfer.files[i];
      previewFile();
    }
  }
}

function dragOver(event){
  event.currentTarget.classList.add('dragover');
  event.preventDefault();
}

function dragLeave(event){
  event.currentTarget.classList.remove('dragover');
  event.preventDefault();
}

function handleFileUpload () {
  file = document.getElementById('upload_file').files[0]; // This is the file we need in the submit method later
  previewFile();
}

function previewFile(){
  var preview = document.getElementById('upload_preview');
  var label = document.getElementById('upload_file_label');
  // This code will create a base 64 preview representation of the image
  if (file) {
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      label.classList.add('sp-hidden');
    }
    // Start the reader job - read file as a data url (base64 format)
    reader.readAsDataURL(file);
  }
}

function submitForm () {

  var validationError = false;

  if(!document.getElementById('add_location_form').checkValidity()){

    validationError = true;

    validateFormElement('title');
    validateFormElement('place');
    validateFormElement('house_number');
    validateFormElement('street');
    validateFormElement('postcode');
    validateFormElement('privacy');

    <?PHP if($show_opening_hours){ ?>
      validateFormElement('opening_hours');
    <?PHP } ?>

    <?PHP if($require_personal_data) { ?>
      validateFormElement('contact_person');
      validateFormElement('telephone');
      validateFormElement('email');
    <?PHP } ?>

  }

  // Check image
  <?PHP if($require_image) { ?>
    document.getElementById('file_hint').classList.add('sp-hidden');
    document.getElementById('file').classList.remove('sp-is-invalid');
    if(!file) {
      validationError = true;
      document.getElementById('file_hint').classList.remove('sp-hidden');
      document.getElementById('file').classList.add('sp-is-invalid');
    }
  <?PHP } ?>

  document.getElementById('submitmap_hint').classList.add('sp-hidden');
  document.getElementById('submitmap').classList.remove('sp-is-invalid');
  if(!lat||!lng){
    validationError = true;
    document.getElementById('submitmap_hint').classList.remove('sp-hidden');
    document.getElementById('submitmap').classList.add('sp-is-invalid');
  }

  document.getElementById('submit_form_errors').classList.add('sp-hidden');
  if(validationError){
    document.getElementById('submit_form_errors').classList.remove('sp-hidden');
  }

  if(!validationError){

    var formData = new FormData()

    // Image
    formData.append('image', file)
    formData.append('title', document.getElementById('title').value);

    // Location
    formData.append('place', document.getElementById('place').value);
    formData.append('house_number', document.getElementById('house_number').value);
    formData.append('street', document.getElementById('street').value);
    formData.append('postcode', document.getElementById('postcode').value);
    formData.append('suburb', suburb);

    <?PHP if($show_opening_hours){ ?>
      formData.append('opening_hours', document.getElementById('opening_hours').value);
    <?PHP } ?>

    // General
    <?PHP if($show_description){ ?>
      formData.append('description', document.getElementById('description').value);
      formData.append('solution', document.getElementById('solution').value);
    <?PHP } ?>

    formData.append('type', document.getElementById('type').value);

    // Contact
    formData.append('contact_person', document.getElementById('contact_person').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('telephone', document.getElementById('telephone').value);

    // Coordinates
    formData.append('lat', lat);
    formData.append('lng', lng);

    // Nonce
    formData.append('_wpnonce', document.getElementById('_wpnonce').value);

    // Disable form on send
    document.getElementById('add_location_form').classList.add('sp-hidden');
    // document.getElementById('submit_button').classList.add('sp-hidden');
    document.getElementById('submit_form_message').classList.remove('sp-hidden');
    document.getElementById('submit_form_message').scrollIntoView();

    // Send Request
    requestJSON("<?=get_site_url(null,'/wp-admin/admin-ajax.php?action=splocationadd') ?>", formData, function(data) {

      if(data.status=='success') {
        document.getElementById('submit_form_success').classList.remove('sp-hidden');
        document.getElementById('submit_form_success').scrollIntoView();
        document.getElementById('submit_form_error').classList.add('sp-hidden');
        document.getElementById('add_location_form').classList.add('sp-hidden');
      }else{
        document.getElementById('submit_form_error').innerHTML = data.message;
        document.getElementById('submit_form_error').classList.remove('sp-hidden');
        document.getElementById('add_location_form').classList.remove('sp-hidden');
        document.getElementById('submit_form_error').scrollIntoView();
      }

      // document.getElementById('submit_button').classList.remove('sp-hidden');
      document.getElementById('submit_form_message').classList.add('sp-hidden');

    }, function(){

      document.getElementById('submit_form_error').innerHTML = 'Leider ist etwas schief gelaufen :-(';
      document.getElementById('submit_form_error').classList.remove('sp-hidden');
      document.getElementById('submit_form_error').scrollIntoView();

      // document.getElementById('submit_button').classList.remove('sp-hidden');
      document.getElementById('add_location_form').classList.remove('sp-hidden');
      document.getElementById('submit_form_message').classList.add('sp-hidden');

    }, 'POST');

  }

}

document.addEventListener("DOMContentLoaded", function(event) {

  // Create the map
  mymap = L.map('submitmap');

  // Default coordinates
  mymap.setView([<?=$lat ?>, <?=$lng ?>], <?=$zoom ?>);

  // Add tile layer
  L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {

  }).addTo(mymap);

  var icon = L.divIcon({
     className: 'map-marker',
     iconSize:null,
     html:'<div class="sp-map-marker"><img style="height:100%;width:auto;" src="<?=plugins_url().'/sp-locations/assets/img/marker.svg' ?>"></div>'
   });

  // Add marker
  mymarker = L.marker([0, 0], {icon: icon}).addTo(mymap);

  // Update center coordinates on click
  mymap.on('click', function (e) {
    lat = e.latlng.lat;
    lng = e.latlng.lng;
    mymap.setView([lat, lng]);
    mymarker.setLatLng([lat, lng]);
    updateAddressFromCoordinates();
  });

  // Auto get coordinates from browser
  getGeolocation(function (position) {
    lat = position.coords.latitude;
    lng = position.coords.longitude;
    mymap.setView([lat, lng], 13)
    mymarker.setLatLng([lat, lng]);
    updateAddressFromCoordinates();
  }, function (error) {
    console.log(error)
  })

});

</script>
