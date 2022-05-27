<?php

use GunHub\Data\Listing;

$listing_data = new Listing( get_the_ID() ); 
?>
<header class="listing-header">
    <?php the_title( '<h1 class="listing-header__title">', '</h1>' ); ?>
    <h3 class="listing-header__price"><?php echo $listing_data->get_price() ?></h3>
</header>

<div class="page-content listing-content">
    <div class="listing-content__left">
        <div class="listing-content__gallery gh-box" id="gallery-items">
            <?php $gallery = $listing_data->get_gallery();

            if (!empty( $gallery )) {
            $first_img = array_shift( $gallery );
            ?>
            <a href="<?php echo $first_img['url'] ?>" title="<?php echo $first_img['alt'] ?>">
                <img src="<?php echo $first_img['sizes']['medium_large']; ?>"
                     alt="<?php echo $first_img['alt']; ?>"/>
            </a>
            <?php
            ?>
            <div >
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
        </div>
        <?php
        }
        get_template_part('templates/parts/listing', 'attribute', $listing_data->get_attributes_list());
        ?>
    </div>

    <div class="listing-content__right">
        <h3>Seller user data</h3>
        <div class="listing-content__description gh-box">
            <h4>Description</h4>
            <?php the_content(); ?>
        </div>
    </div>
</div>