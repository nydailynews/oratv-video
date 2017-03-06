<?php
// Take any GET parameters and turn that into content we can relay back to the template.
// This may involve looking up a record from the CSV of project-specific data.
// Possible GET values: vendor, channel, slug.
// vendor will always be set. channel will be set everywhere but the video index.
// slug will only be set on the video detail view.
#RewriteRule ^ora-([_0-9a-zA-Z-]+)/([_0-9a-zA-Z-]+)/ index.php?vendor=ora&channel=$1&slug=$2 [L]
#RewriteRule ^ora-([_0-9a-zA-Z-]+)/ index.php?vendor=ora&channel=$1 [L]

$vendor = htmlspecialchars($_GET['vendor']);
$channel = htmlspecialchars($_GET['channel']);
$slug = htmlspecialchars($_GET['slug']);
