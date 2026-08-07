import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import metadata from './block.json';
import Edit from './edit';

registerBlockType( metadata.name, {
	// Alignment and layout are registered in JS rather than block.json, which
	// keeps them out of the server-side block type; see "Layout context" in
	// the README.
	//
	// The constrained type has to come from the attribute default, since
	// useInnerBlocksProps hands children the raw supports.layout object when
	// the layout attribute is unset and never reads supports.layout.default.
	// allowEditing hides the layout panel: the outlet's surroundings decide the
	// real width. Neither value serializes, each matching its default.
	attributes: {
		...metadata.attributes,
		align: {
			type: 'string',
			default: 'wide',
		},
		layout: {
			type: 'object',
			default: { type: 'constrained' },
		},
	},
	supports: {
		...metadata.supports,
		align: [ 'wide', 'full' ],
		layout: { allowEditing: false },
	},
	edit: Edit,
	// Saves inner blocks despite having a render callback: the outlet needs
	// them in post_content to find and re-render later.
	save: () => <InnerBlocks.Content />,
} );
