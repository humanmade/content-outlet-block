import { store as blockEditorStore } from '@wordpress/block-editor';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { registerPlugin } from '@wordpress/plugins';

import metadata from './block.json';

/**
 * Re-assert the outlet content block's position at the top of post content.
 *
 * The block's `lock` default deliberately sets only `remove`, not `move`:
 * core's moveBlocksToPosition() bails on canMoveBlocks(), so locking `move`
 * would silently no-op this pin along with the editor's own drag handle.
 * Dragging the block is instead allowed and undone on the next render.
 *
 * @return {null} Renders nothing; exists to hold the enforcement effect.
 */
const PinOutletContent = () => {
	// Only ever searches top-level blocks. Passing '' as the source root for a
	// nested block trips a Gutenberg bug that deletes the post's last block on
	// every call; generalizing this must use getBlockRootClientId() instead.
	const blocks = useSelect(
		( select ) => select( blockEditorStore ).getBlocks(),
		[]
	);
	const { moveBlockToPosition, __unstableMarkNextChangeAsNotPersistent } =
		useDispatch( blockEditorStore );

	useEffect( () => {
		// An empty tree means the editor has not hydrated yet; acting on it
		// produces spurious moves.
		if ( ! blocks.length ) {
			return;
		}

		const index = blocks.findIndex(
			( { name } ) => name === metadata.name
		);

		// Absent (-1) or already first (0); nothing to do.
		if ( index <= 0 ) {
			return;
		}

		// Without this the correction counts as an edit, so merely opening a
		// post whose content block has drifted marks it as having unsaved changes.
		__unstableMarkNextChangeAsNotPersistent();
		moveBlockToPosition( blocks[ index ].clientId, '', '', 0 );
	}, [
		blocks,
		moveBlockToPosition,
		__unstableMarkNextChangeAsNotPersistent,
	] );

	return null;
};

registerPlugin( 'hm-pin-outlet-content', { render: PinOutletContent } );
