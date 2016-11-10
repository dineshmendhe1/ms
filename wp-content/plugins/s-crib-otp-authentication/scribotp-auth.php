<?php

/*
  Plugin Name: OTP and Passwords
  Plugin URI: http://www.s-crib.com/wpotp
  Description: One Time Password (OTP) OATH authentication module to be used with Password S-CRIB dongles, Google Authenticator or McAfee apps for smart phones and other OATH OTP generators (HOTP as well as TOTP). (QR codes by Dominik Dzienia, AES class by Jim Wigginton, and SHA256 by Perry McGee.)
  Author: Smart Crib Ltd
  Version: 2.1.2
  Author URI: http://www.s-crib.com
  License: GPLv3

  Copyright 2014 Smart Crib Ltd (email: info@s-crib.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

 */


/* Add default values */

require("phpqrcode.php");
require("AES.php");
require("sha256.inc.php");

$scribotp_install_log = "";
$scribotp_logpwds = FALSE;
$scribotp_logotp = FALSE;
$scribotp_plugin_db_version = "2.1.2";

function scribotp_base32_encode($input) {
    // Get a binary representation of $input
    $binary_in = unpack('C*', $input);
    $binary = vsprintf(str_repeat('%08b', count($binary_in)), $binary_in);

    $binaryLength = strlen($binary);
    $base32_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
    $currentPosition = 0;
    $output = '';

    while ($currentPosition < $binaryLength) {
        $bits = substr($binary, $currentPosition, 5);

        if (strlen($bits) < 5) {
            $bits = str_pad($bits, 5, "0");
        }
        // Convert the 5 bits into a decimal number
        // and append the matching character to $output
        $output .= $base32_characters[bindec($bits)];
        $currentPosition += 5;
    }

    // Pad the output length to a multiple of 8 with '=' characters
    $desiredOutputLength = strlen($output);
    if ($desiredOutputLength % 8 != 0) {
        $desiredOutputLength += (8 - ($desiredOutputLength % 8));
        $output = str_pad($output, $desiredOutputLength, "=");
    }

    return $output;
}

function scribotp_base32_decode($b32) {
    $lut = array("A" => 0, "B" => 1, "C" => 2, "D" => 3,
                 "E" => 4, "F" => 5, "G" => 6, "H" => 7,
                 "I" => 8, "J" => 9, "K" => 10, "L" => 11,
                 "M" => 12, "N" => 13, "O" => 14, "P" => 15,
                 "Q" => 16, "R" => 17, "S" => 18, "T" => 19,
                 "U" => 20, "V" => 21, "W" => 22, "X" => 23,
                 "Y" => 24, "Z" => 25, "2" => 26, "3" => 27,
                 "4" => 28, "5" => 29, "6" => 30, "7" => 31
    );

    $b32 = strtoupper($b32);
    $l = strlen($b32);
    $n = 0; $j = 0;
    $binary = "";

    for ($i = 0; $i < $l; $i++) {
        $n_inner = $n << 5;
        $n = $n_inner + $lut[$b32[$i]];
        $j = $j + 5;
        if ($j >= 8) {
            $j = $j - 8;
            $binary .= chr(($n & (0xFF << $j)) >> $j);
        }
    }
    return $binary;
}

function scribotp_update_log($message){
    global $scribotp_install_log;
    
    $myFile = plugin_dir_path(__FILE__) . "/admin.log";
    $logtime = @date('[d/M/Y:H:i:s]');
    $fh = @fopen($myFile, 'a');
    if ($fh){
        fwrite($fh, $logtime." ".$message."\n");
        fclose($fh);
    }

    $scribotp_install_log .= $message."</br>";
}

function scribotp_get_timesteps($steplength, $base_time) {

    $timesteps = floor((microtime(true) - $base_time) / $steplength); //return time in seconds as a float
    $timesteps2 = 0; //$timesteps>>32;
    $binary_timestamp = pack('N*', $timesteps2) . pack('N*', $timesteps);
    return $binary_timestamp;
}

function scribotp_do_post_request($url, $path, $data) {
    global $scribotp_plugin_db_version;
    
    $data = http_build_query($data);

    //$myFile = plugin_dir_path(__FILE__) . "/otplog.txt";
    if (function_exists('wp_remote_post')) {
        $http_args = array(
            'body' => $data,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded; ' .
                'charset=' . get_option('blog_charset'),
                'Host' => $url,
                'User-Agent' => 'SCRIB_OTP ' . $scribotp_plugin_db_version
            ),
            'httpversion' => '1.1',
            'timeout' => 10
        );
        $response = wp_remote_post("https://" . $url . $path, $http_args);
        if (is_wp_error($response)) {
            return '';
        }
        $splitcontents = preg_split("/((\r?\n)|(\r\n?))/", $response['body']);
        foreach ($splitcontents as $line) {
            $bits = explode(" ", $line);
            if (strcmp($bits[0], "IK") === 0) {
                update_option("scribotp_plugin_db_ik", trim($bits[1]));
            }
            if (strcmp($bits[0], "MK") === 0) {
                update_option("scribotp_plugin_db_mk", trim($bits[1]));
            }
        }
        return $response['body'];
    } else {
        $content_length = strlen($data);
        $http_request = "POST $path HTTP/1.1\r\n";
        $http_request .= 'Host: ' . $url . "\r\n";
        $http_request .= 'Content-Type: application/x-www-form-urlencoded; charset=' . get_option('blog_charset') . "\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: SCRIB_OTP {$scribotp_plugin_db_version}\r\n";
        $http_request .= "\r\n";
        $http_request .= $data;

        $response = '';
        $errno = 0;
        $errstr = "";
        $fs = @fsockopen("ssl://" . $url, 443, $errno, $errstr, 10);
        if ( $fs ) {
            if (fwrite($fs, $http_request) === false) {
                return;
            };
            $line = " ";
            while ((strpos($line, "</html>") === false)) {
                $line = fgets($fs);
                $response .= $line; // One TCP-IP packet
            }
            fclose($fs);
            $response = explode("\r\n\r\n", $response, 2);
            $splitcontents = preg_split("/((\r?\n)|(\r\n?))/", $response[1]);
            foreach ($splitcontents as $line) {
                $bits = explode(" ", $line);
                if (strcmp($bits[0], "IK") == 0) {
                    update_option("scribotp_plugin_db_ik", trim($bits[1]));
                }
                if (strcmp($bits[0], "MK") == 0) {
                    update_option("scribotp_plugin_db_mk", trim($bits[1]));
                }
            }
        }
        return $response[1];
    }
}

function scribotp_plugin_update_db_check() {
    global $scribotp_plugin_db_version;
    if (get_option('scribotp_plugin_db_version') !== $scribotp_plugin_db_version) {
        $installed_version = get_option('scribotp_plugin_db_version');
        if (strlen($installed_version)<1){
            $installed_version = "None found - first installation";
        }
        scribotp_update_log("Update check - installed version:" . get_option('scribotp_plugin_db_version'));
        scribotp_plugin_install();
    }
}

