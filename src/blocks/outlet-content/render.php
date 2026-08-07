<?php
/**
 * Outlet Content Block: server-side render.
 *
 * Renders nothing in place; content only appears through a matching
 * hm/outlet block elsewhere in the template.
 *
 * @var string $content Rendered inner blocks.
 *
 * @package hm-content-outlet
 */

if ( ! \HM\ContentOutlet\is_rendering_via_outlet() ) {
	return;
}

printf( '<div %s>%s</div>', get_block_wrapper_attributes(), $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
