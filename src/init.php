<?php
/**
 * @since 1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function wenskaarten_app_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'wenskaarten_app-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'wenskaarten_app-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'wenskaarten_app-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	/**
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'cgb/block-wenskaarten-app', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'wenskaarten_app-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'wenskaarten_app-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'wenskaarten_app-cgb-block-editor-css',
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'wenskaarten_app_cgb_block_assets' );

/**
 * Add the options page
 */
function wenskaarten_app_register_options_page() {
	add_options_page('Wenskaarten opties', '(plugin) Wenskaarten', 'manage_options', 'wenskaarten', 'wenskaarten_app_options_page');
}

/**
 * Options page contents
 */
function wenskaarten_app_options_page() {
	global $wpdb;

	//$themes = $wpdb->get_results('SELECT id, name FROM '.$wpdb->prefix.'wenskaarten-theme', ARRAY_A);

	$themes = [['id' => 1, 'name' => 'Placeholder'], ['id' => 2, 'name' => 'Nieuw thema']];

	//$cards = $wpdb->get_results('SELECT id, name, theme, url FROM '.$wpdb->prefix.'wenskaarten-card', ARRAY_A);
	
	$cards = [['id' => 1, 'name' => 'Testkaart', 'theme' => 1, 'url' => 'https://google.com/'], ['id' => 2, 'name' => 'Nieuw Wenskaart', 'theme' => 2, 'url' => 'https://google.com/']];

	handleSettingsFormSubmit($_POST);

	?>
	<div>
		<h2>Thema's en Wenskaarten beheren</h2>
		<h3>Thema's</h3>
		<p>Maak nieuw thema aan:</p>
		<table>
			<form method="post" action="">
				<tr valign="top">
					<th scope="row"><label for="wenskaarten_new_theme_name">Naam</label></th>
					<td><input type="text" id="wenskaarten_new_theme_name" name="wenskaarten_new_theme_name" /></td>
				</tr>
				<tr>
					<td />
					<td><?php submit_button('Voeg toe', 'primary', 'submit', false); ?></td>
				</tr>
			</form>
		</table>
		<br />
		<p>Verwijder bestaand thema:</p>
		<table>
			<tr>
				<th>Naam</th>
				<th>Aantal kaarten</th>
				<th />
			</tr>
			<?php 
			foreach($themes as $theme) { 
				$theme_cards = count(array_filter(
					$cards, 
					function ($card) use (&$theme) { 
						return $card['theme'] === $theme['id']; 
					} 
				));
				?>
				<tr>
					<form method="post" action="database.php">
						<input type="hidden" name="wenskaarten_delete_theme_id" value=<?= $theme['id'] ?> />
						<td><strong><?= $theme['name'] ?></strong></td>
						<td align="center"><?= $theme_cards ?></td>
						<td><?php submit_button('Verwijder', 'delete', 'submit', false); ?></td>
					</form>
				</tr>
			<?php } ?>
		</table>
		<br />
		<h3>Wenskaarten</h3>
		<p>Voeg kaarten toe:</p>
		<table>
			<form method="post" action="database.php" id="wenskaarten_new_card">
				<tr>
					<th scope="row"><label for="wenskaarten_new_card_theme">Thema</label></th>
					<td>
						<select form="wenskaarten_new_card" id="wenskaarten_new_card_theme" name="wenskaarten_new_card_theme">
							<?php foreach($themes as $theme) { ?>
								<option value=<?= $theme['id'] ?>><?= $theme['name'] ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wenskaarten_new_card_name">Naam</label></th>
					<td><input type="text" id="wenskaarten_new_card_name" name="wenskaarten_new_card_name" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="wenskaarten_new_card_url">URL</label></th>
					<td><input type="text" id="wenskaarten_new_card_url" name="wenskaarten_new_card_url" /></td>
				</tr>
				<tr><td /><td><?php submit_button('Voeg toe', 'primary', 'submit', false); ?></td></tr>
			</form>
		</table>
		<br />
		<p>Verwijder bestaande wenskaarten:</p>
		<table>
			<tr>
				<th>Thema</th>
				<th>Naam</th>
				<th>URL</th>
				<th />
			</tr>
			<?php 
			foreach($cards as $card) {
				$card_theme = current(array_filter(
					$themes, 
					function ($theme) use (&$card) { 
						return $theme['id'] === $card['theme']; 
					} 
				));
				?>
				<tr>
					<form method="post" action="database.php">
						<input type="hidden" name="wenskaarten_delete_card_id" value=<?= $card['id'] ?> />
						<td><?= $card_theme['name'] ?></td>
						<td><strong><?= $card['name'] ?></strong></td>
						<td><a href=<?= $card['url'] ?>><?= $card['url'] ?></a></td>
						<td><?php submit_button('Verwijder', 'delete', 'submit', false); ?></td>
					</form>
				</tr>
			<?php } ?>
		</table>
  </div>
	<?php
}

function handleSettingsFormSubmit($post) {
	if (!empty($post)) {
		if (!empty($post['wenskaarten_new_theme_name'])) {
			var_dump('found me!');
			return;
		}
	}
}

// Hook: Settings page.
add_action( 'admin_menu', 'wenskaarten_app_register_options_page' );

/**
 * Create tables for the plugin
 */
function jal_install () {
	global $wpdb;

	$theme_table_name = $wpdb->prefix . 'wenskaarten-theme';
	$card_table_name = $wpdb->prefix . 'wenskaarten-card';

	$charset_collate = $wpdb->get_charset_collate();

	$theme_sql = "CREATE TABLE $theme_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$card_sql = "CREATE TABLE $card_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		url varchar(255) DEFAULT '' NOT NULL,
		theme mediumint(9) NOT NULL,
		timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $theme_sql );
	dbDelta( $card_sql );
}

// Hook: Database tables.
register_activation_hook( __FILE__, 'jal_install' );