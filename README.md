# HM Content Outlet

Author content anywhere in a post, render it at a fixed position in the template.

Two blocks:

- **Outlet Content** (`hm/outlet-content`) — wraps inner blocks in the post body. Renders nothing where it sits.
- **Outlet** (`hm/outlet`) — placed in a template *outside* `post_content` (e.g. above the Post Content block). Renders the current post's Outlet Content block at that position.

One Outlet Content block per post. The Outlet finds it by block name, not by position, so where the editor puts it in the body is irrelevant to output.

## Why not Block Hooks

Block Hooks guarantees *initial insertion*, not position: a dismissed block is recorded in `_wp_ignored_hooked_blocks` and never returns, and once inserted nothing constrains where it sits. Its editor-side REST wiring is also registered only for `post`, `page`, `wp_block` and `wp_navigation`, so on a custom post type a hooked block renders on the front end while staying invisible in the editor.

Rendering through an outlet sidesteps all of it, because position inside `post_content` is never read.

## Editor pinning

The Outlet Content block is pinned to index 0 of the post body — not because output depends on it, but so the post reads correctly while editing. A small editor plugin re-asserts the position whenever the block drifts.

**Do not add `move` to the block's `lock` default.** Core's `moveBlocksToPosition()` returns early on `canMoveBlocks()`, so locking `move` silently no-ops the pin as well as the drag handle, leaving the block genuinely strandable. The default locks `remove` only: the block can't be deleted, can be dragged, and gets put back. The correction is wrapped in `__unstableMarkNextChangeAsNotPersistent()` so opening a post whose block has drifted doesn't report phantom unsaved changes.

The pin searches top-level blocks only. That is what makes `''` safe as the source root — passing `''` for a *nested* block triggers a Gutenberg bug that deletes the post's last block on every call.

## Development

```bash
npm install
npm run build       # or: npm run start
composer install
composer phpcs
npm run lint:js
```

`main` builds to the `release` branch via GitHub Actions; `release` is what Composer installs. Tag a release with the **Tag and Release** workflow.
