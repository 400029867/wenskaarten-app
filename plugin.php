<?php
/**
 * Plugin Name: Wenskaarten
 * Plugin URI: https://stefringoot.com/wenskaarten/
 * Description: A plugin that lets visitors create an ecard and send it via email.
 * Version: 1.0.0
 * Author: Joost Kersjes - 400029867@st.roc.a12.nl
 * Author URI: https://github.com/400029867
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

/**
 * Create tables for the plugin
 */
function jal_install () {
	global $wpdb;

	$theme_table_name = $wpdb->prefix . 'wenskaarten_theme';
	$card_table_name = $wpdb->prefix . 'wenskaarten_card';

	$charset_collate = $wpdb->get_charset_collate();

	$theme_sql = "CREATE TABLE $theme_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	$card_sql = "CREATE TABLE $card_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		url varchar(255) DEFAULT '' NOT NULL,
		theme mediumint(9) NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $theme_sql );
	dbDelta( $card_sql );
}

// Hook: Database tables.
register_activation_hook( __FILE__, 'jal_install' );