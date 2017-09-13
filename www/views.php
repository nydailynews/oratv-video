<?php

class Request {

	// A Request object takes all the information needed to put together a response
	// and returns the response.
	var $template_vars;
	var $markup;

	function __construct($domain, $url_base)
	{
		$this->domain = $domain;
		$this->url_base = $url_base;
		$this->template_vars = array(
			'TITLE' => '', 
			'CHANNEL' => '', 
			'DESCRIPTION' => '', 
			'LONG_DESC' => '', 
			'SHORTURL' => 'http://nydn.us/WorldWarE',
			'KEYWORDS' => 'news,video,\'news video\'', 
			'CANONICAL_URL' => '', 
			'IMAGE_URL' => '',
			'PATHING' => '', 
			'ASIDE_H2' => '', 
			'PLAYER_URL' => '', 
			'UPLOAD_DATE' => '', 
			'MORE' => '', 
			'TWIT_DESC' => '', 
			'PUBDATE' => '2017-03-16', 
			'DATE_FULL' => '', 
			'ADTAXONOMY' => 'news_video', 
			'EPISODE_NUMBER' => '', 
			'PLAYER' => file_get_contents('player.html'),
		);
		$this->markup = file_get_contents('blank.html');
	}

	function return_index()
	{
		$local = array(
			'TITLE' => 'New York Daily News video', 
			'DESCRIPTION' => '', 
			'CANONICAL_URL' => $this->domain . $this->url_base, 
			'PLAYER_URL' => '', 
			'CONTENT' => file_get_contents('content/index.html'),
		);
		$this->template_vars = array_merge($this->template_vars, $local);
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		$this->markup = $this->populate_markup();
		return $this->markup;
	}

	function return_channel($channel, $items)
	{
		// Return markup of an entire html page.
		$local = array(
			'PATHING' => '../', 
			'CANONICAL_URL' => $this->domain . $this->url_base . $this->vendor . '-' . $channel . '/',
			'IMAGE_URL' => $this->domain . $this->url_base . 'img/' . $channel . '.jpg',
			'CONTENT' => file_get_contents('content/channel.html'),
		);
		// This include will populate the $channel_local array.
		// We also need some of the $channel_local vars in the parent-template array,
		// so we merge that later on too.
		include('channel/' . $channel . '.php');

		$this->template_vars = array_merge($this->template_vars, $local, $channel_local);
		$channel_count = count($items->data);
		if ( array_key_exists('PLAYER_TYPE', $this->template_vars) ):
			$this->template_vars['PLAYER'] = file_get_contents('player-' . strtolower($this->template_vars['PLAYER_TYPE']) . '.html');
		endif;
		$this->template_vars['TITLE'] = str_replace('EPISODE_NAME', $items->data[0]['title'], $channel_local['TITLE']);
		$this->template_vars['TITLE'] = str_replace('EPISODE_NUMBER', $channel_count, $this->template_vars['TITLE']);
		$this->template_vars['TITLE'] = str_replace('EPISODE_NAME', '', $channel_local['TITLE']);
		$this->template_vars['TITLE'] = str_replace('EPISODE_NUMBER', '', $this->template_vars['TITLE']);
		$this->template_vars['TITLE'] = str_replace(', Episode : ', '', $this->template_vars['TITLE']);
		$this->template_vars['DESCRIPTION'] = $items->data[0]['description'];
		$this->template_vars['TWIT_DESC'] = str_replace('EPISODE_NUMBER', $channel_count, $this->template_vars['TWIT_DESC']);
		if ( $this->template_vars['LONG_DESC'] != '' ):
			$this->template_vars['ASIDE_H2'] = 'More about ' . $channel_local['CHANNEL'];
		endif;
		//$this->template_vars['ASIDE_H2'] = 'More about “World War E” with Mike Rogers';
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		//$this->template_vars['VIDEO_ID'] = $details['id'];
		$this->template_vars['MORE'] = $this->format_recent_videos($items->data, $channel, 15, $this->template_vars['MORE_LABEL_TEXT']);
		$this->template_vars['THUMBNAILS'] = $this->format_video_thumbnails($items->data, $channel, 4);
		$this->markup = $this->populate_markup();
		return $this->markup;
	}

