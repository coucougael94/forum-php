<?php
	require("connect.php");
	include_once("hair.php");
?>
<title>MSTSOR-Inscription</title>
<style type="text/css">
	.block{margin-top: 20px;}
	label{ display: inline-block;width: 220px;}
	input{margin-top: 5px;}
	.input_text{ display: inline-block;width: 170px; background-color: #EDEDED; border: 2px solid black;border-radius: 3px; height: 18px; font-size: 14px;font-weight: bold;}
	#mail{ display: inline-block;width: 168px;}
	#submit{margin-left: 220px;}
	fieldset{display: inline-block;border: 3px solid rgb(192,192,192); border-radius: 3px;margin-bottom: 20px;}
	legend{font-size: 18px;}
	@media all and (max-device-width: 450px)
	{
	    #submit{margin-left: 10px;}
	}
</style>
<?php
require("header.php");

if(isset($_POST['pseudo'])AND isset($_POST['mail'])AND isset($_POST['mdp'])AND isset($_POST['mdpconf']))
{
	if(htmlspecialchars($_POST['mdp'])==htmlspecialchars($_POST['mdpconf']))
	{
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
	$issetthis_req=$bdd->prepare("SELECT id FROM user WHERE pseudo=?");
	$issetthis_req->execute(array(htmlspecialchars($_POST['pseudo'])));
	$issetthis=$issetthis_req->fetch();
	if($issetthis['id']=="")
	{
		$mdp=hash('haval224,4',$_POST['mdp']);
		$insert_req=$bdd->prepare("INSERT INTO user(pseudo,mail,pass,usertype,inscriptionDate) VALUES(?,?,?,'Utilisateur normal',NOW())");
		$insert_req->execute(array(htmlspecialchars($_POST['pseudo']),htmlspecialchars($_POST['mail']), $mdp));
		$erreur="<p>Vous êtes inscrit !</p>";
	}
	else
		$erreur="<p>Un utilisateur possède déja ce pseudo.</p>";
}
else
	$erreur="<p>Les mots de passes sont différents.</p>";
}

if(!connect())
{
?>
	<div class="block">
	<form method="POST" action="">
		<fieldset>
		<legend>Formulaire d'inscription : </legend>
			<label for="pseudo">Pseudo : </label><input class="input_text" type="text" name="pseudo" id="pseudo"><br>
			<label for="mail">Mail : </label><input class="input_text" type="mail" name="mail" id="mail"><br>
			<label for="mdp">Mot de passe : </label><input class="input_text" type="password" name="mdp" id="mdp"><br>
			<label for="mdpconf">Confirmation du mot de passe : </label><input class="input_text" type="password" name="mdpconf" id="mdpconf">
			<?php
			if(isset($erreur))
				echo "<br>".$erreur;
			?><br>
			<input type="submit" value="s'inscrire !" id="submit">
		</fieldset>
	</form>
	</div>
</body>
<?php
}
else
	echo '<a>Vous êtes déja connécter. <a href="deconnexion.php">Se deconnecter</a></p>';
include("footer.php");
?>
