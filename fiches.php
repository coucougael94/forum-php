<?php
require("connect.php");
	include("hair.php");
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
if(!isset($_GET['id']) AND connect())
{
?>
<title>Créer une page</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<link rel="stylesheet" href="css/wbbtheme.css">
<script src="js/jquery.wysibb.min.js"></script>
<script src="js/fr.js"></script>
<script>
$(function() {
	var wbbOpt = {
		buttons: "bold,italic,underline,|,fontcolor,fontsize,|,smilebox,|,link,img,video",
		lang: "fr",
	  allButtons: {
	    code: {
	      hotkey: "ctrl+shift+3", //Add hotkey
	      transform: {
	        '<div class="mycode"><div class="codetop">Code:</div><div class="codemain">{SELTEXT}</div></div>':'[code]{SELTEXT}[/code]'
	      }
	    }
		}
	}
  $("#wysibb").wysibb(wbbOpt);
})
</script>

<style type="text/css">
	.c_div_msg
	{background-color: #CFCFCF; float: center; display: inline-block; width: 75%;}
	.msg_description
	{float: left; width: 120px;font-weight: bold;}
	.msg_msg
	{font-size: 19px;}
	.msg_com
	{font-size: 13px;font-weight: normal;}
	.msg_texte
	{ border: 4px solid #878787; border-radius: 5px;max-height: 1300px;overflow: scroll;}
	.msg_createur
	{ background-color: #38FF38; float: center; display: inline-block; width: 75%;}
</style>

<?php	include_once("header.php"); ?>
<form method="POST" action="creatfiches.php?commentaires=false">
<p><span style="font-size: 16px;">Le titre de votre nouvelle page :</span>
<input type="text" name="page_title"id="page_title"><br><br>
<textarea id="wysibb" name="wysibb"></textarea>
</p>
<input type="submit">
</form>
<?php
}
else if(!isset($_GET['id']) AND !connect())
{
include_once("header.php");
	echo '<p>Pour créer une fiche, veuillez vous <a href="connexion.php">connécter</a>.</p>';
}
else
{

//visionnage d'une fiche

	$fiches_req=$bdd->prepare("SELECT id,id_createur,titre,contenue FROM fiches WHERE id=?");
	$tmp=intval(htmlspecialchars($_GET['id']));
	$fiches_req->execute(array($tmp));
	$fiches=$fiches_req->fetch();
?>

<title>MSTSOR-<?= $fiches['titre'] ?></title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<link rel="stylesheet" href="css/wbbtheme.css">
<script src="js/jquery.wysibb.min.js"></script>
<script src="js/fr.js"></script>
<script>
$(function() {
	var wbbOpt = {
		buttons: "bold,italic,underline,|,fontcolor,fontsize,|,smilebox,|,link,img,video",
		lang: "fr",
	  allButtons: {
	    code: {
	      hotkey: "ctrl+shift+3", //Add hotkey
	      transform: {
	        '<div class="mycode"><div class="codetop">Code:</div><div class="codemain">{SELTEXT}</div></div>':'[code]{SELTEXT}[/code]'
	      }
	    }
		}
	}
  $("#wysibb").wysibb(wbbOpt);
})
</script>

<style type="text/css">
	.c_div_msg
	{background-color: #CFCFCF; float: center; display: inline-block; width: 75%;}
	.msg_description
	{float: left; width: 120px;font-weight: bold;}
	.msg_msg
	{font-size: 19px;}
	.msg_com
	{font-size: 13px;font-weight: normal;}
	.msg_texte
	{ border: 4px solid #878787; border-radius: 5px;max-height: 1300px;overflow: scroll;}
	.msg_createur
	{ background-color: #38FF38; float: center; display: inline-block; width: 75%;}
</style>
<?php
include_once("header.php");
	if($fiches['id_createur']=='')
	{
		echo '<p> Aucune fiche n\'as pu être trouver. <a href="fiches.php">Créer sa propre fiche</a>.</p>';
	}
	else
	{
		require_once("JBBCode/Parser.php");
		include("jbbvideo.class.php");
		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
		$youtubeEmbed = new YoutubeEmbed();
		$parser->addCodeDefinition($youtubeEmbed);

		$createur_req=$bdd->prepare("SELECT pseudo FROM user WHERE id=?");
		$createur_req->execute(array($fiches['id_createur']));
		$createur=$createur_req->fetch();
		$parser->parse($fiches['contenue']);
		?>
		<h2><?= $fiches['titre'] ?></h2>
		<p style="font-size: 11px;">Cet fiche a été creer par <?= $createur['pseudo'] ?></p>
		<div style="border: 2px solid black;border-radius: 3px;margin-top: 10px;margin-bottom: 10px;">
			<p><?= $parser->getAsHtml() ?></p>
		</div>
		<?php
		$pouce_reqr=$bdd->query("SELECT id_fiche FROM pouce WHERE haut_bas='bas'AND id_fiche='".$fiches['id']."'");
		$pouce_reqv=$bdd->query("SELECT id_fiche FROM pouce WHERE haut_bas='haut'AND id_fiche='".$fiches['id']."'");
		if(connect())
		{
		?>
		<p id="ev">Note: Je recommande: <?= $pouce_reqv->rowCount() ?> &nbsp;&nbsp;&nbsp;Je ne recommande pas: <?= $pouce_reqr->rowCount() ?> <a href="pouces.php?couleur=v&amp;fiches=<?= $fiches['id'] ?>"><img src="valide.png" alt="+1" title="Je recommande" style="width: 20px; height:20px;"></a>&nbsp;&nbsp;<a href="pouces.php?couleur=r&amp;fiches=<?= $fiches['id'] ?>"><img src="invalide.png" alt="-1" title="Je ne recommande pas" style="width: 20px; height:20px;"></a></p>
		<?php
		}
		?>
		<p style="font-size: 20px;font-weight: bold;">Commentaires : </p>
		<?php


		//commentaires
if(!isset($_GET['page']))
	$sujet_req=$bdd->prepare('SELECT id,id_user, commentaires, date_post FROM commentaires_fiches WHERE id_fiche=? ORDER BY date_post LIMIT 0,10');
else
	$sujet_req=$bdd->prepare('SELECT id,id_user, commentaires, date_post FROM commentaires_fiches WHERE id_fiche=? ORDER BY date_post LIMIT '.htmlspecialchars($_GET['page']).','.htmlspecialchars($_GET['page'])+10);
$sujet_req->execute(array(intval(htmlspecialchars($_GET['id']))));



	for($i=0;$sujet=$sujet_req->fetch();$i++)
	{
		$nom_req=$bdd->prepare('SELECT pseudo,usertype FROM user WHERE id=?');
		$nom_req->execute(array($sujet['id_user']));
		$nom=$nom_req->fetch();
		$datemsg = new DateTime($sujet['date_post']);
		if($sujet['id_user']==$fiches['id_createur'])
			echo '<div id="msg'.$i.'" class="msg_createur">';
		else
			echo '<div id="msg'.$i.'" class="c_div_msg">';
		?>
	<p><div class="msg_description"><span class="msg_com">DE : </span><?= $nom['pseudo'] ?> <span class="msg_com">LE : </span><?= date_format($datemsg, 'd/m/Y à H:i:s') ?> <?= $nom['usertype'] ?></div>
		<span class="msg_msg">Message : </span><br><div class="msg_texte"><p><?php
		$parser->parse($sujet['commentaires']);
	 echo $parser->getAsHtml();?></p></div>
	</p>
	</div>
	<hr>
		<?php
		if(!isset($_GET['page']))
		{
			if($i> 10)
			{
				echo '<p><a href="forum.php?id='.$_GET['id'].'&page=2">Page suivante -></a></p>';
				break;
			}
		}
		else
		{
			$temp=intval(htmlspecialchars($_GET['page']))+1;
			if($i> 10)
				echo '<p><a href="forum.php?id='.$_GET['id'].'&page='.$temp.'">Page suivante -></a></p>';
		}
	}
	if(connect() OR $reponse['open']!="ouvert")
	{
	?>
	<form method="POST" action="creatfiches.php?commentaires=true&amp;fiche=<?= $_GET['id'] ?>">
	  <textarea id="wysibb" name="wysibb"></textarea><br><br><input type="submit" value="Déposer le comentaire" style="background-color: #4444FF;">
	</form>
	<?php
	}
}
}//
?>

<?php
	include_once("footer.php");
?>