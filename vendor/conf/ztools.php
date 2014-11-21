<?php
/**
 * Zikula Tools - to use if Zikula is not loaded
 *
 * @copyright Nikolay Petkov
 * @license GNU/GPL
 */
 
/**
 * Ztools
 */
class Ztools
{
    /**
     * Zikula ZConfig.
     *
     * @var array
     */
    public static $ZConfig = array();

    /**
     * Connection link to database.
     *
     * @var MySQL link identifier 
     */
    public static $dblink;

    /**
     * Name of database.
     *
     * @var string
     */
    public static $dbname;

    /**
     * Array with data from Zikula session table.
     *
     * @var array
     */
    public static $sessiondata;

    /**
     * Return location and file name for Zikula config.php.
     *
     * @param integer  $maxdeeplevel      Max deep level for calling root
     *
     * @return path and file name if found, empty string otherwise
     */
    public static function getConfigFile($maxdeeplevel = 8)
    {
        $configfile = '';
        $updirprefix = '';
        for ($i = 1; $i <= $maxdeeplevel; $i++) {
            $configfile = __DIR__.$updirprefix.'/config/config.php';
            if (file_exists($configfile)) {
                $configfile = realpath($configfile);
                break;
            }
            $updirprefix .= '/..';;
        }

        return $configfile;
    }

    /**
     * Include Zikula config.php.
     *
     * @param integer  $maxdeeplevel      Max deep level for calling root
     *
     * @return boolean true or false
     */
    public static function IncludeConfigFile($maxdeeplevel = 8)
    {
        $success = false;
        $configfile = self::getConfigFile($maxdeeplevel);
        if (!empty($configfile)) {
            // here if we include once config file, then have to make checking also self::$ZConfig = $ZConfig to accrue once!
            require $configfile;
            self::$ZConfig = $ZConfig;
            /*require_once $configfile;
            if (!isset(self::$ZConfig['DBInfo'])) {
                self::$ZConfig = $ZConfig;
            }*/
            $success = true;
        }

        return $success;
    }

    /**
     * Open a connection to Zikula MySQL.
     *
     * @return MySQL link identifier or false
     */
    public static function mysqlConnect()
    {
        self::$dbname = self::$ZConfig['DBInfo']['databases']['default']['dbname'];
        self::$dblink = mysql_connect(self::$ZConfig['DBInfo']['databases']['default']['host'], self::$ZConfig['DBInfo']['databases']['default']['user'], self::$ZConfig['DBInfo']['databases']['default']['password']);
        if (self::$dblink) {
            if (!mysql_select_db(self::$dbname)) {
                echo 'Can not select database';
            }
        } else {
            echo 'Can not connect to MySql server';
        }

        return self::$dblink;
    }

    /**
     * Include Zikula config.php and open a connection to Zikula MySQL.
     *
     * @return MySQL link identifier or false
     */
    public static function ConfigMysqlConnect()
    {
        $configisok = self::IncludeConfigFile();
        if ($configisok) {
            self::mysqlConnect();
        }

        return self::$dblink;
    }

    /**
     * Execute a MySQL query.
     *
     * @param string  $sql
     *
     * @return MySQL resource or boolean (mysql_query)
     */
    public static function MysqlQuery($sql)
    {
        if (!self::$dblink) {
            self::mysqlConnect();
        }
        if (self::$dblink) {
            $rSet = mysql_query($sql, self::$dblink) or die("Bad query: ".$sql);
        }

        return $rSet;
    }

    /**
     * Execute a MySQL query and return result (1 row only) as associative array.
     *
     * @param string  $sql
     *
     * @return array
     */
    public static function MysqlQueryFetchArray($sql)
    {
        $rSet = self::MysqlQuery($sql);
        if ($rSet) {
            return mysql_fetch_array($rSet, MYSQL_ASSOC);
        }

        return false;
    }

    /**
     * Zikula module variables.
     *
     * @param string  $modname
     *
     * @return array with Zikula session variables
     */
    public static function ZikulaModuleVars($modname)
    {
        $sql = 'SELECT * FROM `module_vars` WHERE `modname`="'.$modname.'"';
        $rSet = self::MysqlQuery($sql);
        $vars = array();
        while ($var = mysql_fetch_array($rSet)){
            $vars[$var['name']] = unserialize($var['value']);
        }

        return $vars;
    }

    /**
     * Zikula session info.
     *
     * @param string  $sessid
     *
     * @return array with data from Zikula session table
     */
    public static function ZikulaSessionInfo($sessid)
    {
        $sql = 'SELECT * FROM `session_info` WHERE `sessid`="'.$sessid.'"';
        self::$sessiondata = self::MysqlQueryFetchArray($sql);

        return self::$sessiondata;
    }

    /**
     * Zikula session user Id.
     *
     * @param string  $sessid
     *
     * @return integer User Id from Zikula session table
     */
    public static function ZikulaSessionUserid($sessid)
    {
        $userid = 0;
        if (!is_array(self::$sessiondata)) {
            self::ZikulaSessionInfo($sessid);
        }
        if (is_array(self::$sessiondata)) {
            $userid = self::$sessiondata['uid'];
        }

        return $userid;
    }

    /**
     * Zikula group Ids for given user.
     *
     * @param string  $userid
     *
     * @return array Array with group Id's to which user belongs
     */
    public static function ZikulaUserGroupids($userid)
    {
        $usergroupids = array();
        if ($userid > 0) {
            $sql = 'SELECT * FROM `group_membership` WHERE `uid`='.$userid;
            $rSet = self::MysqlQuery($sql);
            while ($r = mysql_fetch_object($rSet)){
                $usergroupids[] = $r->gid;
            }
        }

        return $usergroupids;
    }

    /**
     * Zikula user admin status
     *
     * @param string  $userid
     * @param string  $groupslist    Group, or list of groups to check
     *
     * @return boolean 
     */
    public static function ZikulaUserIsAdmin($userid, $groupadminslist = '2')
    {
        if ($userid > 0) {
            if (empty($groupadminslist)) {
                // 2 is default Zikula admin group Id
                $groupadminslist = '2';
            }
            return self::ZikulaUserIsInGroup($userid, $groupadminslist);
        }

        return false;
    }

    /**
     * Zikula user group belonging
     *
     * @param string  $userid
     * @param string  $groupslist    Group, or list of groups to check
     *
     * @return boolean 
     */
    public static function ZikulaUserIsInGroup($userid, $groupslist = '')
    {
        if ($userid > 0 and $groupslist) {

            // get groups for the given user
            $usergroupids = self::ZikulaUserGroupids($userid);
            // chech to see if at least one of user group ids is in the given list of group ids
            $arrayids = explode(",", $groupslist);
            foreach ($usergroupids as $usergroupid) {
                if (in_array($usergroupid, $arrayids)) {
                    return true;
                }
            }
        }

        return false;
    }

}
