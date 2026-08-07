<?php
/**
 * Outlet Block: server-side render.
 *
 * Renders the current post's hm/outlet-content block content at this position.
 *
 * @package hm-content-outlet
 */

echo \HM\ContentOutlet\render_outlet_content( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
