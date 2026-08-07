<?php
/**
 * Frontend render template for the outlet content block.
 *
 * Renders only when an outlet asked for it, so the block stays invisible
 * where it sits in the post body.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! \HM\ContentOutlet\is_rendering_via_outlet() ) {
	return;
}

printf( '<div %s>%s</div>', get_block_wrapper_attributes(), $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
