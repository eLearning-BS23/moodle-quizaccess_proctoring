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
$string['accessdenied'] = 'Zugriff verweigert';
$string['action_upload_image'] = 'Aktion';
$string['actions'] = 'Aktionen';
$string['additional_settings'] = 'Allgemeine Einstellungen';
$string['analyzbtn'] = 'Analysieren';
$string['analyzbtnconfirm'] = 'Klicken Sie auf die Schaltfläche Analysieren, um den Gesichtsabgleich des Benutzers durchzuführen.';
$string['analyzimage'] = 'Bilder analysieren';
$string['areyousure_delete_all_course_record'] = 'Sind Sie sicher, dass Sie alle Bilder und Aufzeichnungen der Schüler, die während der Prüfungen aufgenommen wurden, für <b>diesen Kurs</b> löschen möchten?';
$string['areyousure_delete_all_record'] = 'Sind Sie sicher, dass Sie alle Bilder der Schüler löschen möchten, die während der Prüfungen aufgenommen wurden?';
$string['areyousure_delete_image'] = 'Möchten Sie dieses Bild löschen?';
$string['areyousure_delete_record'] = 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?';
$string['back'] = 'Zurück';
$string['cancel_image_upload'] = 'Bild-Upload abgebrochen';
$string['confirmdeletioncourse'] = 'Sind Sie sicher, dass Sie die Bilder dieses Kurses löschen möchten?';
$string['confirmdeletionquiz'] = 'Sind Sie sicher, dass Sie die Bilder dieses Quiz löschen möchten?';
$string['course_proctoring_summary'] = 'Kursbericht';
$string['dateverified'] = 'Datum und Uhrzeit';
$string['delete'] = 'Löschen';
$string['delete_images_task'] = 'Aufgabe zum Löschen von Bildern';
$string['delete_images_task_desc'] = 'Alle Proctoring-Bilder löschen';
$string['deleteallcourse'] = 'Kursbilder löschen';
$string['deletequizdata'] = 'Quiz-Bilder löschen';
$string['email']  = 'E-Mail-Adresse';
$string['enable_web_camera_before_submitting'] = 'Sie müssen die Webcam aktivieren, bevor Sie dieses Quiz absenden!';
$string['eprotroringreports'] = 'Proctoring-Bericht für: ';
$string['eprotroringreportsdesc'] = 'In diesem Bericht finden Sie alle Bilder der Schüler, die während der Prüfung aufgenommen wurden. Jetzt können Sie deren Identität, wie ihr Profilbild und Webcam-Bilder, überprüfen.';
$string['error_face_not_found'] = 'Kein Gesicht im Bild gefunden. Bitte kontaktieren Sie den Administrator.';
$string['error_invalid_report'] = 'Ungültige Berichtsdaten. Bitte versuchen Sie es erneut.';
$string['examdata'] = 'Für diese Prüfungssitzung sind keine Daten verfügbar. Bitte überprüfen Sie die Prüfungseinrichtung oder die Überwachungseinstellungen.';
$string['execute_facematch_task'] = 'Gesichtsabgleich-Aufgabe ausführen';
$string['facefound'] = 'Gesicht im hochgeladenen Bild gefunden.';
$string['facematch'] = 'Gesichtsabgleich erfolgreich. Die Identität des Schülers wurde überprüft.';
$string['facematched'] = 'Gesicht abgeglichen.';
$string['facematchs'] = 'Alle Bilder wurden erfolgreich analysiert. Bitte überprüfen Sie sie, um den Gesichtsabgleich zu verifizieren.';
$string['facenotfound'] = 'Kein Gesicht im hochgeladenen Bild gefunden.';
$string['facenotfoundoncam'] = 'Gesicht nicht gefunden. Versuchen Sie, Ihre Kamera in eine bessere Beleuchtung zu ändern. Danke.';
$string['facenotmatched'] = 'Gesicht nicht abgeglichen.';
$string['foundtext'] = 'Gefunden';
$string['identity_mismatch_label'] = 'Identitätsabweichung';
$string['image'] = 'Bild hochladen';
$string['image_not_uploaded'] = 'Das hochgeladene Bild enthält keine Gesichter.';
$string['image_updated'] = 'Bild aktualisiert';
$string['image_upload'] = 'Bild hochladen';
$string['info:cameraallow'] = 'Ihre Kamera wird jetzt verwendet.';
$string['initiate_facematch_task'] = 'Gesichtsabgleich-Aufgabe initiieren';
$string['initiate_facematch_task_desc'] = 'Initiert eine Gesichtsabgleich-Aufgabe, um Bilder für die Proctoring-Verifizierung zu vergleichen.';
$string['invalid_api'] = 'Der angegebene BS API-Schlüssel ist ungültig.';
$string['invalid_facematch_method'] = 'Ungültige Gesichtsabgleich-Methode in den Einstellungen. Bitte geben Sie gültige "BS" API-Anmeldedaten für die Gesichtsabgleich-Methode an.';
$string['invalid_service_api'] = 'Der angegebene BS-Dienst-API ist ungültig.';
$string['invalidapi'] = 'BS API-Schlüssel ist ungültig. Bitte kontaktieren Sie den Administrator.';
$string['invalidsesskey'] = 'Ungültiger Sitzungsschlüssel. Bitte versuchen Sie es erneut.';
$string['invalidtype'] = 'Der angegebene Typ ist ungültig.';
$string['mainsettingspagebtn'] = 'Proctoring-Einstellungen';
$string['modal:facevalidation'] = 'Gesicht validiert:';
$string['modal:pending'] = 'Ausstehend';
$string['modal:validateface'] = 'Gesichtserkennung validieren';
$string['name'] = 'Schülername';
$string['no_permission'] = 'Sie haben keine ausreichende Berechtigung, um diese Seite anzuzeigen';
$string['nodata'] = 'Keine Daten für die angegebenen Kriterien gefunden.';
$string['none'] = 'Keine';
$string['nopermission'] = 'Sie haben keine Berechtigung, diese Aktion durchzuführen.';
$string['notenrolled'] = 'Sie sind nicht in diesem Kurs eingeschrieben oder haben nicht die erforderlichen Berechtigungen.';
$string['notfoundtext'] = 'Nicht gefunden';
$string['notpermissionreport'] = 'Proctoring-Berichte sind für Sie deaktiviert.';
$string['notrequired'] = 'Nicht erforderlich';
$string['nousersfound'] = 'Keine Benutzer gefunden';
$string['numberofimages'] = 'Anzahl der Bilder';
$string['openwebcam'] = 'Erlauben Sie Ihre Webcam, um fortzufahren';
$string['photoalttext'] = 'Der Bildschirmausdruck erscheint in diesem Feld.';
$string['photonotuploaded'] = 'Foto nicht hochgeladen. Bitte kontaktieren Sie den Administrator.';
$string['picturesreport'] = 'Proctoring-Bericht anzeigen';
$string['picturesusedreport'] = 'Dies sind die während des Quiz aufgenommenen Bilder.';
$string['plugin_description'] = 'Das Moodle Proctoring-Plugin erhöht die Sicherheit von Online-Quizzen durch Erfassung und Überprüfung von Benutzeridentitäten mittels Webcam-Bildern. Es wurde entwickelt, um sicherzustellen, dass nur autorisierte Benutzer das Quiz absolvieren können, und bietet eine sichere und zuverlässige Proctoring-Lösung.';
$string['pluginname'] = 'Proctoring für Moodle';
$string['privacy:core_files'] = 'QuizAccess Proctoring Webcam-Bilder';
$string['privacy:metadata'] = 'Wir teilen keine persönlichen Daten mit Dritten.';
$string['privacy:metadata:core_files'] = 'Das Quiz Access speichert das Benutzerbild, das während des Quiz-Versuchs von der Webcam aufgenommen wurde.';
$string['privacy:metadata:courseid'] = 'Die ID des Kurses, der Proctoring verwendet.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Moodle Quiz-Access Proctoring-Log-Tabelle, die das Bild des Benutzers speichert.';
$string['privacy:metadata:quizid'] = 'Die ID des Quiz, das Proctoring verwendet.';
$string['privacy:metadata:status'] = 'Der Status des Proctoring.';
$string['privacy:metadata:userid'] = 'Die ID des Benutzers, der das Quiz absolviert hat.';
$string['privacy:metadata:webcampicture'] = 'Der Name des vom Proctoring aufgenommenen Bildes.';
$string['pro_version_description'] = 'Verbessern Sie Ihre Online-Prüfungen mit Moodle Proctoring Pro! Erkennen Sie Tab-Wechsel, überwachen Sie Zwischenablage-Aktivitäten, nutzen Sie Gesichtserkennung für Echtzeit-Überwachung und greifen Sie auf detaillierte Proctoring-Berichte zu, um faire und sichere Bewertungen zu gewährleisten.';
$string['pro_version_text'] = 'Mehr über die Pro-Version erfahren';
$string['pro_version_products_text'] = 'Alle Produkte anzeigen';
$string['pro_version_title_text'] = 'Holen Sie sich Proctoring Pro.';
$string['proctoring:analyzeimages'] = 'Proctoring Bilder analysieren';
$string['proctoring:deletecamshots'] = 'Bilder aus Proctoring-Logs löschen.';
$string['proctoring:getcamshots'] = 'Proctoring Webcam-Bilder abrufen';
$string['proctoring:sendcamshot'] = 'Proctoring Webcam-Foto senden';
$string['proctoring:viewreport'] = 'Proctoring Bericht anzeigen';
$string['proctoring_pro_promo'] = 'Proctoring Pro Promo';
$string['proctoring_pro_promo:admin'] = 'Detaillierte Admin-Berichte';
$string['proctoring_pro_promo:adminlist1'] = 'Bietet eine detaillierte Ansicht aller Teilnehmer-Protokollierungen.';
$string['proctoring_pro_promo:adminlist2'] = 'Ermöglicht das Herunterladen eines umfassenden PDF-Berichts.';
$string['proctoring_pro_promo:detectcopypaste'] = 'Kopier- und Einfüge-Betrugserkennung';
$string['proctoring_pro_promo:detectcopypastelist1'] = 'Erkennt jegliche Kopier- und Einfügeaktionen während des Quiz-Versuchs.';
$string['proctoring_pro_promo:detectcopypastelist2'] = 'Protokolliert jeden Versuch, Text zu kopieren oder einzufügen.';
$string['proctoring_pro_promo:email'] = 'E-Mail-Support';
$string['proctoring_pro_promo:emailsupport'] = 'Erhalten Sie direkten E-Mail-Support von unserem Team.';
$string['proctoring_pro_promo:emailsupportlist1'] = 'Erhalten Sie 24/7 E-Mail-Support für alle Fragen oder Probleme.';
$string['proctoring_pro_promo:feature'] = 'Funktionen von Proctoring Pro';
$string['proctoring_pro_promo:featurelist1'] = 'Kompatibel mit Gesichtserkennungsdienst (AWS).';
$string['proctoring_pro_promo:featurelist2'] = 'Erkennt, ob die Webcam während des gesamten Versuchs aktiviert war.';
$string['proctoring_pro_promo:featurelist3'] = 'Erkennt, ob der Benutzer zu einer anderen Anwendung/Registerkarte gewechselt hat.';
$string['proctoring_pro_promo:featurelist4'] = 'Erkennt, ob der Benutzer das Browserfenster in der Größe verändert hat.';
$string['proctoring_pro_promo:featurelist5'] = 'Erkennt, ob während des Versuchs kopiert und eingefügt wurde.';
$string['proctoring_pro_promo:featurelist6'] = 'Erkennt, ob der Benutzer die F12-Taste gedrückt hat.';
$string['proctoring_pro_promo:featurelist7'] = 'Detaillierter Admin-Bericht jedes Ereignisprotokolls und Webcam-Bilder.';
$string['proctoring_pro_promo:featurelist8'] = 'Admin-Zusammenfassungsbericht aller Benutzer.';
$string['proctoring_pro_promo:featurelist9'] = 'E-Mail-Support/Fehlerbehebungen';
$string['proctoring_pro_promo:header'] = 'Sichern Sie Ihre Online-Prüfungen mit Proctoring Pro Technologie für unübertroffene Überwachung';
$string['proctoring_pro_promo:learnmore'] = 'Mehr erfahren';
$string['proctoring_pro_promo:mail'] = 'Kontaktieren Sie uns unter';
$string['proctoring_pro_promo:namefree'] = 'Proctoring (Kostenlos)';
$string['proctoring_pro_promo:namepro'] = 'Proctoring Pro';
$string['proctoring_pro_promo:pdfgenerator'] = 'PDF-Berichterstellung';
$string['proctoring_pro_promo:pdfgeneratordesc'] = 'Erstellt einen detaillierten PDF-Bericht für jeden Benutzer, der alle protokollierten Ereignisse enthält.';
$string['proctoring_pro_promo:profeature'] = 'Neuerungen in Proctoring Pro 2.0';
$string['proctoring_pro_promo:profeaturebulkphotoupload'] = 'Massenfoto-Upload';
$string['proctoring_pro_promo:profeaturebulkphotouploaddesc'] = 'Erlaubt Admins, Bilder für mehrere Benutzer gleichzeitig über eine Zip-Datei hochzuladen oder einzelne Bilder hochzuladen.';
$string['proctoring_pro_promo:profeaturehphotofillter'] = 'Fotofilterung';
$string['proctoring_pro_promo:profeaturehphotofillterdesc'] = 'Admins können Benutzer danach filtern, ob ihr Foto hochgeladen ist oder ob das Gesicht des Benutzers in den erfassten Bildern fehlt.';
$string['proctoring_pro_promo:screenmonitoring'] = 'Bildschirmgrößenüberwachung';
$string['proctoring_pro_promo:screenmonitoringlist1'] = 'Erkennt alle Änderungen der Bildschirmgröße während des Quiz-Versuchs.';
$string['proctoring_pro_promo:screenmonitoringlist2'] = 'Protokolliert jede Instanz, wenn der Benutzer das Quiz-Fenster in der Größe verändert.';
$string['proctoring_pro_promo:subheader'] = 'Holen Sie sich jetzt das Proctoring Pro Plugin.';
$string['proctoring_pro_promo:suscipiousevent'] = 'Andere verdächtige Ereignisse';
$string['proctoring_pro_promo:suscipiouseventlist1'] = 'Erkennt, ob die F12-Taste während der Prüfung gedrückt wird.';
$string['proctoring_pro_promo:suscipiouseventlist2'] = 'Protokolliert jede Instanz, wenn der Benutzer F12 während des Quiz-Versuchs drückt.';
$string['proctoring_pro_promo:tabmonitoring'] = 'Fokus-Tab-Überwachung';
$string['proctoring_pro_promo:tabmonitoringlist1'] = 'Erkennt, ob der Benutzer zu einem anderen Fenster oder einer anderen Registerkarte wechselt.';
$string['proctoring_pro_promo:tabmonitoringlist2'] = 'Protokolliert jede Instanz, wenn der Benutzer die Prüfungs-Registerkarte oder das Fenster verlässt.';
$string['proctoring_pro_promo:webcam'] = 'Webcam-Erkennung';
$string['proctoring_pro_promo:webcamlist1'] = 'Erkennt, ob die Webcam während des gesamten Prüfungsversuchs aktiviert blieb.';
$string['proctoring_pro_promo:webcamlist2'] = 'Protokolliert alle Instanzen, in denen die Webcam deaktiviert wird.';
$string['proctoring_pro_promo_heading'] = 'Proctoring Pro Promo';
$string['proctoring_report'] = 'Proctoring-Bericht';
$string['proctoringheader'] = '<strong>Um mit diesem Quiz-Versuch fortzufahren, müssen Sie Ihre Webcam öffnen, und es werden zufällig einige Ihrer Bilder während des Quiz aufgenommen.</strong>';
$string['proctoringlabel'] = 'Ich stimme dem Validierungsprozess zu.';
$string['proctoringrequired'] = 'Webcam-Identitätsvalidierung';
$string['proctoringrequired_help'] = 'Die Aktivierung von Proctoring erfordert, dass Schüler während des Quiz-Versuchs mit Webcam und Bildschirmaufzeichnung überwacht werden.';
$string['proctoringrequiredoption'] = 'Webcam-Erfassung durch Proctoring aktivieren';
$string['proctoringstatement'] = 'Diese Prüfung erfordert Webcam-Zugriff.<br />(Bitte erlauben Sie den Webcam-Zugriff).';
$string['provide_image'] = 'Bitte geben Sie ein Bild zum Hochladen an.';
$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';
$string['quiztitle'] = 'Quiz-Titel';
$string['report_search_clear'] = 'Löschen';
$string['report_search_placeholder'] = 'Nach E-Mail oder Name suchen';
$string['report_search_submit'] = 'Suchen';
$string['reportpage'] = 'Kurs-Proctoring-Zusammenfassung';
$string['setting:adminimagedescription'] = 'Diese Bilder werden als Basisbilder für die Gesichtsverifizierung verwendet. Bitte stellen Sie sicher, dass jedes Bild ein deutlich sichtbares Gesicht enthält.';
$string['setting:adminimagepage'] = 'Proctoring-Benutzerliste';

