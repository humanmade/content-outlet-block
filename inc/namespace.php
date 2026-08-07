<?php
/**
 * Plugin bootstrap, block registration, and outlet rendering.
 */

namespace HM\ContentOutlet;

const CONTENT_BLOCK = 'hm/outlet-content';

/**
 * Attach hooks.
 */
function bootstrap(): void {
	add_action( 'init', __NAMESPACE__ . '\\register_blocks' );
}

/**
 * Register every block built into build/blocks/.
 *
 * The wp-scripts build emits one directory per block, each with a compiled block.json
 * whose file: references resolve relative to the build output.
 */
function register_blocks(): void {
	foreach ( glob( PLUGIN_PATH . '/build/blocks/*/block.json' ) as $block_metadata_file ) {
		register_block_type_from_metadata( $block_metadata_file );
	}
}

/**
 * Get or set whether the content block is being rendered by an outlet.
 *
 * The content block's own render callback reads this to decide whether to
 * render, which is what keeps it from also appearing where it sits in the
 * post body.
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
 * Render a post's content block, for use by the outlet block.
 *
 * @param int $post_id Post to search.
 * @return string Rendered content, or '' when the post has no content block.
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
 * Find the first block of the given name, at any depth.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed blocks.
 * @param string                           $name   Block name to find.
 * @return ?array<string, mixed> Parsed block, or null when absent.
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
