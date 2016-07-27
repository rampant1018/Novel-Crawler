<?php

$url = 'http://ck101.com/thread-1911678-1-1.html';

$pagedom = new DOMDocument();
$pagedom->loadHTML(file_get_contents($url));

$postlist = $pagedom->getElementById('postlist');
$postlists = $postlist->getElementsByTagName('div');

// Find all post id
$post_id_list = findPostIds($postlists);
print_r($post_id_list);

foreach($post_id_list as $post_id) {
	getPostContent($pagedom, $post_id);
}

function findPostIds($posts) {
	$post_id_list = array();
	foreach($posts as $post) {
		$post_id = $post->getAttribute('id');
		if(strpos($post_id, "post_") !== false
		   && (strpos($post_id, "post_rate") === false &&
		   strpos($post_id, "post_new") === false)) {
			array_push($post_id_list, $post_id);
		}
	}

	return $post_id_list;
}

function getPostContent($posts, $post_id) {
	// post_{id} ==> postmessage_{id}
	$content_id = 'postmessage_' . substr($post_id, 5);
	$content = $posts->getElementById($content_id);
	print_r($content->nodeValue);
}

?>