$string['setting:bs_api'] = 'BS-Dienst-API';
$string['setting:bs_api_key'] = 'BS API-Schlüssel';
$string['setting:bs_api_keydesc'] = 'Geben Sie den API-Schlüssel für den BS-Gesichtsabgleich-Dienst ein.';
$string['setting:bs_apidesc'] = 'BS-Dienst-API-Endpunkt.';
$string['setting:bs_apifacematchthreshold'] = 'Gesichtsabgleich-Schwellenwert (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'Der prozentuale Schwellenwert für die Gesichtsverifizierung mit dem BS-Dienst. (Standard: 68%)';
$string['setting:camshotdelay'] = 'Die Verzögerung zwischen Webcam-Bildern (Sekunden)';
$string['setting:camshotdelay_desc'] = 'Der angegebene Wert ist die Verzögerung in Sekunden zwischen jedem Webcam-Bild.';
$string['setting:camshotwidth'] = 'Die Breite des Webcam-Bildes (Pixel)';
$string['setting:camshotwidth_desc'] = 'Der angegebene Wert ist die Breite des Webcam-Bildes. Die Bildhöhe wird entsprechend skaliert.';
$string['setting:facematch'] = 'Anzahl der Gesichtsabgleiche pro Quiz';
$string['setting:facematchdesc'] = 'Anzahl der Gesichtsabgleich-Prüfungen. Verwenden Sie 0 oder weniger, um alle Snapshots zu überprüfen.';
$string['setting:fc_method'] = 'Gesichtsabgleich-Methode';
$string['setting:fc_methoddesc'] = 'Dienst zur Gesichtserkennung. Optionen: BS, Keine.';
$string['setting:fcthreshold'] = 'Gesichtsabgleich-Schwellenwert-Prozentsatz';
$string['setting:fcthresholddesc'] = 'Gesichtsabgleich-Schwellenwert-Prozentsatz';
$string['setting:uploaduserimages'] = 'Basisbild für Benutzer hochladen';
$string['setting:userslist'] = 'Benutzerbilder hochladen';
$string['setting:validation_max'] = 'Der Wert darf höchstens {$a} betragen.';
$string['setting:validation_min'] = 'Der Wert muss mindestens {$a} betragen.';
$string['settings:deleteallsuccess'] = 'Alle Einträge wurden erfolgreich gelöscht.';
$string['settings:deleteuserimagesuccess'] = 'Benutzerbild erfolgreich gelöscht.';
$string['settings:fcheckquizstart'] = 'Gesichtsvalidierung beim Quiz-Start';
$string['settings:fcheckquizstart_desc'] = 'Wenn aktiviert, müssen Benutzer ihr Gesicht validieren, bevor sie das Quiz starten können.';

