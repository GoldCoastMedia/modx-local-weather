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
	'area' => 'localweather',
	'xtype' => 'text-password',
), '', true, true);

return $settings;
