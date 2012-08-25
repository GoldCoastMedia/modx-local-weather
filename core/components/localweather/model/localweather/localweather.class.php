<?php
/**
 * Local Local Weather
 *
 * Copyright (c) 2012 Gold Coast Media Ltd
 *
 * This file is part of Local Weather.
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

	// Default configuration
	public $config = array(
		'cachelifetime' => 1800,
		'cachename'     => NULL,
		'css'           => 'assets/components/weather/css/localweather.css',
		'method'        => 'curl',
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
	}
	
	/**
	 * Get a MODx chunk
	 *
	 * @param   string  $name	        chunk name
	 * @param   array   $properties	chunk properties
	 * @return  object  returns modChunk
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
	 * @return  array|false
	 */	
	protected function prepare_array($string)
	{
		$csv = array_map('trim', explode(',', $string));
		$csv = ( is_array($csv) ) ? $csv : FALSE;

		return $csv;
	}
}

