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
			'SHORTURL' => '',
			'KEYWORDS' => '', 
			'CANONICALURL' => '', 
			'IMGNAME' => '',
			'PATHING' => '', 
			'SIDEBAR' => '', 
			'PLAYERURL' => '', 
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
