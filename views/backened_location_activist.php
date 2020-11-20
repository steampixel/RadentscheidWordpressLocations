<?php

if($contact_person||$email||$telephone) {

  if($rsa_public_key) {

    ?>

    <p>
      The contact data of this activist is encrypted.
      Insert the private key here and click the "decrypt" button to view its data.
    </p>

    <div>
      <textarea style="max-width:500px;width:100%;height:200px;" id="rsaPrivKey"></textarea>
      <textarea style="display:none;" id="rsaPubKey"><?=$rsa_public_key ?></textarea>
      <textarea style="display:none;" id="contact_person"><?=$contact_person ?></textarea>
      <textarea style="display:none;" id="email"><?=$email ?></textarea>
      <textarea style="display:none;" id="telephone"><?=$telephone ?></textarea>
      <input type="button" class="button" onclick="sp_rsa_decrypt()" value="decrypt" />
    </div>

    <script>
    function sp_rsa_decrypt() {

      var decrypt = new JSEncrypt();
      var encrypt = new JSEncrypt();

      var rsaPubKey = document.getElementById('rsaPubKey').value;
      var rsaPrivKey = document.getElementById('rsaPrivKey').value;

      decrypt.setPrivateKey(rsaPrivKey);
      encrypt.setPublicKey(rsaPubKey);

      // decrypt the data
      var contact_person_decrypted = decrypt.decrypt(document.getElementById('contact_person').value);
      var email_decrypted = decrypt.decrypt(document.getElementById('email').value);
      var telephone_decrypted = decrypt.decrypt(document.getElementById('telephone').value);

      // Test the decrypted data by encrypting it again
      var contact_person_encrypted = encrypt.encrypt(contact_person);
      var email_encrypted = encrypt.encrypt(email);
      var telephone_encrypted = encrypt.encrypt(telephone);

      // if(
      //   contact_person_encrypted == document.getElementById('contact_person').value &&
      //   email_encrypted ==  document.getElementById('email').value &&
      //   telephone_encrypted ==  document.getElementById('telephone').value
      // ) {
        alert(
          'Contact person: '+
          contact_person_decrypted+"\r\n"+
          'Email: '+
          email_decrypted+"\r\n"+
          'Telephone: '+
          telephone_decrypted
        );
      // }


    }
    </script>

    <?PHP

  } else {
    ?>

    <p>
      Contact person: <?=$contact_person ?><br/>
      E-Mail: <?=$email ?><br/>
      Telephone: <?=$telephone ?>
    </p>

    <?PHP
  }

  ?>

  <sub>
    This plugin is programmed to the best of my knowledge and belief. But I can not rule out mistakes.
    In addition, Wordpress and its plugin ecosystem are known for its numerous security holes in history.
    You should therefore delete the data of the activists when you no longer need them.
    The worst case would be if unprivileged have access to this data.
  </sub>
  <br>
  <input type="submit" class="button" style="color:red;" value="Permanently destroy this contact data" name="sp_remove_activist_data">

  <?PHP
} else {

  ?>
  No contact data available.
  <?PHP

} ?>
