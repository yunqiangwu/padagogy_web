<?php 
if ( is_tax('gallery') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'gallery' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'gallery' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('videos') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'videos' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'videos' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('taobao') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'taobao' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'taobao' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('notice') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'notice' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'notice' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'tao' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'taobao', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'taobao' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'taobao' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . sprintf(__( '正文', 'begin' )) . '';
}

if ( 'picture' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'gallery', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'gallery' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'gallery' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . sprintf(__( '正文', 'begin' )) . '';
}

if ( 'video' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'videos', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'videos' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'videos' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . sprintf(__( '正文', 'begin' )) . '';
}

if ( 'bulletin' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'notice', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'notice' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'notice' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . sprintf(__( '正文', 'begin' )) . '';
}

// woo
if ( is_tax('product_cat') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_cat' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'product_cat' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}


if ( 'product' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'product_cat' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . get_the_title() . $after;
}

if ( is_tax('product_tag') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_tag' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'product_tag' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

// edd
if ( is_tax('download_category') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'download_category' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'download_category' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'download' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'download_category', array( 'orderby' => 'download', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'download_category' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . get_the_title() . $after;
}

if ( is_tax('download_tag') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'download_tag' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'download_tag' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

// dwqa
if ( is_tax('dwqa-question_category') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'dwqa-question_category' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'dwqa-question_category' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'dwqa-question' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'dwqa-question_category', array( 'orderby' => 'download', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'dwqa-question_category' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . sprintf(__( '问题', 'begin' )) . '';
}

if ( is_tax('dwqa-question_tag') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'download_tag' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'dwqa-question_tag' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}


if ( is_tax('favorites') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'favorites' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'favorites' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="fa fa-angle-right"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}