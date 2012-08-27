<?php
/**
 * Local Weather
 *
 * Copyright (c) 2012 Gold Coast Media Ltd
 *
 * This file is part of Local Weather for MODx.
 *
 * Local Weather is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Local Weather is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Local Weather if not, write to the Free Software Foundation, Inc., 59 
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package  localweather
 * @author   Dan Gibbs <dan@goldcoastmedia.co.uk>
 */

class LocalWeather {

	const API_URL = 'http://free.worldweatheronline.com/feed/weather.ashx?';
	
	// Default configuration
	public $config = array(
		'cachelifetime' => 1800,
		'cachename'     => NULL,
		'css'           => 'assets/components/localweather/css/localweather.css',
		'country'       => NULL,
		'days'          => 5,
		'iconurl'       => 'assets/components/localweather/icons/',
		'location'      => 'London',
		'measurement'   => 'c',
		'method'        => 'curl',
		'tpl'           => 'weather',
	);
	
	// MODx caching options
	public $cache_opts = array(
		xPDO::OPT_CACHE_KEY => 'includes/elements/localweather',
	);
	
	protected $modx = NULL;
	
	public function __construct(modX &$modx, array &$config)
	{
		$this->modx =& $modx;
		$this->modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
		$this->modx->lexicon->load('localweather:default');

		// Force all parameters to lowercase
		$config = array_change_key_case($config, CASE_LOWER);

		// Merge snippet parameters with default config
		$this->config = array_merge($this->config, $config);
	}
	
	public function run()
	{
		$resource = &$this->modx->resource;
		
		// API URL parameters
		$url_params = array(
			'key'         => '',
			'num_of_days' => $this->config['days'],
			'q'           => '',
			'format'      => 'json',
		);
	}
	
	/**
	 * Get the weather feed
	 *
	 * @param   string  $url     The URL
	 * @param   string  $method  The method used to fetch the feed
	 * @return  bool
	 */
	protected function get_feed($url = NULL, $method = NULL)
	{
		if( !is_null($url) )
		{
			$method = strtolower('fetch_' . $method);
			return $this->$method($url);
		}
		else
		{
			$error = $this->modx->lexicon('weather.error_fetch_feed', array('url', $url));
			$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);
			return FALSE;
		}
	}
	
	/**
	 * Get a MODx chunk
	 *
	 * @param   string  $name	 chunk name
	 * @param   array   $properties	 chunk properties
	 * @return  object  returns	 modChunk
	 */
	protected function get_chunk($name, $properties = array())
	{
		$chunk = $this->modx->getChunk($name, $properties);
		return $chunk;
	}
	
	/**
	 * Insert CSS into the a documents head
	 *
	 * @param   array  $arr  css files
	 * @return  void
	 */
	protected function insert_css($stylesheets = array())
	{
		if( !is_array($stylesheets))
		{
			// FIXME: A better way to do this
			$stylesheet = str_split($stylesheet, strlen($stylesheet));
		}

		foreach ($stylesheets as $css)
		{
			$this->modx->regClientCSS($css);
		}
	}

	/**
	 * Return array from comma separated arguments
	 *
	 * @param   string       $string  comma separated string
	 * @return  array|FALSE
	 */	
	protected function prepare_array($string)
	{
		$csv = array_map('trim', explode(',', $string));
		$csv = ( is_array($csv) ) ? $csv : FALSE;

		return $csv;
	}
}