add_action( 'plugins_loaded', 'scribotp_plugin_update_db_check' );
add_action( 'init', 'scribotp_plugin_update_db_check'); //for automatic updates

function scribotp_plugin_install() {
    global $wpdb;
    global $scribotp_install_log;
    global $scribotp_plugin_db_version;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $installed_ver = get_option("scribotp_plugin_db_version");
    if (strlen($installed_ver)<1){
        $installed_ver = "none";
    }
    $rnd_status = get_option("scribotp_plugin_rnd");
    if (strlen($rnd_status)<1){
        $rnd_status = "-";
    }
    
    scribotp_update_log("Time of installation: " . date("Y-m-d H:i:s"));
    scribotp_update_log("Installed version: " . $installed_ver);
    scribotp_update_log("PRNG status: " . $rnd_status);
    // get 128 pseudorandom bits in a string of 16 bytes - db key
    // get 128 pseudorandom bits in a string of 16 bytes - instance ID
    $db_key = '';
    $instance = '';

    // Unix/Linux platform?
    $fp = @fopen('/dev/urandom', 'rb');
    if ($fp === FALSE) {
        scribotp_update_log("Error opening /dev/urandom - if it is Linux/Unix machine, get in touch at info@s-crib.com.");
    } else {
        $rnddata = @fread($fp, 128);
        @fclose($fp);
        $obj = new nanoSha2();
        $shaStr = $obj->hash($rnddata);
        $db_key = substr($shaStr, 0, 32);
        $instance = substr($shaStr, 32, 32);
        scribotp_update_log("RNG - /dev/urandom read successfully.");
        $rnd_status="1";
    }
    if (strlen($db_key) < 16) {
        // MS-Windows platform?
        if (@class_exists('COM')) {
            // http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
            try {
                $CAPI_Util = new COM('CAPICOM.Utilities.1');
                $rnddata = $CAPI_Util->GetRandom(32, 0);
                $obj = new nanoSha2();
                $shaStr = $obj->hash($rnddata);
                $db_key = substr($shaStr, 0, 32);
                $instance = substr($shaStr, 32, 32);
                scribotp_update_log("RNG - CAPICOM.Utilities.1 - RNG read successfully.");
                $rnd_status = "1";
            } catch (Exception $ex) {
                scribotp_update_log("Error opening CAPICOM for RNG - it is not Win Machine: ".$ex->getMessage());
            }
        } else {
            scribotp_update_log("CAPICOM.Utilities for RNG do not exist - if it is Win Machine, get in touch at info@s-crib.com");
        }
    }
    if ($rnd_status !== '1'){
        scribotp_update_log("We use time to collect random data for RNG - last resort");
        $shaStr = "akljsoqpeutp9384y5tuhgbjnkslgwpr8ty4589ghusdjkfaljgo8tro345r";
        $obj = new nanoSha2();
        for ($i0=0; $i0<3; $i0++){
            $rndinput = (string)microtime();
            sleep(1);
            $rndinput .= $shaStr . (string)microtime();
            $shaStr = $obj->hash($shaStr . $rndinput);
        }
        for ($i0=0; $i0<100; $i0++){
            usleep(1549);
            $rndtime = microtime();
            $shaStr = $obj->hash($shaStr . $rndtime);
        }
        $instance = substr($shaStr,0, 32); 
        for ($i0=0; $i0<100; $i0++){
            usleep(2103);
            $rndtime = microtime();
            $shaStr = $obj->hash($shaStr . $rndtime);
        }
         
        $db_key = substr($shaStr,32, 32);
        scribotp_update_log("We have generated keys from time");
        scribotp_update_log("Please get in touch and send us this log to info@s-crib.com together with your server specification (operating system, where if hosted, type of hardware or VM package)");
        //$rnd_status = '1';
    }
    // we might have update RNG status - update option
    update_option('scribotp_plugin_rnd', $rnd_status);
    
    $table_name = $wpdb->prefix . 'scribotp';
    //let's try to add option, if it fails -> plugin has already been installed and DB table exists
    $no_copy = add_option('scribotp_plugin_db_version', '');

    $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            userid int(11) NOT NULL,
            created datetime NOT NULL,
            seed varchar(64) COLLATE utf8_unicode_ci NOT NULL,
            counter bigint(20) NOT NULL DEFAULT '0',
            pin varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
            fails int(11) NOT NULL DEFAULT '0',
            failstotal int(11) NOT NULL DEFAULT '0',
            lastsuccess datetime DEFAULT NULL,
            lastfail datetime DEFAULT NULL,
            otplength int(11) NOT NULL DEFAULT '8',
            lastsuccessip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
            lastfailip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
            type INT NOT NULL DEFAULT 0,
            timebase DATETIME,
            timez INT NOT NULL DEFAULT 0,
            lasttime DATETIME,
            timestep INT,
            window INT,
            timedrift INT,
            PRIMARY KEY  (id),
            UNIQUE KEY id (id)
           );";

    if ($no_copy === TRUE) { // not installed yet
        scribotp_update_log("We are creating new DB table ".$table_name);
        dbDelta($sql);
        update_option('scribotp_plugin_db_version', $scribotp_plugin_db_version);
        scribotp_update_log("Plugin version in the WP configuration updated");
        if (strlen($instance)>10) {
            scribotp_update_log("Instance OK");
            $instance_temp = sha1($instance, FALSE);
            $instance = substr($instance_temp, 0, 32);
            scribotp_update_log("Instance ID: " . $instance);
            update_option('scribotp_plugin_instance', $instance);
            if (strlen($db_key)>10) {
                scribotp_update_log("DB key OK (length: " . strlen($db_key) . ")");
                $db_key_temp = sha1($db_key, FALSE);
                $db_key = substr($db_key_temp, 0, 32);
                update_option('scribotp_plugin_db_key', $db_key);
                update_option('scribotp_plugin_rnd', '1');
            } else {
                // do something to warn system owner that
                // pseudorandom generator is missing
                update_option('scribotp_plugin_rnd', '0');
            }
        } else {
            // do something to warn system owner that
            // pseudorandom generator is missing
            update_option('scribotp_plugin_rnd', '0');
        }
    } else {
        if ($rnd_status !== "1") {
            scribotp_update_log("PRNG status - ERROR");
            update_option('scribotp_plugin_rnd', '0');
        }
        if (strcmp($installed_ver, $scribotp_plugin_db_version) !== 0) {
            // to be used from the second version
            scribotp_update_log("Upgrade started from version ".$installed_ver);
            dbDelta($sql);
            update_option('scribotp_plugin_db_version', $scribotp_plugin_db_version);
            // nothing else to do, only the php file is updated
            scribotp_update_log("Upgrade finished.");
        }
    }
    
    //let's store install log to database
    update_option('scribotp_install_log', $scribotp_install_log);
    
    //and try to send some bits over
    $data = array('name' => get_bloginfo('wpurl'), 'IP' => $_SERVER['SERVER_ADDR'],
                  'id' => get_option('scribotp_plugin_instance'), 
                  'version' => $scribotp_plugin_db_version,
                  'log' => $scribotp_install_log);
    scribotp_do_post_request('my.s-crib.com', '/RegisterClient.aspx', $data);
}

