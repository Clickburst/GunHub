<?php
use GunHub\Data\Listing;
use GunHub\GunHub;

global $post;

$listing_data = new Listing( get_the_ID() );
$post_link = get_permalink();

$attributes = [
    'state' => $listing_data->get_state(),    
    'action' => $listing_data->get_action(),    
    'sights' => $listing_data->get_sights(),    
    'calibre' => $listing_data->get_calibre(),    
];

?>

<article class="listing-loop">
    <div class="listing-loop__img">
        <div class="img-wrapper">
            <?php printf( '<a href="%s">%s</a>', esc_url( $post_link ), get_the_post_thumbnail( $post, 'post-medium' ) );        ?>
        </div>
    </div>
    <div class="listing-loop__body">
        <div class="listing-header">
        <?php printf( '<h2 class="%s"><a href="%s">%s</a></h2>', 'listing-header__title', esc_url( $post_link ), esc_html( get_the_title() ) ); ?>
        </div>
        <div class="listing-loop__price"><?php echo $listing_data->get_price(); ?></div>
        <div class="listing-loop__condition"><?php echo $listing_data->get_condition(); ?></div>
    </div>
    <div class="listing-loop__attributes">

        <?php GunHub::get_instance()->plugin_path . '/templates/parts/listing-attribute.php' ?>
        
    </div>
</article>