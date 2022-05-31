<div class="listing-search">
    <form action="<?php echo esc_url($listings_root); ?>" class="listing-search__form">

        <div class="listing-search__select-boxes">
            <?php
            foreach ( $taxonomies as $taxonomy_select_box ) {
                ?>
                <div class="gf-field-wrapper <?php echo $taxonomy_select_box['slug'] ?> al-js-<?php echo $taxonomy_select_box['slug'] ?> ">
                    <label><?php echo $taxonomy_select_box['title']; ?></label>
                    <select class="gh-js-<?php echo $taxonomy_select_box['slug']; ?> gf-js-change"
                            name="<?php echo $taxonomy_select_box['slug'] ?>">
                        <option value="0"><?php _e('All', 'gunhub') ?></option>
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
            <div class="gf-field-wrapper">
                <label><?php _e('Keyword') ?></label>
                <input type="text" name="s" placeholder="<?php _e('Keyword', 'gunhub'); ?>" value="<?php echo esc_attr( $keyword ); ?>">
            </div>
            <div class="gf-field-wrapper gf-field-wrapper__align-right">
                <button type="submit"><?php _e('Search', 'gunhub'); ?></button>
            </div>
        </div>
        

<!--        <input type="hidden" name="page" value="1">-->
<!--        <input type="hidden" name="action" value="gh_ajax_load_content">-->
    </form>
</div>
