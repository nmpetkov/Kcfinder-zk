<?php
/**
 * Smarty function to insert Kcfinder manager
 *
 * Available parameters:
 *   images_dir:     Directory for images to manage
 *
 * Example
 *   {kcfinderscript_window images_dir='userdata\addressbook'}
 *      and call can be for example:
 *   <a href="#" onclick="openKCFinder(document.getElementById('address_img'));">{img modname='core' set='icons/extrasmall' src="search.gif"}</a>
 *
 * @author       Nikolay Petkov
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $assign      (optional) If set then result will be assigned to this template variable
 */
function smarty_function_kcfinderscript_window($params, &$smarty)
{
    $assign = isset($params['assign']) ? $params['assign'] : null;
    $images_dir = isset($params['images_dir']) ? $params['images_dir'] : null;

    $session_id = session_id();
    $result = "
<script type=\"text/javascript\">
function openKCFinder(field) {
    window.KCFinder = {
        callBack: function(url) {
            field.value = url;
            window.KCFinder = null;
        }
    };
    window.open('plugins/Kcfinder/vendor/browse.php?type=images".($images_dir ? "&dir=".$images_dir : "")."&s=".$session_id."', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}
</script>
";

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
