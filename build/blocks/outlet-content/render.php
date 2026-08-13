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
	
/** @var string $content Pre-rendered nested block content as HTML string. */
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $content;
