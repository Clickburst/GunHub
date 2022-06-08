<?php

get_header();

?>
<main <?php post_class( 'site-main single-listing' ); ?> role="main">
    <?php do_action('gunhub_single_listing_body'); ?>
</main>
<?php
get_footer();
