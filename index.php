<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 09/11/15
 * Time: 08:45
 */

require('header.php');
 ?>

<div class="row post">
    <div class="col-md-offset-3 col-md-6">
        <div class="thumbnail">
            <div class="caption form-group">
                <h2>Write a new post</h2>
                    <form method="POST" action="">
                       <label>Pseudo</label>
                        <input class="form-control" type="text" name="titre" placeholder="Pseudo" /><br/>
                        <label>Content</label>
                        <textarea class="form-control" type="text" size="70" name="contenu" placeholder="What's on your mind ?" height="50px"></textarea><br/>
                        <input class="form-control" type="submit" value="Envoyer" />

                    </form>
            </div>
        </div>
    </div>
</div>

<?php

//Ajout d'un post dans la base de donnée

// Connexion à la base de données

    $bdd = new PDO('mysql:host=localhost;dbname=netfriends;charset=utf8', 'root', 'root');

if(isset($_POST['titre']) AND isset($_POST['contenu']))
{
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $reqpost = $bdd->prepare("INSERT INTO billets(titre, contenu) VALUES ('$titre','$contenu') ");
    $reqpost->execute(array($titre, $contenu));
    header('location:index.php');

}

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

// On récupère les 5 derniers posts
$req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 10');

// Boucle pour récupérer les posts
while ($donnees = $req->fetch())
{
    ?>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="thumbnail">
                <div class="caption">
                    <h3><?php echo htmlspecialchars($donnees['titre']); ?></h3>
                    <p>le <?php echo $donnees['date_creation_fr']; ?></p>
                    <p><?php echo $donnees['contenu']; ?></p>
                    <p><a href="#" class="btn btn-primary" role="button">Comments</a> <a href="#" class="btn btn-default" role="button">Add comment</a></p>
                </div>
                <hr>

                <div class="flip">Show comments</div>
                <div class="write">

<?php
        // Récupération des commentaires
        $req1 = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y\') AS date_commentaire_fr FROM comments WHERE id_billet = ? ORDER BY date_commentaire');
        $req1->execute(array($donnees['id']));

        //Boucle des commentaires
        while ($donnees1 = $req1->fetch())
        {
        ?>

        <p><strong><?php echo htmlspecialchars($donnees1['auteur']); ?></strong> le <?php echo $donnees1['date_commentaire_fr']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($donnees1['commentaire'])); ?></p>



            <?php
        } // Fin de la boucle des commentaires
$req1->closeCursor();
?>
            </div><!--Referme la div de tous les commentaires-->

                <div class="flip">Add comment</div>
                <!--Bouton pour afficher l'ajout de commentaire-->

                <!-- ADD COMMENT -->
                <div class="write caption form-group col-md-6">
                    <h2>Write a new comment</h2>
                    <form method="POST" action="">
                        <label>Auteur</label>
                        <input class="form-control" type="text" name="auteur" placeholder="Auteur" /><br/>
                        <label>Content</label>
                        <input class="form-control" type="text" size="70" name="commentaire" placeholder="What's on your mind ?" /><br/>
                        <input class="form-control" type="submit" value="Envoyer" />

                    </form>
                </div>

                <!--END NEW COMMENT-->

                <?php

                //Ajout d'un commentaire dans la base de donnée

                // Connexion à la base de données

                $bdd = new PDO('mysql:host=localhost;dbname=netfriends;charset=utf8', 'root', 'root');


                if(isset($_POST['auteur']) AND isset($_POST['commentaire']))
                {
                    $auteur = $_POST['auteur'];
                    $commentaire = $_POST['commentaire'];
                    $idpost = $donnees['id'];
                    $reqcom = $bdd->prepare("INSERT INTO comments(id_billet, auteur, commentaire) VALUES ('$idpost','$auteur','$commentaire') ");
                    $reqcom->execute(array($idpost, $auteur,$commentaire));

                }

                ?>
        </div

    </div>

    <?php
} // Fin de la boucle des posts
$req->closeCursor();
?>
</div>
</div>
<!-- Fin div container-->
<footer>
</footer>
<script src="js/jquery-2.1.4.min.js"></script>
<script>
    $(function(){
        $(".flip").on("click",function(){
            $(this).next().slideToggle();
        });
    });
</script>

</body>
</html>