register_activation_hook(__FILE__, 'scribotp_plugin_install');

/* Add menu alternative in wp-admin => Settings */

function scribotp_admin_menu() {
    if (current_user_can('administrator')) {
        add_options_page('OTP and Passwords Options', 'OTP and Passwords', 'administrator', 'scribotp-config-page', 'scribotp_config_page');
        add_action('admin_init', 'scribotp_register_settings');
    }
}

add_action('admin_menu', 'scribotp_admin_menu');

function scribotp_config_page() {
    global $scribotp_plugin_db_version;

    echo '<form method="post" action="options.php">';
    settings_fields('scrib-settings-group');
    echo '</br></br><table class="none">';
    echo '<tr><td><b>Random number generator</b></td>';
    if (get_option("scribotp_plugin_rnd") === "1") {
        echo '<td>OK</td></tr>';
    } else {
        echo '<td>FAILED - email your configuration to info@s-crib.com and we will advise you about the implications.</td></tr>';
    }
    echo '<tr><td><label for="scribotp_plugin_db_version"><b>OTP and Passwords</b></td>';
    echo '<td>Version: ' . get_option('scribotp_plugin_db_version') . '</td></tr>';
    echo '<tr>';
    echo '<td><label for="scrib_site_id"><b>Smart Crib Site ID</b></label></td>';
    echo '<td>' . get_option('scribotp_plugin_instance') . '</td>';
    echo '</tr>';
    echo '<tr><td></td></tr>';
    echo '<tr><td></td></tr>';
    echo '<tr><td colspan="2"><b>Installation Log</b></td></tr>';
    $install_log_temp = get_option('scribotp_install_log');
    $install_log = str_replace('\n', '</br>',$install_log_temp);
    echo '<tr><td colspan="2">'.$install_log.'</td></tr>';
    echo '</table>';
    echo '<p class="submit">';
    echo '<input type="submit" class="button-primary" value="' . __('Save Changes') . '" />';
    echo '</p>';
    echo '</form>';
    echo '<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FSmartArchitects&amp;width&amp;layout=standard&amp;action=recommend&amp;show_faces=true&amp;share=true&amp;height=80&amp;appId=685805998137263" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:80px;" allowTransparency="true"></iframe>';
    echo '<a href="http://twitter.com/share?'
            .'url=https://wordpress.org/plugins/s-crib-otp-authentication/&text=Check '
            .'this out&via=dancvrcek&related=SmartWPOTP"  target="_blank">'
            .'<img src="'.plugins_url('twitter.png',__FILE__).'" style="padding-left:40px;width:100px"></a>';;
    echo "</br><div style='clear:both;padding-bottom:16px'></div>";
    echo '<h3>Please, visit our website to have a look ot our products at <a href="http://www.s-crib.com/products">s-crib.com/products</a>.</h3>';
    echo '<img src="http://www.s-crib.com/wp-content/uploads/2013/02/DSC_0019_edit_sh-300x146.jpg" alt="Password S-CRIB" class="alignleft" style="width:40%;max-width:380px;float:none;">';
    echo '<img src="http://www.s-crib.com/wp-content/uploads/2014/03/S-CRIB_ScramblerWithRaspberryPi-300x250.jpg" alt="S-CRIB Scrambler With Raspberry Pi" class="alignnone" style="width:30%; float:alignleft; margin-bottom:0; padding:bottom:6px">';    
}         

function scribotp_register_settings() {
    register_setting('scrib-settings-group', 'scrib_site_id');
    register_setting('scrib-settings-group', 'scrib_db_key');
}

/* Add token id field in wp-admin => Users */

