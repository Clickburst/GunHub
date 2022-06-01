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
            <?php
            the_archive_title( '<h1 class="entry-title">', '</h1>' );
            the_archive_description( '<p class="archive-description">', '</p>' );
            ?>
        </header>
    <?php endif; ?>
    
    <?php 
    echo do_shortcode('[listings_search_form]')
    ?>
    
    <div class="page-content">
        <?php
        if( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                get_template_part( 'templates/listing','loop' );
            }
        } else {
            get_template_part( 'templates/no', 'listings' );
        }
        ?>
    </div>

    <?php wp_link_pages(); ?>

    <?php
    global $wp_query;
    if ( $wp_query->max_num_pages > 1 ) :
        ?>
        <div class="gh-listings-pagination">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( 'Prev', 'textdomain' ),
                'next_text' => __( 'Next', 'textdomain' ),
            ) );
            ?>
        </div>
        <?php
    endif; ?>
</main>

<?php
get_footer();
