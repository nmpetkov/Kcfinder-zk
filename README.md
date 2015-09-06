Kcfinder
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
2. Copy all files in respective directories in main Zikula directory (files and folder from 'Kcfinder-zk' into 'plugins/Kcfinder').
3. Optional: Configure plugin in Zikula Admin Panel: Extensions, System Plugins, Kcfinder, Configure plugin.
4. For Scribite with CKEditor: Enter proper path "plugins/Kcfinder/vendor" to file manager in CKEditor plugin config page.

USAGE

1. All documentation to use kcfinder can find in vendor website: http://kcfinder.sunhater.com/.
2. To work Zikula configuration and permissions, it is a must to pass session ID as GET parameter to KCFinder. Examples are with default location for KCFinder: utils/kcfinder directory.

	FROM PHP CODE:
	- use session_id php function to obtain session Id.
	- add parameter s=xxxxxx to URL, where xxxxxx is session Id obtained with session_id php function:
		example: URL='utils/kcfinder/browse.php?type=images&s=e9b534d2d33366f64a4aa05b5f58ff1e02a24957'
	
	FROM TEMPLATES:
	- session id can be assigned to a template variable:
	{callfunc x_function='session_id' x_assign='session_id'}
    - add parameter s={$session_id} to URL.
    
EXAMPLE USAGE

1. Module Scribite, https://github.com/zikula-modules/Scribite.
2. Module AddressBook, https://github.com/nmpetkov/AddressBook.

Search for KCFinder in all files.
