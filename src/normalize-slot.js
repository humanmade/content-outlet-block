/**
 * Normalize a slot name, so slots match regardless of case or spacing.
 *
 * Mirrors normalize_slot() in PHP: lowercase, spaces become hyphens, and
 * anything outside a-z, 0-9, hyphen and underscore is dropped.
 *
 * @param {string} slot Raw slot name.
 * @return {string} Normalized slot name, or '' for the default slot.
 */
export default function normalizeSlot( slot = '' ) {
	return slot
		.toLowerCase()
		.replace( / /g, '-' )
		.replace( /[^a-z0-9_-]/g, '' );
}
