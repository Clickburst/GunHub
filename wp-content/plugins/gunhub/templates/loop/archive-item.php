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

$actions = $status = $js_selector ='';
if( $is_account = function_exists('is_account_page') && is_account_page() ) {
    $actions = $listing_data->get_action_buttons_html(); 
    $status = $listing_data->get_pretty_status_html();
    $js_selector = 'gunhub-listing-wrapper';
}
?>

<article class="listing-loop" <?php echo $js_selector; ?>>
    <?php echo $actions; ?>
    <?php echo $status; ?>
    <div class="listing-loop__img">
        <div class="img-wrapper">
            <?php 
            if( $is_account ) {
                echo $listing_data->get_featured_image_html();
            } else {
                printf( '<a href="%s">%s</a>', esc_url( $post_link ), $listing_data->get_featured_image_html() );
            }
            ?>
        </div>
    </div>
    <div class="listing-loop__body">
        <div class="listing-header">
            <?php
            if( $is_account ) {
                printf( '<h2 class="listing-header__title">%s</h2>', esc_html( get_the_title() ) );
            } else {
                printf( '<h2 class="listing-header__title"><a href="%s">%s</a></h2>',  esc_url( $post_link ), esc_html( get_the_title() ) );
            }
            ?>
        </div>
        <div class="listing-loop__price"><?php echo $listing_data->get_price(); ?></div>
        <div class="listing-loop__condition"><?php echo $listing_data->get_condition(); ?></div>
    </div>
    <div class="listing-loop__attributes">
        <?php require GunHub::get_instance()->plugin_path . '/templates/parts/listing-attribute.php' ?>
    </div>
</article>