{$header}
<div class="z-admin-content-pagetitle">
    {icon type='gears' size='small'}
    <h3>{gt text='Kcfinder plugin settings'}</h3>
</div>

<form id="kcfinder-configuration" class="z-form" action="{modurl modname='Extensions' type='adminplugin' func='dispatch' _plugin='Kcfinder' _action='updateConfig'}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <fieldset>
            <legend>{gt text='General settings'}</legend>

            <div class="z-formrow">
                <label for="kcfinder_dir">{gt text='Kcfinder install directory'}</label>
                <input type="text" id="kcfinder_dir" name="kcfinder_dir" value="{$vars.kcfinder_dir|safetext}" />
                <p class="z-formnote z-sub">{gt text='Default is utils/kcfinder.'}</p>
            </div>

            <div class="z-formrow">
                <label for="upload_dir">{gt text='Default upload directory'}</label>
                <input type="text" id="upload_dir" name="upload_dir" value="{$vars.upload_dir|safetext}" />
                <p class="z-formnote z-sub">{gt text='Default is userdata/upload.'}</p>
            </div>

            <div class="z-formrow">
                <label for="thumbs_dir">{gt text='Default thumbnails directory'}</label>
                <input type="text" id="thumbs_dir" name="thumbs_dir" value="{$vars.thumbs_dir|safetext}" />
                <p class="z-formnote z-sub">{gt text='Relative to main upload directory.'}</p>
            </div>

            <div class="z-formrow">
                <label for="listgroups_admin">{gt text='Groups with admin (delete/rename) permissions'}</label>
                <input type="text" id="listgroups_admin" name="listgroups_admin" value="{$vars.listgroups_admin|safetext}" />
                <p class="z-formnote z-sub">{gt text='Comma separated list of user groups IDs.'}</p>
            </div>

            <div class="z-formrow">
                <label for="listgroups_upload">{gt text='Groups with upload permissions'}</label>
                <input type="text" id="listgroups_upload" name="listgroups_upload" value="{$vars.listgroups_upload|safetext}" />
                <p class="z-formnote z-sub">{gt text='Comma separated list of user groups IDs.'}</p>
            </div>

            <div class="z-formrow">
                <label for="listusers_admin">{gt text='Users with admin (delete/rename) permissions'}</label>
                <input type="text" id="listusers_admin" name="listusers_admin" value="{$vars.listusers_admin|safetext}" />
                <p class="z-formnote z-sub">{gt text='Comma separated list of users IDs.'}</p>
            </div>

            <div class="z-formrow">
                <label for="listusers_upload">{gt text='Users with upload permissions'}</label>
                <input type="text" id="listusers_upload" name="listusers_upload" value="{$vars.listusers_upload|safetext}" />
                <p class="z-formnote z-sub">{gt text='Comma separated list of users IDs.'}</p>
            </div>

            <div class="z-formrow">
                <label for="kcfinder_theme">{gt text='Visual theme of KCFinder'}</label>
                <input type="text" id="kcfinder_theme" name="kcfinder_theme" value="{$vars.kcfinder_theme|safetext}" />
                <p class="z-formnote z-sub">{gt text='Can be oxygen or dark.'}</p>
            </div>

            <div class="z-formrow">
                <label for="kcfinder_jpegQuality">{gt text='JPEG quality'}</label>
                <input type="text" id="kcfinder_jpegQuality" name="kcfinder_jpegQuality" value="{$vars.kcfinder_jpegQuality|safetext}" />
                <p class="z-formnote z-sub">{gt text='JPEG compression quality of thumbnails and resized images.'}</p>
            </div>

            <div class="z-formrow">
                <label for="kcfinder_maxImageWidth">{gt text='Maximum image width and height'}</label>
                <input type="text" id="kcfinder_maxImageWidth" name="kcfinder_maxImageWidth" value="{$vars.kcfinder_maxImageWidth|safetext}" />
                <input type="text" id="kcfinder_maxImageHeight" name="kcfinder_maxImageHeight" value="{$vars.kcfinder_maxImageHeight|safetext}" />
                <p class="z-formnote z-sub">{gt text='If uploaded image resolution exceeds these settings it will be automatically resized. If both are set to zero, images will not be resized. If one of these settings is set to zero, the image will be proportionally resized to fit the other setting.'}</p>
            </div>

            <div class="z-formrow">
                <label for="kcfinder_thumbWidth">{gt text='Thumbnail width and height'}</label>
                <input type="text" id="kcfinder_thumbWidth" name="kcfinder_thumbWidth" value="{$vars.kcfinder_thumbWidth|safetext}" />
                <input type="text" id="kcfinder_thumbHeight" name="kcfinder_thumbHeight" value="{$vars.kcfinder_thumbHeight|safetext}" />
                <p class="z-formnote z-sub">{gt text='Resolution for the generated thumbnail images.'}</p>
            </div>
        </fieldset>

        <div class="z-buttons z-formbuttons">
        {button src=button_ok.png set=icons/extrasmall __alt='Save' __title='Save' __text='Save'}
            <a href="{modurl modname='Extensions' type='adminplugin' func='dispatch' _plugin='Kcfinder' _action='configure'}" title="{gt text='Cancel'}">{img modname=core src=button_cancel.png set=icons/extrasmall __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
        </div>
    </div>
</form>

{$footer}