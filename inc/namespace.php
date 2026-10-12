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
 * Render a post's content block for one slot, for use by the outlet block.
 *
 * Content blocks without a slot fill the default slot, so posts written
 * before slots existed keep rendering through an outlet without one.
 *
 * @param int    $post_id Post to search.
 * @param string $slot    Slot name, or '' for the default slot.
 * @return string Rendered content, or '' when the post has no content block for the slot.
 */
function render_outlet_content( int $post_id, string $slot = '' ): string {
	$post = $post_id ? get_post( $post_id ) : null;
	if ( ! $post ) {
		return '';
	}

	$block = find_block( parse_blocks( $post->post_content ), CONTENT_BLOCK, $slot );
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
 * @param ?string                          $slot   Slot the block must fill, or null for any slot.
 * @return ?array<string, mixed> Parsed block, or null when absent.
 */
function find_block( array $blocks, string $name, ?string $slot = null ): ?array {
	foreach ( $blocks as $block ) {
		if (
			( $block['blockName'] ?? null ) === $name
			&& ( null === $slot || (string) ( $block['attrs']['slot'] ?? '' ) === $slot )
		) {
			return $block;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$found = find_block( $block['innerBlocks'], $name, $slot );
			if ( $found ) {
				return $found;
			}
		}
	}
	return null;
}
