=== HTML Templates ===
Contributors: LeoGermani
Donate link: http://pirex.com.br/wordpress-plugins
Tags: post, template, html, dynamic, page
Requires at least: 2.5
Tested up to: 2.5
Stable tag: 0.6

Allows you to create small HTML templates to use inside your posts or pages. The templates can contain dynamic fields, wich you set the value when writing a post or page. The plugin adds a button to you TinyMCE Editor for you to choose the template you want to add.

== Description ==

Allows you to create small HTML templates to use inside your posts on pages. The templates can contain dynamic fields, wich you set the value when writing a post or page. The plugin adds a button to you TinyMCE Editor for you to choose the template you want to add.

Its perfect if you have a post or page layout you, or yours site user, post frequently. You can, for instance, create a template for all download links in your site and, when you press the button to add this template to your post, the plugin asks you whats the name and link of the file. Its very useful for creating tables as well.


== Installation ==

. Download the package
. Extract it to the "plugins" folder of your wordpress
. In the Admin Panes go to "Plugins" and activate it

== Usage ==

1. Go to Manage > HTML Templates
2. Enter the name of your template
3. Enter the dynamic fields you want you template to have. (eg name,link)
4. Enter the HTML code for your template, with the dynamic fields between "#". For instance:
   ex1: <a href="#link#">#name#</a>
   ex2: <i>#title#&lt;BR&gt;&lt;img src="#image#"&gt;</i>

You can also use some template tags in your template:
#post-id#, #post-title# and #post-permalink# are accepted.

5. Save it.
6. Create or edit a page or post using the rich text editor
7. Click the new button that appeared on your editor "T" (Templates)
8. Select the template you want to use
9. Fill in the values for the dynamic fields
10. Click on Send to Editor


== TO DO ==

. Allow users to Edit a template after it was created
. javascript validation on the editor
. Improve the appearence of the plugin

