<?php

require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->setMenuType(MENU_TYPE_TITLE, 'Page 2 longue');
$menu->calculate();

print $menu->header();
?>
<p>
On force l'item de page2.
</p>
<?php
print $menu->footer();
?>

