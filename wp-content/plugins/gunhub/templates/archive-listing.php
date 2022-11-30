<?php
use GunHub\GunHub;
use GunHub\Modules\Listing;

echo do_shortcode('[listings_search_form]')
?>

    <div class="page-content gunhub-archive-body">
        <?php
        if( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                Listing::archive_listing_item();
            }
        } else {
            require GunHub::get_instance()->plugin_path . '/templates/no-listings.php';
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