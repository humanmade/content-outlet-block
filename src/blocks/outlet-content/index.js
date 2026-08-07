import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import metadata from './block.json';
import Edit from './edit';

registerBlockType( metadata.name, {
	edit: Edit,
	// Saves inner blocks despite having a render callback: the outlet needs
	// them in post_content to find and re-render later.
	save: () => <InnerBlocks.Content />,
} );