$string['settingscontroll:deleteall'] = 'Alle während der Prüfungen erfassten Einträge löschen';
$string['settingscontroll:deleteallcourseimage'] = 'Alle Bilder und Aufzeichnungen von Schülern löschen, die während der Prüfungen für <b>diesen Kurs</b> erfasst wurden.';
$string['settingscontroll:deletealldescription'] = 'Dies wird dauerhaft alle erfassten Bilder und proctoring-bezogenen Daten löschen. Diese Aktion kann nicht rückgängig gemacht werden.';

$string['settingscontroll:deletealllinktext'] = 'Alle Einträge löschen';
$string['status'] = 'Validierungsstatus';
$string['studentreport'] = 'Schülerbericht';
$string['submit'] = 'Absenden';
$string['summarypagedesc'] = 'In diesem Bericht finden Sie die Zusammenfassung des Proctoring-Berichts für diesen Kurs und seine Quizze. Sie können alle mit dem Quiz und Kurs verbundenen Daten löschen. Dies löscht auch Bilddateien und Protokolle.';
$string['task:delete_images'] = 'Aufgabe zum Löschen von Bildern';
$string['timemodified'] = 'Zuletzt geändert';
$string['upload_first_image'] = 'Bitte laden Sie das Benutzerbild hoch.';
$string['upload_image'] = 'Bild hochladen';
$string['upload_image_heading'] = 'Benutzerbild hochladen';
$string['upload_image_info'] = 'Laden Sie Bilder in das System für die Benutzerverifizierung hoch. Dies hilft, die Integrität Ihrer Online-Quizze zu gewährleisten.';
$string['upload_image_link_text'] = 'Klicken Sie hier, um Benutzerbilder hochzuladen.';
$string['upload_image_message'] = 'Proctoring benötigt Benutzerbilder, um deren Identität zu authentifizieren.';
$string['upload_image_title'] = 'Bild für die Gesichts erkennung hochladen';
$string['uploadimagehere'] = 'Klicken Sie hier, um das Bild hochzuladen.';
$string['user'] = 'Benutzer';
$string['user_image_not_uploaded'] = 'Benutzerbild ist nicht hochgeladen. Bitte laden Sie das Bild hoch.';
$string['user_image_not_uploaded_teacher'] = 'Benutzerbild ist nicht hochgeladen. Bitte kontaktieren Sie den Administrator, um das Bild hochzuladen.';
$string['userimagenotuploaded'] = 'Benutzerbild nicht hochgeladen.';
$string['userlist'] = 'Benutzerliste';
$string['username'] = 'Benutzername';
$string['users_list'] = 'Proctoring für Moodle Benutzerliste';
$string['users_list_info_description'] = 'Diese Seite listet alle Benutzer auf, die ein Basisbild für Proctoring benötigen.
                                        Diese Bilder werden für den Gesichtsabgleich während der Quizze verwendet, um Authentifizierung zu gewährleisten und Identitätsdiebstahl zu verhindern.
                                        Wenn ein Bild nicht hochgeladen ist, kann der Benutzer möglicherweise nicht ordnungsgemäß während der überwachten Prüfungen verifiziert werden. Für weitere Funktionen wie angepasste Filterung, Suche und das Hochladen vieler Bilder auf einmal, ';
$string['videonotavailable'] = 'Videostream nicht verfügbar.';
$string['viewimages'] = 'Bilder anzeigen';
$string['warning:cameraallowwarning'] = 'Bitte erlauben Sie den Kamerazugriff.';
$string['warninglabel'] = 'Warnungen';
$string['webcam'] = 'Webcam';
$string['webcampicture'] = 'Erfasste Bilder';
$string['wrong_during_taking_image'] = 'Bei der Aufnahme des Bildes ist etwas schief gelaufen.';
$string['wrong_during_taking_screenshot'] = 'Bei der Aufnahme des Screenshots ist etwas schief gelaufen.';
$string['youmustagree'] = 'Sie müssen der Validierung Ihrer Identität zustimmen, um fortzufahren.';