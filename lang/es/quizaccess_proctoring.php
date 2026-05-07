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
$string['accessdenied'] = 'Acceso denegado';
$string['action_upload_image'] = 'Acción';
$string['actions'] = 'Acciones';
$string['additional_settings'] = 'Configuración general';
$string['analyzbtn'] = 'Analizar';
$string['analyzbtnconfirm'] = 'Haga clic en el botón Analizar para comprobar la coincidencia facial del usuario.';
$string['analyzimage'] = 'Analizar imágenes';
$string['areyousure_delete_all_course_record'] = '¿Está seguro de que desea eliminar todas las imágenes y registros de los estudiantes capturados durante los exámenes de <b>este curso?</b>';
$string['areyousure_delete_all_record'] = '¿Está seguro de que desea eliminar todas las imágenes de los estudiantes capturadas durante los exámenes?';
$string['areyousure_delete_image'] = '¿Desea eliminar esta imagen?';
$string['areyousure_delete_record'] = '¿Está seguro de que desea eliminar este registro?';
$string['back'] = 'Atrás';
$string['cancel_image_upload'] = 'Carga de imagen cancelada';
$string['confirmdeletioncourse'] = '¿Está seguro de que desea eliminar las imágenes de este curso?';
$string['confirmdeletionquiz'] = '¿Está seguro de que desea eliminar las imágenes de este cuestionario?';
$string['course_proctoring_summary'] = 'Informe del curso';
$string['dateverified'] = 'Fecha y hora';
$string['delete'] = 'Eliminar';
$string['delete_images_task'] = 'Tarea de eliminación de imágenes';
$string['delete_images_task_desc'] = 'Eliminar todas las imágenes de supervisión';
$string['deleteallcourse'] = 'Eliminar imágenes del curso';
$string['deletequizdata'] = 'Eliminar imágenes del cuestionario';
$string['email']  = 'Dirección de correo electrónico';
$string['enable_web_camera_before_submitting'] = 'Debe habilitar la cámara web antes de enviar este cuestionario.';
$string['eprotroringreports'] = 'Informe de supervisión para: ';
$string['eprotroringreportsdesc'] = 'En este informe encontrará todas las imágenes de los estudiantes tomadas durante el examen. Ahora puede validar su identidad, como su foto de perfil y las imágenes de la cámara web.';
$string['error_face_not_found'] = 'No se encontró un rostro en la imagen. Póngase en contacto con el administrador.';
$string['error_invalid_report'] = 'Datos del informe no válidos. Inténtelo de nuevo.';
$string['examdata'] = 'No hay datos disponibles para esta sesión de examen. Compruebe la configuración del examen o de supervisión.';
$string['execute_facematch_task'] = 'Ejecutar tarea de coincidencia facial';
$string['facefound'] = 'Se encontró un rostro en la imagen subida.';
$string['facematch'] = 'Coincidencia facial correcta. La identidad del estudiante ha sido verificada.';
$string['facematched'] = 'Rostro coincidente.';
$string['facematchs'] = 'Todas las imágenes se han analizado correctamente. Revíselas para verificar la coincidencia facial.';
$string['facenotfound'] = 'No se encontró ningún rostro en la imagen subida.';
$string['facenotfoundoncam'] = 'No se encontró ningún rostro. Intente cambiar la cámara o mejorar la iluminación. Gracias.';
$string['facenotmatched'] = 'El rostro no coincide.';
$string['foundtext'] = 'Encontrado';
$string['identity_mismatch_label'] = 'Incompatibilidad de identidad';
$string['image'] = 'Subir imagen';
$string['image_not_uploaded'] = 'La imagen subida no contiene ningún rostro.';
$string['image_updated'] = 'Imagen actualizada';
$string['image_upload'] = 'Subir imagen';
$string['info:cameraallow'] = 'Su cámara está ahora en uso.';
$string['initiate_facematch_task'] = 'Iniciar tarea de coincidencia facial';
$string['initiate_facematch_task_desc'] = 'Inicia una tarea de coincidencia facial para comparar imágenes con fines de verificación en la supervisión.';
$string['invalid_api'] = 'La clave de API de BS proporcionada no es válida.';
$string['invalid_facematch_method'] = 'Método de coincidencia facial no válido en la configuración. Proporcione credenciales válidas de la API "BS" para este método.';
$string['invalid_service_api'] = 'La API del servicio BS proporcionada no es válida.';
$string['invalidapi'] = 'La clave de API de BS no es válida. Póngase en contacto con el administrador.';
$string['invalidsesskey'] = 'Clave de sesión no válida. Inténtelo de nuevo.';
$string['invalidtype'] = 'El tipo proporcionado no es válido.';
$string['mainsettingspagebtn'] = 'Configuración de supervisión';
$string['modal:facevalidation'] = 'Rostro validado:';
$string['modal:pending'] = 'Pendiente';
$string['modal:validateface'] = 'Validar reconocimiento facial';
$string['name'] = 'Nombre del estudiante';
$string['no_permission'] = 'No tiene los permisos adecuados para ver esta página';
$string['nodata'] = 'No se encontraron datos para los criterios indicados.';
$string['none'] = 'Ninguno';
$string['nopermission'] = 'No tiene permiso para realizar esta acción.';
$string['notenrolled'] = 'No está inscrito en este curso o no tiene los permisos necesarios.';
$string['notfoundtext'] = 'No encontrado';
$string['notpermissionreport'] = 'Los informes de supervisión están deshabilitados para usted.';
$string['notrequired'] = 'No requerido';
$string['nousersfound'] = 'No se encontraron usuarios';
$string['numberofimages'] = 'Número de imágenes';
$string['openwebcam'] = 'Permita su cámara web para continuar';
$string['photoalttext'] = 'La captura de pantalla aparecerá en este recuadro.';
$string['photonotuploaded'] = 'Foto no subida. Póngase en contacto con el administrador.';
$string['picturesreport'] = 'Ver informe de supervisión';
$string['picturesusedreport'] = 'Estas son las imágenes capturadas durante el cuestionario.';
$string['plugin_description'] = 'El complemento Proctoring para Moodle mejora la seguridad de los cuestionarios en línea capturando y verificando la identidad de los usuarios mediante imágenes de la cámara web. Está diseñado para garantizar que solo los usuarios autorizados puedan realizar el cuestionario, ofreciendo una solución de supervisión segura y fiable.';
$string['pluginname'] = 'Proctoring para Moodle';
$string['privacy:core_files'] = 'Imágenes de cámara web de QuizAccess Proctoring';
$string['privacy:metadata'] = 'No compartimos ningún dato personal con terceros.';
$string['privacy:metadata:core_files'] = 'Quiz Access almacena la imagen del usuario tomada por la cámara web durante el intento del cuestionario.';
$string['privacy:metadata:courseid'] = 'El ID del curso que usa supervisión.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Tabla de registros de supervisión de acceso al cuestionario de Moodle que almacena la imagen del usuario.';
$string['privacy:metadata:quizid'] = 'El ID del cuestionario que usa supervisión.';
$string['privacy:metadata:status'] = 'El estado de la supervisión.';
$string['privacy:metadata:userid'] = 'El ID del usuario que realizó el cuestionario.';
$string['privacy:metadata:webcampicture'] = 'El nombre de la imagen tomada por el sistema de supervisión.';
$string['pro_version_description'] = 'Mejore sus exámenes en línea con Moodle Proctoring Pro. Detecte cambios de pestaña, supervise la actividad del portapapeles, use reconocimiento facial para monitoreo en tiempo real y acceda a informes detallados de supervisión para garantizar evaluaciones justas y seguras.';
$string['pro_version_text'] = 'Obtenga más información sobre la versión Pro';
$string['pro_version_products_text'] = 'Ver todos los productos';
$string['pro_version_title_text'] = 'Obtenga Proctoring Pro.';
$string['proctoring:analyzeimages'] = 'Analizar imágenes de supervisión';
$string['proctoring:deletecamshots'] = 'Eliminar imágenes de los registros de supervisión.';
$string['proctoring:getcamshots'] = 'Obtener imágenes de cámara web de supervisión';
$string['proctoring:sendcamshot'] = 'Enviar foto de cámara web de supervisión';
$string['proctoring:viewreport'] = 'Ver informe de supervisión';
$string['proctoring_pro_promo'] = 'Promoción de Proctoring Pro';
$string['proctoring_pro_promo:admin'] = 'Informes detallados para administradores';
$string['proctoring_pro_promo:adminlist1'] = 'Ofrece una vista detallada de todos los registros supervisados de los participantes.';
$string['proctoring_pro_promo:adminlist2'] = 'Permite descargar un informe PDF completo.';
$string['proctoring_pro_promo:detectcopypaste'] = 'Detección de fraude por copiar y pegar';
$string['proctoring_pro_promo:detectcopypastelist1'] = 'Detecta cualquier acción de copiar y pegar durante el intento del cuestionario.';
$string['proctoring_pro_promo:detectcopypastelist2'] = 'Registra cada intento de copiar o pegar texto.';
$string['proctoring_pro_promo:email'] = 'Soporte por correo electrónico';
$string['proctoring_pro_promo:emailsupport'] = 'Reciba soporte directo por correo electrónico de nuestro equipo.';
$string['proctoring_pro_promo:emailsupportlist1'] = 'Obtenga soporte por correo electrónico 24/7 para cualquier consulta o problema.';
$string['proctoring_pro_promo:feature'] = 'Funciones de Proctoring Pro';
$string['proctoring_pro_promo:featurelist1'] = 'Compatible con el servicio de reconocimiento facial (AWS).';
$string['proctoring_pro_promo:featurelist2'] = 'Detecta si la cámara web estuvo habilitada durante todo el intento.';
$string['proctoring_pro_promo:featurelist3'] = 'Detecta si el usuario se ha movido a otra aplicación o pestaña.';
$string['proctoring_pro_promo:featurelist4'] = 'Detecta si el usuario ha redimensionado la ventana del navegador.';
$string['proctoring_pro_promo:featurelist5'] = 'Detecta si se produjo una acción de copiar y pegar durante el intento.';
$string['proctoring_pro_promo:featurelist6'] = 'Detecta si el usuario ha pulsado la tecla F12.';
$string['proctoring_pro_promo:featurelist7'] = 'Informe detallado para administradores de cada evento registrado e imágenes de la cámara web.';
$string['proctoring_pro_promo:featurelist8'] = 'Informe resumido para administradores de todos los usuarios.';
$string['proctoring_pro_promo:featurelist9'] = 'Soporte por correo electrónico y corrección de errores';
$string['proctoring_pro_promo:header'] = 'Proteja sus exámenes en línea con la tecnología de vanguardia de Proctoring Pro para una supervisión inigualable';
$string['proctoring_pro_promo:learnmore'] = 'Más información';
$string['proctoring_pro_promo:mail'] = 'Contáctenos en';
$string['proctoring_pro_promo:namefree'] = 'Proctoring (Gratis)';
$string['proctoring_pro_promo:namepro'] = 'Proctoring Pro';
$string['proctoring_pro_promo:pdfgenerator'] = 'Generación de informes PDF';
$string['proctoring_pro_promo:pdfgeneratordesc'] = 'Genera un informe PDF detallado para cada usuario, que contiene todos los eventos registrados.';
$string['proctoring_pro_promo:profeature'] = 'Novedades de Proctoring Pro 2.0';
$string['proctoring_pro_promo:profeaturebulkphotoupload'] = 'Carga masiva de fotos';
$string['proctoring_pro_promo:profeaturebulkphotouploaddesc'] = 'Permite a los administradores subir imágenes para varios usuarios a la vez mediante un archivo zip o subir imágenes individuales.';
$string['proctoring_pro_promo:profeaturehphotofillter'] = 'Filtrado de fotos';
$string['proctoring_pro_promo:profeaturehphotofillterdesc'] = 'Los administradores pueden filtrar usuarios según si su foto está subida o si falta el rostro del usuario en las imágenes capturadas.';
$string['proctoring_pro_promo:screenmonitoring'] = 'Supervisión del tamaño de pantalla';
$string['proctoring_pro_promo:screenmonitoringlist1'] = 'Detecta cualquier cambio en el tamaño de la pantalla durante el intento del cuestionario.';
$string['proctoring_pro_promo:screenmonitoringlist2'] = 'Registra cada vez que el usuario cambia el tamaño de la ventana del cuestionario.';
$string['proctoring_pro_promo:subheader'] = 'Obtenga ahora el complemento Proctoring Pro.';
$string['proctoring_pro_promo:suscipiousevent'] = 'Otros eventos sospechosos';
$string['proctoring_pro_promo:suscipiouseventlist1'] = 'Detecta si se pulsa la tecla F12 durante el examen.';
$string['proctoring_pro_promo:suscipiouseventlist2'] = 'Registra cada vez que el usuario pulsa F12 mientras realiza el cuestionario.';
$string['proctoring_pro_promo:tabmonitoring'] = 'Supervisión del enfoque de pestañas';
$string['proctoring_pro_promo:tabmonitoringlist1'] = 'Detecta si el usuario cambia a otra ventana o pestaña.';
$string['proctoring_pro_promo:tabmonitoringlist2'] = 'Registra cada vez que el usuario abandona la pestaña o ventana del examen.';
$string['proctoring_pro_promo:webcam'] = 'Detección de cámara web';
$string['proctoring_pro_promo:webcamlist1'] = 'Detecta si la cámara web permaneció habilitada durante todo el intento del examen.';
$string['proctoring_pro_promo:webcamlist2'] = 'Registra cualquier instancia en la que la cámara web se deshabilite.';
$string['proctoring_pro_promo_heading'] = 'Promoción de Proctoring Pro';
$string['proctoring_report'] = 'Informe de supervisión';
$string['proctoringheader'] = '<strong>Para continuar con este intento de cuestionario debe abrir su cámara web, y se tomarán algunas de sus fotos aleatoriamente durante el cuestionario.</strong>';
$string['proctoringlabel'] = 'Acepto el proceso de validación.';
$string['proctoringrequired'] = 'Validación de identidad por cámara web';
$string['proctoringrequired_help'] = 'Al habilitar la supervisión, se requiere que los estudiantes sean monitoreados mediante la cámara web y la grabación de pantalla durante el intento del cuestionario.';
$string['proctoringrequiredoption'] = 'Habilitar captura de cámara web mediante Proctoring';
$string['proctoringstatement'] = 'Este examen requiere acceso a la cámara web.<br />(Permita el acceso a la cámara web).';
$string['provide_image'] = 'Proporcione una imagen para subir.';
$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';
$string['quiztitle'] = 'Título del cuestionario';
$string['report_search_clear'] = 'Limpiar';
$string['report_search_placeholder'] = 'Buscar por correo electrónico o nombre';
$string['report_search_submit'] = 'Buscar';
$string['reportpage'] = 'Resumen de supervisión del curso';
$string['setting:adminimagedescription'] = 'Estas imágenes se utilizarán como imágenes base para la verificación facial. Asegúrese de que cada imagen contenga un rostro claramente visible.';
$string['setting:adminimagepage'] = 'Lista de usuarios de supervisión';