	function return_detail($channel, $items, $details)
	{
		// Return markup of an entire html page.
		$local = array(
			'TITLE' => $details['title'], 
			'DESCRIPTION' => $details['description'], 
			'CANONICAL_URL' => $this->domain . $this->url_base . $this->vendor . '-' . $channel . '/' . $details['slug'] . '/', 
			'PATHING' => '../../', 
			'CONTENT' => file_get_contents('content/detail.html'),
		);
		// This include will populate the $channel_local array.
		// We also need some of the $channel_local vars in the parent-template array,
		// so we merge that later on too.
		include('channel/' . $channel . '.php');


		$this->template_vars = array_merge($this->template_vars, $channel_local, $local);
		if ( array_key_exists('PLAYER_TYPE', $this->template_vars) ):
			$this->template_vars['PLAYER'] = file_get_contents('player-' . strtolower($this->template_vars['PLAYER_TYPE']) . '.html');
		endif;
		if ( $this->template_vars['LONG_DESC'] != '' ):
			$this->template_vars['ASIDE_H2'] = 'More about ' . $channel_local['CHANNEL'];
		endif;
		$this->template_vars['KEYWORDS'] = $details['keywords'];
		$this->template_vars['DATE_FULL'] = $this->format_date($details['date']);
		$this->template_vars['PUBDATE'] = $details['date'];
		$this->template_vars['SHORTURL'] = $local['CANONICAL_URL'];
		$this->template_vars['IMAGE_URL'] = $details['image_large_url'];
		$this->template_vars['PLAYER_URL'] = $details['player_url'];
		$this->template_vars['VIDEO_ID'] = $details['id'];
		$this->template_vars['CONTENT'] = $this->populate_markup($local['CONTENT']);
		$this->template_vars['PLAYER'] = $this->populate_markup($this->template_vars['PLAYER']);
		$this->template_vars['MORE'] = $this->format_recent_videos($items->data, $channel, 5, $this->template_vars['MORE_LABEL_TEXT']);
		$this->template_vars['THUMBNAILS'] = $this->format_video_thumbnails($items->data, $channel, 4);
		$this->markup = $this->populate_markup();
		return $this->markup;
	}
	
	function format_date($date, $format='basic')
	{
		// Takes a string such as '2016-03-22' and returns a fancier string, March 22, 2016.
		// We always assume that if the year published is the same as the current year then we don't need to include the year.
		//Warning: strtotime(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in /apps/video/views.php on line 108
		//$d = strtotime($date);
	}

	function format_recent_videos($items, $channel, $limit, $label_text='Episodes')
	{
		// Return an array of recent video objects for formatting.
		$i = 0;
		$return = '';
		foreach ( $items as $key => $value ):
			$i ++;
			if ( $i > $limit ) break;
			$return .= '	<li><a href="' . $this->url_base . $this->vendor . '-' . $channel . '/' . $value['slug'] . '/">' . $value['title'] . '</a></li>' . "\n";
		endforeach;
		return '<h3>' . $label_text . '</h3><ul>' . $return . '</ul>';
	}

	function format_video_thumbnails($items, $channel, $limit)
	{
		// Return the markup for recent videos with thumbnails
		$i = 0;
		$return = '';
		foreach ( $items as $key => $value ):
			$return .= '
            <div class="rel_content2 large-3 medium-3 columns">
                <a href="' . $this->url_base . $this->vendor . '-' . $channel . '/' . $value['slug'] . '/"><img class="img rel_content2 large-3 medium-3 columns" alt="Mike Rogers’ ' . $value['title'] . '" src="' . $value['image_url'] . '"></a>
                <p class="rel_content2"><a href="' . $this->url_base . $this->vendor . '-' . $channel . '/' . $value['slug'] . '/">' . $value['title'] . '</a></p>
			</div>';
			$i += 1;
			if ( $i >= $limit ) break;
		endforeach;
		return $return;
	}

	function get_video($items, $slug)
	{
		// Given a CSV of video items, return the details of a video object.
		foreach ( $items as $value ):
			if ( trim($value['slug']) == trim($slug) ) return $value;
		endforeach;
		return false;
	}

	function return_404()
	{
		$local = array(
			'TITLE' => 'Page not found', 
			'DESCRIPTION' => 'This is your 404 page.', 
			'CANONICAL_URL' => $this->domain . $this->url_base, 
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
