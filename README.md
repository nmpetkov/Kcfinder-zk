Kcfinder 1.0.0
===================

Plugin for Zikula Application Framework.
Provides configurable interface to specify permissions and other parameters, needed for Kcfinder web file manager to work within Zikula 1.3+ environment.
Kcfinder website: Kcfinder website: http://kcfinder.sunhater.com/

This plugin makes two main things:
1. Provides interface to configure KCFinder in Zikula Admin Panel. Plugin itself is not loaded automatically when Zikula boots to optimize Zikula performance. It is activated when is called with proper URL.
2. For now changes one file only from KCFinder package: config.php. This is to apply configured parameters, and to apply user permissions.

ONLINE DEMO FOR KCfinder file manager

Visit: http://kcfinder.sunhater.com/demos/ckeditor. This is KCFinder integration with CKEditor. Press "Image" button from toolbar, then "Browse Server" or "Upload".

PLUGIN INSTALATION

1. Download the plugin (tagged or current version). 
2. Copy all files in respective directories in main Zikula directory.
3. Optional: Configure plugin in Zikula Admin Panel: Extensions, System Plugins, Kcfinder, Configure plugin.
4. For Scribite with CKEditor: Enter proper path "plugins/Kcfinder/vendor" to file manager in CKEditor plugin config page.
