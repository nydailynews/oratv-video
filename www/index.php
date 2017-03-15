<?php
require('env.php');
require(CLASS_PATH . 'class.csv.php');
//include('../class.csv.php');
include('views.php');

// Take any GET parameters and turn that into content we can relay back to the template.
// This may involve looking up a record from the CSV of project-specific data.
// Possible GET values: vendor, channel, slug.
// vendor will always be set. channel will be set everywhere but the video index.
// slug will only be set on the video detail view.
#RewriteRule ^ora-([_0-9a-zA-Z-]+)/([_0-9a-zA-Z-]+)/ index.php?vendor=ora&channel=$1&slug=$2 [L]
#RewriteRule ^ora-([_0-9a-zA-Z-]+)/ index.php?vendor=ora&channel=$1 [L]

// Sanitize and check input
$vendor = htmlspecialchars($_GET['vendor']);
$channel = htmlspecialchars($_GET['channel']);
$slug = htmlspecialchars($_GET['slug']);

$approved_vendors = ['ora'];
$approved_channels = ['mike-rogers-world-war-e'];

$request = new Request();

if ( $slug !== '' ):
	if ( !in_array($vendor, $approved_vendors) ) $request->return_404();
	if ( !in_array($channel, $approved_channels) ) $request->return_404();
	$channel_data = new parseCSV('channel-' . $vendor . '-' . $channel . '.csv');
	echo $request->return_detail($channel, $channel_data, $slug);
elseif ( $channel !== '' ):
	if ( !in_array($vendor, $approved_vendors) ) $request->return_404();
	if ( !in_array($channel, $approved_channels) ) $request->return_404();
	$channel_data = new parseCSV('channel-' . $vendor . '-' . $channel . '.csv');
	echo $request->return_channel($channel, $channel_data);
else:
	echo $request->return_index();
endif;
?>
