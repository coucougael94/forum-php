<?php
	require("connect.php");
if(isset($_POST['pseudo'])AND isset($_POST['mdp']))
{
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
	$fiches_isset_req=$bdd->prepare("SELECT pseudo, pass FROM user WHERE pseudo=? AND pass=?");
	$mdp=hash('haval224,4',$_POST['mdp']);
	$fiches_isset_req->execute(array(htmlspecialchars($_POST['pseudo']),$mdp));
	$fiches_isset=$fiches_isset_req->fetch();
	if($fiches_isset['pseudo']!="")
	{
	$_SESSION['pseudo']=htmlspecialchars($_POST['pseudo']);
	$_SESSION['pass']=$mdp;

	setcookie('pseudo', $_SESSION['pseudo'], time() + 365*24*3600, null, null, false, true);
	setcookie('pass', $mdp, time() + 365*24*3600, null, null, false, true);
	header("Location: index.php");
	}
	else
		$erreur="<p>Mauvais identifiant ou mot de passe.</p>";
}
include("hair.php");?>
<title>MSTSOR-Connexion</title>
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
include("header.php");
if(!connect())
{
?>
	<div class="block">
	<form method="POST" action="">
		<label for="pseudo">Pseudo : </label><input class="input_text" type="text" name="pseudo" id="pseudo"><br>
		<label for="mdp">Mot de passe : </label><input class="input_text" type="password" name="mdp" id="mdp"><br>
		<?php
		if(isset($erreur))
			echo $erreur;
		?>
		<input type="submit">
	</form>
	</div>
</body>
<?php
}
else
	echo '<a>Vous êtes déja connécter. <a href="deconnexion.php">Se deconnecter</a></p>';
include("footer.php");
?>
