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

class SystemPlugin_Kcfinder_Configuration extends Zikula_Controller_AbstractPlugin
{
    /**
     * Parent plugin instance.
     *
     * @var SystemPlugin_Kcfinder_Plugin
     */
    protected $plugin;

    protected function postInitialize()
    {
        // In this controller we don't want caching to be enabled.
        $this->view->setCaching(false);
    }

    /**
     * Fetch and render the configuration template.
     *
     * @return string The rendered template.
     */
    public function configure()
    {
        $modVars = $this->plugin->getVars();

        $this->getView()
            ->assign('header', ModUtil::func('Admin', 'admin', 'adminheader'))
            ->assign('footer', ModUtil::func('Admin', 'admin', 'adminfooter'))
            ->assign('vars', $modVars);

        return $this->getView()->fetch('configuration.tpl');
    }

    /**
     * Update plugin configuration
     */
    public function updateConfig()
    {
        $this->checkCsrfToken();

        $var1 = $this->request->getPost()->get('kcfinder_dir');
        $this->plugin->setVar('kcfinder_dir', $var1);

        $var1 = $this->request->getPost()->get('upload_dir');
        $this->plugin->setVar('upload_dir', $var1);

        $var1 = $this->request->getPost()->get('thumbs_dir');
        $this->plugin->setVar('thumbs_dir', $var1);

        $var1 = $this->request->getPost()->get('listgroups_admin');
        $this->plugin->setVar('listgroups_admin', $var1);

        $var1 = $this->request->getPost()->get('listgroups_upload');
        $this->plugin->setVar('listgroups_upload', $var1);

        $var1 = $this->request->getPost()->get('listusers_admin');
        $this->plugin->setVar('listusers_admin', $var1);

        $var1 = $this->request->getPost()->get('listusers_upload');
        $this->plugin->setVar('listusers_upload', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_theme');
        $this->plugin->setVar('kcfinder_theme', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_jpegQuality');
        $this->plugin->setVar('kcfinder_jpegQuality', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_maxImageWidth');
        $this->plugin->setVar('kcfinder_maxImageWidth', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_maxImageHeight');
        $this->plugin->setVar('kcfinder_maxImageHeight', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_thumbWidth');
        $this->plugin->setVar('kcfinder_thumbWidth', $var1);

        $var1 = $this->request->getPost()->get('kcfinder_thumbHeight');
        $this->plugin->setVar('kcfinder_thumbHeight', $var1);

        LogUtil::registerStatus($this->__('Done! Saved plugin configuration.'));

        $this->redirect(ModUtil::url('Extensions', 'adminplugin', 'dispatch', array(
            '_plugin' => 'Kcfinder',
            '_action' => 'configure'
        )));
    }
}
