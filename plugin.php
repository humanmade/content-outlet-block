<?php
/**
 * Plugin Name:       HM Content Outlet
 * Description:       Author content anywhere in a post, render it at a fixed position in the template.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Human Made
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hm-content-outlet
 *
 * @package hm-content-outlet
 */

namespace HM\ContentOutlet;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

const PLUGIN_VERSION = '1.0.0';
const PLUGIN_PATH    = __DIR__;

require_once __DIR__ . '/inc/namespace.php';

bootstrap();
