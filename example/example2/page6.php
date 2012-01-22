<?php

require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();

print $menu->header();

?>
<p>
Ceci est la page 6.
<a href="page7.php">Lien vers la page 7</a>.
</p>
<?php
print $menu->footer();
?>

