<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$string['accessdenied'] = 'Accès refusé';
$string['action_upload_image'] = 'Action';
$string['actions'] = 'Actions';
$string['additional_settings'] = 'Paramètres généraux';
$string['analyzbtn'] = 'Analyser';
$string['analyzbtnconfirm'] = 'Cliquez sur le bouton Analyser pour vérifier la correspondance faciale de l\'utilisateur.';
$string['analyzimage'] = 'Analyser les images';
$string['areyousure_delete_all_course_record'] = 'Êtes-vous sûr de vouloir supprimer toutes les images et enregistrements des étudiants capturés pendant les examens de <b>ce cours ?</b>';
$string['areyousure_delete_all_record'] = 'Êtes-vous sûr de vouloir supprimer toutes les images des étudiants capturées pendant les examens ?';
$string['areyousure_delete_image'] = 'Voulez-vous supprimer cette image ?';
$string['areyousure_delete_record'] = 'Êtes-vous sûr de vouloir supprimer cet enregistrement ?';
$string['back'] = 'Retour';
$string['cancel_image_upload'] = 'Téléchargement d\'image annulé';
$string['confirmdeletioncourse'] = 'Êtes-vous sûr de vouloir supprimer les images de ce cours ?';
$string['confirmdeletionquiz'] = 'Êtes-vous sûr de vouloir supprimer les images de ce quiz ?';
$string['course_proctoring_summary'] = 'Rapport du cours';
$string['dateverified'] = 'Date et heure';
$string['delete'] = 'Supprimer';
$string['delete_images_task'] = 'Tâche de suppression d\'images';
$string['delete_images_task_desc'] = 'Supprimer toutes les images de surveillance';
$string['deleteallcourse'] = 'Supprimer les images du cours';
$string['deletequizdata'] = 'Supprimer les images du quiz';
$string['email']  = 'Adresse e-mail';
$string['enable_web_camera_before_submitting'] = 'Vous devez activer la webcam avant de soumettre ce quiz !';
$string['eprotroringreports'] = 'Rapport de surveillance pour : ';
$string['eprotroringreportsdesc'] = 'Dans ce rapport vous trouverez toutes les images des étudiants prises pendant l\'examen. Vous pouvez maintenant valider leur identité, comme leur photo de profil et les images de la webcam.';
$string['error_face_not_found'] = 'Aucun visage trouvé dans l\'image. Veuillez contacter l\'administrateur.';
$string['error_invalid_report'] = 'Données de rapport invalides. Veuillez réessayer.';
$string['examdata'] = 'Aucune donnée disponible pour cette session d\'examen. Veuillez vérifier la configuration de l\'examen ou les paramètres de surveillance.';
$string['execute_facematch_task'] = 'Exécuter la tâche de reconnaissance faciale';
$string['facefound'] = 'Visage trouvé dans l\'image téléchargée.';
$string['facematch'] = 'Reconnaissance faciale réussie. L\'identité de l\'étudiant a été vérifiée.';
$string['facematched'] = 'Visage reconnu.';
$string['facematchs'] = 'Toutes les images ont été analysées avec succès. Veuillez les examiner pour vérifier la reconnaissance faciale.';
$string['facenotfound'] = 'Aucun visage trouvé dans l\'image téléchargée.';
$string['facenotfoundoncam'] = 'Aucun visage trouvé. Essayez de changer votre caméra pour une meilleure lumière. Merci.';
$string['facenotmatched'] = 'Visage non reconnu.';
$string['foundtext'] = 'Trouvé';
$string['identity_mismatch_label'] = 'Discordance d\'identité';
$string['image'] = 'Télécharger l\'image';
$string['image_not_uploaded'] = 'L\'image téléchargée ne contient aucun visage.';
$string['image_updated'] = 'Image mise à jour';
$string['image_upload'] = 'Télécharger l\'image';
$string['info:cameraallow'] = 'Votre caméra est maintenant en utilisation.';
$string['initiate_facematch_task'] = 'Initier la tâche de reconnaissance faciale';
$string['initiate_facematch_task_desc'] = 'Initie une tâche de reconnaissance faciale pour comparer les images à des fins de vérification de surveillance.';
$string['invalid_api'] = 'La clé API BS fournie est invalide.';
$string['invalid_facematch_method'] = 'Méthode de reconnaissance faciale invalide dans les paramètres. Veuillez fournir des identifiants API "BS" valides pour la méthode de reconnaissance faciale.';
$string['invalid_service_api'] = 'L\'API de service BS fournie est invalide.';
$string['invalidapi'] = 'La clé API BS est invalide. Veuillez contacter l\'administrateur.';
$string['invalidsesskey'] = 'Clé de session invalide. Veuillez réessayer.';
$string['invalidtype'] = 'Le type fourni est invalide.';
$string['mainsettingspagebtn'] = 'Paramètres de surveillance';
$string['modal:facevalidation'] = 'Visage validé :';
$string['modal:pending'] = 'En attente';
$string['modal:validateface'] = 'Valider la reconnaissance faciale';
$string['name'] = 'Nom de l\'étudiant';
$string['no_permission'] = 'Vous n\'avez pas les permissions appropriées pour voir cette page';
$string['nodata'] = 'Aucune donnée trouvée pour les critères fournis.';
$string['none'] = 'Aucun';
$string['nopermission'] = 'Vous n\'avez pas la permission d\'effectuer cette action.';
$string['notenrolled'] = 'Vous n\'êtes pas inscrit à ce cours ou n\'avez pas les permissions nécessaires.';
$string['notfoundtext'] = 'Non trouvé';
$string['notpermissionreport'] = 'Les rapports de surveillance sont désactivés pour vous.';
$string['notrequired'] = 'Non obligatoire';
$string['nousersfound'] = 'Aucun utilisateur trouvé';
$string['numberofimages'] = 'Nombre d\'images';
$string['openwebcam'] = 'Autorisez votre webcam pour continuer';
$string['photoalttext'] = 'La capture d\'écran apparaîtra dans cette zone.';
$string['photonotuploaded'] = 'Photo non téléchargée. Veuillez contacter l\'administrateur.';
$string['picturesreport'] = 'Voir le rapport de surveillance';
$string['picturesusedreport'] = 'Ce sont les images capturées pendant le quiz.';
$string['plugin_description'] = 'Le plugin Surveillance pour Moodle améliore la sécurité des quiz en ligne en capturant et en vérifiant les identités des utilisateurs par le biais d\'images webcam. Il est conçu pour garantir que seuls les utilisateurs autorisés peuvent tenter le quiz, fournissant une solution de surveillance sécurisée et fiable.';
$string['pluginname'] = 'Surveillance pour Moodle';
$string['privacy:core_files'] = 'Images webcam de QuizAccess Surveillance';
$string['privacy:metadata'] = 'Nous ne partageons aucune donnée personnelle avec des tiers.';
$string['privacy:metadata:core_files'] = 'Quiz Access stocke l\'image de l\'utilisateur prise par la webcam lors de la tentative de quiz.';
$string['privacy:metadata:courseid'] = 'L\'ID du cours qui utilise la surveillance.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Tableau des journaux de surveillance d\'accès au quiz Moodle qui stocke l\'image de l\'utilisateur.';
$string['privacy:metadata:quizid'] = 'L\'ID du quiz qui utilise la surveillance.';
$string['privacy:metadata:status'] = 'L\'état de la surveillance.';
$string['privacy:metadata:userid'] = 'L\'ID de l\'utilisateur qui a tenté le quiz.';
$string['privacy:metadata:webcampicture'] = 'Le nom de l\'image prise par la surveillance.';
$string['pro_version_description'] = 'Améliorez vos examens en ligne avec Surveillance Moodle Pro ! Détectez les changements d\'onglets, supervisez l\'activité du presse-papiers, utilisez la reconnaissance faciale pour la surveillance en temps réel et accédez à des rapports de surveillance détaillés pour garantir des évaluations justes et sécurisées.';
$string['pro_version_text'] = 'En savoir plus sur la version Pro de ce plugin ici.';
$string['pro_version_title_text'] = 'Obtenez Surveillance Pro.';
$string['proctoring:analyzeimages'] = 'Surveillance analyser les images';
$string['proctoring:deletecamshots'] = 'Supprimer les images des journaux de surveillance.';
$string['proctoring:getcamshots'] = 'Surveillance obtenir les images webcam';
$string['proctoring:sendcamshot'] = 'Surveillance envoyer une photo webcam';
$string['proctoring:viewreport'] = 'Surveillance voir le rapport';
$string['proctoring_pro_promo'] = 'Promo Surveillance Pro';
$string['proctoring_pro_promo:admin'] = 'Rapports admin détaillés';
$string['proctoring_pro_promo:adminlist1'] = 'Fournit une vue détaillée de tous les journaux supervisés des participants.';
$string['proctoring_pro_promo:adminlist2'] = 'Permet de télécharger un rapport PDF complet.';
$string['proctoring_pro_promo:detectcopypaste'] = 'Détection de fraude copier-coller';
$string['proctoring_pro_promo:detectcopypastelist1'] = 'Détecte toute action de copier-coller lors de la tentative de quiz.';
$string['proctoring_pro_promo:detectcopypastelist2'] = 'Enregistre chaque tentative de copier ou coller du texte.';
$string['proctoring_pro_promo:email'] = 'Support par e-mail';
$string['proctoring_pro_promo:emailsupport'] = 'Recevez un support direct par e-mail de notre équipe.';
$string['proctoring_pro_promo:emailsupportlist1'] = 'Obtenez un support par e-mail 24/7 pour toute question ou problème.';
$string['proctoring_pro_promo:feature'] = 'Fonctionnalités de Surveillance Pro';
$string['proctoring_pro_promo:featurelist1'] = 'Compatible avec le service de reconnaissance faciale (AWS).';
$string['proctoring_pro_promo:featurelist2'] = 'Détecte si la webcam a été activée pendant toute la tentative.';
$string['proctoring_pro_promo:featurelist3'] = 'Détecte si l\'utilisateur s\'est déplacé vers une autre application/onglet.';
$string['proctoring_pro_promo:featurelist4'] = 'Détecte si l\'utilisateur a redimensionné la fenêtre du navigateur.';
$string['proctoring_pro_promo:featurelist5'] = 'Détecte si un copier-coller s\'est produit lors de la tentative.';
$string['proctoring_pro_promo:featurelist6'] = 'Détecte si l\'utilisateur a appuyé sur la touche F12.';
$string['proctoring_pro_promo:featurelist7'] = 'Rapport détaillé admin de chaque événement enregistré et images webcam.';
$string['proctoring_pro_promo:featurelist8'] = 'Rapport résumé admin de tous les utilisateurs.';
$string['proctoring_pro_promo:featurelist9'] = 'Support par e-mail/corrections de bugs';
$string['proctoring_pro_promo:header'] = 'Sécurisez vos examens en ligne avec la technologie de pointe de Surveillance Pro pour une surveillance inégalée';
$string['proctoring_pro_promo:learnmore'] = 'En savoir plus';
$string['proctoring_pro_promo:mail'] = 'Contactez-nous à';
$string['proctoring_pro_promo:namefree'] = 'Surveillance (Gratuit)';
$string['proctoring_pro_promo:namepro'] = 'Surveillance Pro';
$string['proctoring_pro_promo:pdfgenerator'] = 'Génération de rapport PDF';
$string['proctoring_pro_promo:pdfgeneratordesc'] = 'Génère un rapport PDF détaillé pour chaque utilisateur, contenant tous les événements enregistrés.';
$string['proctoring_pro_promo:profeature'] = 'Nouveautés de Surveillance Pro 2.0';
$string['proctoring_pro_promo:profeaturebulkphotoupload'] = 'Téléchargement en masse de photos';
$string['proctoring_pro_promo:profeaturebulkphotouploaddesc'] = 'Permet aux administrateurs de télécharger des images pour plusieurs utilisateurs à la fois via un fichier zip ou de télécharger des images individuelles.';
$string['proctoring_pro_promo:profeaturehphotofillter'] = 'Filtrage des photos';
$string['proctoring_pro_promo:profeaturehphotofillterdesc'] = 'Les administrateurs peuvent filtrer les utilisateurs en fonction du fait que leur photo soit téléchargée ou si le visage de l\'utilisateur manque dans les images capturées.';
$string['proctoring_pro_promo:screenmonitoring'] = 'Surveillance de la taille d\'écran';
$string['proctoring_pro_promo:screenmonitoringlist1'] = 'Détecte tout changement dans la taille d\'écran lors de la tentative de quiz.';
$string['proctoring_pro_promo:screenmonitoringlist2'] = 'Enregistre chaque instance lorsque l\'utilisateur redimensionne la fenêtre du quiz.';
$string['proctoring_pro_promo:subheader'] = 'Obtenez le plugin Surveillance Pro maintenant.';
$string['proctoring_pro_promo:suscipiousevent'] = 'Autres événements suspects';
$string['proctoring_pro_promo:suscipiouseventlist1'] = 'Détecte si la touche F12 est enfoncée pendant l\'examen.';
$string['proctoring_pro_promo:suscipiouseventlist2'] = 'Enregistre chaque instance lorsque l\'utilisateur appuie sur F12 lors de la tentative du quiz.';
$string['proctoring_pro_promo:tabmonitoring'] = 'Surveillance de l\'onglet en focus';
$string['proctoring_pro_promo:tabmonitoringlist1'] = 'Détecte si l\'utilisateur change vers une autre fenêtre ou un autre onglet.';
$string['proctoring_pro_promo:tabmonitoringlist2'] = 'Enregistre chaque instance lorsque l\'utilisateur s\'éloigne de l\'onglet ou de la fenêtre d\'examen.';
$string['proctoring_pro_promo:webcam'] = 'Détection webcam';
$string['proctoring_pro_promo:webcamlist1'] = 'Détecte si la webcam est restée activée pendant toute la tentative d\'examen.';
$string['proctoring_pro_promo:webcamlist2'] = 'Enregistre toute instance lorsque la webcam est désactivée.';
$string['proctoring_pro_promo_heading'] = 'Promo Surveillance Pro';
$string['proctoring_report'] = 'Rapport de surveillance';
$string['proctoringheader'] = '<strong>Pour continuer avec cette tentative de quiz vous devez ouvrir votre webcam, et certaines de vos images seront prises aléatoirement pendant le quiz.</strong>';
$string['proctoringlabel'] = 'J\'accepte le processus de validation.';
$string['proctoringrequired'] = 'Validation d\'identité par webcam';
$string['proctoringrequired_help'] = 'L\'activation de la surveillance oblige les étudiants à être surveillés à l\'aide de la webcam et de l\'enregistrement d\'écran lors de la tentative du quiz.';
$string['proctoringrequiredoption'] = 'Activer la capture webcam par Surveillance';
$string['proctoringstatement'] = 'Cet examen nécessite un accès à la webcam.<br />(Veuillez autoriser l\'accès à la webcam).';
$string['provide_image'] = 'Veuillez fournir une image à télécharger.';
$string['quizaccess_proctoring'] = 'Quizaccess Surveillance';
$string['quiztitle'] = 'Titre du quiz';
$string['report_search_clear'] = 'Effacer';
$string['report_search_placeholder'] = 'Recherche par e-mail ou nom';
$string['report_search_submit'] = 'Rechercher';
$string['reportpage'] = 'Résumé de surveillance du cours';
$string['setting:adminimagedescription'] = 'Ces images seront utilisées comme images de base pour la vérification faciale. Assurez-vous que chaque image contient un visage clairement visible.';
$string['setting:adminimagepage'] = 'Liste des utilisateurs de surveillance';

