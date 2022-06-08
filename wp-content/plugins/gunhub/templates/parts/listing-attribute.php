<ul class="listing-content__attributes">
    <?php foreach ( $attributes as $key => $val ) {
        if( $val === '' ) continue;
        ?>
        <li>
            <span class="listing-attribute">
                <span class="listing-attribute__title"><?php echo str_replace('_', ' ', $key) ?>:</span> <span class="listing-attribute__value"><?php echo $val; ?></span>
            </span>
        </li>
        <?php
    } ?>

</ul>