<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 3.12
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  *      @link http://kcfinder.sunhater.com
  */

/* IMPORTANT!!! Do not comment or remove uncommented settings in this file
   even if you are using session configuration.
   See http://kcfinder.sunhater.com/install for setting descriptions */

// Zikula settings and permissions ==>
session_start();
$UserIsAdmin = false;
$UserCanUpload = false;
$UserUploadDir = '/userdata/Kcfinder';
$thumbs_dir = '.thumbs';
$kcfinder_theme = 'default';
$kcfinder_jpegQuality = 80;
$kcfinder_maxImageWidth = 1200;
$kcfinder_maxImageHeight = 1200;
$kcfinder_thumbWidth = 100;
$kcfinder_thumbHeight = 100;
if (isset($_GET['s'])) {
    // attempt to locate Zikula config.php and link to database
    require_once __DIR__.'/ztools.php';
    $link = Ztools::ConfigMysqlConnect();
    if ($link) {
        // Get Kcfinder system plugin config variables, if the plugin is installed
        $vars = Ztools::ZikulaModuleVars('systemplugin.kcfinder');
        // setting upload directory and other settings
        if (isset($vars['upload_dir'])) {
            $UserUploadDir = '/'.$vars['upload_dir'];
        }
        if (isset($_GET['dir'])) {
            // possibility to pass upload/browse directory
            $UserUploadDir = '/'.$_GET['dir'];
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
        $userid = Ztools::ZikulaSessionUserid($_GET['s']);

        // list from group and user list of IDs for permissions
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
        if (!$UserIsAdmin) {
        // check user id if are in list with user ids for admins
            $arrayids = explode(",", $useradminslist);
            if (in_array($userid, $arrayids)) {
                $UserIsAdmin = true;
            }
        }
        if (!$UserIsAdmin) {
            // chech user groups ids if are in list with group ids for admins
            $UserIsAdmin =  Ztools::ZikulaUserIsInGroup($userid, $groupadminslist);
        }

        if (!$UserCanUpload) {
            // check user id if are in list with user ids for can upload
            $arrayids = explode(",", $usercanuploadlist);
            if (in_array($userid, $arrayids)) {
                $UserCanUpload = true;
            }
        }
        if (!$UserCanUpload) {
            // chech user groups ids if are in list with group ids for can upload
            $UserCanUpload =  Ztools::ZikulaUserIsInGroup($userid, $groupcanuploadlist);
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

if ($kcfinder_theme == 'oxygen') {
    $kcfinder_theme = 'default';
}
// <== Zikula settings and permissions

$_CONFIG = array(


// GENERAL SETTINGS

    'disabled' => false,
    'uploadURL' => $UserUploadDir,
    'uploadDir' => "",
    'theme' => $kcfinder_theme,

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

    'deniedExts' => "exe com msi bat cgi pl php phps phtml php3 php4 php5 php6 py pyc pyo pcgi pcgi3 pcgi4 pcgi5 pchi6",


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

    '_normalizeFilenames' => false,
    '_check4htaccess' => true,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => "KCFINDER",
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",
    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",

    //'_cssMinCmd' => "java -jar /path/to/yuicompressor.jar --type css {file}",
    //'_jsMinCmd' => "java -jar /path/to/yuicompressor.jar --type js {file}",

);

?>