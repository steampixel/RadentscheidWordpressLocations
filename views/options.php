
<h2>Location Options</h2>

<form method="post" action="options.php">

  <?php settings_fields( 'sp-locations_options_group' ); ?>

  <table style="width:100%;" cellspacing="16">

    <tr valign="top">
      <td scope="row" style="width:200px;text-align:right;">
        <label for="sp-locations_notify_email">
          <strong>Email für die Benachrichtigung bei neu eingereichten Locations</strong><br>
        </label>
      </td>
      <td>
        <input id="sp-locations_notify_email" type="text" name="sp-locations_notify_email" value="<?php echo get_option('sp-locations_notify_email'); ?>" />
      </td>
    </tr>

    <tr valign="top">
      <td scope="row" style="width:200px;text-align:right;">
        <label for="sp-locations_map_post_url">
          <strong>URL zur Kartenseite (Wird benötigt um Links zur Karte auf den Detailseiten zu generieren)</strong><br>
        </label>
      </td>
      <td>
        <input id="sp-locations_map_post_url" type="text" name="sp-locations_map_post_url" value="<?php echo get_option('sp-locations_map_post_url'); ?>" />
      </td>
    </tr>

    <tr valign="top">
      <td scope="row" style="width:200px;text-align:right;">
        <label for="sp-locations_form_post_url">
          <strong>URL zur Formularseite (Wird benötigt um die Links zum Formular auf den Detailseiten zu generieren)</strong><br>
        </label>
      </td>
      <td>
        <input id="sp-locations_form_post_url" type="text" name="sp-locations_form_post_url" value="<?php echo get_option('sp-locations_form_post_url'); ?>" />
      </td>
    </tr>

    <tr valign="top">
      <td scope="row" style="width:200px;text-align:right;">
        <label for="sp-locations_rsa_enable">
          <strong>Enable RSA encryption</strong><br>
          <sub>Enable encryption of the personal activist data (name, email, telephone)</sub>
        </label>
      </td>
      <td>
        <input id="sp-locations_rsa_enable" type="checkbox" name="sp-locations_rsa_enable" <?=(get_option('sp-locations_rsa_enable')? 'checked':'') ?> />
      </td>
    </tr>

    <tr valign="top">
      <td scope="row" style="width:200px;text-align:right;">
        <label for="sp-locations_rsa_public_key">
          <strong>RSA public key</label></strong><br>
          <sub>This key is used to encrypt the personal data (name, email, telephone) of the activists. Attention! Do not put the private key in here!</sub>
        </label>
      </td>
      <td>
        <textarea style="height:200px;width:100%;" id="sp-locations_rsa_public_key" name="sp-locations_rsa_public_key" ><?php echo get_option('sp-locations_rsa_public_key'); ?></textarea>
      </td>
    </tr>

  </table>
  <?php  submit_button(); ?>
</form>
