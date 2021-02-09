<?php
/*
Plugin Name: Tribe Common
Description: An event settings framework for managing shared options
Version: 4.12.16
Author: The Events Calendar
Author URI: http://evnt.is/1x
Text Domain: tribe-common
License: GPLv2 or later
*/

/*
Copyright 2009-2015 by The Events Calendar and the contributors

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// the main plugin class
require_once dirname( __FILE__ ) . '/src/Tribe/Main.php';

Tribe__Main::instance();
