<?php
/**
 * Created with PhpStorm.
 *
 * This file adds by-the-decade subcategories to Inductees category.
 *
 * @author marybaum
 * @package HOF-Parallax
 * @subpackage Customizations
 *
 * Date: 8/5/17
 * Time: 1:28 PM
 */

//remove_action( 'genesis_loop', 'genesis_do_loop' );
//add_action( 'genesis_loop', 'hof_show_subcats_do_loop' );
/**
 * Show linked child categories
 *
 */
/*function hof_show_subcats_do_loop() {

	$cat      = get_query_var( 'cat' );
	$category = get_category( $cat );

	if ( $category->cat_ID ) {

		$categories = get_categories(array(
			'orderby'=> 'name',
			'depth' => 1, // Depth is 1 because we only want the decades.
			'hide_empty' => true,
			'title_li' => 'Inductees',
			'child_of' => '2',
			'childless'=> false,

		));
		echo '<ul>';
		foreach( $categories as $category) {
			if (z_taxonomy_image_url($category->term_id)) {
				echo '<li class="decade">';
				// Category title
				echo '<h2 class="category-title"><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a></h2>';
				// Category image linking to category archive
				echo '<a href="' . get_category_link( $category->term_id ) . '"><img src="'. z_taxonomy_image_url($category->term_id, 'archive') . '" /></a>';
				// Category description
				//echo category_description( $category->term_id );
				// Custom 'Read More' link
				//echo '<a href="' . get_category_link( $category->term_id ) . '">All Posts under ' . $category->name . ' category &raquo;</a>';
				echo '</li>';

			}
		}
		echo '</ul>';

	}
}

genesis();
