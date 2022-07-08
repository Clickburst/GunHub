<?php
/**
 * The template for displaying archive pages.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<main class="site-main" role="main">

    <?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
        <header class="page-header">
            <h1 class="entry-title text-center">Listings</h1>
            <?php
            the_archive_description( '<p class="archive-description">', '</p>' );
            ?>
        </header>
    <?php endif; ?>
    
    <?php do_action('gunhub_archive_listing') ?>
</main>

<?php
get_footer();
