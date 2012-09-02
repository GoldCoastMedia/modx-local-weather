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
 * Local Weather is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Local Weather; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package localweather
 * @author  Dan Gibbs <dan@goldcoastmedia.co.uk>
 */

require_once $modx->getOption('core_path') . 'components/localweather/model/localweather/localweather.class.php';
$weather = new LocalWeather($modx, $scriptProperties);

$result = $weather->run();
unset($weather);

return $result;

