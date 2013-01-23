<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.52-dev
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

// Zikula settings and permissions ==>
session_start();
$UserIsAdmin = false;
$UserCanUpload = false;
$UserUploadDir = '/userdata/upload';
$thumbs_dir = '.thumbs';
$kcfinder_theme = 'oxygen';
$kcfinder_jpegQuality = 80;
$kcfinder_maxImageWidth = 1200;
$kcfinder_maxImageHeight = 1200;
$kcfinder_thumbWidth = 100;
$kcfinder_thumbHeight = 100;
if (isset($_GET['s'])) {
    // attempt to locate Zikula config.php
    $configisfound = true;
    $configfile = __DIR__.'/../../config/config.php';
    if (file_exists($configfile)) {
        require_once $configfile;
    } else {
        $configfile = __DIR__.'/../../../config/config.php';
        if (file_exists($configfile)) {
            require_once $configfile;
        } else {
            $configfile = __DIR__.'/../../../../config/config.php';
            if (file_exists($configfile)) {
                require_once $configfile;
            } else {
                $configisfound = false;
            }
        }
    }
    if ($configisfound) {
        // get session info
        $sDBase = $ZConfig['DBInfo']['databases']['default']['dbname'];
        $sUser  = $ZConfig['DBInfo']['databases']['default']['user'];;
        $sPassW = $ZConfig['DBInfo']['databases']['default']['password'];;
        $sHost  = $ZConfig['DBInfo']['databases']['default']['host'];
        $link = mysql_connect($sHost, $sUser, $sPassW) or cg_die("Could not connect");
        mysql_select_db($sDBase) or cg_die("Could not select database");

        // get Kcfinder system plugin config variables, if the plugin is installed
        $sql = 'SELECT * FROM `module_vars` WHERE `modname`="systemplugin.kcfinder"';
        $rSet = mysql_query($sql, $link) or cg_die("Bad query: ".$sql);
        $vars = array();
        while ($var = mysql_fetch_array($rSet)){
            $vars[$var['name']] = unserialize($var['value']);
        }
        
        // setting upload directory and other settings
        if (isset($vars['upload_dir'])) {
            $UserUploadDir = '/'.$vars['upload_dir'];
        }
        if (isset($vars['thumbs_dir'])) {
            $thumbs_dir = $vars['thumbs_dir'];
        }
        if (isset($vars['kcfinder_theme'])) {
            $kcfinder_theme = $vars['kcfinder_theme'];
        }
        if (isset($vars['kcfinder_jpegQuality'])) {
            $kcfinder_jpegQuality = $vars['kcfinder_jpegQuality'];
        }
        if (isset($vars['kcfinder_maxImageWidth'])) {
            $kcfinder_maxImageWidth = $vars['kcfinder_maxImageWidth'];
        }
        if (isset($vars['kcfinder_maxImageHeight'])) {
            $kcfinder_maxImageHeight = $vars['kcfinder_maxImageHeight'];
        }
        if (isset($vars['kcfinder_thumbWidth'])) {
            $kcfinder_thumbWidth = $vars['kcfinder_thumbWidth'];
        }
        if (isset($vars['kcfinder_thumbHeight'])) {
            $kcfinder_thumbHeight = $vars['kcfinder_thumbHeight'];
        }

        // get user session from Zikula session table, determine user Id
        $sql = 'SELECT * FROM `session_info` WHERE `sessid`="'.$_GET['s'].'"';
        $rSet = mysql_query($sql, $link) or cg_die("Bad query: ".$sql);
        $r = mysql_fetch_object($rSet);
        $userid = 0;
        if ($r) {
            $userid = $r->uid;
        }
        // get user groups
        $arrusergroupids = array();
        if ($userid > 0) {
            $sql = 'SELECT * FROM `group_membership` WHERE `uid`='.$userid;
            $rSet = mysql_query($sql, $link) or cg_die("Bad query: ".$sql);
            while ($r = mysql_fetch_object($rSet)){
                $arrusergroupids[] = $r->gid;
            }
        }

        // list ot group and user list of IDs for permissions
        $useradminslist = '';
        if (isset($vars['listusers_admin'])) {
            $useradminslist = $vars['listusers_admin'];
        }
        $groupadminslist = '2'; // 2 is default Zikula admin group Id
        if (isset($vars['listgroups_admin'])) {
            $groupadminslist = $vars['listgroups_admin'];
        }
        $usercanuploadlist = '';
        if (isset($vars['listusers_upload'])) {
            $usercanuploadlist = $vars['listusers_upload'];
        }
        $groupcanuploadlist = '1'; // 1 is default Zikula group Id for registered users
        if (isset($vars['listgroups_upload'])) {
            $groupcanuploadlist = $vars['listgroups_upload'];
        }

        // check lists
        
        // check user id if are in list with user ids for admins
        $arrayids = explode(",", $useradminslist);
        if (in_array($userid, $arrayids)) {
            $UserIsAdmin = true;
        }

        // chech user groups ids if are in list with group ids for admins
        $arrayids = explode(",", $groupadminslist);
        foreach ($arrusergroupids as $usergroupid) {
            if (in_array($usergroupid, $arrayids)) {
                $UserIsAdmin = true;
            }
        }

        // check user id if are in list with user ids for can upload
        $arrayids = explode(",", $usercanuploadlist);
        if (in_array($userid, $arrayids)) {
            $UserCanUpload = true;
        }

        // chech user groups ids if are in list with group ids for can upload
        $arrayids = explode(",", $groupcanuploadlist);
        foreach ($arrusergroupids as $usergroupid) {
            if (in_array($usergroupid, $arrayids)) {
                $UserCanUpload = true;
            }
        }
    }
} else {
	$UserIsAdmin = $_SESSION['UserIsAdmin'];
	$UserCanUpload = $_SESSION['UserCanUpload'];
	$UserUploadDir = $_SESSION['UserUploadDir'];
	$thumbs_dir = $_SESSION['thumbs_dir'];
	$kcfinder_theme = $_SESSION['kcfinder_theme'];
	$kcfinder_jpegQuality = $_SESSION['kcfinder_jpegQuality'];
	$kcfinder_maxImageWidth = $_SESSION['kcfinder_maxImageWidth'];
	$kcfinder_maxImageHeight = $_SESSION['kcfinder_maxImageHeight'];
	$kcfinder_thumbWidth = $_SESSION['kcfinder_thumbWidth'];
	$kcfinder_thumbHeight = $_SESSION['kcfinder_thumbHeight'];
}
$_SESSION['UserIsAdmin'] = $UserIsAdmin;
$_SESSION['UserCanUpload'] = $UserCanUpload;
$_SESSION['UserUploadDir'] = $UserUploadDir;
$_SESSION['thumbs_dir'] = $thumbs_dir;
$_SESSION['kcfinder_theme'] = $kcfinder_theme;
$_SESSION['kcfinder_jpegQuality'] = $kcfinder_jpegQuality;
$_SESSION['kcfinder_maxImageWidth'] = $kcfinder_maxImageWidth;
$_SESSION['kcfinder_maxImageHeight'] = $kcfinder_maxImageHeight;
$_SESSION['kcfinder_thumbWidth'] = $kcfinder_thumbWidth;
$_SESSION['kcfinder_thumbHeight'] = $kcfinder_thumbHeight;

