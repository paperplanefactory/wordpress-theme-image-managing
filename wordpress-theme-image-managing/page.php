<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
  <?php
  $image_data = array(
    'image_type' => 'post_thumbnail', // options: post_thumbnail, acf_field, acf_sub_field
    'image_value' => 'immagine_acf', // if using a custom field place here the field's name
    'size_fallback' => 'full_desk' // crop to use as fallback (especially for retina images)
  );
  $image_sizes = array( // here the cut-outs or dimensions are defined. They must match the number if data-srcset or srcset parameters in the function
    'retina' => 'full_desk_retina',
    'desktop' => 'full_desk',
    'mobile' => 'content_picture',
    'micro' => 'micro' // this size is used as a micro preload image when using lazyload
  );
  print_theme_image( $image_data, $image_sizes );
  ?>

  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
<?php endwhile; ?>
<?php get_footer(); ?>
