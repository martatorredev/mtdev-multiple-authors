=== MTDev Multiple Authors Block ===
Contributors: martatorre
Tags: authors, coauthors, accessibility, block, gutenberg
Requires at least: 6.3
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Accessible multiple authors block that lets you select and display post co-authors, following WordPress and WCAG best practices.

== Description ==

**MTDev Multiple Authors Block** allows you to select and display multiple authors (co-authors) for any post, using a fully accessible block built with native WordPress features.

✅ **Key features**
- Select additional co-authors for any post directly from the editor.
- Display all authors (primary + co-authors) on the frontend.
- Built entirely with native WordPress components and APIs.
- Fully accessible, following **WCAG 2.1 AA** and **WordPress Accessibility Handbook** guidelines.
- Compatible with both the Post Editor and the Site Editor.

🧠 **Accessibility**
This plugin is developed with accessibility in mind:
- Semantic markup using `<p>`, `<span>`, and `<a>` elements.
- ARIA attributes (`role="group"`, `aria-label="Post authors"`) for assistive technologies.
- Keyboard and screen reader friendly.
- Clear labels and fieldsets in the editor interface.

💡 **Why this plugin**
Many co-author solutions rely on custom tables or outdated interfaces.  
This one uses modern block architecture, REST API, and post meta — clean, future-proof, and 100% WordPress-native.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/mtdev-multiple-authors-block`, or install the plugin through the WordPress Plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Edit a post and insert the **Multiple Authors** block.
4. In the sidebar, select the additional co-authors you want.
5. Update your post — all authors will display on the frontend automatically.

== Frequently Asked Questions ==

= Does this plugin replace the default WordPress author? =
No. The default post author remains the main author.  
The block adds **additional co-authors** and displays them together.

= Is this compatible with Full Site Editing (FSE)? =
Yes, fully compatible. You can use the block inside templates and template parts (like `single` or `post-meta`).

= Can I use it with custom post types? =
Yes. By default it works with posts, but you can extend it with the `mtdev_coauthors_post_types` filter.

== Screenshots ==

1. Block in the editor showing selected co-authors.
2. Frontend output with multiple linked authors.

== Changelog ==

= 1.0.0 =
* Initial release.
* Accessible block for multiple authors.
* Dynamic rendering with semantic HTML and ARIA roles.
* WCAG 2.1 AA compliant.

== Upgrade Notice ==

= 1.0.0 =
First stable release. Make sure to test the block on your existing posts.

== Credits ==

Developed with 💚 by [Marta Torre](https://martatorre.dev)  
WordPress full-stack developer and plugin reviewer.

== License ==

This plugin is licensed under the GPLv2 or later.  
You are free to use, modify, and distribute it under the same license.
