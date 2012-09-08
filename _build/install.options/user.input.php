<?php
/**
 * Script to interact with user during MyComponent package install
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


$output = '
<p>Please enter your <strong>World Weather Online - Free Weather API Key</strong>.</p>
<br />
<p><small>If you do not have one you can 
register for one at <a href="http://www.worldweatheronline.com/register.aspx">http://www.worldweatheronline.com/register.aspx</a>.</small></p>
<br />
<p><small><strong>NOTE: If you are upgrading and already have a key you can leave this blank.</strong></small></p>
<br />
<label for="wwoapikey">API Key</label>
<input type="text" name="wwoapikey" id="wwoapikey" value="" align="left" size="40" maxlength="44" />
<p>&nbsp;</p>';


return $output;
