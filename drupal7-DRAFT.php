<?php
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
module_load_include('inc', 'node', 'node.pages');

/**
 * GS Scrapper
 *
 * Scraps scientific article data from Google Scholar and either create or update a node.
 *
 *  @author Amy Yuen Ying Chan
 *  @param string  $alias the alias of the content page that will list out the scientific articles
 *  @param string  $query the query use for the Google Scholar link
 *
**/

function update_node($bodytext, $alias) {
    $nid = drupal_get_normal_path($alias);

    // If alias is not found, it will return the alias.
    // In such case, create new node:
    if ($nid == $alias) {
        $node = new stdClass();
        $node->title = "Google Scholar";
        $node->type = "page";
        $node->language = LANGUAGE_NONE;
        node_object_prepare($node);

        $node->uid = 1;
        $node->status = 1;
        $node->promote = 0;
        $node->comment = 1;
        $node->path['pathauto'] = TRUE;
    } else {
        $nid = end(explode("/", $nid));
        $node = node_load($nid);
    }

    $node->body[$node->language][0]['value']   = $bodytext;
    $node->body[$node->language][0]['format']  = 'full_html';

    $node = node_submit($node);
    node_save($node);
}

function scrapper($query){
  // if( date('G', $time) == 12 ){
    $link = "https://scholar.google.com/scholar?q=";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link . $query);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $results = curl_exec ($curl);

    $formatted = "<em>Content below is automatically extracted from <a href='" . $link . $query . "'>Google Scholar</a> on " . date("F j, Y, g:i a") . ".</em><br />";
    preg_match_all('/<div class="gs_r">(.*?)<div class="gs_fl">/si', $results, $matches);
    foreach ($matches[0] as $m) {
        preg_match('/<h3 class="gs_rt">(.*?)<\/h3>/si', $m, $title);
        preg_match('/<div class="gs_a">(.*?)<\/div>/si', $m, $citation);
        preg_match('/<div class="gs_rs">(.*?)<\/div>/si', $m, $description);

        $formatted .= "<p>";
        $title = preg_replace("/<span[^>]+\>/i", "", $title[1]);
        $formatted .= "<strong>" . $title . "</strong><br />";
        // $citation = preg_replace("/<div[^>]+?[^>]+?[^>]+>|</div>/i", "", $citation[0]);
        $formatted .= "<em>" . utf8_encode(strip_tags($citation[0])) . "</em><br />";
        $formatted .= strip_tags($description[0]) . "...";
        $formatted .= "</p>";
    }

    curl_close($curl);
    return $formatted;
  // }
}

// Define paramenters
$query = "";
$alias = 'google-scholar';

$bodytext = scrapper($query);
if ($bodytext) {
    update_node($bodytext, $alias);
}