$string['setting:bs_api'] = 'API de service BS';
$string['setting:bs_api_key'] = 'Clé API BS';
$string['setting:bs_api_keydesc'] = 'Entrez la clé API pour le service de reconnaissance faciale BS.';
$string['setting:bs_apidesc'] = 'Point de terminaison API de service BS.';
$string['setting:bs_apifacematchthreshold'] = 'Seuil de reconnaissance faciale (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'Le pourcentage de seuil pour la vérification faciale utilisant le service BS. (Par défaut : 68%)';
$string['setting:camshotdelay'] = 'Le délai entre les images webcam (secondes)';
$string['setting:camshotdelay_desc'] = 'La valeur donnée sera le délai en secondes entre chaque image webcam.';
$string['setting:camshotwidth'] = 'La largeur de l\'image webcam (pixels)';
$string['setting:camshotwidth_desc'] = 'La valeur donnée sera la largeur de l\'image webcam. La hauteur de l\'image sera mise à l\'échelle pour correspondre à cela.';
$string['setting:facematch'] = 'Nombre de reconnaissances faciales par quiz';
$string['setting:facematchdesc'] = 'Nombre de vérifications de reconnaissance faciale. Utilisez 0 ou moins pour vérifier tous les instantanés.';
$string['setting:fc_method'] = 'Méthode de reconnaissance faciale';
$string['setting:fc_methoddesc'] = 'Service utilisé pour reconnaître les visages. Options : BS, Aucune.';
$string['setting:fcthreshold'] = 'Pourcentage de seuil de reconnaissance faciale';
$string['setting:fcthresholddesc'] = 'Pourcentage de seuil de reconnaissance faciale';
$string['setting:uploaduserimages'] = 'Télécharger l\'image de base pour les utilisateurs';
$string['setting:userslist'] = 'Télécharger les images des utilisateurs';
$string['settings:deleteallsuccess'] = 'Tous les enregistrements ont été supprimés avec succès.';
$string['settings:deleteuserimagesuccess'] = 'L\'image de l\'utilisateur a été supprimée avec succès.';
$string['settings:fcheckquizstart'] = 'Validation faciale au démarrage du quiz';
$string['settings:fcheckquizstart_desc'] = 'S\'il est activé, les utilisateurs devront valider leur visage avant de pouvoir démarrer le quiz.';

