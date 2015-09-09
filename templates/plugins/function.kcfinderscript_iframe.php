<?php
/**
 * Smarty function to insert Kcfinder manager as iframe
 *
 * Available parameters:
 *   upload_dir:     Directory for images to manage
 *
 * Example
 *   {kcfinderscript_iframe upload_dir='userdata\addressbook' type='images'}
 *      and call can be for example:
 *   <a href="#" onclick="iframeKCFinder(document.getElementById('address_img'));">{img modname='core' set='icons/extrasmall' src="search.gif"}</a>
 *   and after <input> tag:
 *   <div id="kcfinder_div"></div>
 *   Parameters:
 *      type - predifined are images/flash/files , and javascript functions to call are:
 *              iframeKCFinder/iframeKCFinderFlash/iframeKCFinderFiles
 *
 * @author       Nikolay Petkov
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $assign      (optional) If set then result will be assigned to this template variable
 */
function smarty_function_kcfinderscript_iframe($params, &$smarty)
{
    $assign = isset($params['assign']) ? $params['assign'] : null;
    $upload_dir = isset($params['upload_dir']) ? $params['upload_dir'] : null;
    $type = isset($params['type']) ? $params['type'] : 'images';

    $jsFuncname = 'iframeKCFinder';
    if ($type != 'images') {
        $jsFuncname .= ucfirst($type);
    }

    $session_id = session_id();
    $result = "
<style type=\"text/css\">
#kcfinder_div {
    display: none;
    position: absolute;
    z-index: 9999;
    width: 670px;
    height: 400px;
    background: #e0dfde;
    border: 2px solid #3687e2;
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    padding: 1px;
}
</style>
 
<script type=\"text/javascript\">
function ".$jsFuncname."(field) {
    var div = document.getElementById('kcfinder_div');
    if (div.style.display == \"block\") {
        div.style.display = 'none';
        div.innerHTML = '';
        return;
    }
    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            if (field) { field.value = url; }
            div.style.display = 'none';
            div.innerHTML = '';
        }
    };
    div.innerHTML = '<iframe name=\"kcfinder_iframe\" src=\"/plugins/Kcfinder/vendor/browse.php?type=".$type.($upload_dir ? "&dir=".$upload_dir : "")."&s=".$session_id."\" ' +
        'frameborder=\"0\" width=\"100%\" height=\"100%\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" />';
    div.style.display = 'block';
}
</script>";

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
