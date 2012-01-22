<?php

require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();

print $menu->header();

?>
<p>
Pas de texte.</p>

<p>Le (X)HTML s�mantique</p>

<p>Et malgr� cela, en utilisant des balises comme &lt;i&gt; (pour italique) ou &lt;b&gt; (pour bold ou gras), les webmestres
choissent (souvent inconsciemment, port�s par leur logiciels WYSIWYG) d'ignorer ces possibilit�s en admettant que le caract�re
italique ou gras d'un mot est forc�ment compr�hensible par le destinataire de la page web.</p>

<p>Mais qu'en est-il de l'aveugle qui lit cette m�me page web gr�ce � un lecteur d'�cran. A la voix, que signifie un mot en
italique, et surtout comment le retranscrire pour que l'auditeur comprenne que ce mot a une signification s�mantique particuli�re
? Si le webmestre voulait dire que le mot doit �tre accentu�, peut �tre que le lecteur pourrait hausser le ton. Mais peut �tre que
le webmestre voulait signaler le titre d'un livre, auquel cas hausser le ton va emp�cher une bonne compr�hension du contenu.</p>

<p>Avec cet exemple simple, on se rend compte que ce que le webmestre voulait faire passer, c'est que le mot entre les
balises &lt;i&gt;...&lt;/i&gt; a un sens particulier. Mais alors, ce que le webmestre aurait du faire, c'est coder
pr�cis�ment cela. Et il existe en (X)HTML de nombreuses balises, dites s�mantiques, qui transmettent du sens au lieu
d'un formattage graphique. Par exemple, la balise &lt;em&gt; doit �tre utilis�e pour remplacer &lt;i&gt; lorsque la
signification voulue est l'emphase. Pour un titre de livre, on pourrait utiliser la balise &lt;cite&gt;, et ainsi de suite.</p>

<p>Sans plus rentrer dans des d�tails d�j� grandement et clairement expliqu�s, utiliser du (X)HTML s�mantique est tr�s important
pour le web en g�n�ral et vos documents en particulier. C'est en particulier tr�s important pour l'accessibilit� des pages web aux
handicap�s et aux logiciels et mat�riels alternatifs.</p>

<p>Le probl�me est qu'il n'y a pas beaucoup de logiciels qui font cela automatiquement. La plupart des �diteurs graphiques WYSIWYG,
	de part leur nature m�me, font les choses graphiquement. Alors pour ceux qui veulent faire l'effort, je vous propose une page
	A4 recto verso qui recense les principales balises s�mantiques, ainsi qu'une table de conversion basique des balises
	graphiques vers des balises s�mantiques ou des styles CSS. Cela ne remplace pas une connaissance pointue des sp�cifications
	HTML, mais cela peut aider � trouver la bonne balise au moment o� vous en avez besoin (pour �tre s�r par exemple de ne pas
			utiliser une balise &lt;div&gt; au mauvais moment).</p>

<?php
print $menu->footer();
?>

