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
			'IMGNAME' => ''
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
		$merged = array_merge($this->template_vars, $local);
		$this->template_vars = $merged;
		$this->markup = $this->build_response();
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
		$merged = array_merge($this->template_vars, $local);
		$this->template_vars = $merged;
		$this->markup = $this->build_response();
		header('HTTP/1.0 404 Not Found');
		echo $this->markup;
	}

	function build_response()
	{
		$markup = str_replace(array_keys($this->template_vars), array_values($this->template_vars), $this->markup);
		return $markup;
	}
}
