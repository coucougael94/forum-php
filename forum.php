<?php
	require("connect.php");
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 


	if(!isset($_GET['viewtopic'])AND !isset($_GET['createtopic']))
		header("Location: forum.php?createtopic=true");
	else if(isset($_GET['createtopic'])AND htmlspecialchars($_GET['createtopic'])=="true")
		$reponse['titre']="Creer un nouveau sujet.";
	else
	{
	$donnees=$bdd->prepare("SELECT titre,open,id_createur,id FROM forum WHERE id=?");
	$donnees->execute(array($_GET['viewtopic']));
	$reponse = $donnees->fetch();
	if($reponse['titre']=="")
		header("Location: index.php");
	}
	require_once("JBBCode/Parser.php");

	include_once("hair.php");
	//nGouessant94
	//yDanileRegio2N
?>
	<title>MSTS OR !</title>
<title>MSTSOR-<?php echo $reponse['titre'];?></title>
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

<?php
	include_once("header.php");
	if(!isset($_GET['viewtopic'])AND !isset($_GET['createtopic']))
		header("Location: forum.php?createtopic=true");
	else if(isset($_GET['createtopic'])AND htmlspecialchars($_GET['createtopic'])=="true")
	{
		if(!connect())
			header("Location: connexion.php");
		?>
		<style type="text/css">
			#i_titre_topic{height: 30px; width: 40%; font-size: 16px;margin-left: 15px;}
			.label{margin-left: 15px; font-size: 19px;}
			.c_wysibb{margin-left: 15px; max-width: 56%;margin-right: auto;}
		</style>
		<h1 style="color: blue; margin-left: 12px;">Créer un nouveau sujet</h1>
		<form method="POST" action="creattopic.php?commentaires=false">
		<p>
		<label for="i_titre_topic" class="label">Donner un nom au topic que vous souhaiter créer : </label><br><input type="text" name="titre_topic" id="i_titre_topic" placeholder="Insérer le titre du topic ici"><br><br>
		<span style="font-size: 19px;margin-left: 15px;">Contenue du sujet :</span><br>
		<div class="c_wysibb"><textarea id="wysibb" maxlength="10000" name="wysibb" ></textarea><br>
		<input type="submit" value="créer le sujet" style="background-color: #4444FF;">
		</div>
		</p>
		</form>
		<?php
	}
	else
	{
		?>
<h1>Sujet : <?php echo $reponse['titre'];?></h1>
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
if($reponse['open']=="ouvert")
	echo '<p style="padding-left: 45px;padding-top: 6px;padding-bottom: 6px;background-color: #22DD33; border-radius: 2px;">Ce sujet est ouvert</p>';
if(!isset($_GET['page']))
	$sujet_req=$bdd->prepare('SELECT id,id_user, message, date_post FROM message_forum WHERE id_topic=? ORDER BY date_post LIMIT 0,10');
else
	$sujet_req=$bdd->prepare('SELECT id,id_user, message, date_post FROM message_forum WHERE id_topic=? ORDER BY date_post LIMIT '.htmlspecialchars($_GET['page']).','.htmlspecialchars($_GET['page'])+10);
$sujet_req->execute(array(intval(htmlspecialchars($_GET['viewtopic']))));

	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
	$builder = new JBBCode\CodeDefinitionBuilder("size",'<span style="font-size: {option}px;">{param}</span>');
	$builder->setUseOption(true);
	$parser->addCodeDefinition($builder->build());


class video extends JBBCode\CodeDefinition {
 
    public function __construct()
    {
        parent::__construct();
        $this->setTagName("video");
    }
 
    public function asHtml(JBBCode\ElementNode $el)
    {
        $content = "";
        foreach($el->getChildren() as $child)
            $content .= $child->getAsBBCode();
             
        $foundMatch = preg_match('/v=([A-z0-9=\-]+?)(&.*)?$/i', $content, $matches);
        if(!$foundMatch)
            return $el->getAsBBCode();
        else
            return "<iframe width=\"640\" height=\"390\" src=\"http://www.youtube.com/embed/".$matches[1]."\" frameborder=\"0\" allowfullscreen></iframe>";
    }
}
$video = new video();
$parser->addCodeDefinition($video);

for($i=0;$sujet=$sujet_req->fetch();$i++)
{
	$nom_req=$bdd->prepare('SELECT pseudo,usertype FROM user WHERE id=?');
	$nom_req->execute(array($sujet['id_user']));
	$nom=$nom_req->fetch();
	$datemsg = new DateTime($sujet['date_post']);
	if($sujet['id_user']==$reponse['id_createur'])
		echo '<div id="msg'.$i.'" class="msg_createur">';
	else
		echo '<div id="msg'.$i.'" class="c_div_msg">';
$parser->parse($sujet['message']);
	?>
<p><div class="msg_description"><span class="msg_com">DE : </span><?= $nom['pseudo'] ?> <span class="msg_com">LE : </span><?= date_format($datemsg, 'd/m/Y à H:i:s') ?> <?= $nom['usertype'] ?></div>
	<span class="msg_msg">Message : </span><br><div class="msg_texte"><p><?= $parser->getAsHtml() ?></p></div>
</p>
</div>
<hr>
	<?php
	if(!isset($_GET['page']))
	{
		if($i> 10)
		{
			echo '<p><a href="forum.php?viewtopic='.$_GET['viewtopic'].'&page=2">Page suivante -></a></p>';
			break;
		}
	}
	else
	{
		$temp=intval(htmlspecialchars($_GET['page']))+1;
		if($i> 10)
			echo '<p><a href="forum.php?viewtopic='.$_GET['viewtopic'].'&page='.$temp.'">Page suivante -></a></p>';
	}
}
if(connect() OR $reponse['open']!="ouvert")
{
?>
<form method="POST" action="creattopic.php?commentaires=true&amp;topic=<?= $_GET['viewtopic'] ?>">
  <textarea id="wysibb" name="wysibb"></textarea><br><br><input type="submit" value="Déposer le comentaire" style="background-color: #4444FF;">
</form>
<?php
}
else
	echo '<p> Vous devez vous connécter ou vous inscrire pour déposé un commentaires</p>';
}
include_once("footer.php");
?>
