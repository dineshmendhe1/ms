<?php
/*
 * Uninstall script for s-crib-otp-authentication 
 *
 * Copyright 2013-2014  Smart Crib Ltd  (email : info@s-crib.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */


//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

global $wpdb;
update_option("scribotp_plugin_db_version", "0.0");
update_option("scribotp_plugin_instance", "None");
update_option("scribotp_plugin_rnd", "Empty");
update_option("scribotp_plugin_db_key", "None");
delete_option('scribotp_plugin_db_version');
delete_option('scribotp_plugin_instance');
delete_option('scribotp_plugin_rnd');
delete_option('scribotp_plugin_db_key');

$table_name = $wpdb->prefix . 'scribotp';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);
?>

