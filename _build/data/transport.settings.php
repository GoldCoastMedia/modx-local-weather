<?php
/* This section is ONLY for new System Settings to be added to
 * The System Settings grid. If you include existing settings,
 * they will be removed on uninstall. Existing setting can be
 * set in a script resolver (see install.script.php).
 */

$settings = array();

$settings['setting_localweather.key']= $modx->newObject('modSystemSetting');
$settings['setting_localweather.key']->fromArray(array (
	'key' => 'localweather.key',
	'description' => 'setting_localweather.key_desc',
	'value' => '',
	'namespace' => 'localweather',
	'area' => 'API',
	'xtype' => 'text-password',
), '', true, true);

$settings['setting_localweather.cachelifetime']= $modx->newObject('modSystemSetting');
$settings['setting_localweather.cachelifetime']->fromArray(array (
	'key' => 'localweather.cachelifetime',
	'description' => 'setting_localweather.cachelifetime_desc',
	'value' => '1800',
	'namespace' => 'localweather',
	'area' => 'Caching',
	'xtype' => 'textfield',
), '', true, true);

$settings['setting_localweather.country']= $modx->newObject('modSystemSetting');
$settings['setting_localweather.country']->fromArray(array (
	'key' => 'localweather.country',
	'description' => 'setting_localweather.country_desc',
	'value' => 'UK',
	'namespace' => 'localweather',
	'area' => 'API',
	'xtype' => 'textfield',
), '', true, true);

$settings['setting_localweather.timeout']= $modx->newObject('modSystemSetting');
$settings['setting_localweather.timeout']->fromArray(array (
	'key' => 'localweather.timeout',
	'description' => 'setting_localweather.timeout_desc',
	'value' => 10,
	'namespace' => 'localweather',
	'area' => 'API',
	'xtype' => 'textfield',
), '', true, true);

return $settings;
