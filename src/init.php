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
	$post_result = handleSettingsFormSubmit();
	$themes = getThemesFromDb();
	$cards = getCardsFromDb();

	?>
	<div>
		<?php if(null !== $post_result) { ?>
			<div class="messages-box" style="display:inline-block;background:<?= $post_result['success'] === true ? 'green' : 'red'; ?>">
				<p style="display:inline-block;color:white;margin:20px"><?= $post_result['message'] ?></p>
			</div>
		<?php } ?>
		<h1>Thema's en Wenskaarten beheren</h1>
		<h3>Thema's</h3>
		<p>Maak nieuw thema aan:</p>
		<table>
			<form method="post" action="">
				<tr valign="top">
					<th scope="row"><label for="wenskaarten_new_theme_name">Naam <span style="color:red">*</span></label></th>
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
				<th>Actie</th>
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
					<form method="post" action="">
						<input type="hidden" name="wenskaarten_delete_theme_id" value=<?= $theme['id'] ?> />
						<th style="color:darkgreen"><?= $theme['name'] ?></th>
						<td align="center"><?= $theme_cards ?></td>
						<td><?php submit_button('Verwijder', 'delete', 'submit', false); ?></td>
					</form>
				</tr>
			<?php } ?>
		</table>
		<p><em>Bij het verwijderen van een thema worden ook zijn wenskaarten verwijderd.</em></p>
		<br />
		<h3>Wenskaarten</h3>
		<p>Voeg kaarten toe:</p>
		<table>
			<form method="post" action="" id="wenskaarten_new_card">
				<tr>
					<th scope="row"><label for="wenskaarten_new_card_theme">Thema <span style="color:red">*</span></label></th>
					<td>
						<select form="wenskaarten_new_card" id="wenskaarten_new_card_theme" name="wenskaarten_new_card_theme">
							<?php foreach($themes as $theme) { ?>
								<option value=<?= $theme['id'] ?>><?= $theme['name'] ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wenskaarten_new_card_name">Naam <span style="color:red">*</span></label></th>
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
		<?php 
		foreach($themes as $theme) { 
			$theme_cards = array_filter(
				$cards, 
				function ($card) use (&$theme) { 
					return $card['theme'] === $theme['id']; 
				} 
			);
			?>
			<table>
				<tr>
				<th colspan="3" style="color:darkgreen"><?= $theme['name'] ?></th>
				</tr>
				<tr>
					<th>Naam</th>
					<th>URL</th>
					<th>Actie</th>
				</tr>
				<?php foreach($theme_cards as $card) { ?>
					<tr>
						<form method="post" action="">
							<input type="hidden" name="wenskaarten_delete_card_id" value=<?= $card['id'] ?> />
							<td><strong><?= $card['name'] ?></strong></td>
							<td><a href=<?= $card['url'] ?>><?= $card['url'] ?></a></td>
							<td><?php submit_button('Verwijder', 'delete', 'submit', false); ?></td>
						</form>
					</tr>
				<?php } ?>
				<?php if(empty($theme_cards)) { ?>
					<tr>
						<td colspan="3">Dit thema heeft nog geen wenskaarten</td>
					</tr>
				<?php } ?>
			</table>
			<br />
		<?php } ?>
  </div>
	<?php
}

