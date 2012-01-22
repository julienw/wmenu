<?php

require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();

print $menu->header();

?>
<p>
Pas de texte.</p>

<p>Le (X)HTML sémantique</p>

<p>Et malgré cela, en utilisant des balises comme &lt;i&gt; (pour italique) ou &lt;b&gt; (pour bold ou gras), les webmestres
choissent (souvent inconsciemment, portés par leur logiciels WYSIWYG) d'ignorer ces possibilités en admettant que le caractère
italique ou gras d'un mot est forcément compréhensible par le destinataire de la page web.</p>

<p>Mais qu'en est-il de l'aveugle qui lit cette même page web grâce à un lecteur d'écran. A la voix, que signifie un mot en
italique, et surtout comment le retranscrire pour que l'auditeur comprenne que ce mot a une signification sémantique particulière
? Si le webmestre voulait dire que le mot doit être accentué, peut être que le lecteur pourrait hausser le ton. Mais peut être que
le webmestre voulait signaler le titre d'un livre, auquel cas hausser le ton va empêcher une bonne compréhension du contenu.</p>

<p>Avec cet exemple simple, on se rend compte que ce que le webmestre voulait faire passer, c'est que le mot entre les
balises &lt;i&gt;...&lt;/i&gt; a un sens particulier. Mais alors, ce que le webmestre aurait du faire, c'est coder
précisément cela. Et il existe en (X)HTML de nombreuses balises, dites sémantiques, qui transmettent du sens au lieu
d'un formattage graphique. Par exemple, la balise &lt;em&gt; doit être utilisée pour remplacer &lt;i&gt; lorsque la
signification voulue est l'emphase. Pour un titre de livre, on pourrait utiliser la balise &lt;cite&gt;, et ainsi de suite.</p>

<p>Sans plus rentrer dans des détails déjà grandement et clairement expliqués, utiliser du (X)HTML sémantique est très important
pour le web en général et vos documents en particulier. C'est en particulier très important pour l'accessibilité des pages web aux
handicapés et aux logiciels et matériels alternatifs.</p>

<p>Le problème est qu'il n'y a pas beaucoup de logiciels qui font cela automatiquement. La plupart des éditeurs graphiques WYSIWYG,
	de part leur nature même, font les choses graphiquement. Alors pour ceux qui veulent faire l'effort, je vous propose une page
	A4 recto verso qui recense les principales balises sémantiques, ainsi qu'une table de conversion basique des balises
	graphiques vers des balises sémantiques ou des styles CSS. Cela ne remplace pas une connaissance pointue des spécifications
	HTML, mais cela peut aider à trouver la bonne balise au moment où vous en avez besoin (pour être sûr par exemple de ne pas
			utiliser une balise &lt;div&gt; au mauvais moment).</p>

<?php
print $menu->footer();
?>

