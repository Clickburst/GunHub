<?php

get_header();

?>
<main <?php post_class( 'site-main single-listing' ); ?> role="main">
    <?php get_template_part( 'templates/listing', 'single' ); ?>
</main>

    
<?php
    get_template_part( 'templates/blueimp-gallery' );
    
get_footer();
