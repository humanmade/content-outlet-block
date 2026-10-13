import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

import normalizeSlot from '../../normalize-slot';

export default function Edit( { attributes: { slot }, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Outlet', 'hm-content-outlet' ) }>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Slot', 'hm-content-outlet' ) }
						help={ __(
							'Renders the Outlet Content block with the same slot. Leave empty for the default slot.',
							'hm-content-outlet'
						) }
						value={ slot }
						onChange={ ( value ) =>
							setAttributes( { slot: normalizeSlot( value ) } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<p>
					{ slot
						? sprintf(
								/* translators: %s: slot name. */
								__(
									'Outlet: renders this post’s Outlet Content block for the “%s” slot here.',
									'hm-content-outlet'
								),
								slot
						  )
						: __(
								'Outlet: renders this post’s Outlet Content block here.',
								'hm-content-outlet'
						  ) }
				</p>
			</div>
		</>
	);
}
