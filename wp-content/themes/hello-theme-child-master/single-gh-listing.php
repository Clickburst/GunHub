<?php

get_header();

?>
<main <?php post_class( 'single-listing site-main' ); ?> role="main">
    <?php do_action('gunhub_single_listing'); ?>
</main>
<?php
get_footer();
