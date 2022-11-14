<?php
global $post;
$author_id = $post->post_author;
use GunHub\Data\Listing;
use GunHub\Data\Seller;

$listing_data = new Listing( get_the_ID() );
$seller_data = new Seller( $author_id )
?>

<?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
<header class="listing-header">
    <?php the_title( '<h1 class="listing-header__title">', '</h1>' ); ?>
    <h3 class="listing-header__price"><?php echo $listing_data->get_price() ?></h3>
</header>

<div class="page-content listing-content">
    <div class="listing-content__left">
        <div class="listing-content__gallery gh-box" id="gallery-items">
            <?php $gallery = $listing_data->get_gallery();

            if ( !empty( $gallery ) ) {
                $first_img = array_shift( $gallery );
                ?>
                <a href="<?php echo $first_img['url'] ?>" title="<?php echo $first_img['alt'] ?>">
                    <img src="<?php echo $first_img['sizes']['medium_large']; ?>"
                         alt="<?php echo $first_img['alt']; ?>"/>
                </a>
                <?php
                ?>
                <div class="listing-content__gallery-list">
                    <?php
                    foreach ( $gallery as $item ) {
                        ?>
                        <a href="<?php echo $item['url'] ?>" title="<?php echo $item['alt'] ?>">
                            <img src="<?php echo $item['sizes']['thumbnail']; ?>" alt="<?php echo $item['alt']; ?>"/>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            $attributes = $listing_data->get_attributes_list();
            require 'parts/listing-attribute.php';
            ?>
        </div>
        
        <?php do_action('gunhub_before_listing_description', get_the_ID()); ?>

        <div class="listing-content__description gh-box gh-box__with-padding">
            <h4 class="gh-section-title"><?php esc_html_e('Description', 'gunhub'); ?></h4>
            <?php the_content(); ?>
        </div>
        <?php do_action('gunhub_after_listing_description', get_the_ID()); ?>

    </div>

    <div class="listing-content__right">
        <div class="listing-content__seller-data gh-box gh-box__with-padding">
            <h4 class="gh-section-title"><?php esc_html_e('Seller Information', 'gunhub'); ?></h4>
            <?php
            $attributes = $seller_data->get_data();
            require 'parts/listing-attribute.php';
            ?>
        </div>

        <div class="enqueue-about-listing gh-box gh-box__with-padding">
            <h4 class="gh-section-title"><?php printf( __('Date Listed: %s'), get_the_date() ); ?></h4>
            <?php do_action('gunhub_contact_seller_form'); ?>
        </div>
    </div>
</div>