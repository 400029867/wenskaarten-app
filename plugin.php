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
