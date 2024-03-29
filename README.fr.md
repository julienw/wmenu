WMenu - Un menu pour les sites PHP
==================================
Version 2.0
----------------------------------

Attention: ce projet est assez ancien et peut ne pas convenir aux standards
actuels. Notamment, il n'a pas été testé (encore) avec PHP5.

1 - Le but
----------
Plutôt que de développer pour chaque site un nouveau système de menu
mal fichu, il est plus intéressant d'en développer un une bonne fois pour
toutes, de manière assez flexible pour s'adapter à beaucoup de configurations.
Par ailleurs, cela permet de le développer indépendamment d'un site
particulier, et donc d'augmenter ses fonctionnalités.

2 - Fonctionnalités retenues
----------------------------
- XHTML1 + CSS ;
- un fichier de configuration descriptif ;
- il s'adapte automatiquement à la page d'où il est appelé, mais on peut
également "forcer" une page ;
- accessible.

3 - Installation
----------------
Deux cas sont à considérer.

 a - Un site uniquement
 - Copier les fichiers utils.inc.php et wmenu.class.inc.php, ainsi
 qu'éventuellement empty.inc.php, dans le répertoire include de votre projet.
 Il faut que ce répertoire soit dans la variable de PHP include_path; vous
 pouvez la configurer dans un .htaccess par exemple :

 php_value include_path "repertoire include:/usr/share/pear:."

 - Copier le fichier stylemenu.css, ainsi que styleie.css vers le répertoire de
 vos styles, et importez le premier dans votre fichier de style principal
 (il devra s'appeler style.css):

 @import url(stylemenu.css);

 - Vous pouvez enfin copier le fichier empty.php vers le répertoire racine du
 site, si vous en avez l'usage.

 b - Plusieurs sites sur la même machine
 Au lieu de copier plusieurs fois les fichiers pour chaque site, il est
 possible de les mettre dans un même répertoire, et de positionner la variable
 include_path :

 php_value include_path "repertoire include specifique:repertoire include general:/usr/share/pear:."

 Par ailleurs, vous pouvez faire un lien symbolique des fichiers CSS des
 différents sites vers un même CSS se trouvant dans un répertoire commun, ceci
 afin de faciliter les mises à jour.

4 - Configuration
-----------------
Elle se fait via un unique fichier menuconf.inc.php, à mettre dans un des
répertoires include de la variable include_path.

Ce fichier définit une classe, par exemple SiteConfiguration. Cette classe va
contenir 4 variables :
- $base_url définit le répertoire de base de l'URL du site ;
- $site_title définit le nom du site, qui sera affiché dans la barre
supérieure
- $texts est un tableau associatif, qui définit certaines chaînes de
caractères affichées. Pour l'instant, il n'utilise que deux clefs :
 + main : intitulé du lien vers le contenu ;
 + menu : intitulé du lien vers le menu ;
- $menu est un arbre mis sous forme de tableaux, qui définit le menu lui-même.
 + Chaque clé est un intitulé, et sa profondeur détermine la profondeur de
 l'élément. À un intitulé est associé un tableau de deux éléments: le premier
 est de type "page", le 2e est de type "noeud".
 + Un élément de type "page" est soit une chaine de caractère définissant un
 lien HTML, soit un tableau contenant plusieurs éléments de type "page". Si
 c'est un tableau, alors le lien pointera vers son 1er élément, mais la visite
 de n'importe laquelle de ces pages mettra cet intitulé en surbrillance.
 + Un élément de type "noeud" est un arbre à part entière.
 + Un lien HTML peut être relatif ou absolu. Par ailleurs, il peut être
 préfixé d'un ou plusieurs caractéres spéciaux (voir plus bas).

- Les caractères spéciaux :
   ! : ouvre dans une nouvelle fenêtre
   + : met en valeur
   @ : un clic sur cet élément ne fera rien (meme pour des sous-menus)

- Si un lien est une chaîne vide, alors :
 + si c'est un sous-menu, il ira sur la page "empty", afin d'ouvrir le sous-menu
 + si c'est un élément feuille, il ne fera rien (comme avec le caractere spécial @)

Voyez l'exemple proposé pour plus de clarté ;)

5 - Utilisation
---------------

a - Fonctionnement de base
Chaque fichier PHP doit commencer par :

<?php
require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();
?>

Cela va générer le menu html, de manière interne à la classe.
Après quoi, la méthode header() retourne l'en-tête de la page :

<?php
print $menu->header();
?>

De même, la fonction footer() retourne le pied de la page :

<?php
print $menu->footer();
?>


b - Configurer le fonctionnement
Deux comportements peuvent être modifiés.

 - Forcer la surbrillance d'un élément du menu. Pour cela, il suffit d'appeler
 la méthode setMenuType avant calculate :
<?php
$menu = new WMenu (new SiteConfiguration);
$menu->setMenuType(MENU_TYPE_TITLE, 'Intitulé');
$menu->calculate();
?>

 La constante MENU_TYPE_TITLE définit un calcul par intitulé, alors que la
 constante par défaut MENU_TYPE_URL fera le calcul en se basant sur l'url de
 la page.

 - Afficher le menu dans le header au lieu du footer. Cela se fait dans le
 fichier de configuration menuconf.inc.php, en définissant la variable
 $menu_place.
 Si elle n'est pas définie, le menu se mettra dans le pied de page : cela
 permet de débuter tout de suite par le contenu pour les navigateurs non
 graphiques.
 Les constantes possibles sont :
 + 'footer' (par défaut) pour le placer dans le pied de page ;
 + 'header' pour le placer dans l'en-tête de la page ;
 + 'none' pour ne pas le placer du tout.

6 - Changer l'apparence
-----------------------
Puisque le menu est en XHTML, toute l'apparence est définie dans le fichier CSS
stylemenu.css (et dans une moindre mesure, dans styleie.css, pour les styles
spécifiques à Internet Explorer). Ainsi, il suffit de modifier ce fichier pour
faire correspondre le style du menu à votre site.

Pour modifier un peu plus en profondeur, il est possible de modifier
directement les méthodes header() et footer(), pour changer respectivement
l'en-tête et le pied de page. Il est évidemment possible également d'hériter
de la classe WMenu :-)

Il faut noter par ailleurs que l'objet passé en argument au constructeur de
WMenu peut très bien être construit dynamiquement, au lieu d'être défini
statiquement dans un fichier inclus. Cela peut être utile pour un site
conséquent, avec plusieurs sections, auxquelles correspond un menu 
différent.