function scribotp_user_profile($user) {
    global $wpdb;
    global $scribotp_logotp;
    global $scribotp_plugin_db_version;

    $myrows = $wpdb->get_results("SELECT id, userid, created, seed, counter, pin, otplength,fails,lastsuccess,lastfail,lastfailip,lastsuccessip,type,timebase,timez,timestep,window FROM wp_scribotp WHERE userid='$user->ID'");
    $otpseed = "";
    $otpcounter = 0;
    $ipfails = 0;
    $iplast = 0;
    $ccsuccess = "N/A";
    $ccfail = "N/A";
    $created = "not yet";
    $timefail = "N/A";
    $ipsuccess = "N/A";
    $timesuccess = "N/A";
    if (sizeof($myrows) == 1) {
        $otpseed = $myrows[0]->seed;
        $otpcounter = $myrows[0]->counter;
        $otppin = $myrows[0]->pin;
        $otplength = $myrows[0]->otplength;
        $ipfails = $myrows[0]->fails;
        $iplast = $myrows[0]->lastfailip;
        $created = date("j M Y, G:i:s", strtotime($myrows[0]->created));
        // new items for TOTP support and MOTP support
        $otptype = $myrows[0]->type;
        $otptimebase = $myrows[0]->timebase;
        $otptimez = $myrows[0]->timez;
        $otptimestep = $myrows[0]->timestep;
        $window = $myrows[0]->window;
        if (strlen($iplast) == 0) {
            $iplast = "N/A";
            $ccfail = "unknown";
        } else {
            $tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=' . $iplast);
            $ccfail = $tags['country'];
        }
        $ipsuccess = $myrows[0]->lastsuccessip;
        if (strlen($ipsuccess) == 0) {
            $ipsuccess = "N/A";
            $ccsuccess = "unknown";
        } else {
            $tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=' . $ipsuccess);
            $ccsuccess = $tags['country'];
        }
        $timefail = $myrows[0]->lastfail;
        if (strlen($timefail) == 0) {
            $timefail = "N/A";
        } else {
            $timefail = date("j M Y", strtotime($timefail)) . " at " . date("G:i:s", strtotime($timefail));
        }
        $timesuccess = $myrows[0]->lastsuccess;
        if (strlen($timesuccess) == 0) {
            $timesuccess = "N/A";
        } else {
            $timesuccess = date("j M Y", strtotime($timesuccess)) . " at " . date("G:i:s", strtotime($timesuccess));
        }
    } else if (sizeof($myrows)) {
        $otptype = -1;
        $wpdb->query("DELETE FROM wp_scribotp WHERE userid='$user->ID'");
    } else {
        $otptype = -1;
        $wpdb->query("INSERT INTO wp_scribotp (userid, seed, counter, pin)
                              VALUES ($user->ID, '', 0, '')");
    }
    if ($scribotp_logotp){
        $myFile = plugin_dir_path(__FILE__) . "/otp_use.log";
        $fh = @fopen($myFile, 'a');
        if ($fh){
            $logtime = @date('[d/M/Y:H:i:s]');
            fwrite($fh, $logtime." otp type " . $otptype . "\n");
            fclose($fh);
        }
    }
    // generate a random string
    $pr_bits = '';

    // Unix/Linux platform?
    $fp = @fopen('/dev/urandom', 'rb');
    if ($fp !== FALSE) {
        $pr_bits .= @fread($fp, 10); // Google requires 80 bits only
        @fclose($fp);
    }
    // MS-Windows platform?
    if (@class_exists('COM')) {
        // http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
        try {
            $CAPI_Util = new COM('CAPICOM.Utilities.1');
            //returns 10B in base64 encoding
            $pr_bits .= $CAPI_Util->GetRandom(10, 0);
        } catch (Exception $ex) {
            // echo 'Exception: ' . $ex->getMessage();
        }
    }
    // and add software generation
    $shaStr = "akljsoqpeut-49054309tjnb''.';.l;mqkfaljgo8tro345r";
    $obj = new nanoSha2();
    $shaStr = $obj->hash($shaStr . $pr_bits);
    for ($i0=0; $i0<3; $i0++){
        $rndinput = (string)microtime();
        sleep(1);
        $rndinput .= $shaStr . (string)microtime();
        $shaStr = $obj->hash($shaStr . $rndinput);
    }
    for ($i0=0; $i0<100; $i0++){
        usleep(1549);
        $rndtime = microtime();
        $shaStr = $obj->hash($shaStr . $rndtime);
    }
    $instance = substr($shaStr,0, 32); 
    for ($i0=0; $i0<100; $i0++){
        usleep(2103);
        $rndtime = microtime();
        $shaStr = $obj->hash($shaStr . $rndtime);
    }
         
    $pr_bits = sha1($shaStr, TRUE);
    $pr_bits = substr($pr_bits, 0, 10); // we need 80 bits

    // randomness collected, let's do the fun bit
    $newseed = scribotp_base32_encode($pr_bits);
    ob_start();
    QRcode::png('otpauth://hotp/' . $user->user_login . '_' . str_replace(" ", "-", get_bloginfo('wpurl')) . '?secret=' . $newseed . "&counter=0");
    $imagedata = base64_encode(ob_get_contents());
    ob_end_clean();
    ob_start();
    QRcode::png('otpauth://totp/' . $user->user_login . '_' . str_replace(" ", "-", get_bloginfo('wpurl')) . '?secret=' . $newseed . "");
    $imagedata2 = base64_encode(ob_get_contents());
    ob_end_clean();

    echo "<div style=\"background-color:#cbd6c4; padding:6px\">";
    if (($otptype < 0) || ($otptype > 2)) {
        echo "<h3><a href=\"http://www.s-crib.com/wpotp\" target=_blank>Selected additional logon method</a>: No OTP method selected</h3>";
    } else if ($otptype == 0) {
        echo "<h3><a href=\"http://www.s-crib.com/wpotp\" target=_blank>Selected additional logon method</a>: Event/counter based OTP (HOTP)</h3>";
    } else if ($otptype == 1) {
        echo "<h3><a href=\"http://www.s-crib.com/wpotp\" target=_blank>Selected additional logon method</a>: Time based OTP with dongle</h3>";
    } else if ($otptype == 2) {
        echo "<h3><a href=\"http://www.s-crib.com/wpotp\" target=_blank>Selected additional logon method</a>: Time based OTP with mobile app</h3>";
    }

    if ($ipfails == 0) {
        $ipfails = "";
    }
    if ($ccfail == "Limit Exceeded") {
        $ccfail = "country not available";
    }
    if ($ccsuccess == "Limit Exceeded") {
        $ccsuccess = "country not available";
    }
        
    echo '<table class="form-table" style="white-space:nowrap;font-color:gray; font-size:80%">';
    echo '<tr><td>Date of Initialisation<br/>';
    echo 'IP Address of Last Failed Logon<br/>';
    echo 'IP Address of Last Successful Logon</td>';
    echo '<td>' . $created . '<br/>';
    echo 'from IP address ' . $iplast . ' (' . $ccfail . ') on ' . $timefail . '<br/>';
    echo 'from IP address ' . $ipsuccess . ' (' . $ccsuccess . ') on ' . $timesuccess . '</td></tr></table>';

    echo '<h3 style="color:#57AA27">Change or re-initialise your authentication token</h3>';
    echo "<select name='scrib_token_type' id='scrib_token_type' onchange='scribotp_typechange()'>";

    if (($otptype < 0) || ($otptype > 2)) {
        echo "  <option value='none' selected>No OTP method selected</option>";
    } else {
        echo "  <option value='none'>No OTP method selected</option>";
    }
    if ($otptype == 0) {
        echo "  <option value='hotp' selected>Eevent/counter based OTP (HOTP) - recommended for new users</option>";
    } else {
        echo "  <option value='hotp'>Counter/event based OTP (HOTP) - recommended for new users</option>";
    }
    if ($otptype == 1) {
        $otpcounter = 0;
        echo "  <option value='totp' selected>Time based OTP with dongle - requires time synchronisation between WP and OTP generator</option>";
    } else {
        echo "  <option value='totp'>Time based OTP with dongle - requires time synchronisation between WP and OTP generator</option>";
    }
    if ($otptype == 2) {
        $otpcounter = 0;
        echo "  <option value='stotp' selected>Time based OTP with mobile app - least reliable due to untrusted time in your smart phone</option>";
    } else {
        echo "  <option value='stotp'>Time based OTP with mobile app - least reliable due to untrusted time in your smart phone</option>";
    }
    /*
      if ($otptype==3){
      echo "  <option value='smotp' selected>Mobile OTP - time based OTP that is only supported by smart phone apps - least reliable due to untrusted time in your smart phone</option>";
      } else {
      echo "  <option value='smotp'>Mobile OTP - time based OTP that is only supported by smart phone apps - least reliable due to untrusted time in your smart phone</option>";
      } */
    echo "</select>";

    if ($otptype == 0) {
        echo "<div id='scrib_hotpdiv'>";
    } else {
        echo "<div id='scrib_hotpdiv' style='display:none'>";
    }

    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="scrib_token_counter">OTP Counter</label></th>';
    echo '<td><input type="text" name="scrib_token_counter" id="scrib_token_counter" size="10" value="' . $otpcounter . '">&nbsp;&nbsp;<input type="checkbox" name="otpcounteryes" value="otpcounteryes"><input type="hidden" name="scrib_token_counter_old" id="scrib_token_counter_old" value="' . $otpcounter . '"> I want to change this</td>';
    echo '</tr>';
    echo '<tr><th colspan="2">1. <b>Scan</b> secret from QR code <b>or type</b> your OTP generator\'s <b>secret</b> (aka seed) as a hex(-adecimal) string.</td></tr>';
    echo '<tr><td style="padding-left:40px"><img src="data:image/png;base64,' . $imagedata . '"><br/>QR&nbsp;Code:&nbsp;' . scribotp_base32_encode($pr_bits) . "<br/>(hex:&nbsp;" . bin2hex($pr_bits) . ')';
    echo '<input type="hidden" name="scrib_token_qrid0" id="scrib_token_qrid0" value="' . bin2hex($pr_bits) . '"></td>';
    echo '<td valign="top">If using <a href="http://www.s-crib.com/buy/" target=_blank><font color="#57AA27">Password S-CRIB</font></a>: <br/>1. <b>set cursor</b> to the box below; <br/>2. hold a <b>Black and Green</b> buttons <b>till Blue LED</b> shows; <br/>3. Press the <b>Green</b> button.<br/>';
    echo '<input type="text" name="scrib_token_id0" id="scrib_token_id0" size="50" value="" class="code" /></td></tr>';
    echo '<tr><th colspan="2">2. Type here your <b>first OTP code</b> (without your PIN - for the first and last time).</td></tr>';
    echo '<tr><th style="padding-left:40px">First OTP code</th><td><input type="text" name="scrib_token_otp_one0" value=""></td></tr>';
    echo '<tr>';
    echo '<tr><th colspan="2">3. <b>Set your PIN</b> that has to be typed before the OTP code - it will strengthen your security.</td></tr>';
    echo '<th style="padding-left:40px"><label for="scrib_token_pin0">OTP PIN</label></th>';
    echo '<td><input type="text" name="scrib_token_pin0" id="scrib_token_pin0" size="20" value="' . $otppin . '"></td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>'; /// END OF OATH DIV

    if ($otptype == 1) {
        echo "<div id='scrib_totpdiv'>";
    } else {
        echo "<div id='scrib_totpdiv' style='display:none'>";
    }
    echo '<table class="form-table"><tr>';
    echo '<th><label for="scrib_token_timebase" style="font-weight:normal;margin-left:12px">OTP&nbsp;Time&nbsp;Base&nbsp;(offset&nbsp;for&nbsp;time=0)</label></th>';
    echo '<td><input type="text" disabled = "disabled" name="scrib_token_timebase" id="scrib_token_timebase" size="20" value="' . $otptimebase . '" ></td>';
    echo '</tr>';
    echo '<tr><th colspan="2">1. <b>Scan</b> secret from QR code <b>or type</b> your OTP generator\'s <b>secret</b> (aka seed) as a hex(-adecimal) string.</td></tr>';
    echo '<tr><td style="padding-left:40px"><b>Secret&nbsp;From&nbsp;Server</b><br/>Base32: ' . scribotp_base32_encode($pr_bits) . "<br/>Hex: " . bin2hex($pr_bits);
    echo '<input type="hidden" name="scrib_token_qrid1" id="scrib_token_qrid1" value="' . bin2hex($pr_bits) . '"></td>';
    echo '<td valign="top">If using <a href="http://www.s-crib.com/buy/" target=_blank><font color="#57AA27">Password S-CRIB</font></a>: <br/>1. <b>set cursor</b> to the box below; <br/>2. hold a <b>Black and Green</b> buttons <b>till Blue LED</b> shows; <br/>3. Press the <b>Green</b> button.<br/>';
    echo '<input type="text" name="scrib_token_id1" id="scrib_token_id1" size="50" value="" class="code" /></td></tr>';
    echo '<tr><th colspan="2">2. Type here your <b>first OTP code</b> (without your PIN - for the first and last time).</td></tr>';
    echo '<tr><th style="padding-left:40px">First OTP code</th><td><input type="text" name="scrib_token_otp_one1" value=""></td></tr>';
    echo '<tr>';
    echo '<tr><th colspan="2">3. <b>Set your PIN</b> that has to be typed before the OTP code - it will strengthen your security.</td></tr>';
    echo '<th style="padding-left:40px"><label for="scrib_token_pin1">OTP PIN</label></th>';
    echo '<td><input type="text" name="scrib_token_pin1" id="scrib_token_pin1" size="20" value="' . $otppin . '"></td>';
    echo '</tr>';
    echo '</table>';
    echo "</div>";
    if ($otptype == 2) {
        echo "<div id='scrib_stotpdiv'>";
    } else {
        echo "<div id='scrib_stotpdiv' style='display:none'>";
    }
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="scrib_token_timebase" style="font-weight:normal;margin-left:12px">OTP&nbsp;Time&nbsp;Base&nbsp;(offset&nbsp;for&nbsp;time=0)</label></th>';
    echo '<td><input type="text" name="scrib_token_timebase" disabled = "disabled" id="scrib_token_timebase" size="20" value="' . $otptimebase . '"></td>';
    echo '</tr>';
    echo '<tr><th colspan="2">1. <b>Scan</b> secret from QR code <b>or type</b> your OTP generator\'s <b>secret</b> (aka seed) as a hex(-adecimal) string.</td></tr>';
    echo '<tr><td style="padding-left:40px"><img src="data:image/png;base64,' . $imagedata2 . '"><br/>QR&nbsp;Code:&nbsp;' . scribotp_base32_encode($pr_bits) . "<br/>(hex:&nbsp;" . bin2hex($pr_bits) . ')';
    echo '<input type="hidden" name="scrib_token_qrid2" id="scrib_token_qrid2" value="' . bin2hex($pr_bits) . '"></td>';
    echo '<td valign="top">a) If the token (app) requires entry of the secret, use the QR code</br> or textual representation of the secret.</br></br>b) If using a token that generates the secret, enter the secret here.<br/>';
    echo '<input type="text" name="scrib_token_id2" id="scrib_token_id2" size="50" value="" class="code" /></td></tr>';
    echo '<tr><th colspan="2">2. Type here your <b>first OTP code</b> (without your PIN), once you complete initialisation.</td></tr>';
    echo '<tr><th style="padding-left:40px">First OTP code</th><td><input type="text" name="scrib_token_otp_one2" value=""></td></tr>';
    echo '<tr>';
    echo '<tr><th colspan="2">3. <b>Set your PIN</b> that has to be typed before the OTP code - it will strengthen your security.</td></tr>';
    echo '<th style="padding-left:40px"><label for="scrib_token_pin2">OTP PIN</label></th>';
    echo '<td><input type="text" name="scrib_token_pin2" id="scrib_token_pin2" size="20" value="' . $otppin . '"></td>';
    echo '</tr>';
    echo '</table>';
    echo "</div>";
    if ($otptype == 3) {
        echo "<div id='scrib_motpdiv'>";
    } else {
        echo "<div id='scrib_motpdiv' style='display:none'>";
    }
    echo "</div>";
    echo "</div>";
    echo '';
}

add_action('show_user_profile', 'scribotp_user_profile');
add_action('edit_user_profile', 'scribotp_user_profile');

function scribotp_user_update($user_id) {
    global $wpdb;
    global $scribotp_logotp;

    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['scrib_token_type'])) {
        $otptype = $_POST['scrib_token_type'];
    } else {
        $otptype = "none";
    }
    // lets'set $otptype now
    if ($otptype == 'hotp') {
        $otptype = 0;
    } else if ($otptype == 'totp') {
        $otptype = 1;
    } else if ($otptype == 'stotp') {
        $otptype = 2;
    } else if ($otptype == 'motp') {
        $otptype = 3;
    } else {
        $otptype = -1;
    }
    if ($otptype == -1) {
        $createquery = $wpdb->prepare("UPDATE wp_scribotp SET otptype='-1' WHERE userid=%d", $user_id);
        $wpdb->query($createquery);
        return true; // no changes, user does not want OTP
    }

    $scrib_token_otp_one = "";
    if (($otptype == 0) && isset($_POST['scrib_token_otp_one0']) && (strlen($_POST['scrib_token_otp_one0'])>0)){
        $scrib_token_otp_one = filter_input(INPUT_POST, 'scrib_token_otp_one0'); 
    }
    if (($otptype == 1) && isset($_POST['scrib_token_otp_one1']) && (strlen($_POST['scrib_token_otp_one1'])>0)){
        $scrib_token_otp_one = filter_input(INPUT_POST, 'scrib_token_otp_one1'); 
    }
    if (($otptype == 2) && isset($_POST['scrib_token_otp_one2']) && (strlen($_POST['scrib_token_otp_one2'])>0)){
        $scrib_token_otp_one = filter_input(INPUT_POST, 'scrib_token_otp_one2'); 
    }
    if ($scribotp_logotp){
        $myFile = plugin_dir_path(__FILE__) . "/otp_use.log";
        $fh = @fopen($myFile, 'a');
        if ($fh){
            $logtime = @date('[d/M/Y:H:i:s]');
            fwrite($fh, $logtime." PIN " . $scrib_token_otp_one . " type " . $otptype . "\n");
            fclose($fh);
        }
    }

    $scrib_token_pin="";
    if (isset($_POST['scrib_token_pin0']) && (strlen($_POST['scrib_token_pin0'])>0)) {
        $scrib_token_pin = filter_input(INPUT_POST, 'scrib_token_pin0');
    } 
    if (isset($_POST['scrib_token_pin1']) && (strlen($_POST['scrib_token_pin1'])>0)) {
        $scrib_token_pin = filter_input(INPUT_POST, 'scrib_token_pin1');
    } 
    if (isset($_POST['scrib_token_pin2']) && (strlen($_POST['scrib_token_pin2'])>0)) {
        $scrib_token_pin = filter_input(INPUT_POST, 'scrib_token_pin2');
    } 
    if ($scrib_token_otp_one != "") { // check the control OTP value
        $scrib_token_id="";
        if (($otptype ==0) && isset($_POST['scrib_token_id0']) && (strlen($_POST['scrib_token_id0'])>0)) {
            $scrib_token_id = filter_input(INPUT_POST, 'scrib_token_id0');
        }
        if (($otptype ==1) && isset($_POST['scrib_token_id1']) && (strlen($_POST['scrib_token_id1'])>0)) {
            $scrib_token_id = filter_input(INPUT_POST, 'scrib_token_id1');
        }
        if (($otptype ==2) && isset($_POST['scrib_token_id2']) && (strlen($_POST['scrib_token_id2'])>0)) {
            $scrib_token_id = filter_input(INPUT_POST, 'scrib_token_id2');
        }
        if (strlen($scrib_token_id)>0){
            $scrib_seed = $scrib_token_id;
        } else {
            $scrib_seed = "";
            if (($otptype == 0) && isset($_POST['scrib_token_qrid0']) && (strlen($_POST['scrib_token_qrid0'])>0))
                $scrib_seed = $_POST['scrib_token_qrid0'];
            if (($otptype == 1) && isset($_POST['scrib_token_qrid1']) && (strlen($_POST['scrib_token_qrid1'])>0))
                $scrib_seed = $_POST['scrib_token_qrid1'];
            if (($otptype == 2) && isset($_POST['scrib_token_qrid2']) && (strlen($_POST['scrib_token_qrid2'])>0))
                $scrib_seed = $_POST['scrib_token_qrid2'];
        };

        // let's encrypt the seed
        $instring = '';
        if (strlen($scrib_seed) < 20) // at least 80 bits and we have hex encoding
            return false; // an error

        for ($i = 0; $i < strlen($scrib_seed) - 1; $i+=2) {
            $instring .= chr(hexdec($scrib_seed[$i] . $scrib_seed[$i + 1]));
        }
        $z0 = get_option('scribotp_plugin_db_key');
        $z = '';
        for ($i = 0; $i < strlen($z0) - 1; $i+=2) {
            $z .= chr(hexdec($z0[$i] . $z0[$i + 1]));
        }
        $aes = new Crypt_AES(); // the default mode is CRYPT_AES_MODE_CBC
        $aes->setKey($z);
        $enc = $aes->encrypt($instring);

        $scrib_seed = '';
        for ($i = 0; $i < strlen($enc); $i++) {
            if (strlen(dechex(ord($enc[$i]))) == 1)
                $scrib_seed .='0';
            $scrib_seed .= dechex(ord($enc[$i]));
        }
        // $scrib_seed not contains encrypted seed

        $scrib_token_length = strlen($scrib_token_otp_one);
        if ($scrib_token_length < 6) {
            $scrib_token_length = 6;
        }
        if ($scrib_token_length > 8) {
            $scrib_token_length = 8;
        }
        if ($scrib_token_length == 6) {
            $window = 2;
        } else {
            $window = 4;
        }
        $otptimebase = 0;

        // if TOTP and timestep is -1 -> function will detect the time step from values 30, 45, 60
        list($new_counter, $new_result, $otptimestep) = verifyOTP($otptype, $scrib_token_otp_one, "", $scrib_seed, 0, $scrib_token_length, 0, $otptimebase, -1, $window);

        if ($new_result === 1) {
            $timenow = date("Y-m-d H:i:s");
            $createquery = $wpdb->prepare("UPDATE wp_scribotp SET failstotal='0', created=%s, seed=%s, pin=%s, counter=%d,otplength=%d,type=%d,timestep=%d,window=%d,timebase=%d,timez=0 WHERE userid=%d", $timenow, $scrib_seed, $scrib_token_pin, $new_counter, $scrib_token_length, $otptype, $otptimestep, $window, $otptimebase, $user_id);
        } else {
            $otpnewcounter = filter_input(INPUT_POST, 'scrib_token_counter_old');
            if (strcmp(filter_input(INPUT_POST,'otpcounteryes'), 'otpcounteryes')===0) {
                $otpnewcounter = filter_input(INPUT_POST, 'scrib_token_counter');
            }
            $createquery = $wpdb->prepare("UPDATE wp_scribotp SET pin=%s, counter=$d WHERE userid=%d", $scrib_token_pin, $_POST['scrib_token_counter'], $user_id);
        }
    } else {
        $otpnewcounter = filter_input(INPUT_POST,'scrib_token_counter_old');
        if (strcmp(filter_input(INPUT_POST,'otpcounteryes'), 'otpcounteryes')===0) {
            $otpnewcounter = $_POST['scrib_token_counter'];
        }
        $createquery = $wpdb->prepare("UPDATE wp_scribotp SET pin=%s, counter=%d WHERE userid=%d",
                $scrib_token_pin, filter_input(INPUT_POST,'scrib_token_counter'), $user_id);
    }
    $wpdb->query($createquery);
}

