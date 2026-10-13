<?php
/**
 * Frontend render template for the outlet content block.
 *
 * Renders only when an outlet is rendering this block's slot, so the block
 * stays invisible where it sits in the post body, and inside another slot's
 * content block.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( \HM\ContentOutlet\rendering_slot() !== \HM\ContentOutlet\normalize_slot( $attributes['slot'] ?? '' ) ) {
	return;
}

/**
 * Pre-rendered nested block content.
 *
 * @var string $content HTML string.
 */
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $content;
