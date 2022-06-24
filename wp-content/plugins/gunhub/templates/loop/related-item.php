<?php
use GunHub\Data\Listing;

global $post;

$listing_data = new Listing( get_the_ID() );

$post_link = get_permalink();
?>

<article class="listing-loop">
    <div class="listing-loop__img">
        <div class="img-wrapper">
            <?php printf( '<a href="%s">%s</a>', esc_url( $post_link ), $listing_data->get_featured_image_html() ); ?>
        </div>
    </div>
    <div class="listing-loop__body">
        <div class="listing-header">
        <?php printf( '<h2 class="%s"><a href="%s">%s</a></h2>', 'listing-header__title', esc_url( $post_link ), esc_html( get_the_title() ) ); ?>
        </div>
        <div class="listing-loop__condition"><?php echo $listing_data->get_condition(); ?></div>

        <div class="listing-loop__price"><?php echo $listing_data->get_price(); ?></div>
    </div>
</article>