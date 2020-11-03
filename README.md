# Pantheon .htaccess Redirects and Rewrites

Since Pantheon leverages Nginx for HTTP/HTTPS, we often find ourselves wondering how we can recreate our Apache mod_redirects and mod_rewrites on Pantheon. One way to do this, is to replicate those features in PHP.

In this repository, you will find *pantheon_rewrites.php*, a file which contains code to mimic Apache's mod_redirect and mod_rewrite capabilities on Pantheon. This file gets included at the very beginning of your Drupal *settings.php* or Wordpress *wp-config.php* files, and will perform rewrites and redirects, prior to full Wordpress or Drupal bootstrap.

## Configuring Your Redirects and Rewrites

First thing you will need to do is edit the *pantheon_rewrites.php* file with your redirects and rewrites. 

### Redirects

Open up *pantheon_rewrites.php* in your preferred editor. Find the *$redirects* array declaration (around line 62). You will see:

```
$redirects = [
  '/OLD_PATH' => '/NEW_PATH',  // You can copy/paste and duplicate this line for more redirects
];
```
Replace *OLD_PATH* with the path you wish to redirect. Replace *NEW_PATH* with the new path. If you need to add additional redirects, simply copy the line:
```
'/OLD_PATH' => '/NEW_PATH',
```
And paste it under the first redirect array object. Repeat the process for each new redirect.

### Rewrites

Open up *pantheon_rewrites.php* in your preferred editor. Find the *$mod_rewrites* array declaration (around line 102). You will see:

```
$mod_rewrites = [
  '/OLD_PATH' => '/NEW_PATH',  // You can copy/paste and duplicate this line for more rewrites
];
```
Replace *OLD_PATH* with the request path. Replace *NEW_PATH* with the rewrite proxy path. If you need to add additional rewrites, simply copy the line:
```
'/OLD_PATH' => '/NEW_PATH',
```
And paste it under the first rewrite array object. Repeat the process for each new rewrite.


### Installation for Drupal

After you have completed entering your redirect and/or rewrite paths, it's time to install this script into your Drupal codebase.

Add/upload the *pantheon_rewrites.php* script to *sites/default* directory.

Open up *settings.php* and insert the following code at the top, on or around lines 2 or 3 of the file.  
```
// Include line for settings.php
   $pantheon_rewrite_file = __DIR__ .  '/pantheon_rewrites.php';
   if(file_exists($pantheon_rewrite_file)) {
      include 'pantheon_rewrites.php';
   }
```

### Installation for Wordpress

After you have completed entering your redirect and/or rewrite paths, it's time to install this script into your Wordpress codebase.

Add/upload the *pantheon_rewrites.php* script to the Wordpress directory that contains the file *wp-config.php*.

Open up *wp-config.php* and insert the following code at the top, on or around lines 2 or 3 of the file.  
```
// Include line for wp-config.php
   $pantheon_rewrite_file = __DIR__ .  '/pantheon_rewrites.php';
   if(file_exists($pantheon_rewrite_file)) {
      include 'pantheon_rewrites.php';
   }
```
## Frequently Asked Questions

*Will this capture and pass query string variables for rewrites?*

Yes. Any query string parameters are passed to the rewrite proxy request and the redirects.

*Will this increase the load on my site?*

The redirects will not effect load on your site. The rewrites will add 1 additional request since this code will leverage a CURL call to retrieve the proxied page.
