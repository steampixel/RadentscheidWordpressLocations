<?php

namespace Sp;

// Link image type to correct image loader and saver
// - makes it easier to add additional types later on
// - makes the function easier to read
const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
];

class Thumbnail {

  private static function getType($src){
    // get the type of the image
    // we need the type to determine the correct loader
    $type = \exif_imagetype($src);

    // if no valid type or no handler found -> exit
    if (!$type || !IMAGE_HANDLERS[$type]) {
        return null;
    }

    return $type;
  }

  private static function load($src, $type){

    // load the image with the correct loader
    $image = \call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

    // no image found at supplied location -> exit
    if (!$image) {
        return null;
    }

    return $image;
  }

  private static function store($image, $dest, $type){
    // save the duplicate version of the image to disk
    return \call_user_func(
        IMAGE_HANDLERS[$type]['save'],
        $image,
        $dest,
        IMAGE_HANDLERS[$type]['quality']
    );
  }

  public static function rotate($src, $angle) {

    $type = self::getType($src);
    if(!$type){  return null;   }

    $image = self::load($src, $type);
    if(!$image){  return null;  }

    $image = \imagerotate ( $image , $angle, 0 );

    // save the duplicate version of the image to disk
    self::store($image, $src, $type);
  }

  public static function create($src, $dest, $targetWidth, $targetHeight = null) {

      $type = self::getType($src);
      if(!$type){  return null;   }

      $image = self::load($src, $type);
      if(!$image){  return null;  }

      // get original image width and height
      $width = \imagesx($image);
      $height = \imagesy($image);

      // maintain aspect ratio when no height set
      if ($targetHeight == null) {

          // get width to height ratio
          $ratio = $width / $height;

          // if is portrait
          // use ratio to scale height to fit in square
          if ($width > $height) {
              $targetHeight = \floor($targetWidth / $ratio);
          }
          // if is landscape
          // use ratio to scale width to fit in square
          else {
              $targetHeight = $targetWidth;
              $targetWidth = \floor($targetWidth * $ratio);
          }
      }

      // create duplicate image based on calculated target size
      $thumbnail = \imagecreatetruecolor($targetWidth, $targetHeight);

      // set transparency options for GIFs and PNGs
      if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

          // make image transparent
          \imagecolortransparent(
              $thumbnail,
              \imagecolorallocate($thumbnail, 0, 0, 0)
          );

          // additional settings for PNGs
          if ($type == IMAGETYPE_PNG) {
              \imagealphablending($thumbnail, false);
              \imagesavealpha($thumbnail, true);
          }
      }

      // copy entire source image to duplicate image and resize
      \imagecopyresampled(
          $thumbnail,
          $image,
          0, 0, 0, 0,
          $targetWidth, $targetHeight,
          $width, $height
      );

      // save the duplicate version of the image to disk
      self::store($thumbnail, $dest, $type);
  }

}
