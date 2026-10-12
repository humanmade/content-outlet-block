import {
	useBlockProps,
	useInnerBlocksProps,
	InspectorControls,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { PanelBody, Notice, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function Edit( {
	attributes: { slot },
	setAttributes,
	clientId,
} ) {
	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		templateLock: false,
	} );

	// Only the first Outlet Content block for a slot renders, so flag any
	// later one rather than let it disappear silently.
	const isDuplicate = useSelect(
		( select ) => {
			const { getBlocksByName, getBlockAttributes } =
				select( blockEditorStore );
			const sameSlot = getBlocksByName( 'hm/outlet-content' ).filter(
				( id ) => ( getBlockAttributes( id )?.slot ?? '' ) === slot
			);
			return sameSlot.length > 1 && sameSlot[ 0 ] !== clientId;
		},
		[ slot, clientId ]
	);

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Outlet Content', 'hm-content-outlet' ) }
				>
					<Notice status="info" isDismissible={ false }>
						{ __(
							'This content renders through the Outlet block for the same slot elsewhere in the template, not in this position.',
							'hm-content-outlet'
						) }
					</Notice>
					{ isDuplicate && (
						<Notice status="warning" isDismissible={ false }>
							{ __(
								'Another Outlet Content block earlier in this post fills the same slot, so this one will not render.',
								'hm-content-outlet'
							) }
						</Notice>
					) }
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Slot', 'hm-content-outlet' ) }
						help={ __(
							'Matches the slot of an Outlet block in the template. Leave empty for the default slot.',
							'hm-content-outlet'
						) }
						value={ slot }
						onChange={ ( value ) =>
							setAttributes( { slot: value.trim() } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerBlocksProps } />
		</>
	);
}