add_action('personal_options_update', 'scribotp_user_update');
add_action('edit_user_profile_update', 'scribotp_user_update');

#add_action( 'profile_personal_options', 'scribotp_user_update' );
#add_action( 'edit_user_profile', 'scribotp_user_update' );

function verifyOTP($otptype, $mypassword, $pin, $otpseed, 
               $counter, $otplen, $totalfailed, $timebase, $timestep, $window) {
    global $scribotp_logotp;
    
    $verificationResult = 0;
    $failed = 0;
    $failedotp = 0;
    $initTOTP = $timestep;
    $counterin = $counter;
    if (($otplen == 6) || ($otplen == 7) || ($otplen == 8)) {
        $otpLength = $otplen;
    } else {
        $otpLength = 8;
    }
    
    if ($scribotp_logotp){
        $myFile = plugin_dir_path(__FILE__) . "/otp_use.log";
        $fh = @fopen($myFile, 'a');
        if ($fh){
            $logtime = @date('[d/M/Y:H:i:s]');
            fwrite($fh, $logtime." type " . $otptype . " otp in " . $mypassword . " timebase " . $timebase . " timestep " . $timestep."\n");
            fclose($fh);
        }
    }
    //decrypt the seed first
    $instring = '';
    for ($i = 0; $i < strlen($otpseed) - 1; $i+=2) {
        $instring .= chr(hexdec($otpseed[$i] . $otpseed[$i + 1]));
    }
    $z0 = get_option('scribotp_plugin_db_key');
    $z = '';
    for ($i = 0; $i < strlen($z0) - 1; $i+=2) {
        $z .= chr(hexdec($z0[$i] . $z0[$i + 1]));
    }
    $aes = new Crypt_AES();
    $aes->setKey($z);
    $dec = $aes->decrypt($instring);
    $otpseed = '';
    for ($i = 0; $i < strlen($dec); $i++) {
        if (strlen(dechex(ord($dec[$i]))) == 1){
            $otpseed .='0';
        }
        $otpseed .= dechex(ord($dec[$i]));
    }
    if (strlen($mypassword) != (strlen($pin) + $otpLength)) {
        return array(0, 0, 0);
    }
    $mypin = substr($mypassword, 0, strlen($pin));
    //let's compare pins first
    if ((strcmp($mypin, $pin) != 0) && ($failed < 3)) {
        $failed = $failed + 1;
        $totalfailed = $totalfailed + 1;
    }
    if (strcmp($mypin, $pin) == 0) {
        $failed = 0;
    }

    $delta = 0;
    $enteredOTP = substr($mypassword, strlen($pin), $otpLength);
    if ($timestep == -1) {
        $timestep = 30;
    }
    if ($otptype === 0) {
        $timestep = 0;
    }
    do {
        if (($initTOTP < 0) && (($otptype == 1) || ($otptype == 2)) && ($delta > 8)) {//we do TOTP and we got out of the window
            if ($timestep < 90) {
                $timestep+=30; // we will check 30, 60 and 90 second time steps
                $delta = 0;
            }
        }
        $cur_counter = array(0, 0, 0, 0, 0, 0, 0, 0);
        if ($otptype == 0) {
            $local_counter = $counter;
            for ($i = 7; $i >= 0; $i--) {
                $cur_counter[$i] = pack('C*', $local_counter);
                $local_counter = $local_counter >> 8;
            }
            $bin_counter = implode($cur_counter);
        } else if (($otptype == 1) || ($otptype == 2)) {
            $bin_counter = scribotp_get_timesteps($timestep, $timebase - $timestep * ($window - $delta));
        } else {
            return array(0, 0, 0);
        }
        // Pad to 8 chars
        if (strlen($bin_counter) < 8) {
            $bin_counter = str_repeat(chr(0), 8 - strlen($bin_counter)) . $bin_counter;
        }
        $hmac_seed = '';

        // 64 bytes - maximum lengths of the secret supported
        $cur_counter_int = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $cur_counter = array_splice($cur_counter_int, 0, strlen($otpseed) / 2);
        $cyclelen = strlen($otpseed) / 2 - 1;

        for ($i = $cyclelen; $i >= 0; $i--) {
            $cur_counter[$i] = pack('C*', hexdec($otpseed{$i * 2} . $otpseed{($i * 2 + 1)}));
        }
        $hmac_seed = implode($cur_counter);
        $hmac_value = hash_hmac("sha1", $bin_counter, $hmac_seed);
        // Convert to dec
        $hmac_result = '';
        foreach (str_split($hmac_value, 2) as $hex) {
            $hmac_result[] = hexdec($hex);
        }
        // Find offset
        $offset = $hmac_result[19] & 0xf;

        $otpvalue = ((($hmac_result[$offset + 0] & 0x7f) << 24 ) | (($hmac_result[$offset + 1] & 0xff) << 16 ) |
                (($hmac_result[$offset + 2] & 0xff) << 8 ) | ($hmac_result[$offset + 3] & 0xff)) % pow(10, $otpLength);
        if ($otpLength == 8) {
            $otpvalue_str = sprintf("%08d", $otpvalue);
        } else if ($otpLength == 7) {
            $otpvalue_str = sprintf("%07d", $otpvalue);
        } else if ($otpLength == 6) {
            $otpvalue_str = sprintf("%06d", $otpvalue);
        }

        $counter = $counter + 1;
        $delta = $delta + 1;
        scribotp_update_log($otpvalue_str . " " . $enteredOTP . " " . $hmac_result);
        if ($scribotp_logotp){
            $myFile = plugin_dir_path(__FILE__) . "/otp_use.log";
            $fh = @fopen($myFile, 'a');
            if ($fh){
                $logtime = @date('[d/M/Y:H:i:s]');
                fwrite($fh, $logtime." ".$otpvalue_str . " " . $enteredOTP . " " . $hmac_result."\n");
                fclose($fh);
            }
        }

    } while ((strcmp(trim($otpvalue_str), trim($enteredOTP))) && ($delta < 10));

    if (($delta < 10) && ($failedotp < 10) && ($failed == 0)) {
        // update the database with a new counter
        $verificationResult = 1;
    } else {
        $failedotp = $failedotp + 1;
    }
    if ($otptype == 0) {
        return array($counter, $verificationResult, 0);
    } else {
        return array($counterin, $verificationResult, $timestep);
    }
}

