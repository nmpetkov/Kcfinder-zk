<?php
/**
 * Copyright Zikula Foundation 2012 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version.
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class SystemPlugin_Kcfinder_Plugin extends Zikula_AbstractPlugin implements Zikula_Plugin_ConfigurableInterface
{
    /**
     * Get plugin meta data.
     *
     * @return array Meta data.
     */
    protected function getMeta()
    {
        return array(
            'displayname' => $this->__('Kcfinder'),
            'description' => $this->__('Provides Kcfinder web file manager'),
            'version'     => '1.0.0'
        );
    }

    /**
     * Checks plugin version and performs install/upgrade routine when needed.
     */
    public function preInitialize()
    {
        $version = $this->getVar('version', false);
        if (!$version) {
            $this->install();
        } elseif ($version !==  $this->getMetaVersion()) {
            $this->upgrade($version);
        }
    }

    /**
     * Initialise.
     *
     * Runs at plugin init time.
     *
     * @return void
     */
    public function initialize()
    {
        $definition = new Zikula_ServiceManager_Definition('SystemPlugin_Kcfinder_Manager', array(
            new Zikula_ServiceManager_Reference('zikula.servicemanager'),
            new Zikula_ServiceManager_Reference($this->getServiceId())
        ));
        $this->serviceManager->registerService('systemplugin.kcfinder.manager', $definition, false);
    }

    /**
     * Return configuration controller instance.
     *
     * @return SystemPlugin_Kcfinder_Configuration
     */
    public function getConfigurationController()
    {
        return new SystemPlugin_Kcfinder_Configuration($this->getServiceManager(), $this);
    }

    /**
     * Performs install routine.
     *
     * @return bool
     */
    public function install()
    {
        $defaults = $this->defaultSettings();
        $this->setVars($defaults);

        return true;
    }

    /**
     * Performs upgrade routine.
     *
     * @param string $oldVersion
     *
     * @return bool
     */
    public function upgrade($oldVersion)
    {
        return true;
    }

    /**
     * Returns plugin default settings.
     *
     * @return array
     */
    public function defaultSettings()
    {
        $settings = array(
            'version' => $this->getMetaVersion(),
            'upload_dir' => 'userdata/kcfinder',
            'thumbs_dir' => '.thumbs',
            'listgroups_admin' => '2',  // default group Id for administrators
            'listgroups_upload' => '1',  // default group Id for users
            'listusers_admin' => '',
            'listusers_upload' => '',
            'kcfinder_theme' => 'oxygen',
            'kcfinder_jpegQuality' => 80,
            'kcfinder_maxImageWidth' => 1200,
            'kcfinder_maxImageHeight' => 1200,
            'kcfinder_thumbWidth' => 100,
            'kcfinder_thumbHeight' => 100
        );

        return $settings;
    }

    /**
     * Gets Manager service
     *
     * @return SystemPlugin_Kcfinder_Manager
     */
    public function getManager()
    {
        return $this->getServiceManager()->getService('systemplugin.kcfinder.manager');
    }

    /**
     * Convenience Module SetVar.
     *
     * @param string $key   Key.
     * @param mixed  $value Value, default empty.
     *
     * @return object This.
     */
    public function setVar($key, $value='')
    {
        ModUtil::setVar($this->getServiceId(), $key, $value);

        return $this;
    }

    /**
     * Convenience Module SetVars.
     *
     * @param array $vars Array of key => value.
     *
     * @return object This.
     */
    public function setVars(array $vars)
    {
        ModUtil::setVars($this->getServiceId(), $vars);

        return $this;
    }

    /**
     * Convenience Module GetVar.
     *
     * @param string  $key     Key.
     * @param boolean $default Default, false if not found.
     *
     * @return mixed
     */
    public function getVar($key, $default=false)
    {
        return ModUtil::getVar($this->getServiceId(), $key, $default);
    }

    /**
     * Convenience Module GetVars for all keys in this module.
     *
     * @return mixed
     */
    public function getVars()
    {
        return ModUtil::getVar($this->getServiceId());
    }

    /**
     * Convenience Module DelVar.
     *
     * @param string $key Key.
     *
     * @return object This.
     */
    public function delVar($key)
    {
        ModUtil::delVar($this->getServiceId(), $key);

        return $this;
    }

    /**
     * Convenience Module DelVar for all keys for this module.
     *
     * @return object This.
     */
    public function delVars()
    {
        ModUtil::delVar($this->getServiceId());

        return $this;
    }
}
