import {
	useBlockProps,
	useInnerBlocksProps,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		templateLock: false,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Outlet Content', 'hm-content-outlet' ) }
				>
					<Notice status="info" isDismissible={ false }>
						{ __(
							'This content renders through a matching Outlet block elsewhere in the template, not in this position.',
							'hm-content-outlet'
						) }
					</Notice>
				</PanelBody>
			</InspectorControls>
			<div { ...innerBlocksProps } />
		</>
	);
}
