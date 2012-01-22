<?php
/*

 Copyright 2003 Julien Wajsberg <felash@gmail.com> and Association MiNET <minet@minet.net>

 This file is part of WMenu.

 WMenu is free software; you can redistribute it and/or modify
 it under the terms of the GNU Lesser General Public License as
 published by the Free Software Foundation, version 2 of the License.

 WMenu is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Lesser General Public License for more details.

 You should have received a copy of the GNU Lesser General Public
 License along with WMenu; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 1-1307  USA

*/

require_once('utils.inc.php');

define ('MENU_RESERVED_CHARS', '+!@'); // caracteres au debut des urls
define ('MENU_TYPE_URL', 1);
define ('MENU_TYPE_TITLE', 2);
define ('MENU_PLACE_FOOTER', 1);
define ('MENU_PLACE_HEADER', 2);
define ('MENU_PLACE_NONE', 3);

class WMenu {

	var $_menu;			/* menu sous forme d'un arbre, en tableau */
	var $_base_url;		/* url de base */
	var $_prefix;
	var $_path;			/* chemin calculé, en fonction de la page appelée */
	var $_html_menu;	/* menu qui va être affiché */
	var $_sitetitle;
	var $_pagetitle;
	var $_pagetitletext;
	var $_menu_type = MENU_TYPE_URL;
	var $_menu_place = MENU_PLACE_FOOTER;
	var $_menu_type_arg = null;

	function WMenu ($config) {
		$this->_menu = $config->menu;
		$this->setBaseUrl($config->base_url);
		$this->setSiteTitle($config->site_title);
		$this->setTexts($config->texts);
		$this->setMenuPlace($config->menu_place);
	}

	function setTexts ($texts) {
		$this->_texts = $texts;
	}

	function setMenuType($menutype, $menuarg = null) {
		$this->_menu_type = $menutype;
		$this->_menu_type_arg = $menuarg;
	}

	function setMenuPlace($menuplace) {
		if (empty($menuplace)) return; /* don't do anything if it's undefined */
		switch ($menuplace) {
			case 'footer':
				$this->_menu_place = MENU_PLACE_FOOTER;
				break;
			case 'header':
				$this->_menu_place = MENU_PLACE_HEADER;
				break;
			default:
				$this->_menu_place = MENU_PLACE_NONE;
		}
	}

	function setBaseUrl ($base_url) {
		$this->_base_url = $base_url;
		$this->_prefix = get_prefix($this->_base_url);
	}
	
	function setSiteTitle ($title) {
		$this->_sitetitle = $title;
	}

	function _path2path ($path) {
		ksort($path, SORT_NUMERIC);

		foreach ($path as $element) {
			$newpath[] = $element[0];	// on aurait pu utiliser array_walk mais bon..
		}

		return $newpath;
	}

	
	function _compute_path_byurl() {

		$scriptname = $_SERVER['PHP_SELF'];

		$scriptname = preg_replace("{^$this->_base_url/}", "", $scriptname);

		$current_depth = 0;
		$pile = array(array(NULL, $this->_menu, 1));	// pile de couples (item contenant un titre, un noeud, une profondeur)
		$path = array(); // tableau dont les clés seront les niveaux

		// parcours itératif d'un arbre en profondeur
		while ($noeud = array_pop($pile)) { // dépile
			if ($current_depth == $noeud[2])			// si on reste au meme niveau
				unset($path[$current_depth]);			// alors on enleve le dernier composant ajouté

			if ($current_depth > $noeud[2]) {			// si on remonte de niveau
				foreach ($path as $depth => $value) { 	// on enleve ts les elements de chemins inferieurs
					if ($depth >= $noeud[2]) {			// ou egaux a la profondeur courante
						unset($path[$depth]);			// note: on aurait pu faire ca sans 'if', mais c'est
					}									// pour essayer d'optimiser
				}
			}

			list ($title, $noeud, $current_depth) = $noeud;

			if ($title !== NULL) {
				$path[$current_depth][] = $title; // un nouveau composant de chemin
			}


			foreach ($noeud as $title => $value) {

				$node_urls = preg_replace("/^[" . MENU_RESERVED_CHARS . "]+/", "", $value[0]);

				if (! is_array($node_urls)) $node_urls = array($node_urls); // transformation de $node_url en tableau
				if (in_array($scriptname, $node_urls)) {
					$path[$current_depth + 1][] = $title; // final push
					$this->_path = $this->_path2path ($path);
					return true;
				}

				if (isset($value[1]) && is_array($value[1])) {
					array_push($pile, array($title, $value[1], $current_depth + 1)); // empile
				}

			}
		}
		return false;
	}

