
<?PHP
if($hasImage){
  ?>
  <img src="<?=$src ?>" style="width:100%;">
  <?PHP
}else{
  ?>
  <div>Kein Bild</div>
  <?PHP
}
?>

<label>
  Bild austauschen:
  <input type="file" accept="image/*" name="sp_change_image">
</label>

<label>

  <input type="submit" value="Bild drehen" name="sp_rotate_image">
</label>
