<?php

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
			'LONG_DESC' => '', 
			'SHORTURL' => 'http://nydn.us/WorldWarE',
			'KEYWORDS' => 'news,video,\'news video\'', 
			'CANONICALURL' => '', 
			'IMGNAME' => '',
			'PATHING' => '', 
			'SIDEBAR' => '', 
			'PLAYERURL' => '', 
			'UPLOAD_DATE' => '', 
			'MORE' => '', 
			'PLAYER' => file_get_contents('player.html'),
		);
		$this->markup = file_get_contents('blank.html');
	}

	function return_index()
	{
		$local = array(
			'TITLE' => 'New York Daily News video', 
			'DESCRIPTION' => '', 
			'CANONICALURL' => 'http://interactive.nydailynews.com/video/', 
			'PLAYERURL' => '', 
			'CONTENT' => file_get_contents('content/index.html'),
		);
		$this->template_vars = array_merge($this->template_vars, $local);
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		$this->markup = $this->populate_markup();
		return $this->markup;
	}

	function return_channel($channel)
	{
		// Return markup of an entire html page.
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
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		$this->markup = $this->populate_markup();
		return $this->markup;
	}
	
	function get_recent_videos()
	{
		// Return an array of recent video objects for formatting.
	}

	function get_video()
	{
		// Return the details of a video object
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
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		$this->markup = $this->populate_markup();
		header('HTTP/1.0 404 Not Found');
		return $this->markup;
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
