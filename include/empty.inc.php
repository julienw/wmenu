<?php

/*
   empty.inc.php

   this page will be called if the user want to show
   a page with no content, but with subpages.

   it is passed 1 argument by GET method :

   - page : in which 'virtual' page we are.

   */

require_once("wmenu.class.inc.php");
require_once("menuconf.inc.php");
require_once("utils.inc.php");

if ((!isset($_GET['page'])) or empty($_GET['page'])) {
	do_error404();
}

$menu = new WMenu(new SiteConfiguration);
$menu->setMenuType(MENU_TYPE_TITLE, $_GET['page']);
if ($menu->calculate() === false) {
	do_error404();
}

print $menu->header();
?>

<p>Liste des rubriques&nbsp;:</p>
<ul>
<?php
$items = $menu->get_items();

foreach ($items as $title => $item) {
	print "<li><a href='$item'>$title</a></li>\n";
}

print "</ul>\n";

print $menu->footer();
?>

