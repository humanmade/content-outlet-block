<?php
/**
 * Frontend render template for the outlet block.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo \HM\ContentOutlet\render_outlet_content( get_the_ID() );