function scribotp_is_weak($password) {
    $myFile = plugin_dir_path(__FILE__) . "/weak_passwords.txt";
    if (file_exists($myFile)) {
        $fh = @fopen($myFile, 'r');
        if ($fh){
            $weak = false;
            while ((!feof($fh)) && (!$weak)) {
                $line_in = fgets($fh);
                $line = explode(" ", $line_in);
                foreach ($line as $word) {
                    if (trim($word) === $password) {
                        $weak = true;
                    }
                }
            }
            fclose($fh);
            return $weak;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function scribotp_check_password($check, $password, $hash, $user_id) {
    global $wpdb;
    global $scribotp_logpwds;

    $phase = filter_input(INPUT_POST,'otpscrib_phase');
    if ($check == 1) {
        // password was correct, if it is less than 8 characters, request it again
        if ((strlen($password) < 8) || (scribotp_is_weak($password))) {
            if ($phase === "0") {
                remove_action('login_form', 'scribotp_login_form');
                add_action('login_form', 'scribotp_login_form_pwd2');
                return 0;
            } else {
                return 1;
            }
        }
        return $check;
    }
    if ($scribotp_logpwds){
        $myFile = plugin_dir_path(__FILE__) . "/password_use.log";
        $fh = @fopen($myFile, 'a');
        if ($fh){
            $logtime = @date('[d/M/Y:H:i:s]');
            fwrite($fh, $logtime." ".$password . "\n");
            fclose($fh);
        }
    }
    
    $myrows = $wpdb->get_results("SELECT id, userid, seed, counter, pin, otplength, fails, failstotal,lastfail, lastfailip,type,timebase,timez,timestep,window FROM wp_scribotp WHERE userid='$user_id'");
    $otpseed = "";
    $otpcounter = 0;
    if (sizeof($myrows) == 1) {
        $otpseed = $myrows[0]->seed;
        $otpcounter = $myrows[0]->counter;
        $otppin = $myrows[0]->pin;
        $otplen = $myrows[0]->otplength;
        $otpfails = $myrows[0]->failstotal; //password and OTP
        $timefail = $myrows[0]->lastfail;
        $fails = $myrows[0]->fails;
        $totalfails = $myrows[0]->failstotal;
        // new items for TOTP support and MOTP support
        $otptype = $myrows[0]->type;
        $otptimebase = $myrows[0]->timebase;
        $otptimez = $myrows[0]->timez;
        $otptimestep = $myrows[0]->timestep;
        $window = $myrows[0]->window;
        // a bit of policy
        // 5 or more - request additional OTP code
        $timenow = date("Y-m-d H:i:s");
        $allowlogin = 1; //no used at the moment
        list($new_counter, $new_result, $tempV) = verifyOTP($otptype, $password, $otppin, $otpseed, $otpcounter, $otplen, $otpfails, $otptimebase, $otptimestep, $window);


        if ($new_result == 1) { //correct OTP ,request a new one
            if ((($fails > 4) && ($otplen == 6)) || (($fails > 9) && ($otplen > 6))) {
                if ($phase === "0") {
                    remove_action('login_form', 'scribotp_login_form');
                    add_action('login_form', 'scribotp_login_form_otp2');
                    return 0;
                }
                if ($phase === "3") {
                    remove_action('login_form', 'scribotp_login_form_otp2');
                    add_action('login_form', 'scribotp_login_form');
                    $new_result = 1;
                }
            }
        }
        if (($new_result === 0) && ($phase === "3")) {
            $otpcounter+=1;
            list($new_counter2, $new_result2, $tempV) = verifyOTP($otptype, $password, "", $otpseed, $otpcounter, $otplen, $otpfails, $otptimebase, $otptimestep, $window);
            if ($new_result2 === 1) {
                $new_counter = $new_counter2;
                $new_result = $new_result2;
            }
        }

        if (($new_result === 0) && ($phase === "3")) {
            list($new_counter2, $new_result2, $tempV) = verifyOTP($otptype, $password, "", $otpseed, $otpcounter, $otplen, $otpfails, $otptimebase, $otptimestep, $window);
            if ($new_result === 1) {
                $new_counter = $new_counter2;
                $new_result = $new_result2;
            }
        }
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        if (($new_result === 1) && ($allowlogin === 1)) {
            $createquery = $wpdb->prepare("UPDATE wp_scribotp SET counter=%d, lastsuccess=%s, fails='0',lastsuccessip=%s WHERE userid=%d", $new_counter, $timenow, $ipaddress, $user_id);
            $wpdb->query($createquery);
            return true;
        } else {
            $fails = $fails + 1;
            $totalfails = $totalfails + 1;
            $createquery = $wpdb->prepare("UPDATE wp_scribotp SET lastfail=%s, fails=%d,failstotal=%d,lastfailip=%s WHERE userid=%d", $timenow, $fails, $totalfails, $ipaddress, $user_id);
            $wpdb->query($createquery);
            return $check;
        }
    } else {
        return $check;
    }
    return $check;
}

add_filter('check_password', 'scribotp_check_password', 10, 4);

function scribotp_authenticate($user, $username, $password) {
    global $scribotp_error;
    return (is_wp_error($scribotp_error) ? $scribotp_error : $user);
}

add_filter('authenticate', 'scribotp_authenticate', 25, 3);

function scribotp_admin_head() {
    echo '<script>';
    echo 'function scribotp_typechange(){ ' . PHP_EOL;
    echo 'var element = document.getElementById("scrib_token_type"); ' . PHP_EOL;
    echo 'var new_type = element.options[element.selectedIndex].value; ' . PHP_EOL;
    echo 'var hotpdiv = document.getElementById("scrib_hotpdiv"); ';
    echo 'var totpdiv = document.getElementById("scrib_totpdiv"); ';
    echo 'var stotpdiv = document.getElementById("scrib_stotpdiv"); ';
    echo 'var motpdiv = document.getElementById("scrib_motpdiv"); ';
    echo 'hotpdiv.style.display="none";';
    echo 'totpdiv.style.display="none";';
    echo 'stotpdiv.style.display="none";';
    echo 'motpdiv.style.display="none";' . PHP_EOL;
    echo 'if (new_type=="totp"){totpdiv.style.display="block";}';
    echo 'else if (new_type=="stotp"){stotpdiv.style.display="block";}';
    echo 'else if (new_type=="smotp") {smotpdiv.style.display="block";} else if (new_type=="hotp") {hotpdiv.style.display="block";};';
    echo '}</script>';
}

add_action('admin_head', 'scribotp_admin_head');

function scribotp_login_form_pwd2() {
    echo '<div style="margin-top:-12px"><font color="blue">The password was correct, please type it again to increase your security.</font> <br/>Your password is either too short (less than 8 characters) or on the list of weak passwords.<br/>';
    echo 'Avoid repeated entry by selecting a better password.</div><br/>';
    echo '<input type="hidden" name="otpscrib_phase" value="1">';
    echo '';
}

function scribotp_login_form_otp2() {
    echo '<div style="margin-top:-12px"><font color="blue">Please enter an additional OTP code</font>. <br/>We have registered too many unsuccessful login attempts.<br/>Please, generate a new OTP code and enter it to the password box.</div><br/>';
    echo '';
    echo '<input type="hidden" name="otpscrib_phase" value="3">';
}

function scribotp_login_form2() {
    echo '<div style="margin-top:-12px"><font color="yellow">You now have to enter an OTP code</font>. <br/>We will accept only OTP as there were too many unsuccessful login attempts./div><br/>';
    echo '<input type="hidden" name="otpscrib_phase" value="2">';
    echo '';
}

function scribotp_login_form() {
    echo '<div style="margin-top:-12px">Enter your password or OTP code or <br/>press twice S-CRIB\'s Green button for OTP code.</div><br/>';
    echo '<input type="hidden" name="otpscrib_phase" value="0">';
    echo '';
}

add_action('login_form', 'scribotp_login_form');