// <== Zikula settings and permissions

$_CONFIG = array(


// GENERAL SETTINGS

    'disabled' => false,
    'theme' => $kcfinder_theme,
    'uploadURL' => $UserUploadDir,
    'uploadDir' => "",

    'types' => array(

    // (F)CKEditor types
        'files'   =>  "",
        'flash'   =>  "swf",
        'images'  =>  "*img",

    // TinyMCE types
        'file'    =>  "",
        'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
        'image'   =>  "*img",
    ),


// IMAGE SETTINGS

    'imageDriversPriority' => "imagick gmagick gd",
    'jpegQuality' => $kcfinder_jpegQuality,
    'thumbsDir' => $thumbs_dir,

    'maxImageWidth' => $kcfinder_maxImageWidth,
    'maxImageHeight' => $kcfinder_maxImageHeight,

    'thumbWidth' => $kcfinder_thumbWidth,
    'thumbHeight' => $kcfinder_thumbHeight,

    'watermark' => "",


// DISABLE / ENABLE SETTINGS

    'denyZipDownload' => !$UserIsAdmin,
    'denyUpdateCheck' => !$UserIsAdmin,
    'denyExtensionRename' => !$UserIsAdmin,


// PERMISSION SETTINGS

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => $UserCanUpload,
            'delete' => $UserIsAdmin,
            'copy'   => true,
            'move'   => $UserIsAdmin,
            'rename' => $UserIsAdmin
        ),

        'dirs' => array(
            'create' => $UserIsAdmin,
            'delete' => $UserIsAdmin,
            'rename' => $UserIsAdmin
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",


// MISC SETTINGS

    'filenameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'dirnameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'mime_magic' => "",

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',


// THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION SETTINGS

    '_check4htaccess' => true,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);

?>