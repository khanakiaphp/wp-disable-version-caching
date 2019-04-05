# Disable Version Caching 
**Tags:** browser cache, clear, assets, frontend, development  
**Requires at least:** 4.0  
**Tested up to:** 5.1  
**Stable tag:** 2.3.1  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Updates the assets version of all CSS and JS files. Shows the latest changes on the site without asking the client to clear browser cache.


## Description 

Are you a frontend developer? Do you want to clear browser cache for all users? Just activate this plugin and show your work!

Disable Version Caching allows you to update the assets version of all CSS and JS files automatically or manually in one click.

Now you can show the latest changes on the site without asking the client to clear the cache.


### How it works? 

Usually, WordPress loads assets using query param "ver" in the URL (e.g., style.css?ver=4.9.6). It allows browsers to cache these files until the parameter will not be updated.

To prevent caching of CSS and JS files, this plugin adds a unique number (e.g., 1526903434) to the "ver" parameter (e.g., style.css?ver=4.9.6.1526903434) for all links, loaded using wp_enqueue_style and wp_enqueue_script functions.


### For developers 

By default, this plugin updates all assets files every time a user loads a page and adds options in the admin panel (Settings -> Disable Version Caching) which allows you to configure updating of these files.

But you can also set the version of CSS and JS files programmatically.

Just insert this code in functions.php file of your theme and change the value of assets_version when you need to update assets: 

`disable_version_caching( array( 
	'assets_version' => '123' 
) );`


## Installation 


### From WordPress dashboard 

1. Visit "Plugins > Add New".
2. Search for "Disable Version Caching".
3. Install and activate Disable Version Caching plugin.


### From WordPress.org site 

1. Download Disable Version Caching plugin.
2. Upload the "disable-version-caching" directory to your "/wp-content/plugins/" directory.
3. Activate Disable Version Caching on your Plugins page.


## Changelog 


### 1.0 
* First version of Disable Version Caching plugin.
