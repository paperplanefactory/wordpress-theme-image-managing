<?php
// custom image size for featured images
add_theme_support( 'post-thumbnails' );
add_image_size( 'full_desk_retina', 3840, 9999);
add_image_size( 'full_desk', 1920, 9999);
add_image_size( 'content_picture', 768, 9999);
add_image_size( 'logo_size', 200, 9999);
add_image_size( 'micro', 10, 9999);


function print_theme_image( $image_data, $image_sizes ) {
  if( count( $image_data ) > 0 ) {
    $image_data_select = $image_data['image_type']; // verify if it's post_thumbnail, acf_field or acf_sub_field
    $size_fallback = $image_data['size_fallback']; // verify crop to use as fallback
    if ( $image_data_select === 'acf_field' ) { // ACF FIELD - be sure to set "image -> image array in ACF options"
      $thumb_id_pre = get_field( $image_data['image_value'] ); // retrieve the image data array
      $thumb_id = $thumb_id_pre['ID']; // retrieve the image ID
    }
    elseif ( $image_data_select === 'acf_sub_field' ) { // ACF SUB FIELD - be sure to set "image -> image array in ACF options"
      $thumb_id_pre = get_sub_field( $image_data['image_value'] ); // retrieve the image data array
      $thumb_id = $thumb_id_pre['ID']; // retrieve the image ID
    }
    elseif ( $image_data_select === 'post_thumbnail' ) { // nomrmal featured image
      $thumb_id = get_post_thumbnail_id( $post_id ); // retrieve the image ID
    }
    if ( $thumb_id != '' ) {
      $attachment_title = get_the_title( $thumb_id ); // image title
      $attachment_alt = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ); // image alt text
      if( count( $image_sizes ) > 0 ) {
        $sharped_iamges = array(); // declare arry to use later in data-srcset
        foreach( $image_sizes as $image_size) {
          $thumb_url[$image_size] = wp_get_attachment_image_src( $thumb_id, $image_size, true ); // retrive array for desired size
          if ( $thumb_url[$image_size][3] == true ) { // check if cropped image exists
            $sharped_iamges[] = $thumb_url[$image_size][0]; // retrive image URL
          }
          else { // if cropped image does not exist
            $thumb_url[$image_size] = wp_get_attachment_image_src( $thumb_id, $size_fallback, true ); // retrive array for fallback size
            $sharped_iamges[] = $thumb_url[$image_size][0]; // retrive fallback image URL
          }
        }
        // this is simple HTML - remember to use lazyload (https://github.com/verlok/lazyload) for better performance
        $html_image_output = '';
        $html_image_output .= '<div class="no-the-100">';
        $html_image_output .= '<picture>';
        $html_image_output .= '<source media="(max-width: 767px)" data-srcset="'.$sharped_iamges[2].'">';
        $html_image_output .= '<source media="(max-width: 1920px)" data-srcset="'.$sharped_iamges[1].'">';
        $html_image_output .= '<source media="(min-width: 1921px)" data-srcset="'.$sharped_iamges[0].'">';
        $html_image_output .= '<img data-src="'.$sharped_iamges[1].'" src="'.$sharped_iamges[3].'" title="'.$attachment_title.'" alt="'.$attachment_alt.'" class="lazy" />';
        $html_image_output .= '</picture>';
        $html_image_output .= '</div>';
        echo $html_image_output;
      }
    }
  }
}