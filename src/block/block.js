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
		return <App />;
	},

	// FIXME: The 'save' prop only loads pure HTML. This means our dynamic React will not work.
	/**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		return <App />;
	},
});

/*
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;
 
registerBlockType( 'gutenberg-examples/example-05-dynamic', {
    title: 'Example: last post',
    icon: 'megaphone',
    category: 'widgets',
 
    edit: withSelect( ( select ) => {
        return {
            posts: select( 'core' ).getEntityRecords( 'postType', 'post' )
        };
    } )( ( { posts, className } ) => {
 
        if ( ! posts ) {
            return "Loading...";
        }
 
        if ( posts && posts.length === 0 ) {
            return "No posts";
        }
 
        let post = posts[ 0 ];
 
        return <a className={ className } href={ post.link }>
            { post.title.rendered }
        </a>;
    } ),
} );
*/
