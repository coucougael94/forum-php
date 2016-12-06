<?php
require("connect.php");
//couleur = v ou r
//fiches l'id de la fiche
require("hair.php");
echo '<title>MSTSOR-pouces</title>';
require("header.php");

	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
$fiches_isset_req=$bdd->prepare("SELECT id FROM fiches WHERE id=?");
$fiches_isset_req->execute(array(htmlspecialchars($_GET['fiches'])));
$fiches_isset=$fiches_isset_req->fetch();
if($fiches_isset['id']!="")
{
if(connect()AND isset($_GET["couleur"])AND isset($_GET['fiches']))
{
	$id_user=$bdd->prepare("SELECT id FROM user WHERE pseudo=? AND pass=?");
	$id_user->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
	$user=$id_user->fetch();
	$pouces=$bdd->prepare("SELECT haut_bas FROM pouce WHERE id_user=?");
	$pouces->execute(array($user['id']));
	$pouce= $pouces->fetch();
	if($pouce['haut_bas']=="bas" OR $pouce['haut_bas']=="haut")//le client a déja mis un pouce, s'il en met un a l'envers de l'autre fois, cela modifiera le precedent pouce, pour le mettre dans la nouvelle position.
	{
		if(htmlspecialchars($_GET['couleur'])=="v"AND $pouce['haut_bas']=='bas')
		{
		$insert=$bdd->prepare("UPDATE pouce set haut_bas='haut' WHERE id_user=?");
		$insert->execute(array($user['id']));
		header('Location: fiches.php?id='.htmlspecialchars($_GET['fiches']));
		}
		elseif(htmlspecialchars($_GET['couleur'])=="r"AND $pouce['haut_bas']=='haut')
		{
		$insert=$bdd->prepare("UPDATE pouce set haut_bas='bas' WHERE id_user=?");
		$insert->execute(array($user['id']));	
		header('Location: fiches.php?id='.htmlspecialchars($_GET['fiches']));
		}
		else
		{
			echo '<p>Vous avez déja mis un pouce. Ce pouce n\'est donc pas pris en compte.</p>';
			?>
			<script type="text/javascript">
			setTimeout("document.location.replace('<?= 'fiches.php?id='.htmlspecialchars($_GET['fiches']) ?>')", '1100');
			</script>
			<?php
		}
	}
	else
	{
		$insert=$bdd->prepare("INSERT INTO pouce(id_user,id_fiche,haut_bas) VALUES(?,?,?)");
		if(htmlspecialchars($_GET['couleur'])=="v")
		{
			$insert->execute(array($user['id'],htmlspecialchars($_GET['fiches']),"haut"));
			header('Location: fiches.php?id='.htmlspecialchars($_GET['fiches']));
		}
		else if(htmlspecialchars($_GET['couleur'])=="r")
		{
			$insert->execute(array($user['id'],htmlspecialchars($_GET['fiches']),"bas"));	
			header('Location: fiches.php?id='.htmlspecialchars($_GET['fiches']));
		}
		else
		{
			echo '<p>1Vous avez déja mis un pouce. Ce pouce n\'est donc pas pris en compte.</p>';
			?>
			<script type="text/javascript">
			setTimeout("document.location.replace('<?= 'fiches.php?id='.htmlspecialchars($_GET['fiches']) ?>')", '1100');
			</script>
			<?php
		}
	}
}
else
	header('Location: fiches.php?id='.htmlspecialchars($_GET['fiches']));
}
else
	echo '<br/>
<b>Fatal error</b>: Uncaught exception \'PDOException\' with message \'SQLSTATE[42S22]: Column not found: 1054 Unknown column \'confirme\' in \'where clause\'\' in /index.php:104
Stack trace:
#0 /index.php(104): PDO-&gt;query(\'SELECT * FROM a...\')
#1 {main}
thrown in <b>/index.php</b> on line <b>104</b><br/>
';
include("footer.php");
?>
