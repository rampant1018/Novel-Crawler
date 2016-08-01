<?php
$thread_id = $_GET{'thread_id'};
$post_id = $_GET{'post_id'};

$filename = $thread_id . '/' . $post_id;

print_r(file_get_contents($filename));
?>
