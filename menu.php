<h2>Novel List:</h2>
<ul data-role="listview" class="ui-listview">
<?php
$thread_list = array();
$handle = fopen('menu.txt', 'r');
if($handle) {
	while($entry = fscanf($handle, "%s %s\n")) {
		array_push($thread_list, $entry);
	}
	fclose($handle);
}
else {
	echo 'file open error';
}

foreach($thread_list as $entry) {
	list($thread_id, $thread_name) = $entry;

	$list_item = '<li class="ui-last-child"><a href="#" onclick=selectNovel('
	             . $thread_id . ') '
	             . 'class="ui-btn ui-btn-icon-right ui-icon-carat-r">'
				 . $thread_name
				 . '</a></li>' . "\n";
	print_r($list_item);
}
?>
</ul>
