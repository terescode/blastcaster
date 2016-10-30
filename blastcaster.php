<?php
/**
 * Plugin Name: BlastCaster
 * Description: Keep all your content marketing streams relevant by quickly
 * and easily create engaging posts for content you've curated
 * Version:     1.0.0
 * Author:      Terescode, LLC
 * Author URI:  http://www.terescode.com
 * Text Domain: blastcaster
 * Domain Path: /languages
 * @package BlastCaster
 */

if ( ! defined( 'WPINC' ) ) {
	return -1;
}

require_once 'includes/constants.php';
require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-plugin.php';

$blastcaster_plugin = com_terescode_create_blastcaster();
$blastcaster_plugin->init();
