<?php
/**
 * Copyright 2012 Gold Coast Media Ltd
 *
 * Local Weather is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Local Weather is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Local Weather; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package     localweather
 * @subpackage  build
 * @author      Dan Gibbs <dan@goldcoastmedia.co.uk>
 */

$modx =& $object->xpdo;
$category = 'LocalWeather';

/* set to TRUE to connect property sets to elements */
$connectPropertySets = TRUE;

$success = TRUE;

$modx->log(xPDO::LOG_LEVEL_INFO, 'Running PHP Resolver.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
	/* This code will execute during an install */
	case xPDOTransport::ACTION_INSTALL:
		$apikey = $modx->getOption('wwoapikey', $options);
		$modx->log(xPDO::LOG_LEVEL_INFO,'Setting API key');
		
		$setting = $modx->newObject('modSystemSetting');
		$setting->set('key', 'localweather.key');
		$setting->set('description', 'setting_localweather.key_desc');
		$setting->set('value', $apikey);
		$setting->set('namespace', 'localweather');
		$setting->set('area', 'localweather');
		$setting->set('xtype', 'password');
		$setting->save();
		break;

	/* This code will execute during an upgrade */
	case xPDOTransport::ACTION_UPGRADE:

		/* put any upgrade tasks (if any) here such as removing
		   obsolete files, settings, elements, resources, etc.
		*/

		$success = TRUE;
		break;

	/* This code will execute during an uninstall */
	case xPDOTransport::ACTION_UNINSTALL:
		$modx->log(xPDO::LOG_LEVEL_INFO,'Uninstalling . . .');
		$success = TRUE;
		break;

}
$modx->log(xPDO::LOG_LEVEL_INFO, 'Script resolver actions completed. Group hug.');
return $success;
