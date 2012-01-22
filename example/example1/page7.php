<?php

require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();

print $menu->header();

?>
<p>
Pas de texte.
</p>
<?php
print $menu->footer();
?>

