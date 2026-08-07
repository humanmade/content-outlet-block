# HM Content Outlet

Author content anywhere in a post, render it at a fixed position in the template.

Two blocks:

- **Outlet Content** (`hm/outlet-content`) — wraps inner blocks in the post body. Renders nothing where it sits.
- **Outlet** (`hm/outlet`) — placed in a template *outside* `post_content`, e.g. above the Post Content block. Renders the current post's Outlet Content block at that position.

The Outlet finds the content block by name, at any depth, so where the editor puts it in the body has no bearing on output. One per post (`supports.multiple: false`), since the Outlet renders the first match.

## Why not Block Hooks

Block Hooks guarantees *initial insertion*, not position. A dismissed block is recorded in `_wp_ignored_hooked_blocks` and never returns, and once inserted nothing constrains where it sits. Its editor-side REST wiring is registered only for `post`, `page`, `wp_block` and `wp_navigation`, so on a custom post type a hooked block renders on the front end while staying invisible in the editor.

Rendering through an outlet avoids all of it, because position inside `post_content` is never read.

## Placement is the host's business

This plugin deliberately takes no view on where the content block sits in the editor, whether it can be moved, or whether it can be deleted. Those are editorial decisions belonging to the site that installs it.

If you do implement pinning, one constraint is worth knowing in advance: **do not lock `move`.** Core's `moveBlocksToPosition()` returns early on `canMoveBlocks()`, so a `move` lock silently no-ops any programmatic reposition along with the drag handle — leaving the block strandable rather than pinned. Lock `remove` if you want it undeletable, and correct position rather than preventing it.

## Development

```bash
npm install
npm run build       # or: npm run start
composer install
composer phpcs
npm run lint:js
```

`main` builds to the `release` branch via GitHub Actions; `release` is what Composer installs. Tag with the **Tag and Release** workflow.