function handleSettingsFormSubmit() {
	if (empty($_POST)) {
		return null;
	}

	global $wpdb;

	$theme_table_name = $wpdb->prefix . 'wenskaarten_theme';
	$card_table_name = $wpdb->prefix . 'wenskaarten_card';

	// Handle create new theme
	if (array_key_exists('wenskaarten_new_theme_name', $_POST)) {
		$name = $_POST['wenskaarten_new_theme_name'];
		
		// TODO: Validation
		if (empty($name)) {
			return [
				'success' => false,
				'message' => "Name cannot be empty."
			];
		}
		$wpdb->show_errors();

		// Insert the theme
		$result = $wpdb->insert(
			$theme_table_name, 
			['name' => $name],
			['%s']
		);
		if (false === $result) {
			return [
				'success' => false,
				'message' => "Could not create a theme with name $name"
			];
		}

		// Success
		return [
			'success' => true, 
			'message' => "Created the following theme: $name"
		];
	}

	// Handle delete theme
	if (array_key_exists('wenskaarten_delete_theme_id', $_POST)) {
		$id = $_POST['wenskaarten_delete_theme_id'];

		// Make sure the id is numeric
		if (false === ctype_digit($id)) {
			return [
				'success' => false, 
				'message' => "Error trying to delete the theme:Theme with id $id not found."
			];
		}

		// Delete the cards in the theme
		$card_result = $wpdb->delete(
			$card_table_name,
			['theme' => $id],
			['%d']
		);
		if (false === $card_result) {
			return [
				'success' => false, 
				'message' => "Error trying to delete the theme: Card(s) could not be deleted."
			];
		}

		// Delete the theme
		$theme_result = $wpdb->delete(
			$theme_table_name,
			['id' => $id],
			['%d']
		);
		if (false === $theme_result) {
			return [
				'success' => false,
				'message' => "Error trying to delete the theme: Theme could not be deleted."
			];
		}

		// Success
		return [
			'success' => true,
			'message' => "Deleted $theme_result theme and $card_result card(s)"
		];
	}

	// Handle create new card
	if (array_key_exists('wenskaarten_new_card_theme', $_POST) && array_key_exists('wenskaarten_new_card_name', $_POST)) {
		$name = $_POST['wenskaarten_new_card_name'];
		$theme = $_POST['wenskaarten_new_card_theme'];
		$url = $_POST['wenskaarten_new_card_url'];
		if (empty($url)) {
			$url = null;
		}

		// TODO: Validation for name
		if (empty($name)) {
			return [
				'success' => false,
				'message' => "Name cannot be empty."
			];
		}

		// Make sure theme is a number
		if (false === ctype_digit($theme)) {
			return [
				'succes' => false,
				'message' => "Theme id is not a number:\n$theme",
				'field' => 'wenskaarten_new_card_theme'
			];
		}

		// Validate the url if given
		if (null !== $url && !filter_var($url, FILTER_VALIDATE_URL)) {
			return [
				'succes' => false,
				'message' => "Given URL is not valid:\n$url",
				'field' => 'wenskaarten_new_card_url'
			];
		}

		// Insert the card
		$result = $wpdb->insert(
			$card_table_name, 
			[
				'name' => $name, 
				'theme' => $theme, 
				'url' => $url
			],
			['%s', '%d', '%s']
		);
		if (false === $result) {
			return [
				'success' => false,
				'message' => "Could not create a card with name $name under theme $theme."
			];
		}

		// Success
		return [
			'success' => true, 
			'message' => "Created a new card under theme $theme with the name $name."
		];
	}

	if (array_key_exists('wenskaarten_delete_card_id', $_POST)) {
		$id = $_POST['wenskaarten_delete_card_id'];

		// Make sure the id is numeric
		if (false === ctype_digit($id)) {
			return [
				'success' => false, 
				'message' => "Given card id is not a number:\n$id"
			];
		}

		// Delete the card
		$result = $wpdb->delete(
			$card_table_name,
			['id' => $id],
			['%d']
		);
		if (false === $result) {
			return [
				'success' => false, 
				'message' => "Error trying to delete the card."
			];
		}

		// Success
		return [
			'success' => true,
			'message' => "Successfully deleted the card."
		];
	}

	// $_POST has unhandled data
	return [
		'success' => false,
		'message' => 'Found unhandled request, check the "name" attribute of your input.'
	];
}

function getThemesFromDb() {
	global $wpdb;

	return $wpdb->get_results('SELECT id, name FROM '.$wpdb->prefix.'wenskaarten_theme', ARRAY_A);
}

function getCardsFromDb() {
	global $wpdb;

	return $wpdb->get_results('SELECT id, name, theme, url FROM '.$wpdb->prefix.'wenskaarten_card', ARRAY_A);
}

// Hook: Settings page.
add_action( 'admin_menu', 'wenskaarten_app_register_options_page' );
