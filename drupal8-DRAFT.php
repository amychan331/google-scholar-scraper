<?php
/**
 * GS Scrapper
 *
 * Scraps scientific article data from Google Scholar and either create or update a node.
 * Status INCOMPLETE. Switched to be Drupal 7 compatible module.
 *
 *  @author Amy Yuen Ying Chan
 *
**/

module_load_include('inc', 'node', 'node.pages');

function update_node($bodytext) {
    $nid = \Drupal::service('path.alias_manager')->getPathByAlias('/google-scholar');

    // If alias is not found, it will return the argument '/google-scholar'.
    // In such case, create new node:
    if ($nid == '/google-scholar') {
        $node = new stdClass();
        $node->title = "Google Scholar";
        $node->type = "page";
        node_object_prepare($node);
        $node->language = LANGUAGE_NONE;
        $node->status = 1;
        $node->comment = 0;
        $node->path['pathauto'] = TRUE;
    } else {
        echo("Alias found\n");
        $nid = end(explode("/", $nid));
        echo($nid."\n");
        $node = node_load($nid);
        if ($node) { echo("Node loading success.\n"); }

    }

    $node->body[$node->language][0]['value']   = $bodytext;
    $node->body[$node->language][0]['format']  = 'full_html';

    $node = node_submit($node);
    node_save($node);
}

function googlescholar_cron(){
  if( date('G', $time) == 12 ){
    $query = "";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://scholar.google.com/scholar?q=" . $query);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $results = curl_exec ($curl);

    preg_match_all('/<div class="gs_r">(.*?)<div class="gs_fl">/si', $results, $matches);
    foreach ($matches[0] as $m) {
        preg_match('/<h3 class="gs_rt">(.*?)<\/h3>/si', $m, $title);
        preg_match('/<div class="gs_a">(.*?)<\/div>/si', $m, $citation);
        preg_match('/<div class="gs_rs">(.*?)<\/div>/si', $m, $description);
        $formatted[] = array(
            'title'=>$title[1],
            'citation'=>$citation[0],
            'description'=>$description[0]
        );
    }

    curl_close($curl);
    return $formatted;
  }
}

// Define paramenters
$query = "";
$alias = 'google-scholar';

update_node('testing...', );
?>