	// permet de forcer le menu sur une certaine entree
	function _compute_path_bytitle () {
		$title = $this->_menu_type_arg;

		$current_depth = 0;
		$pile = array(array(NULL, $this->_menu, 1));
		$path = array();

		// parcours itératif d'un arbre en profondeur
		while ($noeud = array_pop($pile)) { // dépile

			if ($current_depth >= $noeud[2])			// si on reste au meme niveau
				array_pop($path);						// alors on enleve le dernier composant ajouté

			list ($curtitle, $noeud, $current_depth) = $noeud;
			if ($curtitle !== NULL) {
				array_push($path, $curtitle); // un nouveau composant de chemin
			}

			foreach ($noeud as $curtitle => $item) {

				if ($curtitle === $title) {
					array_push($path, $curtitle); // final push
					$this->_path = $path;
					return true;
				}

				if (isset($item[1]) && is_array($item[1])) {
					array_push($pile, array($curtitle, $item[1], $current_depth + 1)); // empile
				}

			}
		}

		return false;
	}


	/* execute the correct function depending on the TYPE */
	/* use inheritance ? */
	function compute_path () {
		switch ($this->_menu_type) {
			case MENU_TYPE_URL :
				return $this->_compute_path_byurl();
			case MENU_TYPE_TITLE :
				return $this->_compute_path_bytitle();
		}
		return false;
	}
	
	function build_menu () {
		$string = "<div id='menu'>\n";
		$string .= "<a name='menulink' id='menulink'></a>\n";
		if (empty($this->_path)) {
			$string .= "<p>The menu could not be built.</p>";
		} else {
			$string .= $this->_build_menu_rec($this->_menu, $this->_path);
		}
		$string .= "</div>";

		$this->_html_menu = $string;
		return true;
	}

	/* fonction récursive */
	function _build_menu_rec ($menu, $path, $padding = 1) {

		$string = "";
		$next_path = array_shift($path);


		foreach ($menu as $title => $item) {
			$class1 = $class2 = $classend1 = $classend2 = "";
			$prefix = $this->_prefix;
			if ($next_path === $title) {
				// si cet item fait partie du chemin
				$class1 = "<div class='selected'>";
				$classend1 = "</div>";
			}

			if (is_array($item[1])) {
				// si c'est un sous menu
				if ($next_path === $title) {
					// déroulé
					$suffix = '&nbsp;-';
				} else {
					// enroulé
					$suffix = '&nbsp;+';
				}
			} else {
				$suffix = "";
			}

			$string .= "<div style='padding-left: ${padding}em'>{$class1}";
			$string .= $this->_get_link_for_item ($title, $item[0], is_array($item[1]), $suffix);
			$string .= "{$classend1}</div>\n";

			if (($next_path === $title) && (is_array($item[1]))) {
				// si cet item fait partie du chemin et que c'est un sous-menu
				$string .= $this->_build_menu_rec($item[1], $path, $padding + 1);
			}
		}

		return $string;
	}


	/* this function returns (and caches) a string, that is an html link
	   (A anchor) to the item,
	   
	   $title : this item's title ;
	   $link : the url to be linked (if it is an array of
		 links, then we'll take only the first one) ;
	   $submenu : a boolean : true if this item is a submenu, false if it's a leaf.
	   $suffix : an optional suffix, to be appended to the title ;
	 */

	function _get_link_for_item ($title, $link, $submenu, $suffix = "") {
		if (isset($this->_html_links[$title])) { // if the result for this title is known
			return $this->_html_links[$title];
		}

		$prefix = $this->_prefix;

		if (empty($link)) {				// this item has no link
			if ($submenu) {	// if this item is a submenu
				$link = "empty.php?page=". rawurlencode($title);
			} else {					// if this item is a leaf
				$prefix = "javascript: return false;";
			}
		}

		$target = "";

		if (is_array($link)) $link = $link[0]; // if it's an array, let's take the first element
		while (true) {
			switch ($link{0}) {
				/* lien externe */
				case '!' :
					$target = " TARGET='_blank'";
					$prefix = "";
					break;
					/* mise en valeur (exemple : acces restreint) */
				case '+' :
					$class = "<div class='emph'>"; // emph like in teX :p
					$classend = "</div>";
					break;
				case '@' :
					// vraiment rien faire
					$link = substr($link, 1);
					preg_match("/^[" . MENU_RESERVED_CHARS . "]+/", $link, $matchresult);
					$link = $matchresult[0];
					$prefix = "javascript: return false;";
					break;
				default :
					break 2;
			}
			$link = substr ($link, 1); /* on enlève la 1ere lettre */
		}

		$html_link = "$class<a href='$prefix$link' $target>$title$suffix</a>$classend\n";
		$this->_html_links[$title] = $html_link;
		return $html_link;
	}

