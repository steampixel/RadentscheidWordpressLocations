
<form class="sp-xhr-form" method="post" action="/wp-admin/admin-ajax.php?action=splocationadd" <?=(get_option('sp-locations_rsa_enable')? 'data-enable-rsa-encryption="true" data-rsa-public-key="'.get_option('sp-locations_rsa_public_key').'"':'') ?>>

  <div class="sp-xhr-form-fields">

    <div class="sp-columns sp-has-margin-bottom-2">
      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          <label for="title"><?=$name_label ?></label>
          <input id="title" required minlength="1" maxlength="200" placeholder="<?=$name_placeholder ?>" class="sp-xhr-form-data sp-xhr-form-input" type="text" name="title">
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="title">Bitte gib einen Titel ein!</div>
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="type">Was möchtest du melden?</label>
          <select required class="sp-xhr-form-data sp-xhr-form-input" name="type" id="type">
            <?PHP
            foreach($available_types as $available_type) {
              $marker_key = get_post_meta($available_type->ID, 'key', true);
              ?>
              <option value="<?=$marker_key?>" <?=($selected_type==$marker_key? 'selected':'') ?>><?=$available_type->post_title ?></option>
              <?PHP
            }
            ?>
          </select>
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="type">Bitte wähle aus, was du melden möchtest!</div>
        </div>

        <div class="sp-has-margin-bottom-2">
          <?php
          $max_upload = (int)(ini_get('upload_max_filesize'));
          $max_post = (int)(ini_get('post_max_size'));
          $memory_limit = (int)(ini_get('memory_limit'));
          $upload_mb = min($max_upload, $max_post, $memory_limit);
          ?>
          <label for="upload_file"><?=$file_label ?> (<?=($require_image?'':'optional ') ?>max <?=$upload_mb ?>MB)</label>
          <label class="sp-file-uploader">
            <input <?=($require_image?' required':' ') ?> id="upload_file" class="sp-xhr-form-data sp-xhr-form-input sp-file-uploader-input" name="image" type="file" accept="image/*">
            <span class="sp-file-uploader-label">
              <?=$file_placeholder ?>
            </span>
            <img class="sp-file-uploader-image">
          </label>
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="image">Bitte wähle ein Bild aus!</div>
        </div>
      </div>
    </div>

    <div class="sp-location-picker sp-has-margin-bottom-2" data-lat="<?=$lat ?>" data-lng="<?=$lng ?>" data-zoom="<?=$zoom ?>" data-marker-icon="<?=plugin_dir_url(__DIR__).'assets/img/marker.svg'; ?>">
      <label for="street">Gib die Adresse ein oder setze die Position auf der Karte</label>
      <div class="sp-columns enable-wrap">

        <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet sp-has-margin-bottom-2">
          <div class="sp-location-picker-map"></div>
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lat" name="lat">
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lng" name="lng">
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-suburb" name="suburb">
          <div class="sp-location-picker-map-hint sp-xhr-form-hint sp-has-text-red">
            Die Koordinaten konnten scheinbar nicht automatisch ermittelt werden. Bitte setze die Position auf der Karte selbst.
          </div>
        </div>

        <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">

          <div class="sp-has-margin-bottom-2">
            <label for="street">Straße und Hausnummer<?=($require_address?'':' (optional)') ?></label>
            <div class="sp-columns">
              <div class="sp-column is-two-third">
                <input <?=($require_address?'required':'') ?> id="street" minlength="1" maxlength="200" placeholder="Straße" class="sp-location-picker-input-street sp-xhr-form-data sp-xhr-form-input" type="text" name="street">
              </div>
              <div class="sp-column is-one-third">
                <input <?=($require_address?'required':'') ?> minlength="1" maxlength="10" placeholder="Hausnummer" class="sp-location-picker-input-house-number sp-xhr-form-data sp-xhr-form-input" type="text" name="house_number">
              </div>
            </div>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="street">Bitte gib eine Straße ein!</div>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="house_number">Bitte gib eine Hausnummer ein!</div>
          </div>

          <div class="sp-has-margin-bottom-2">
            <label for="postcode">Postleitzahl und Ort<?=($require_address?'':' (optional)') ?></label>
            <div class="sp-columns">
              <div class="sp-column is-one-third">
                <input <?=($require_address?'required':'') ?> id="postcode" placeholder="Postleitzahl" class="sp-location-picker-input-postcode sp-xhr-form-data sp-xhr-form-input" type="number" name="postcode">
              </div>
              <div class="sp-column is-two-third">
                <input <?=($require_address?'required':'') ?> minlength="1" maxlength="200" placeholder="Ort" class="sp-location-picker-input-place sp-xhr-form-data sp-xhr-form-input" type="text" name="place">
              </div>
            </div>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="postcode">Bitte gib eine Postleitzahl an!</div>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="place">Bitte gib einen Ort ein!</div>
          </div>

        </div>

      </div>
    </div>

    <?PHP
    // This is a simple honeypot
    // The form will only simulate the success if this field gets filled
    ?>
    <label class="sp-fg56bn67">
      Email
      <input placeholder="Deine Mail" class="sp-xhr-form-data sp-xhr-form-input" type="email" name="mail">
    </label>

    <?PHP if($show_description){ ?>

      <div class="sp-columns enable-wrap">

        <div class="sp-column is-full">

          <div class="sp-has-margin-bottom-2">
            <label for="description">Beschreibe kurz, was dich stört (optional)</label>
            <textarea minlength="1" maxlength="2000" placeholder="Mich stört, dass..." class="sp-xhr-form-data sp-xhr-form-input" name="description" id="description"></textarea>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="description">Bitte beschreibe kurz, was dich stört!</div>
          </div>

          <div class="sp-has-margin-bottom-2">
            <label for="solution">Wie könnte das Problem gelöst werden? (optional)</label>
            <textarea minlength="1" maxlength="2000" placeholder="Ich denke, dass..." class="sp-xhr-form-data sp-xhr-form-input" name="solution" id="solution"></textarea>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="solution">Beschreibe, wie das Problem gelöst werden könnte!</div>
          </div>

        </div>
      </div>
    <?PHP } ?>

    <div class="sp-columns">
      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          Bitte gib an, wen wir bezüglich weiterer Fragen zu diesem Ort kontaktieren können
          <span class="sp-xhr-form-hint">(Diese Daten sind nicht öffentlich<?=(get_option('sp-locations_rsa_enable')?' und speziell durch Ende-Zu-Ende Verschlüsselung geschützt':'') ?>)</span>
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="contact_person">Name<?=($require_personal_data?'':' (optional)') ?></label>
          <input <?=($require_personal_data?'required':'') ?> data-encrypt="true" minlength="1" maxlength="200" placeholder="Name und Nachname" class="sp-xhr-form-data sp-xhr-form-input" type="text" name="contact_person" id="contact_person">
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="contact_person">Bitte gib den Namen einer Kontaktperson an!</div>
        </div>

      </div>
    </div>

    <div class="sp-columns enable-wrap">

      <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet">
        <div class="sp-has-margin-bottom-2">
          <label for="email">Email<?=($require_personal_data?'':' (optional)') ?></label>
          <input <?=($require_personal_data?'required':'') ?> data-encrypt="true" minlength="1" maxlength="200" placeholder="Email" class="sp-xhr-form-data sp-xhr-form-input" type="email" name="email" id="email">
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="email">Bitte gib eine Mail für die Kontaktperson an!</div>
        </div>
      </div>

      <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">
        <div class="sp-has-margin-bottom-2">
          <label for="telephone">Telefonnummer<?=($require_personal_data?'':' (optional)') ?></label>
          <input <?=($require_personal_data?'required':'') ?> data-encrypt="true" minlength="1" maxlength="200" placeholder="Telefonnummer" class="sp-xhr-form-data sp-xhr-form-input" type="text" name="telephone" id="telephone">
          <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="telephone">Bitte gib an, wie wir die Kontaktperson telefonisch erreichen können!</div>
        </div>
      </div>

    </div>

    <div class="sp-columns sp-has-margin-bottom-2">
      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          <label>
            <input name="privacy" type="checkbox" class="sp-xhr-form-data" required>
            <span class="sp-xhr-form-hint">
              Du bestätigst hiermit, dass die von dir eingegebenen Daten zu diesem Ort auf der Website veröffentlicht werden dürfen.
              Zudem werden die hochgeladenen Daten der Öffentlichkeit zur freien Verwendung über unsere Schnittstelle angeboten.
              Deine persönlichen Kontaktinformationen dienen lediglich der Kontaktaufnahme durch unser Team und sind nicht öffentlich einsehbar.
              Du bestätigst, dass du die Rechte an den evenuell hochgeladenen Bildern besitzt.
              Du hast außerdem die Datenschutzbestimmungen gelesen, verstanden und akzeptiert.
            </span>
            <div class="sp-xhr-form-hint sp-has-text-red sp-hidden" data-input="privacy">Bitte bestätige die Datenschutzbestimmungen!</div>
          </label>
        </div>

        <div>
          <?php wp_nonce_field( 'sp_location_add' ); ?>

          <input class="sp-xhr-form-submit" type="button" value="<?=$submit_value ?>">
        </div>

      </div>
    </div>

  </div>

  <div class="sp-xhr-form-wait sp-hidden">
    <img src="<?=plugins_url().'/sp-locations/assets/img/ajax-loader.gif' ?>" >
    Bitte warte einen Moment. Deine Daten werden geprüft und hochgeladen.
  </div>

  <div class="sp-xhr-form-validation-error sp-hidden sp-has-text-red">
    Einige Felder sind nicht korrekt ausgefüllt. Bitte schau dir das Formular noch einmal an.
  </div>

  <div class="sp-xhr-form-error sp-hidden sp-has-text-red">
    Es ist leider ein Problem aufgetreten, welches wir jetzt nicht lösen können. Bitte nimm Kontakt mit uns auf.
  </div>

  <div class="sp-xhr-form-success sp-hidden sp-has-text-green">
    Vielen Dank! Wir prüfen deine Daten und setzen uns so schnell wie möglich mit dir in Verbindung.
    Danach schalten wir den Ort auf unserer interaktiven Karte frei.
  </div>

</form>
