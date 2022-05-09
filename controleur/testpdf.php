<?php
    require ("../tfpdf/tfpdf.php");

    session_start();
    if($_SESSION["Mat"]==null||$_SESSION["redirection"]=="gestionnaire"){
        header("location:../index.php");
        session_destroy();
    }

    if (isset($_GET['num'])) {
        makenewPDF();
    }

    function makenewPDF(){
        $intervention=$_GET['num'];

        $db = mysqli_connect("127.0.0.1", "root", "", "ap2"); /* CONNEXION A LA BASE DE DONNEES */
        mysqli_set_charset($db, 'utf8'); /* ENCODAGE DES DONNEES DE LA BDD VERS LE SITE EN UTF-8 */

        $sql = "SELECT client.Nom_Prenom,client.Numero_Client,client.Telephone_Client,technicien.Nom_Employe,technicien.Prenom_Employe,Agence.Nom_Agence,client.Adresse,intervention.Date_Visite,intervention.Heure_Visite,materiel.Reference_Interne,materiel.Numero_de_Serie,type_materiel.Libelle_Type_materiel   /* SELECTION DES DONNEES A AFFICHER SUR Le PDF */
                FROM intervention,client,controler,materiel,type_materiel,technicien,agence
                WHERE intervention.Numero_Client=client.Numero_Client
                    AND intervention.Numero_Intervention=controler.Numero_Intervention
                    AND intervention.Matricule = technicien.Matricule
                    AND technicien.Numero_Agence = agence.Numero_Agence
                    AND controler.Numero_de_Serie=materiel.Numero_de_Serie
                    AND materiel.Reference_Interne=type_materiel.Reference_Interne
                    AND intervention.Numero_Intervention='$intervention'";
        $liste = mysqli_query($db, $sql);

        foreach($liste as $ligne) {
            $numero_intervention = $intervention;
            $nom_client = $ligne['Nom_Prenom'];
            $numero_client = $ligne['Numero_Client'];
            $adresse_client = $ligne['Adresse'];
            $date_visite = $ligne['Date_Visite'];
            $heure_visite = $ligne['Heure_Visite'];
            $materiel = $ligne['Reference_Interne'];
            $num_serie_materiel = $ligne['Numero_de_Serie'];
            $libelle_type_materiel = $ligne['Libelle_Type_materiel'];
            $agence = $ligne['Nom_Agence'];
            $nomtech = $ligne['Nom_Employe'];
            $prenomtech = $ligne['Prenom_Employe'];
            $telephonecli = $ligne['Telephone_Client'];
        }

        $pdf = new TFPDF('P', 'mm', 'A4');

        //Ajouter une nouvelle page
        $pdf->AddPage();

        //Encodage en UTF-8
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

        // entete
        $pdf->Image('../img/logo.png', 10, 29, 15, 15);

        // Saut de ligne
        $pdf->Ln(16);

        // Police DejaVu 16
        $pdf->SetFont('DejaVu', '', 16);

        // Titre
        $pdf->Cell(0, 20, "FICHE D'INTERVENTION - N°" . $numero_intervention, 'TB', 1, 'C');
        $pdf->Cell(0, 10, 'Client N°: '.$numero_client, 0, 1, 'C');

        // Saut de ligne
        $pdf->Ln(5);

        // Début en police Arial normale taille 10
        $pdf->SetFont('DejaVu', '', 12);
        $h = 12;
        $retrait = "         ";
        $pdf->Write($h, 'Date : ' . $date_visite . "\n");
        $pdf->Write($h, $retrait . "Monsieur ou Madame : ");

        //Ecriture en Gras-Italique-Souligné(U)
        $pdf->Write($h, $nom_client . "\n");

        //Ecriture normal
        $pdf->SetFont('', '');
        $pdf->Write($h, $retrait . "Adresse : " . $adresse_client . "\n");
        $pdf->Write($h, $retrait . "Téléphone : 0" . $telephonecli . "\n");
        $pdf->Write($h, $retrait . "Intervention prévue à : " . $heure_visite . " \n");

        $pdf->Write($h, $retrait . " \n Intervention sur : \n");
        $pdf->Write($h, $retrait . "Référence matériel :  " . $materiel . "  \n");
        $pdf->Write($h, $retrait . "Type du matériel :  " . $libelle_type_materiel . "  \n");
        $pdf->Write($h, $retrait . "Numéro de série :  " . $num_serie_materiel . " \n");

        $pdf->SetY(190);
        $pdf->SetX(38);
        $pdf->Write($h, "Signature Technicien :  \n");

        $pdf->SetX(110); //CASE SIGNATURE TECHNICIEN
        $pdf->Cell(80, 20, "", 1, 1, 'C');

        $pdf->SetY(190);
        $pdf->SetX(130);
        $pdf->Write($h, "Signature Client :  \n");

        $pdf->SetX(20); //CASE SIGNATURE CLIENT
        $pdf->Cell(80, 20, "", 1, 1, 'C');

        $pdf->SetFont('DejaVu', '', 12);
        $pdf->SetY(235);
        $pdf->Cell(0, 8, 'Fait à Lille Le : ' . date('d/m/Y'), 0, 1, 'C');

        // Décalage de 20 mm à droite
        $pdf->Cell(55);
        $pdf->Cell(80, 8, "".$agence, 1, 1, 'C');

        // Décalage de 20 mm à droite
        $pdf->Cell(55);
        $pdf->Cell(80, 10, ''.$nomtech.' '.$prenomtech, 'LRB', 1, 'C'); // LRB : Left-Right-Bottom (Bas)

        $pdf->SetY(264);
        $pdf->SetX(10);
        $pdf->SetFont('DejaVu', '', 9);
        $pdf->Write($h, "*La présente attestation est délivrée à l'intéressé pour servir et valoir le fait que la présente intervention a bien été réalisée. \n");

        //Afficher le pdf
        $pdf->Output('', '', true);
    }