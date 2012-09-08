<?php
/**
 * Local Weather transport chunks
 *
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

$chunks = array();

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'weather_c',
    'description' => 'Current Weather (C)',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/weather_c.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'forecast_c',
    'description' => 'Weather Forecast Item (row in C)',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/forecast_c.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 1,
    'name' => 'weather_f',
    'description' => 'Current Weather (F)',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/weather_f.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 4,
    'name' => 'forecast_f',
    'description' => 'Weather Forecast Item (row in F)',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/forecast_f.chunk.tpl'),
    'properties' => '',
),'',true,true);

return $chunks;
