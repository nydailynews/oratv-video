<?php
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
	$request->return_detail($channel, $slug);
elseif ( $channel !== '' ):
	if ( !in_array($vendor, $approved_vendors) ) $request->return_404();
	if ( !in_array($channel, $approved_channels) ) $request->return_404();
	$request->return_channel($channel);
else:
	$request->return_index();
endif;

class Request {

	// A Request object takes all the information needed to put together a response
	// and returns the response.
	var $template_vars;
	var $markup;

	function __construct()
	{
		$this->template_vars = array(
			'TITLE' => '', 
			'DESCRIPTION' => '', 
			'SHORTURL' => '',
			'KEYWORDS' => '', 
			'CANONICALURL' => '', 
			'IMGNAME' => '',
			'PATHING' => '', 
			'SIDEBAR' => '', 
		);
		$this->markup = file_get_contents('blank.html');
	}

	function return_index()
	{
		$local = array(
			'TITLE' => 'New York Daily News video', 
			'DESCRIPTION' => '', 
			'CANONICALURL' => '', 
			'CONTENT' => file_get_contents('content/index.html'),
		);
		$this->template_vars = array_merge($this->template_vars, $local);
		$this->markup = $this->populate_markup();
		echo $this->markup;
	}

	function return_channel($channel)
	{
		$local = array(
			'PATHING' => '../', 
			'CONTENT' => file_get_contents('content/channel.html'),
		);
		// This include will populate the $channel_local array.
		// We also need some of the $channel_local vars in the parent-template array,
		// so we merge that later on too.
		include('channel/' . $channel . '.php');

		$this->template_vars = array_merge($this->template_vars, $local, $channel_local);
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->markup = $this->populate_markup();
		echo $this->markup;
	}

	function return_404()
	{
		$local = array(
			'TITLE' => 'Page not found', 
			'DESCRIPTION' => 'This is your 404 page.', 
			'CANONICALURL' => '', 
			'CONTENT' => file_get_contents('content/404.html'),
		);
		$this->template_vars = array_merge($this->template_vars, $local);
		$this->markup = $this->populate_markup();
		header('HTTP/1.0 404 Not Found');
		echo $this->markup;
	}

	function populate_markup($markup='')
	{
		// Replace a string of markup with the template vars array.
		// If $markup is not passed the function will default to the $this->markup value.
		if ( $markup == '' ) $markup = $this->markup;

		$return = str_replace(array_keys($this->template_vars), array_values($this->template_vars), $markup);
		return $return;
	}
}
