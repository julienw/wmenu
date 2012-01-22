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
<p><a href='force.php'>Lien vers une page qui force le hilight de "page 2"</a></p>
<?php
print $menu->footer();
?>

