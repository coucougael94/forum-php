<?php
	require_once("connect.php");
?>
<style type="text/css">
	
	@media all and (min-device-width: 600px)
	{
	    #header_nav_li{display: block;}
	}
</style>
</head>
<body>
<header>
	<nav>
	<div class="header_div">
		<ul class="header_nav_ul">
			<li class="header_nav_li"><a href="topics.php" style="color: black;text-decoration: none;">Forum</a></li>
			<li class="header_nav_li"><a href="page.php" style="color: black;text-decoration: none;">Fiches</a></li>
			<?php
			if(!connect()){
			?>
			<li class="header_nav_li"><a href="inscription.php" style="color: black;text-decoration: none;">Inscription</a></li>
			<li class="header_nav_li"><a href="connexion.php" style="color: black;text-decoration: none;">Connexion</a></li>
			<?php
			}
			else
			{
			?>
			<li class="header_nav_li"><a href="deconnexion.php" style="color: black;text-decoration: none;">Deconnexion</a></li>
			<?php
			}
			?>
		</ul>
	</div>
	</nav>
</header>

