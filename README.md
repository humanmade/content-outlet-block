# Content Outlet Block

Have you ever wanted to take some part of your post content in a complex template, and output it separately from the rest of the main content area? Maybe you have a hero section that displays above some template chrome, say. You could bake all that into the content template, but that can require either brittle block locking, editorial discipline, or both if you want the template to be consistently applied across the site.

This plugin proposes a wrapper block which can hold any other block content, authored
anywhere within a post, and then an outlet block which can be used elsewhere in the page template to render the wrapped content at a fixed position.

Details on those blocks:

- **Outlet Content** (`hm/outlet-content`) wraps inner blocks in the post body. Renders nothing where it sits.
- **Outlet** (`hm/outlet`) is placed in a template *outside* `post_content`, _e.g._ above a wrapping columns unit in the single-post template. Renders the current post's wrapped Outlet Content blocks at that position.

The Outlet finds the content block by name, at any depth, so where the editor puts it in the body has no bearing on output. At present there can only be one content wrapper per post (`supports.multiple: false`), since the Outlet renders the first match.

## Development

Installing build dependencies and building the compiled asset bundles
```bash
npm install
npm run build # or: npm run start
```

Checking code for style, syntax and safety
```
composer install
composer phpcs
npm run lint:js
```

Code merged to `main` automatically builds to the `release` branch via GitHub Actions; `release` is what Composer installs.

Tag versions with the [**Tag and Release** workflow](https://github.com/humanmade/content-outlet-block/actions) after bumping the version number in `plugin.php`.
