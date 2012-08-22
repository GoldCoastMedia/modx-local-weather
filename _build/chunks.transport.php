<?php
/**
 * Utility snippet for Local Weather development
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

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define sources */
$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'source_core' => $root . 'core/components/localweather',
    'source_assets' => $root . 'assets/components/localweather',
    'data' => $root . '_build/data/',
    'docs' => $root . 'core/components/localweather/docs/',
);
unset($root);

/* instantiate MODx */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


/* create category */

$category = $modx->getObject('modCategory', array('category'=>'Local Weather'));

if (! $category) {
    $category= $modx->newObject('modCategory');
    $category->set('category','Local Weather');
}

/* add chunks */
$modx->log(modX::LOG_LEVEL_INFO,'Adding in chunks.');
$chunks = include $sources['data'].'transport.chunks.php';

foreach($chunks as $chunk) {
    $chunk->set('id','');
}
if (is_array($chunks)) {
    $category->addMany($chunks);
} else { $modx->log(modX::LOG_LEVEL_FATAL,'Adding chunks failed.'); }

if ($category->save()) {
    $modx->log(modX::LOG_LEVEL_INFO,'Everything saved.');
}
/* create category vehicle */

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);
$modx->log(xPDO::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();
