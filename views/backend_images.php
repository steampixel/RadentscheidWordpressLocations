
<?PHP

$images = get_post_meta( $post_id, 'images', true )

?>

<table>

  <tr>
    <th style="width:40%;">Image</th>
    <th style="width:40%;">Image description</th>
    <th style=""></th>
  </tr>

  <?PHP
  if($images) {

    foreach($images as $key => $image) {

      ?>

      <tr>
        <td>
          <img src="<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/'.$image['src'].'?rand='.rand( 0 , 9999 ) ?>" style="width:100%;">
        </td>
        <td valign="top">
          <textarea style="width:100%;min-height:200px;" name="sp_edit_image_description_<?=$key ?>"><?=$image['description'] ?></textarea>
        </td>
        <td valign="top">
          Change image: <input type="file" accept="image/*" name="sp_edit_image_src_<?=$key ?>"><br/>
          <input type="submit" class="button" value="Save">
          <input type="submit" class="button" value="Rotate" name="sp_edit_image_rotate_<?=$key ?>">
          <input type="submit" style="color:red;" class="button" value="Delete" name="sp_edit_image_remove_<?=$key ?>">
        </td>
      </tr>

      <?PHP

    }

  }
  ?>

  <tr>
    <td>

    </td>
    <td>
      <textarea style="width:100%;min-height:200px;" name="sp_create_image_description"></textarea>
    </td>
    <td valign="top">
      New image: <input type="file" accept="image/*" name="sp_create_image_src">
      <input type="submit" class="button" value="Save">
    </td>
  </tr>

</table>
