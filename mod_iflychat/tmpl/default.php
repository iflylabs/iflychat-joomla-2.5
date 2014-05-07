<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

// no direct access
defined('_JEXEC') or die;


?>

<?php

{
    ?>
    <div class="mod_iflychat">

        <?php

        $r   = '<script type="text/javascript">';

        $r .=   $items;
        $r .= '<script type="text/javascript" src="' . JURI::base().'modules/'.$module->module .  '/js/iflychat.js"></script>';
        $r  .= '<script>  window.my_var_handle ="' . JURI::base().'modules/'.$module->module . '"</script>';
        echo "$r";
        ?>
    </div>
<?php
}
?>