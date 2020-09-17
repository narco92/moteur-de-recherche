<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <title>Administrateur</title>


</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <div class="container-fluid">

                    <br>
                    <h1 class="h3 mb-2 text-gray-800">Documents</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <form method="POST" action="" enctype="multipart/form-data" id="ajout">
                                <input type="file" id="doc" name="doc" accept="html">
                                <input type="submit" id="soumettre" name="soumettre" value="soumettre">
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                        </head>
                                    <tbody>
                                        <?php
                                        //UPLOAD fichier ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        require_once('function.php');
                                        require_once('connexion.php');
                                        //auto upload classes ///////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        spl_autoload_register(function ($class) {
                                            require 'classes/' . $class . '.php';
                                        });
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        //declaration de gestionnaire
                                        $gesDocs = new GestionDocs($bdd);
                                        $gesMots = new GestionMots($bdd);
                                        $gesRel = new GestionRlations($bdd);
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        //ajouter un document 
                                        if (isset($_POST['soumettre'])) {
                                            if (isset($_FILES['doc']) and $_FILES['doc']['error'] == 0) {
                                                if ($_FILES['doc']['size'] <= 1000000) {
                                                    $infosfichier = pathinfo($_FILES['doc']['name']);
                                                    if ($infosfichier['extension'] == 'html') {
                                                        if (!file_exists("uploads")) {
                                                            mkdir("uploads");
                                                        }
                                                        move_uploaded_file($_FILES['doc']['tmp_name'], 'uploads/' . basename($_FILES['doc']['name']));
                                                        echo "L'envoi a bien été effectué !";
                                                        $lien = 'uploads/' . $infosfichier['basename'];
                                                        $titre = getTitle($lien);
                                                        $descriptions = get_meta_description($lien);

                                                        //$infosfichier['filename']
                                                        $doc = new Docs(['nom' => $titre, 'lien' => $lien, 'descriptions' => $descriptions]);
                                                        $gesDocs->addDoc($doc);
                                                    }
                                                }
                                            }
                                        }
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if (isset($_GET['suprimer'])) {
                                            $gesDocs->deleteDoc($_GET['suprimer']);
                                        }
                                        $docs = $gesDocs->getListDocs();
                                        foreach ($docs as $doc) {
                                            ?>

                                            <tr>
                                                <td><a href="<?php echo $doc['lien']; ?>"><?php echo $doc['nom']; ?></a></td>
                                                <td><?php echo $doc['descriptions']; ?></td>
                                                <td><a href="admin.php?suprimer=<?php echo $doc['id']; ?>">Suprimer !</a></td>
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2019</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
foreach ($docs as $doc) {
    $fichierHtml = $doc['lien'];
    $head = mb_strtolower(gethead($fichierHtml));
    $body = mb_strtolower(getBody($fichierHtml));


    $mots_vides = file_get_contents("mots_vides.txt");
    //$separateur = +×;
    $separateur = " .'’#{}[]()<>-:_;,=!\n\t\\/\"“+*«";
    $tab_mots_vides = explodebis($separateur, $mots_vides);
    $head_words = explodebis($separateur, $head);
    $head_words = array_filter($head_words);
    $head_words = array_diff($head_words, $tab_mots_vides);
    $head_words = array_count_values($head_words);
    $head_words = arrayCoef($head_words);

    $body_words = explodebis($separateur, $body);
    $body_words = array_filter($body_words);
    $body_words = array_diff($body_words, $tab_mots_vides);
    $body_words = array_count_values($body_words);

    $words = fusion($head_words, $body_words);
    addTobdd($gesMots, $gesRel, $words, $doc['id']);
}
?>