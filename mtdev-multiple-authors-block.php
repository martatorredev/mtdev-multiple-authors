<?php
/**
 * Plugin Name:       MTDev Multiple Authors Block
 * Plugin URI:        https://martatorre.dev
 * Description:       Accessible block to select and display multiple authors (co-authors) for WordPress posts, following WordPress and WCAG best practices.
 * Version:           1.0.0
 * Author:            Marta Torre
 * Author URI:        https://martatorre.dev
 * Text Domain:       mtdev-multiple-authors-block
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load plugin textdomain for translations.
 */
function mtdev_mab_load_textdomain() {
	load_plugin_textdomain(
		'mtdev-multiple-authors-block',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'mtdev_mab_load_textdomain' );

/**
 * Register meta and block type.
 */
function mtdev_mab_init() {
	// Register post meta to store coauthors.
	register_post_meta(
		'',
		'mtdev_coauthors',
		array(
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'integer',
					),
				),
			),
			'auth_callback'     => 'mtdev_mab_meta_auth_callback',
			'sanitize_callback' => 'mtdev_mab_sanitize_coauthors',
		)
	);

	// Register block (dynamic).
	register_block_type(
		__DIR__,
		array(
			'render_callback' => 'mtdev_mab_render_block',
		)
	);
}
add_action( 'init', 'mtdev_mab_init' );

/**
 * Authorize editing of coauthors meta.
 *
 * @return bool
 */
function mtdev_mab_meta_auth_callback() {
	return current_user_can( 'edit_posts' );
}

/**
 * Sanitize the coauthors meta field.
 *
 * @param mixed $value Raw meta value.
 * @return array Sanitized array of unique integer IDs.
 */
function mtdev_mab_sanitize_coauthors( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$sanitized = array();

	foreach ( $value as $id ) {
		$id = (int) $id;

		if ( $id > 0 ) {
			$sanitized[] = $id;
		}
	}

	return array_values( array_unique( $sanitized ) );
}

/**
 * Get all authors (primary + coauthors) for a given post.
 *
 * @param int|WP_Post $post Post ID or object.
 * @return WP_User[] Array of WP_User objects.
 */
function mtdev_mab_get_all_authors( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}

	$authors = array();

	// Primary author.
	$primary = get_user_by( 'id', (int) $post->post_author );
	if ( $primary instanceof WP_User ) {
		$authors[ $primary->ID ] = $primary;
	}

	// Coauthors from meta.
	$meta = get_post_meta( $post->ID, 'mtdev_coauthors', true );
	if ( ! is_array( $meta ) ) {
		$meta = array();
	}

	foreach ( $meta as $user_id ) {
		$user = get_user_by( 'id', (int) $user_id );
		if ( $user instanceof WP_User ) {
			$authors[ $user->ID ] = $user;
		}
	}

	return array_values( $authors );
}

/**
 * Render callback for the Multiple Authors block.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content (unused for dynamic block).
 * @param WP_Block $block      Block instance.
 *
 * @return string HTML output of the block.
 */
function mtdev_mab_render_block( $attributes = array(), $content = '', $block = null ) {
	if ( ! $block instanceof WP_Block ) {
		return '';
	}

	$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;
	$post    = get_post( $post_id );

	if ( ! $post ) {
		return '';
	}

	$authors = mtdev_mab_get_all_authors( $post );
	if ( empty( $authors ) ) {
		return '';
	}

	$tag = isset( $attributes['tagName'] ) && $attributes['tagName']
		? $attributes['tagName']
		: 'p';

	$show_label = ! empty( $attributes['showLabel'] );
	$label_text = isset( $attributes['labelText'] ) && '' !== $attributes['labelText']
		? $attributes['labelText']
		: __( 'By', 'mtdev-multiple-authors-block' );

	$separator = isset( $attributes['separator'] ) && '' !== $attributes['separator']
		? $attributes['separator']
		: ', ';

	// Build list of authors with accessible links.
	$names = array();

	foreach ( $authors as $author ) {
		$url  = get_author_posts_url( $author->ID );
		$name = $author->display_name;

		$names[] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $url ),
			esc_html( $name )
		);
	}

	$separator_html = esc_html( $separator ) . ' ';
	$html_names     = implode( $separator_html, $names );

	$classes = 'mtdev-multiple-authors';
	if ( ! empty( $attributes['className'] ) ) {
		$classes .= ' ' . sanitize_html_class( $attributes['className'] );
	}

	$tag              = tag_escape( $tag );
	$group_aria_label = esc_attr__( 'Post authors', 'mtdev-multiple-authors-block' );

	$output  = '<' . $tag . ' class="' . esc_attr( $classes ) . '" role="group" aria-label="' . $group_aria_label . '">';
	if ( $show_label && $label_text ) {
		$output .= '<span class="mtdev-multiple-authors__label">' . esc_html( $label_text ) . ' </span>';
	}
	$output .= '<span class="mtdev-multiple-authors__list">' . $html_names . '</span>';
	$output .= '</' . $tag . '>';

	return $output;
}
