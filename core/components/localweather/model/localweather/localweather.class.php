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

	// Default configuration
	public $config = array(
		'basecss'       => '{MODX_ASSETS_URL}localweather/css/base.css',
		'cachelifetime' => 1800,
		'cachename'     => NULL,
		'css'           => NULL,
		'country'       => NULL,
		'current'       => TRUE,
		'days'          => 5,
		'forecast'      => TRUE,
		'iconurl'       => '{MODX_ASSETS_URL}localweather/icons/default/',
		'key'           => NULL,
		'location'      => 'London',
		'method'        => 'curl',
		'phpdate'       => '%a',
		'rowtpl'        => 'localweather_forecast',
		'theme'         => 'default',
		'themeurl'      => NULL,
		'timeout'       => 5,
		'tpl'           => 'localweather_weather',
	);

	// MODx caching options
	public $cache_opts = array(
		xPDO::OPT_CACHE_KEY => 'includes/elements/localweather',
	);

	protected $modx      = NULL;
	protected $namespace = 'localweather.';
	protected $api_url = 'http://api.worldweatheronline.com/free/v1/weather.ashx?';

	public function __construct(modX &$modx, array &$config)
	{
		$this->modx =& $modx;
		$this->modx->lexicon->load('localweather:default');

		// Force all parameters to lowercase
		$config = array_change_key_case($config, CASE_LOWER);

		// Get MODx Manager settings
		$settings = $this->modx->newQuery('modSystemSetting')->where(array(
			'key:LIKE' => $this->namespace . '%'
		));
		$settings = $this->modx->getCollection('modSystemSetting', $settings);
		
		// Apply MODx manager settings
		foreach($settings as $key => $setting) {
			$key = str_replace($this->namespace, '', $key);

			// Don't overwrite snippet params
			if(empty($config[$key]) OR $config[$key] === NULL) {
				$config[$key] = $setting->get('value');
			}
		}

		// Merge snippet parameters and system settings with default config
		$this->config = array_merge($this->config, $config);

		// Enable debugging
		if($this->config['debug'])
			$this->modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
	}

	// Main snippet execution
	public function run()
	{
		$url = $this->build_request_uri();
		$feed = $this->feed_cache($this->config['cachename'], $this->config['cachelifetime'], $url);

		if($this->valid_feed($feed) === FALSE)
		{
			// TODO: Improve error message
			$error = $this->modx->lexicon('localweather.error_feed_failed');
			$this->modx->log(modX::LOG_LEVEL_ERROR, $error);
		}
		else
		{
			$output = NULL;
			$feed = json_decode($feed);

			// Current weather
			if($this->config['current'])
			{
				$location = $feed->data->request[0]->query;
				$current = $feed->data->current_condition[0];
				$output .= $this->weather_current($current, $location);
			}

			// Weather forecast
			if($this->config['forecast'])
			{
				$forecast = $feed->data->weather;
				$output .= $this->weather_forecast($forecast);
			}

			// Add base CSS
			if($this->config['basecss'])
			{
				$stylesheets = $this->prepare_array($this->config['basecss']);
				$this->insert_css($stylesheets);
			}

			// Add CSS
			if($this->config['css'])
			{
				$stylesheets = $this->prepare_array($this->config['css']);
				$this->insert_css($stylesheets);
			}

			// Add theme CSS
			$this->theme( $this->config['theme'], $this->config['themeurl'] );

			return $output;
		}
	}

	/**
	 * Get the output for the current weather condition
	 *
	 * @param   object       $current  feed current weather JSON object
	 * @return  NULL|string
	 */
	protected function weather_current($current, $location = NULL)
	{
		$properties = array(
			'day'                => strftime($this->config['phpdate']),
			'cloudcover'         => $current->cloudcover,
			'humidity'           => $current->humidity,
			'location'           => $location,
			'observation_time'   => $current->observation_time,
			'precipMM'           => $current->precipMM,
			'pressure'           => $current->pressure,
			'temp_C'             => $current->temp_C,
			'temp_F'             => $current->temp_F,
			'theme'              => $this->config['theme'],
			'visibility'         => $current->visibility,
			'visibilityMiles'    => $this->miles($current->visibility),
			'weatherCode'        => $current->weatherCode,
			'weatherDesc'        => $this->modx->lexicon('localweather.condition_' . $current->weatherCode),
			'weatherDescDefault' => $current->weatherDesc[0]->value,
			'weatherIconUrl'     => $current->weatherIconUrl[0]->value,
			'winddir16Point'     => $current->winddir16Point,
			'winddirDegree'      => $current->winddirDegree,
			'windspeedKmph'      => $current->windspeedKmph,
			'windspeedMiles'     => $current->windspeedMiles,
		);
		
		$icon_properties = $this->weather_icon($current->weatherIconUrl[0]->value);
		$properties = array_merge($properties, $icon_properties);

		return $this->get_chunk($this->config['tpl'], $properties);
	}

	/**
	 * Get the output of each individual weather forecast
	 *
	 * @param   object       $forecast  feed JSON forecast object
	 * @return  NULL|string
	 */
	protected function weather_forecast($forecast)
	{
		$parsed = NULL;

		foreach($forecast as $key => $weather)
		{
			$properties = array(
				'day'                => strftime($this->config['phpdate'], strtotime($weather->date)),
				'date'               => $weather->date,
				'precipMM'           => $weather->precipMM,
				'tempMaxC'           => $weather->tempMaxC,
				'tempMaxF'           => $weather->tempMaxF,
				'tempMinC'           => $weather->tempMinC,
				'tempMinF'           => $weather->tempMinF,
				'theme'              => $this->config['theme'],
				'weatherCode'        => $weather->weatherCode,
				'weatherDesc'        => $this->modx->lexicon('localweather.condition_' . $weather->weatherCode),
				'weatherDescDefault' => $weather->weatherDesc[0]->value,
				'weatherIconUrl'     => $weather->weatherIconUrl[0]->value,
				'winddir16Point'     => $weather->winddir16Point,
				'winddirDegree'      => $weather->winddirDegree,
				'winddirection'      => $weather->winddirection,
				'windspeedKmph'      => $weather->windspeedKmph,
				'windspeedMiles'     => $weather->windspeedMiles,
			);
			
			$icon_properties = $this->weather_icon($weather->weatherIconUrl[0]->value);
			$properties = array_merge($properties, $icon_properties);

			$parsed .= $this->get_chunk($this->config['rowtpl'], $properties);
		}

		return $parsed;
	}
	
	/**
	 * Get additional icon properties
	 *
	 * @param   string  $iconurl the icon url
	 * @return  array
	 */
	protected function weather_icon($iconurl)
	{
		$icon = parse_url($iconurl, PHP_URL_PATH);
		$icon_info = pathinfo($icon);
		
		$original = $icon_info['filename'];
		$condition = explode('_', $original, 3);
		$condition = $condition[sizeof($condition) - 1];
		
		$icon_properties = array(
			'weatherIconName'      => $original,
			'weatherIconCondition' => $condition,
			'weatherIconUrlCustom' => $this->config['iconurl'],
		);
		
		return $icon_properties;
	}

	/**
	 * Check and return if the feeds JSON is valid
	 *
	 * @param   string  $feed  the JSON feed
	 * @return  bool
	 */
	protected function valid_feed($feed)
	{
		$json_feed = json_decode($feed);

		if(function_exists('json_last_error'))
		{
			if(json_last_error() !== JSON_ERROR_NONE)
				$json_feed = NULL;
		}

		if($feed === NULL OR $json_feed === NULL)
		{
			$error = $this->modx->lexicon('localweather.error_parsing_feed');
			$this->modx->log(modX::LOG_LEVEL_ERROR, $error);
			return FALSE;
		}
		else
		{
			// Check for feed based errors
			if(property_exists($json_feed->data, 'error'))
			{
				foreach($json_feed->data->error as $error)
				{
					$this->modx->log(modX::LOG_LEVEL_ERROR, $error->msg);
				}

				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}

	/**
	 * Fetch from or cache or create new request
	 *
	 * @param   string  $name    cache name
	 * @param   string  $life    cache lifetime
	 * @param   string  $url     feed URL
	 * @return  string
	 */
	protected function feed_cache($name, $life, $url)
	{
		$cachename = ( !empty($name) ) ? $name: $this->modx->resource->get('id');

		if($life > 0)
		{
			if(!$cached = $this->modx->cacheManager->get($cachename, $this->cache_opts))
			{
				$cached = $this->get_feed($url, $this->config['method'], $this->config['timeout']);

				// Only cache valid feeds!
				if($this->valid_feed($cached) AND $cached !== NULL)
					$this->modx->cacheManager->set($cachename, $cached, $life, $this->cache_opts);
			}

			return $cached;
		}
		else
		{
			return $this->get_feed($url, $this->config['method'], $this->config['timeout']);
		}
	}

	/**
	 * Build the request URL
	 *
	 * @return  string|bool
	 */
	protected function build_request_uri()
	{
		if(empty($this->config['key']) OR $this->config['key'] === NULL)
		{
			$error = $this->modx->lexicon('localweather.no_key');
			$this->modx->log(modX::LOG_LEVEL_ERROR, $error);
			return FALSE;
		}
		else
		{
			// API URL parameters
			$url_params = array(
				'key'         => $this->config['key'],
				'num_of_days' => $this->config['days'],
				'q'           => $this->config['location'],
				'format'      => 'json',
			);

			// Add a country if set
			if($this->config['country'] !== NULL)
				$url_params['q'] = sprintf('%s,%s', $url_params['q'], $this->config['country']);

			$url = $this->api_url . http_build_query($url_params);

			return $url;
		}
	}

	/**
	 * Get the weather feed
	 *
	 * @param   string  $url     The URL
	 * @param   string  $method  The method used to fetch the feed
	 * @return  bool
	 */
	protected function get_feed($url = NULL, $method = NULL, $timeout = 5)
	{
		if( !is_null($url) )
		{
			$method = strtolower('fetch_' . $method);
			return $this->$method($url, $timeout);
		}
		else
		{
			$error = $this->modx->lexicon('localweather.error_fetch_feed', array('url', $url));
			$this->modx->log(modX::LOG_LEVEL_ERROR, $error);
			return FALSE;
		}
	}

	/**
	* Fetch feed via cURL.
	*
	* @param   string  $url
	* @param   int     $timeout
	* @return  string  Returns XML
	*/
	protected function fetch_curl($url, $timeout = 5)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$feed = curl_exec($ch);
		curl_close($ch);

		return $feed;
	}

	/**
	* Returns remote feed via file_get_contents function.
	*
	* @param   string  $url
	* @param   int     $timeout
	* @return  string  Returns XML
	*/
	protected function fetch_file_get_contents($url, $timeout = 5)
	{
		$sc = stream_context_create(array('http' => array('timeout' => (int) $timeout)));
		$feed = file_get_contents($url, FALSE, $sc);
		return $feed;
	}
	
	/**
	 * Theme
	 *
	 * @param   string  $name   Theme name
	 * @param   string  $url    Theme URL
	 */
	protected function theme($theme = NULL, $themeurl = NULL)
	{
		$css = 'css/' . $theme . '.css';
		$url = $themeurl ? $url.$css : MODX_ASSETS_URL.'components/localweather/'.$css;
		
		if($url) {
			$insert = $this->prepare_array($url);
			
			if( is_array($insert) )
				$this->insert_css($insert);
		}
	}

	/**
	 * Convert km to miles
	 *
	 * @param   int  $km
	 * @return  int  mi
	 */
	protected function miles($km, $precision = 0)
	{
		return round($km * 0.621371192, (int) $precision);
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
		$csv = explode(',', $string);
		if($csv) $csv = array_map('trim', $csv);
		$csv = ( is_array($csv) ) ? $csv : array();

		return $csv;
	}
}