$string['setting:bs_api'] = 'API del servicio BS';
$string['setting:bs_api_key'] = 'Clave API de BS';
$string['setting:bs_api_keydesc'] = 'Introduzca la clave API para el servicio BS de coincidencia facial.';
$string['setting:bs_apidesc'] = 'Punto final de la API del servicio BS.';
$string['setting:bs_apifacematchthreshold'] = 'Umbral de coincidencia facial (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'El porcentaje de umbral para la verificación facial mediante el servicio BS. (Predeterminado: 68%)';
$string['setting:camshotdelay'] = 'Intervalo entre imágenes de la cámara web (segundos)';
$string['setting:camshotdelay_desc'] = 'El valor indicado será el intervalo en segundos entre cada imagen de la cámara web.';
$string['setting:camshotwidth'] = 'Ancho de la imagen de la cámara web (píxeles)';
$string['setting:camshotwidth_desc'] = 'El valor indicado será el ancho de la imagen de la cámara web. La altura se ajustará proporcionalmente a este valor.';
$string['setting:facematch'] = 'Número de coincidencias faciales por cuestionario';
$string['setting:facematchdesc'] = 'Número de comprobaciones de coincidencia facial. Use 0 o un valor menor para comprobar todas las capturas.';
$string['setting:fc_method'] = 'Método de coincidencia facial';
$string['setting:fc_methoddesc'] = 'Servicio utilizado para comparar rostros. Opciones: BS, Ninguno.';
$string['setting:fcthreshold'] = 'Porcentaje de umbral de coincidencia facial';
$string['setting:fcthresholddesc'] = 'Porcentaje de umbral de coincidencia facial';
$string['setting:uploaduserimages'] = 'Subir imagen base de los usuarios';
$string['setting:userslist'] = 'Subir imágenes de usuarios';
$string['settings:deleteallsuccess'] = 'Todos los registros se eliminaron correctamente.';
$string['settings:deleteuserimagesuccess'] = 'La imagen del usuario se eliminó correctamente.';
$string['settings:fcheckquizstart'] = 'Validación facial al comenzar el cuestionario';
$string['settings:fcheckquizstart_desc'] = 'Si está habilitado, los usuarios deberán validar su rostro antes de poder comenzar el cuestionario.';

