<?php

$thread_id = '1911678';
IteratePosts($thread_id);

function getPageDOM($url) {
	print_r($url);
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents($url));

	return $dom;
}

function IteratePosts($thread_id) {
	$url = 'http://ck101.com/thread-'. $thread_id . '-1-1.html';
	$pagedom = getPageDOM($url);
	$page_end = getLastPageNumber($pagedom);

	mkdir($thread_id);

	for($page_id = 1, $post_id = 1; $page_id <= $page_end; $page_id++) {
		$url = 'http://ck101.com/thread-'. $thread_id . '-' . $page_id . '-1.html';

		$pagedom = getPageDOM($url);
		$postlist = $pagedom->getElementById('postlist');
		$postlists = $postlist->getElementsByTagName('div');

		$post_id_list = findPostIds($postlists);

		foreach($post_id_list as $pid) {
			$content = getPostContent($pagedom, $pid);
			$filename = $thread_id . '/' . $post_id;
			file_put_contents($filename, $content);

			$post_id++;
		}
	}
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

	return replaceNewline(innerXML($content));
}

function getLastPageNumber($pagedom) {
	$pgt = $pagedom->getElementById('pgt');
	$pages = $pgt->getElementsByTagName('a');

	foreach($pages as $page) {
		if(strpos($page->getAttribute('class'), "last") !== false) {
			return intval(substr($page->nodeValue, 4));
		}
	}
}

function innerXML($node) {
	$doc = $node->ownerDocument;
	$frag = $doc->createDocumentFragment();
	foreach($node->childNodes as $child) {
		$frag->appendChild($child->cloneNode(true));
	}

	return $doc->saveXML($frag);
}

function replaceNewline($content) {
	$pattern = '/<br \/><br \/>&#13;/';
	$replacement = "\n";
	$content = preg_replace($pattern, $replacement, $content);

	$pattern = '/&#13;/';
	$replacement = '';
	$content = preg_replace($pattern, $replacement, $content);

	$pattern = '/<br \/>/';
	$replacement = '';
	$content = preg_replace($pattern, $replacement, $content);

	return $content;
}

?>
