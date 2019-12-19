
<h2>Location Options</h2>

<form method="post" action="options.php">

  <?php settings_fields( 'sp-locations_options_group' ); ?>

  <table style="width:100%;" cellspacing="16">
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
