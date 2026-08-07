<?php
/**
 * HM Content Outlet main namespace.
 *
 * @package hm-content-outlet
 */

namespace HM\ContentOutlet;

const CONTENT_BLOCK = 'hm/outlet-content';

/**
 * Connect namespace functions to hooks.
 */
function bootstrap(): void {
	add_action( 'init', __NAMESPACE__ . '\register_blocks' );
}

/**
 * Register the outlet content and outlet blocks.
 */
function register_blocks(): void {
	register_block_type_from_metadata( PLUGIN_PATH . '/build/blocks/outlet-content/block.json' );
	register_block_type_from_metadata( PLUGIN_PATH . '/build/blocks/outlet/block.json' );
}

/**
 * Get or set whether the outlet content block is currently being rendered by
 * an outlet, rather than in its own position in post content.
 *
 * The content block's render callback checks this flag to decide whether to
 * render at all, so its content appears once, only through the outlet.
 *
 * @param ?bool $set When given, updates the flag before returning it.
 * @return bool Current flag state.
 */
function is_rendering_via_outlet( ?bool $set = null ): bool {
	static $rendering = false;
	if ( $set !== null ) {
		$rendering = $set;
	}
	return $rendering;
}

/**
 * Render a post's outlet content block, for use by the outlet block.
 *
 * @param int $post_id Post to search for an outlet content block.
 * @return string Rendered content, or '' if the post has no content block.
 */
function render_outlet_content( int $post_id ): string {
	$post = $post_id ? get_post( $post_id ) : null;
	if ( ! $post ) {
		return '';
	}

	$block = find_block( parse_blocks( $post->post_content ), CONTENT_BLOCK );
	if ( ! $block ) {
		return '';
	}

	is_rendering_via_outlet( true );
	$html = render_block( $block );
	is_rendering_via_outlet( false );

	return $html;
}

/**
 * Recursively find the first block of the given name in a parsed block tree.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed blocks.
 * @param string                           $name   Block name to find.
 * @return ?array<string, mixed> Parsed block, or null if not found.
 */
function find_block( array $blocks, string $name ): ?array {
	foreach ( $blocks as $block ) {
		if ( ( $block['blockName'] ?? null ) === $name ) {
			return $block;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$found = find_block( $block['innerBlocks'], $name );
			if ( $found ) {
				return $found;
			}
		}
	}
	return null;
}
