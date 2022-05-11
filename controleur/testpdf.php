<?php
    require ("../tfpdf/tfpdf.php"); //IMPORTATION DU FICHIER QUI CONTIENT LES FONCTIONS DE LA LIBRAIRIE FPDF

    include_once '../modele/PDF_request.php';

    session_start(); //RECUPERE LA SESSION ACTIVE

    if($_SESSION["Mat"]==null||$_SESSION["redirection"]!="technicien"){ //SI SESSION ACTIVE N'APPARTIENT PAS A UN TECHNICIEN ON DECONNECTE ET RENVOI VERS LA PAGE DE CONNEXION
        header("location:../index.php");
        session_destroy();
    }

    if (isset($_GET['num'])) { //LANCE LA FONCTION DE CREATION D'UN NOUVEAU PDF SI LE NUMERO D'INTERVENTION EST RENSEIGNÉE
        makenewPDF();
    }

    function makenewPDF(){ //FONCTION QUI CRÉER LE PDF
        $intervention=$_GET['num'];

        foreach(collectDatas($intervention) as $ligne) {


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
            $pdf->Cell(0, 20, "FICHE D'INTERVENTION - N°" . $intervention, 'TB', 1, 'C');
            $pdf->Cell(0, 10, 'Client N°: ' . $ligne['Numero_Client'], 0, 1, 'C');

            // Saut de ligne
            $pdf->Ln(5);

            // Début en police Arial normale taille 10
            $pdf->SetFont('DejaVu', '', 12);
            $h = 12;
            $retrait = "         ";
            $pdf->Write($h, 'Date : ' . $ligne['Date_Visite'] . "\n");
            $pdf->Write($h, $retrait . "Monsieur ou Madame : ");

            //Ecriture en Gras-Italique-Souligné(U)
            $pdf->Write($h, $ligne['Nom_Prenom'] . "\n");

            //Ecriture normal
            $pdf->SetFont('', '');
            $pdf->Write($h, $retrait . "Adresse : " . $ligne['Adresse'] . "\n");
            $pdf->Write($h, $retrait . "Téléphone : 0" . $ligne['Telephone_Client'] . "\n");
            $pdf->Write($h, $retrait . "Intervention prévue à : " . $ligne['Heure_Visite'] . " \n");

            $pdf->Write($h, $retrait . " \n Intervention sur : \n");
            $pdf->Write($h, $retrait . "Référence matériel :  " . $ligne['Reference_Interne'] . "  \n");
            $pdf->Write($h, $retrait . "Type du matériel :  " . $ligne['Libelle_Type_materiel'] . "  \n");
            $pdf->Write($h, $retrait . "Numéro de série :  " . $ligne['Numero_de_Serie'] . " \n");

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
            $pdf->Cell(80, 8, "" . $ligne['Nom_Agence'], 1, 1, 'C');

            // Décalage de 20 mm à droite
            $pdf->Cell(55);
            $pdf->Cell(80, 10, '' . $ligne['Nom_Employe'] . ' ' . $ligne['Prenom_Employe'], 'LRB', 1, 'C'); // LRB : Left-Right-Bottom (Bas)

            $pdf->SetY(264);
            $pdf->SetX(10);
            $pdf->SetFont('DejaVu', '', 9);
            $pdf->Write($h, "*La présente attestation est délivrée à l'intéressé pour servir et valoir le fait que la présente intervention a bien été réalisée. \n");

            $pdf->Output('', '', true); //RENVOIE LE PDF CRÉÉ CI-DESSUS
        }
    }