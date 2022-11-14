<div class="listing-search">
    <form action="<?php echo esc_url($listings_root); ?>" class="listing-search__form" gh-search-listings-form>
        <div class="listing-search__select-boxes">
            <?php
            foreach ( $taxonomies as $taxonomy_select_box ) {
                ?>
                <div class="gf-field-wrapper <?php echo $taxonomy_select_box['slug'] ?> al-js-<?php echo $taxonomy_select_box['slug'] ?> ">
                    <select class="gh-js-<?php echo $taxonomy_select_box['slug']; ?> select2"
                            name="<?php echo $taxonomy_select_box['slug'] ?>">
                        <option value=""><?php echo $taxonomy_select_box['title']; ?></option>
                        <?php foreach ($taxonomy_select_box['options'] as $term_data) { ?>
                            <option value="<?php echo $term_data['slug']; ?>"
                                    data-slug="<?php echo $term_data['slug']; ?>"
                                <?php selected($term_data['selected']) ?>><?php echo $term_data['name']; ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <?php
            } ?>
        </div>
        <div class="listing-search__submit-and-keyword">
            <div class="gf-field-wrapper gf-field-wrapper__text">
                <input type="text" name="s" placeholder="<?php _e('Keyword', 'gunhub'); ?>" value="<?php echo esc_attr( $keyword ); ?>">
            </div>
            <div class="gf-field-wrapper gf-field-wrapper__submit-button">
                <button type="submit"><?php _e('Search', 'gunhub'); ?> <span class="hide-xs"><?php _e('Listing', 'gunhub'); ?></span></button>
            </div>
        </div>
    </form>
</div>
