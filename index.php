<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 09/11/15
 * Time: 08:45
 */

require('header.php');
 ?>


<div class="container-fluid">

    <div class="page-header">

        <h1>Feed NetFriends</h1>

    </div>

<?php
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=netfriends;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

// On récupère les 5 derniers billets
$req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5');

while ($donnees = $req->fetch())
{
    ?>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="thumbnail">
<!--                <img src="..." alt="...">-->
                <div class="caption">
                    <h3><?php echo htmlspecialchars($donnees['titre']); ?></h3>
                    <p>le <?php echo $donnees['date_creation_fr']; ?></p>
                    <p><a href="#" class="btn btn-primary" role="button">Comments</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>

<?php
        // Récupération des commentaires
        $req1 = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y\') AS date_commentaire_fr FROM comments WHERE id_billet = ? ORDER BY date_commentaire');
        $req1->execute(array($donnees['id']));

        while ($donnees1 = $req1->fetch())
        {
        ?>
        <hr>
        <p><strong><?php echo htmlspecialchars($donnees1['auteur']); ?></strong> le <?php echo $donnees1['date_commentaire_fr']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($donnees1['commentaire'])); ?></p>

        </div>
    </div>

        <?php
        } // Fin de la boucle des commentaires
        $req1->closeCursor();
        ?>
        <!--<p>
            <em><a href="comments.php?billet=<?php /*echo $donnees['id']; */?>">Commentaires</a></em>

        </p>-->
    </div>

    <?php
} // Fin de la boucle des billets
$req->closeCursor();
?>

</div>
<!-- Fin div container-->
<footer>
</footer>

</body>
</html>
