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
 * Get or set the slot an outlet is currently rendering.
 *
 * The content block's own render callback compares this with its slot to
 * decide whether to render, which keeps it from also appearing where it sits
 * in the post body, and keeps a content block nested inside another slot's
 * content block from rendering as part of that slot.
 *
 * @param string|null|false $set Slot to set, null when no outlet is rendering, or false to only read.
 * @return ?string Slot being rendered ('' for the default slot), or null when no outlet is rendering.
 */
function rendering_slot( string|null|false $set = false ): ?string {
	static $slot = null;
	if ( $set !== false ) {
		$slot = $set;
	}
	return $slot;
}

/**
 * Normalize a slot name, so slots match regardless of case or spacing.
 *
 * Mirrors normalizeSlot() in the editor, for markup written by hand.
 *
 * @param mixed $slot Raw slot attribute.
 * @return string Normalized slot name, or '' for the default slot.
 */
function normalize_slot( mixed $slot ): string {
	return sanitize_key( str_replace( ' ', '-', (string) $slot ) );
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

	$slot  = normalize_slot( $slot );
	$block = find_block( parse_blocks( $post->post_content ), CONTENT_BLOCK, $slot );
	if ( ! $block ) {
		return '';
	}

	rendering_slot( $slot );
	$html = render_block( $block );
	rendering_slot( null );

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
			&& ( null === $slot || normalize_slot( $block['attrs']['slot'] ?? '' ) === $slot )
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
