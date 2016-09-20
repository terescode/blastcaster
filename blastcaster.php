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

defined( 'WPINC' ) || die;

require_once( 'includes/constants.php' );
require_once( BC_PLUGIN_DIR . 'admin/class-blastcaster-plugin.php' );

BlastCasterPlugin::run();
