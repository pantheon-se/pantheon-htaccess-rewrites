<?php


/**
 * Pantheon Rewrites provides a way for you to create Apache redirects and mod_rewrites in
 * Drupal and Wordpress. Since Pantheon leverages Nginx for HTTP/HTTPS, redirects and rewrites in
 * your .htaccess file will not work.
 *
 * Installation
 *
 * Drupal:
 *
 *    Place this pantheon_rewrites.php file in sites/default
 *    Add the following to line 2 of settings.php
 *          // Include line for settings.php or wp-config.php
            $pantheon_rewrite_file = __DIR__ .  '/pantheon_rewrites.php';
            if(file_exists($pantheon_rewrite_file)) {
              include 'pantheon_rewrites.php';
            }
 *
 * Wordpress:
 *
 *    Place this pantheon_rewrites.php file in the same directory as wp-config.php, typically your docroot
 *    Add the following to line 2 of wp-config.php
 *          // Include line for settings.php or wp-config.php
            $pantheon_rewrite_file = __DIR__ .  '/pantheon_rewrites.php';
            if(file_exists($pantheon_rewrite_file)) {
              include 'pantheon_rewrites.php';
            }
 *
 *
 * DISCLAIMER: Use this file at your own risk. Pantheon makes no warranties against this file
 *
 */


/**
 * Page Redirects
 *
 * The following code provides redirect capabilities for your site. It should be noted that both Drupal and Wordpress
 * have modules and plugins that can replicate this capability, and we recommend using one of those, however, if your
 * use case will not allow that, here is an alternative for redirects.
 *
 * Usage:
 *
 *  To use page redirects, edit the $redirects array as follows, one redirect per line:
 *
 *    $redirects = [
 *        '/OLD_PATH' => '/NEW_PATH',
 *       '/SECOND_OLD_PATH' => '/SECOND_NEW_PATH',
 *    ]
 *
 *
 *
 */


/**
 * Array of page redirects. This is what you edit.
 */
$redirects = [
  '/OLD_PATH' => '/NEW_PATH',  // You can copy/paste and duplicate this line for more redirects
];

/**
 * Redirect Loop code. DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!
 */
// Loop through requests for partial matching
foreach ($redirects as $source => $target) {
  if (strpos($_SERVER['REQUEST_URI'], $source) !== false) {
    // Check for static requests
    $prefix = (strpos($target, 'http') == 0) ? '' : "https://" . $_SERVER['HTTP_HOST'];
    // Redirect
    header("Location: " . $prefix . $target);
    exit();
  }
}


/**
 * URL Rewrites
 *
 * The following code provides mod_rewrite capabilities for your site. The following code will loop through the $mod_rewrite array
 * and proxy the new page without redirecting the user.
 *
 * Usage:
 *
 *  To use page rewrites, edit the mod_rewrites array as follows, one rewrite per line:
 *
 *    $mod_rewrites = [
 *        '/OLD_PATH' => '/NEW_PATH',
 *       '/SECOND_OLD_PATH' => '/SECOND_NEW_PATH',
 *    ]
 *
 *
 *
 */

/**
 * Array of URL rewrites. This is what you edit.
 */
$mod_rewrites = [
  '/OLD_PATH' => '/NEW_PATH',  // You can copy/paste and duplicate this line for more rewrites
];

/**
 * Rewrite Loop code. DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!
 */
foreach ($mod_rewrites as $source => $target) {
  if (strpos($_SERVER['REQUEST_URI'], $source) !== false) {

    // Build URL
    $url = "http://" . $_SERVER['HTTP_HOST'] . $target;

    // Fetch the proxy page markup and output as response.
    $page = get_proxy_site_page($url);
    echo $page['content'];
    exit();
  }
}


/**
 * Proxy page request function. DO NOT EDIT OR REMOVE THIS FUNCTION!
 *
 * @param [string] $url
 * @return void
 */
function get_proxy_site_page($url)
{
  $options = [
    CURLOPT_RETURNTRANSFER => true,     // return web page
    CURLOPT_HEADER         => false,     // return headers
    CURLOPT_FOLLOWLOCATION => true,     // follow redirects
    CURLOPT_ENCODING       => "",       // handle all encodings
    CURLOPT_AUTOREFERER    => true,     // set referer on redirect
    CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
    CURLOPT_TIMEOUT        => 120,      // timeout on response
    CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
  ];
  $ch = curl_init($url);
  curl_setopt_array($ch, $options);
  $remoteSite = curl_exec($ch);
  $header = curl_getinfo($ch);

  curl_close($ch);
  $header['content'] = $remoteSite;
  return $header;
}