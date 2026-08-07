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

### Local Environment

This project uses [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) to run a lightweight, containerized WordPress instance at [localhost:6858](http://localhost:6858) for testing purposes. The default username for the localhost environment is `admin`, with the password `password`.

These commands can be used to interact with the environment:

Command | Purpose
---- | ----
`npm run env:start` | Start the local environment at http://localhost:6858
`npm run env:stop` | Turn off the local environment
`npm run env:cli -- wp ...` | Run WP-CLI commands within the environment
`npm run env:logs` | Open (and tail) the error logs for the application<sup>&ddagger;</sup>
`npm run env:db` | Open the database in the mysql command line
`npm run env:destroy` | Fully destroy the local environment (deletes container database)

<sup>&ddagger;</sup> This command deliberately filters out GET/OPTIONS/HEAD/POST/PUT access log entries

## Release Process

Code merged to `main` automatically builds to the `release` branch via GitHub Actions; `release` is what Composer installs.

Tag versions with the [**Tag and Release** workflow](https://github.com/humanmade/content-outlet-block/actions) after bumping the version number in `plugin.php`.