$string['settingscontroll:deleteall'] = 'Eliminar todos los registros capturados durante los exámenes';
$string['settingscontroll:deleteallcourseimage'] = 'Eliminar todas las imágenes y registros de los estudiantes capturados durante los exámenes de <b>este curso</b>.';
$string['settingscontroll:deletealldescription'] = 'Esto eliminará permanentemente todas las imágenes capturadas y los datos relacionados con la supervisión. Esta acción no se puede deshacer.';

$string['settingscontroll:deletealllinktext'] = 'Eliminar todos los registros';
$string['status'] = 'Estado de validación';
$string['studentreport'] = 'Informe del estudiante';
$string['submit'] = 'Enviar';
$string['summarypagedesc'] = 'En este informe encontrará el resumen del informe de supervisión para este curso y sus cuestionarios. Puede eliminar todos los datos relacionados con el cuestionario y el curso. Esto eliminará los archivos de imagen, así como los registros.';
$string['task:delete_images'] = 'Tarea de eliminación de imágenes';
$string['timemodified'] = 'Última modificación';
$string['upload_first_image'] = 'Suba la imagen del usuario.';
$string['upload_image'] = 'Subir imagen';
$string['upload_image_heading'] = 'Subir imagen del usuario';
$string['upload_image_info'] = 'Suba imágenes al sistema para la verificación de usuarios. Esto ayuda a garantizar la integridad de sus cuestionarios en línea.';
$string['upload_image_link_text'] = 'Haga clic aquí para subir imágenes de usuarios.';
$string['upload_image_message'] = 'Proctoring necesita imágenes de los usuarios para autenticar su identidad.';
$string['upload_image_title'] = 'Subir imagen para detección facial';
$string['uploadimagehere'] = 'Haga clic aquí para subir la imagen.';
$string['user'] = 'Usuarios';
$string['user_image_not_uploaded'] = 'La imagen del usuario no está subida. Súbala, por favor.';
$string['user_image_not_uploaded_teacher'] = 'La imagen del usuario no está subida. Póngase en contacto con el administrador para subirla.';
$string['userimagenotuploaded'] = 'La imagen del usuario no está subida.';
$string['userlist'] = 'Lista de usuarios';
$string['username'] = 'Nombre de usuario';
$string['users_list'] = 'Lista de usuarios de Proctoring para Moodle';
$string['users_list_info_description'] = 'Esta página muestra todos los usuarios que requieren una imagen base para la supervisión.
                                        Estas imágenes se utilizarán para la coincidencia facial durante los cuestionarios, con el fin de garantizar la autenticación y evitar la suplantación de identidad.
                                        Si no se sube una imagen, es posible que el usuario no pueda ser verificado correctamente durante los exámenes supervisados. Para obtener más funciones, como filtrado personalizado, búsqueda y carga de muchas imágenes a la vez, ';
$string['videonotavailable'] = 'La transmisión de video no está disponible.';
$string['viewimages'] = 'Ver imágenes';
$string['warning:cameraallowwarning'] = 'Permita el acceso a la cámara.';
$string['warninglabel'] = 'Advertencias';
$string['webcam'] = 'Cámara web';
$string['webcampicture'] = 'Imágenes capturadas';
$string['wrong_during_taking_image'] = 'Algo salió mal al tomar la imagen.';
$string['wrong_during_taking_screenshot'] = 'Algo salió mal al tomar la captura de pantalla.';
$string['youmustagree'] = 'Debe aceptar validar su identidad antes de continuar.';
