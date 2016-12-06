<?php
	require("connect.php");
	include_once("hair.php");
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
?>
<title>liste des fiches</title>
<style type="text/css">
	table{border-collapse: collapse;margin-top: 10px;}
	th,td{border: 2px solid black; padding: 5px;}
	th{background-color: blue;}
	label,input{height: 25px; font-size: 20px;}
	form{margin-top: 10px;}
</style>
<?php
	include_once("header.php");
?>
<form method="GET" action="">
<label for="search_page">Rechercher une page : </label><input type="search" name="search" id="search_page"><input type="submit" style="height: 27px; background-color: #88AA88;" value="Rechercher !"></form>
<table><tr><th>Titre de la fiche</th><th>Créateur</th><th>Nombre de recommandations</th></tr><?php
if(!isset($_GET['page']))
{
	if(isset($_GET['search']))
	{
		$forum_req=$bdd->query("SELECT id_createur,titre,id FROM fiches WHERE titre LIKE '%".htmlspecialchars($_GET['search'])."%' ORDER BY id DESC LIMIT 0,10");
	}
	else
		$forum_req=$bdd->query("SELECT id_createur,titre,id FROM fiches ORDER BY id DESC LIMIT 0,10");
}
else
{
	if(isset($_GET['search']))
	{
		$forum_req=$bdd->prepare("SELECT id_createur,titre,id FROM fiches WHERE titre LIKE '%".htmlspecialchars($_GET['search'])."%' ORDER BY id DESC LIMIT ?,?");
		$forum_req->execute(array(intval(htmlspecialchars($_GET['page'])),intval(htmlspecialchars($_GET['page']))+10));
	}
	else
		$forum_req=$bdd->query("SELECT id_createur,titre,id FROM fiches ORDER BY id DESC LIMIT 0,10");
}
$prochainepage="";//on prepare la chaine qui va affiché un lien vers la prochaine page
$nbPageaffiche=0;
	for($i=0;$forum=$forum_req->fetch();$i++)
	{
		$user_req=$bdd->prepare('SELECT pseudo FROM user WHERE id=?');
		$user_req->execute(array($forum['id_createur']));
		$user=$user_req->fetch();

		$pouce_req=$bdd->prepare('SELECT pseudo FROM pouce WHERE haut_bas="haut" id_fiche='.$forum['id']);


		echo '<tr><td><a href="fiches.php?id='.$forum['id'].'">'.$forum['titre'].'</a></td><td>'.$user['pseudo'].'</td><td>'.$pouce_req->rowCount();
		echo '</td></tr>';
		if($i>10)
		{
			$pagetmp=intval(htmlspecialchars($_GET['page']));
			$pagetmp=$pagetmp+1;//ou $page_tmp++
			if(!isset($_GET['page']))
				$prochainepage='<a href="topics.php?page=2">Page suivante</a>';
			else
				$prochainepage='<a href="topics.php?page='.$pagetmp.'">Page suivante</a>';
			break;
		}
		$nbPageaffiche++;
	}
echo '</table>';
echo $prochainepage;
if($nbPageaffiche<9)
	echo '<p>Il n\'y as plus de résultat a afficher. Vous pouvez créer votre page <a href="fiches.php">ici</a>.</p>';
	include_once("footer.php");

?>