$string['settingscontroll:deleteall'] = 'Supprimer tous les enregistrements capturés pendant les examens';
$string['settingscontroll:deleteallcourseimage'] = 'Supprimer toutes les images et enregistrements des étudiants capturés pendant les examens de <b>ce cours</b>.';
$string['settingscontroll:deletealldescription'] = 'Ceci supprimera définitivement toutes les images capturées et les données de surveillance associées. Cette action ne peut pas être annulée.';

$string['settingscontroll:deletealllinktext'] = 'Supprimer tous les enregistrements';
$string['status'] = 'Statut de validation';
$string['studentreport'] = 'Rapport étudiant';
$string['submit'] = 'Soumettre';
$string['summarypagedesc'] = 'Dans ce rapport vous trouverez le résumé du rapport de surveillance pour ce cours et ses quiz. Vous pouvez supprimer toutes les données liées au quiz et au cours. Cela supprimera les fichiers image ainsi que les journaux.';
$string['task:delete_images'] = 'Tâche de suppression d\'images';
$string['timemodified'] = 'Dernière modification';
$string['upload_first_image'] = 'Veuillez télécharger l\'image de l\'utilisateur.';
$string['upload_image'] = 'Télécharger l\'image';
$string['upload_image_heading'] = 'Télécharger l\'image de l\'utilisateur';
$string['upload_image_info'] = 'Téléchargez les images dans le système pour la vérification de l\'utilisateur. Cela aide à garantir l\'intégrité de vos quiz en ligne.';
$string['upload_image_link_text'] = 'Cliquez ici pour télécharger les images des utilisateurs.';
$string['upload_image_message'] = 'La surveillance a besoin d\'images d\'utilisateur pour authentifier leurs identités.';
$string['upload_image_title'] = 'Télécharger l\'image pour la détection faciale';
$string['uploadimagehere'] = 'Cliquez ici pour télécharger l\'image.';
$string['user'] = 'Utilisateurs';
$string['user_image_not_uploaded'] = 'L\'image de l\'utilisateur n\'a pas été téléchargée. Veuillez télécharger l\'image.';
$string['user_image_not_uploaded_teacher'] = 'L\'image de l\'utilisateur n\'a pas été téléchargée. Veuillez contacter l\'administrateur pour télécharger l\'image.';
$string['userimagenotuploaded'] = 'Image de l\'utilisateur non téléchargée.';
$string['userlist'] = 'Liste des utilisateurs';
$string['username'] = 'Nom d\'utilisateur';
$string['users_list'] = 'Liste des utilisateurs de Surveillance pour Moodle';
$string['users_list_info_description'] = 'Cette page liste tous les utilisateurs qui ont besoin d\'une image de base pour la surveillance.
                                        Ces images seront utilisées pour la reconnaissance faciale lors des quiz pour assurer l\'authentification et éviter l\'usurpation d\'identité.
                                        Si une image n\'est pas téléchargée, l\'utilisateur peut ne pas être correctement vérifié lors des examens surveillés. Pour obtenir plus de fonctionnalités, comme un filtrage personnalisé, une recherche et un téléchargement de nombreuses images à la fois, ';
$string['videonotavailable'] = 'Flux vidéo non disponible.';
$string['viewimages'] = 'Voir les images';
$string['warning:cameraallowwarning'] = 'Veuillez autoriser l\'accès à la caméra.';
$string['warninglabel'] = 'Avertissements';
$string['webcam'] = 'Webcam';
$string['webcampicture'] = 'Images capturées';
$string['wrong_during_taking_image'] = 'Une erreur s\'est produite lors de la prise de l\'image.';
$string['wrong_during_taking_screenshot'] = 'Une erreur s\'est produite lors de la prise de la capture d\'écran.';
$string['youmustagree'] = 'Vous devez accepter de valider votre identité avant de continuer.';
