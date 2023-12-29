<?php
/*

 Copyright 2003 Julien Wajsberg <flash@minet.net> and Association MiNET <minet@minet.net>

 This file is part of WMenu.

 WMenu is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, version 2 of the License.

 WMenu is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with WMenu; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 1-1307  USA

   WMenu

   Configuration file
   
   by Flash <flash@minet.net>
   March 2004

Caractères spéciaux (à spécifier _au début_ !):
   ! : ouvre dans une nouvelle fenetre
   + : met en valeur
   @ : un click sur cet item ne fera rien (meme pour des sous menus)

Notes :
   On peut utiliser un tableau comme nom de fichier, dans ce cas cet item
   sera reconnu pour chacun des noms de fichiers. Un click sur l'item
   dirigera alors la navigation vers le 1er element du tableau.

   Si un nom de fichier est vide, un click sur l'item correspondant entrainera :
   - si c'est un sous-menu, il ira sur la page empty, afin d'ouvrir le sous-menu
   - si c'est un item feuille, il ne fera rien (comme avec le caractere @)

Limitations :
   On ne peut pas utiliser deux fois le même titre.

   */

class SiteConfiguration {

	var $base_url = "/example2";
	var $site_title = "Site d'exemple de WMenu";
	var $texts = array (
			'main'	=> 'Aller au contenu', 
			'menu'	=> 'Aller au menu', 
			);

	var $menu_place = 'footer'; /* footer, header or none */

	var $menu = array(
			"Mon Home" => array("index.php", array(
					"Page 1" => array("page1.php"),
					"Page 2 longue" => array("page2.php", array(
							"Page 3" => array("page3.php"),
							)
						),
					"Page 4" => array("page4.php"),
					"Liens Google" => array ("", array (
							"Google" => array("http://www.google.fr"),
							/* exemples plus compliqués */
							"Google nouvelle page" => array("!http://www.google.fr"),
							"Google mis en valeur" => array("+http://www.google.fr"),
							"Google en valeur et nv page" => array("!+http://www.google.fr"),
							)
						),
					"Null" => array("@"),
					"Sous-menu empty" => array("", array(
							"page 5" => array("page5.php"),
							)
						),
					/* plusieurs pages pour le même item (utile pour un formulaire et son résultat) */
					"Item multiple" => array(array("page6.php", "page7.php")),
					"Liens de démo" => array("liens.php"),
			)
		)
	);

}

