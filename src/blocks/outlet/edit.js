import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<p>
				{ __(
					'Outlet: renders this post’s Outlet Content block here.',
					'hm-content-outlet'
				) }
			</p>
		</div>
	);
}