	/* returns the prelude : links to various sections, for accessibility */
	function _getPrelude () {
		if (! empty($this->_texts['main'])) {
			$string = "<a href='#mainlink'>" . $this->_texts['main'] . "</a>";
		}

		if ((! empty($this->_texts['main'])) and (! empty($this->_texts['menu']))) {
			$string .= ' | ';
		}

		if (! empty($this->_texts['menu'])) {
			$string .= "<a href='#menulink'>" . $this->_texts['menu'] . "</a>";
		}

		if (! empty($string)) {
			$string = "<div id='prelude'>$string</div>";
		}

		return $string;
	}
	
	function header () {
		if ($this->_menu_place == MENU_PLACE_HEADER) {
			$menu = $this->_html_menu;
		} else {
			$menu = "";
		}

		$prelude = $this->_getPrelude();

		$result = <<<HTML
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>$this->_sitetitle : $this->_pagetitletext</title>
<!-- loaded by all browsers : -->
<link rel='stylesheet' type='text/css' href='{$this->_prefix}style.css' />
<!-- not loaded by IE : -->
<!-- disabled for validation <link rel='stylesheet' type='text/css' href='{$this->_prefix}stylemoz.css' disabled='disabled' /> -->
<!-- loaded by IE only : -->
<script language="JScript" type="text/jscript">
<!--
   if(document.all)
	   document.createStyleSheet("{$this->_prefix}styleie.css");
// -->
</script>
<!-- not loaded by NS4 and IE3 : -->
<style type="text/css">
<!--
  @import url({$this->_prefix}stylefancy.css);
-->
</style>
</head>
<body>
<div id='sitetitle'>
$prelude
<h1>$this->_sitetitle</h1>
<div id='logo'><span>logo</span></div>
</div>
$menu
<div id='main'>
<a name='mainlink' id='mainlink'></a>
<h2>$this->_pagetitle</h2>
HTML;
	return $result;
	}

	function footer () {
		$result = "</div>";
		if ($this->_menu_place == MENU_PLACE_FOOTER) {
			$result .= $this->_html_menu . "\n";
		}
		$result .= <<<HTML
</body>
</html>
HTML;
		return $result;
	}


	/*
	   this function will compute the path as a string (with A anchors)
	 */
	function compute_pagetitle() {

		if (empty($this->_path)) return "Invalid page.";

		$this->_pagetitletext = join (" &gt; ", $this->_path);
		if (empty($this->_menu)) {
			$pagetitle = $this->_pagetitletext;
		} else {
			$noeud = $this->_menu;	/* first node */
			$path = $this->_path;
			$lastpath = array_pop($path);
			foreach ($path as $title) {	/* for each item in the path */

				$item = $noeud[$title]; /* get the item for the current title */

				$pagetitle .= $this->_get_link_for_item ($title, $item[0], is_array($item[1]));
				$pagetitle .= " &gt; ";

				if (is_array($item[1])) {
					$noeud = $item[1]; /* go deeper, for the next item in $path */
				}
			}
			$pagetitle .= $lastpath;
		}

		$this->_pagetitle = $pagetitle;
		return true;
	}


	function get_items () {
		$noeud = $this->_menu;

		/* on descend dans l'arbre jusqu'à arriver à la bonne page */
		foreach ($this->_path as $title) {
			if (isset($noeud[$title][1]))
				$noeud = $noeud[$title][1];
			else return array();
		}

		/* pour chaque enfant de ce noeud, on set l'url à la clé du titre */
		foreach ($noeud as $title => $item) {
			if (is_array($item[0])) $item[0] = $item[0][0];
			$result[$title] = preg_replace('/^[' . MENU_RESERVED_CHARS . ']+/', '', $item[0]);
		}

		return $result;
	}

	function calculate () {
		return ($this->compute_path() & $this->compute_pagetitle() & $this->build_menu());
	}
}

