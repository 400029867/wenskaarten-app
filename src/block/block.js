import './style.scss';
import './editor.scss';

import MyComponent from './components/myComponent';
import App from './app';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/block-wenskaarten-app', {
	title: __('Wenskaarten app blok'),
	icon: 'shield',
	category: 'common',
	keywords: [__('wenskaarten-app'), __('Ecard'), __('e-card')],

	/**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function(props) {
		return <App viewOnly />;
	},

	/**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		return <App />;
	},
});
