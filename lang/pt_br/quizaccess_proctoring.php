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
$string['accessdenied'] = 'Acesso Negado';
$string['action_upload_image'] = 'Ação';
$string['actions'] = 'Ações';
$string['additional_settings'] = 'Configurações gerais';
$string['analyzbtn'] = 'Analisar';
$string['analyzbtnconfirm'] = 'Clique no botão Analisar para verificar a correspondência facial do usuário.';
$string['analyzimage'] = 'Analisar imagens';
$string['areyousure_delete_all_course_record'] = 'Tem certeza de que deseja excluir todas as imagens e registros dos alunos capturados durante os exames de <b>este curso?</b>';
$string['areyousure_delete_all_record'] = 'Tem certeza de que deseja excluir todas as imagens dos alunos capturadas durante os exames?';
$string['areyousure_delete_image'] = 'Deseja excluir esta imagem?';
$string['areyousure_delete_record'] = 'Tem certeza de que deseja excluir este registro?';
$string['back'] = 'Voltar';
$string['cancel_image_upload'] = 'Upload de imagem cancelado';
$string['confirmdeletioncourse'] = 'Tem certeza de que deseja excluir as imagens deste curso?';
$string['confirmdeletionquiz'] = 'Tem certeza de que deseja excluir as imagens deste questionário?';
$string['course_proctoring_summary'] = 'Relatório do Curso';
$string['dateverified'] = 'Data e hora';
$string['delete'] = 'Excluir';
$string['delete_images_task'] = 'Tarefa de exclusão de imagens';
$string['delete_images_task_desc'] = 'Excluir todas as imagens de proctoring';
$string['deleteallcourse'] = 'Excluir imagens do curso';
$string['deletequizdata'] = 'Excluir imagens do questionário';
$string['email']  = 'Endereço de e-mail';
$string['enable_web_camera_before_submitting'] = 'Você precisa habilitar a webcam antes de enviar este questionário!';
$string['eprotroringreports'] = 'Relatório de proctoring para: ';
$string['eprotroringreportsdesc'] = 'Neste relatório você encontrará todas as imagens dos alunos capturadas durante o exame. Agora você pode validar suas identidades, como sua foto de perfil e imagens da webcam.';
$string['error_face_not_found'] = 'Nenhum rosto foi encontrado na imagem. Entre em contato com o administrador.';
$string['error_invalid_report'] = 'Dados de relatório inválidos. Tente novamente.';
$string['examdata'] = 'Nenhum dado disponível para esta sessão de exame. Verifique a configuração do exame ou as configurações de monitoramento.';
$string['execute_facematch_task'] = 'Executar tarefa de correspondência facial';
$string['facefound'] = 'Rosto encontrado na imagem enviada.';
$string['facematch'] = 'Correspondência facial bem-sucedida. A identidade do aluno foi verificada.';
$string['facematched'] = 'Rosto correspondido.';
$string['facematchs'] = 'Todas as imagens foram analisadas com sucesso. Revise-as para verificar a correspondência facial.';
$string['facenotfound'] = 'Nenhum rosto foi encontrado na imagem enviada.';
$string['facenotfoundoncam'] = 'Nenhum rosto encontrado. Tente mudar sua câmera para uma iluminação melhor. Obrigado.';
$string['facenotmatched'] = 'Rosto não correspondido.';
$string['foundtext'] = 'Encontrado';
$string['identity_mismatch_label'] = 'Incompatibilidade de Identidade';
$string['image'] = 'Enviar Imagem';
$string['image_not_uploaded'] = 'A imagem enviada não contém nenhum rosto.';
$string['image_updated'] = 'Imagem atualizada';
$string['image_upload'] = 'Enviar imagem';
$string['info:cameraallow'] = 'Sua câmera agora está em uso.';
$string['initiate_facematch_task'] = 'Iniciar tarefa de correspondência facial';
$string['initiate_facematch_task_desc'] = 'Inicia uma tarefa de correspondência facial para comparar imagens para verificação de proctoring.';
$string['invalid_api'] = 'A chave API de BS fornecida é inválida.';
$string['invalid_facematch_method'] = 'Método de correspondência facial inválido nas configurações. Forneça credenciais válidas de API "BS" para o método de correspondência facial.';
$string['invalid_service_api'] = 'A API de serviço de BS fornecida é inválida.';
$string['invalidapi'] = 'A chave API de BS é inválida. Entre em contato com o administrador.';
$string['invalidsesskey'] = 'Chave de sessão inválida. Tente novamente.';
$string['invalidtype'] = 'O tipo fornecido é inválido.';
$string['mainsettingspagebtn'] = 'Configurações de proctoring';
$string['modal:facevalidation'] = 'Rosto validado:';
$string['modal:pending'] = 'Pendente';
$string['modal:validateface'] = 'Validar reconhecimento facial';
$string['name'] = 'Nome do aluno';
$string['no_permission'] = 'Você não possui permissões adequadas para ver esta página';
$string['nodata'] = 'Nenhum dado encontrado para os critérios fornecidos.';
$string['none'] = 'Nenhum';
$string['nopermission'] = 'Você não tem permissão para realizar esta ação.';
$string['notenrolled'] = 'Você não está matriculado neste curso ou não tem as permissões necessárias.';
$string['notfoundtext'] = 'Não Encontrado';
$string['notpermissionreport'] = 'Os relatórios de proctoring estão desabilitados para você.';
$string['notrequired'] = 'Não obrigatório';
$string['nousersfound'] = 'Nenhum usuário encontrado';
$string['numberofimages'] = 'Número de imagens';
$string['openwebcam'] = 'Permita sua webcam para continuar';
$string['photoalttext'] = 'A captura de tela aparecerá nesta caixa.';
$string['photonotuploaded'] = 'Foto não enviada. Entre em contato com o administrador.';
$string['picturesreport'] = 'Visualizar relatório de proctoring';
$string['picturesusedreport'] = 'Estas são as imagens capturadas durante o questionário.';
$string['plugin_description'] = 'O plugin de Proctoring para Moodle aprimora a segurança de questionários on-line capturando e verificando identidades de usuários através de imagens da webcam. É projetado para garantir que apenas usuários autorizados possam tentar o questionário, fornecendo uma solução de proctoring segura e confiável.';
$string['pluginname'] = 'Proctoring para Moodle';
$string['privacy:core_files'] = 'Imagens de webcam do QuizAccess Proctoring';
$string['privacy:metadata'] = 'Não compartilhamos dados pessoais com terceiros.';
$string['privacy:metadata:core_files'] = 'O Quiz Access armazena a imagem do usuário capturada pela webcam durante a tentativa de questionário.';
$string['privacy:metadata:courseid'] = 'O ID do curso que usa proctoring.';
$string['privacy:metadata:quizaccess_proctoring_logs'] = 'Tabela de logs de proctoring do acesso ao questionário do Moodle que armazena a imagem do usuário.';
$string['privacy:metadata:quizid'] = 'O ID do questionário que usa proctoring.';
$string['privacy:metadata:status'] = 'O status do proctoring.';
$string['privacy:metadata:userid'] = 'O ID do usuário que realizou o questionário.';
$string['privacy:metadata:webcampicture'] = 'O nome da imagem capturada pelo proctoring.';
$string['pro_version_description'] = 'Melhore seus exames on-line com o Moodle Proctoring Pro! Detecte troca de abas, monitore atividade da área de transferência, use reconhecimento facial para monitoramento em tempo real e acesse relatórios de proctoring detalhados para garantir avaliações justas e seguras.';
$string['pro_version_text'] = 'Saiba mais sobre a versão Pro';
$string['pro_version_products_text'] = 'Ver todos os produtos';
$string['pro_version_title_text'] = 'Obtenha Proctoring Pro.';
$string['proctoring:analyzeimages'] = 'Proctoring analisar imagens';
$string['proctoring:deletecamshots'] = 'Excluir imagens dos logs de proctoring.';
$string['proctoring:getcamshots'] = 'Proctoring obter imagens da webcam';
$string['proctoring:sendcamshot'] = 'Proctoring enviar foto da webcam';
$string['proctoring:viewreport'] = 'Proctoring visualizar relatório';
$string['proctoring_pro_promo'] = 'Promo do Proctoring Pro';
$string['proctoring_pro_promo:admin'] = 'Relatórios detalhados do admin';
$string['proctoring_pro_promo:adminlist1'] = 'Fornece uma visualização detalhada de todos os logs monitorados dos participantes.';
$string['proctoring_pro_promo:adminlist2'] = 'Permite baixar um relatório PDF abrangente.';
$string['proctoring_pro_promo:detectcopypaste'] = 'Detecção de fraude de copiar e colar';
$string['proctoring_pro_promo:detectcopypastelist1'] = 'Detecta qualquer ações de copiar e colar durante a tentativa do questionário.';
$string['proctoring_pro_promo:detectcopypastelist2'] = 'Registra cada tentativa de copiar ou colar texto.';
$string['proctoring_pro_promo:email'] = 'Suporte por e-mail';
$string['proctoring_pro_promo:emailsupport'] = 'Receba suporte direto por e-mail da nossa equipe.';
$string['proctoring_pro_promo:emailsupportlist1'] = 'Obtenha suporte por e-mail 24/7 para qualquer dúvida ou problema.';
$string['proctoring_pro_promo:feature'] = 'Recursos do Proctoring Pro';
$string['proctoring_pro_promo:featurelist1'] = 'Compatível com serviço de reconhecimento facial (AWS).';
$string['proctoring_pro_promo:featurelist2'] = 'Detecta se a webcam foi habilitada durante toda a tentativa.';
$string['proctoring_pro_promo:featurelist3'] = 'Detecta se o usuário se moveu para outro aplicativo/aba.';
$string['proctoring_pro_promo:featurelist4'] = 'Detecta se o usuário redimensionou a janela do navegador.';
$string['proctoring_pro_promo:featurelist5'] = 'Detecta se copiar e colar ocorreu durante a tentativa.';
$string['proctoring_pro_promo:featurelist6'] = 'Detecta se o usuário pressionou a tecla F12.';
$string['proctoring_pro_promo:featurelist7'] = 'Relatório detalhado do admin de cada evento registrado e imagens da webcam.';
$string['proctoring_pro_promo:featurelist8'] = 'Relatório resumido do admin de todos os usuários.';
$string['proctoring_pro_promo:featurelist9'] = 'Suporte por e-mail/correções de bugs';
$string['proctoring_pro_promo:header'] = 'Proteja seus exames on-line com a tecnologia de ponta do Proctoring Pro para monitoramento imbatível';
$string['proctoring_pro_promo:learnmore'] = 'Saiba mais';
$string['proctoring_pro_promo:mail'] = 'Entre em contato conosco em';
$string['proctoring_pro_promo:namefree'] = 'Proctoring (Gratuito)';
$string['proctoring_pro_promo:namepro'] = 'Proctoring Pro';
$string['proctoring_pro_promo:pdfgenerator'] = 'Geração de relatório PDF';
$string['proctoring_pro_promo:pdfgeneratordesc'] = 'Gera um relatório PDF detalhado para cada usuário, contendo todos os eventos registrados.';
$string['proctoring_pro_promo:profeature'] = 'Novidades no Proctoring Pro 2.0';
$string['proctoring_pro_promo:profeaturebulkphotoupload'] = 'Upload em lote de fotos';
$string['proctoring_pro_promo:profeaturebulkphotouploaddesc'] = 'Permite que administradores façam upload de imagens para vários usuários de uma vez através de um arquivo zip ou carreguem imagens individuais.';
$string['proctoring_pro_promo:profeaturehphotofillter'] = 'Filtragem de fotos';
$string['proctoring_pro_promo:profeaturehphotofillterdesc'] = 'Os administradores podem filtrar usuários com base no fato de sua foto estar carregada ou se o rosto do usuário está faltando nas imagens capturadas.';
$string['proctoring_pro_promo:screenmonitoring'] = 'Monitoramento do tamanho da tela';
$string['proctoring_pro_promo:screenmonitoringlist1'] = 'Detecta qualquer alteração no tamanho da tela durante a tentativa do questionário.';
$string['proctoring_pro_promo:screenmonitoringlist2'] = 'Registra cada instância quando o usuário redimensiona a janela do questionário.';
$string['proctoring_pro_promo:subheader'] = 'Obtenha o plugin Proctoring Pro agora.';
$string['proctoring_pro_promo:suscipiousevent'] = 'Outros eventos suspeitos';
$string['proctoring_pro_promo:suscipiouseventlist1'] = 'Detecta se a tecla F12 é pressionada durante o exame.';
$string['proctoring_pro_promo:suscipiouseventlist2'] = 'Registra cada instância quando o usuário pressiona F12 ao tentar o questionário.';
$string['proctoring_pro_promo:tabmonitoring'] = 'Monitoramento de abas em foco';
$string['proctoring_pro_promo:tabmonitoringlist1'] = 'Detecta se o usuário muda para outra janela ou aba.';
$string['proctoring_pro_promo:tabmonitoringlist2'] = 'Registra cada instância quando o usuário sai da aba ou janela do exame.';
$string['proctoring_pro_promo:webcam'] = 'Detecção de webcam';
$string['proctoring_pro_promo:webcamlist1'] = 'Detecta se a webcam permaneceu habilitada durante toda a tentativa do exame.';
$string['proctoring_pro_promo:webcamlist2'] = 'Registra qualquer instância quando a webcam é desabilitada.';
$string['proctoring_pro_promo_heading'] = 'Promo do Proctoring Pro';
$string['proctoring_report'] = 'Relatório de proctoring';
$string['proctoringheader'] = '<strong>Para continuar com esta tentativa de questionário, você deve abrir sua webcam, e algumas de suas imagens serão capturadas aleatoriamente durante o questionário.</strong>';
$string['proctoringlabel'] = 'Concordo com o processo de validação.';
$string['proctoringrequired'] = 'Validação de identidade por webcam';
$string['proctoringrequired_help'] = 'Habilitar proctoring requer que os alunos sejam monitorados usando webcam e gravação de tela durante a tentativa do questionário.';
$string['proctoringrequiredoption'] = 'Habilitar captura de webcam por Proctoring';
$string['proctoringstatement'] = 'Este exame requer acesso à webcam.<br />(Permita acesso à webcam).';
$string['provide_image'] = 'Forneça uma imagem para fazer upload.';
$string['quizaccess_proctoring'] = 'Quizaccess Proctoring';
$string['quiztitle'] = 'Título do Questionário';
$string['report_search_clear'] = 'Limpar';
$string['report_search_placeholder'] = 'Pesquisar por e-mail ou nome';
$string['report_search_submit'] = 'Pesquisar';
$string['reportpage'] = 'Resumo de Proctoring do Curso';
$string['setting:adminimagedescription'] = 'Essas imagens serão usadas como imagens base para verificação facial. Certifique-se de que cada imagem contenha um rosto claramente visível.';
$string['setting:adminimagepage'] = 'Lista de Usuários de Proctoring';

$string['setting:bs_api'] = 'API de serviço de BS';
$string['setting:bs_api_key'] = 'Chave API de BS';
$string['setting:bs_api_keydesc'] = 'Digite a chave API para o serviço de correspondência facial de BS.';
$string['setting:bs_apidesc'] = 'Endpoint de API de serviço de BS.';
$string['setting:bs_apifacematchthreshold'] = 'Limite de correspondência facial (BS)';
$string['setting:bs_bs_apifacematchthresholddesc'] = 'O percentual de limite para verificação facial usando o serviço de BS. (Padrão: 68%)';
$string['setting:camshotdelay'] = 'O atraso entre imagens da webcam (segundos)';
$string['setting:camshotdelay_desc'] = 'O valor fornecido será o atraso em segundos entre cada imagem da webcam.';
$string['setting:camshotwidth'] = 'A largura da imagem da webcam (pixels)';
$string['setting:camshotwidth_desc'] = 'O valor fornecido será a largura da imagem da webcam. A altura da imagem será dimensionada para corresponder a isso.';
$string['setting:facematch'] = 'Número de correspondências faciais por questionário';
$string['setting:facematchdesc'] = 'Número de verificações de correspondência facial. Use 0 ou menos para verificar todos os snapshots.';
$string['setting:fc_method'] = 'Método de correspondência facial';
$string['setting:fc_methoddesc'] = 'Serviço usado para corresponder rostos. Opções: BS, Nenhum.';
$string['setting:fcthreshold'] = 'Percentual de limite de correspondência facial';
$string['setting:fcthresholddesc'] = 'Percentual de limite de correspondência facial';
$string['setting:uploaduserimages'] = 'Fazer upload de imagem base para usuários';
$string['setting:userslist'] = 'Fazer upload de imagens de usuário';
$string['settings:deleteallsuccess'] = 'Todos os registros foram excluídos com sucesso.';
$string['settings:deleteuserimagesuccess'] = 'Imagem do usuário excluída com sucesso.';
$string['settings:fcheckquizstart'] = 'Validação facial no início do questionário';
$string['settings:fcheckquizstart_desc'] = 'Se habilitado, os usuários devem validar seu rosto antes de poderem iniciar o questionário.';

$string['settingscontroll:deleteall'] = 'Excluir todos os registros capturados durante os exames';
$string['settingscontroll:deleteallcourseimage'] = 'Excluir todas as imagens e registros dos alunos capturados durante os exames de <b>este curso</b>.';
$string['settingscontroll:deletealldescription'] = 'Isso excluirá permanentemente todas as imagens capturadas e dados relacionados a proctoring. Esta ação não pode ser desfeita.';

$string['settingscontroll:deletealllinktext'] = 'Excluir todos os registros';
$string['status'] = 'Status de validação';
$string['studentreport'] = 'Relatório do aluno';
$string['submit'] = 'Enviar';
$string['summarypagedesc'] = 'Neste relatório você encontrará o resumo do relatório de proctoring para este curso e seus questionários. Você pode excluir todos os dados relacionados ao questionário e ao curso. Isso excluirá arquivos de imagem e logs.';
$string['task:delete_images'] = 'Tarefa de exclusão de imagens';
$string['timemodified'] = 'Última modificação';
$string['upload_first_image'] = 'Faça upload da imagem do usuário.';
$string['upload_image'] = 'Fazer upload de imagem';
$string['upload_image_heading'] = 'Fazer upload de imagem de usuário';
$string['upload_image_info'] = 'Faça upload de imagens para o sistema para verificação de usuário. Isso ajuda a garantir a integridade de seus questionários on-line.';
$string['upload_image_link_text'] = 'Clique aqui para fazer upload de imagens de usuário.';
$string['upload_image_message'] = 'O Proctoring precisa de imagens de usuário para autenticar suas identidades.';
$string['upload_image_title'] = 'Fazer upload de imagem para detecção facial';
$string['uploadimagehere'] = 'Clique aqui para fazer upload da imagem.';
$string['user'] = 'Usuários';
$string['user_image_not_uploaded'] = 'A imagem do usuário não foi carregada. Faça upload da imagem.';
$string['user_image_not_uploaded_teacher'] = 'A imagem do usuário não foi carregada. Entre em contato com o administrador para fazer upload da imagem.';
$string['userimagenotuploaded'] = 'Imagem do usuário não enviada.';
$string['userlist'] = 'Lista de usuários';
$string['username'] = 'Nome de Usuário';
$string['users_list'] = 'Lista de usuários do Proctoring para Moodle';
$string['users_list_info_description'] = 'Esta página lista todos os usuários que necessitam de uma imagem base para proctoring.
                                        Essas imagens serão usadas para correspondência facial durante questionários para garantir autenticação e evitar personificação.
                                        Se uma imagem não for carregada, o usuário pode não ser verificado corretamente durante exames monitorados. Para obter mais recursos, como filtragem personalizada, pesquisa e carregamento de muitas imagens de uma vez, ';
$string['videonotavailable'] = 'Fluxo de vídeo não disponível.';
$string['viewimages'] = 'Visualizar imagens';
$string['warning:cameraallowwarning'] = 'Permita o acesso à câmera.';
$string['warninglabel'] = 'Avisos';
$string['webcam'] = 'Webcam';
$string['webcampicture'] = 'Imagens capturadas';
$string['wrong_during_taking_image'] = 'Algo deu errado ao capturar a imagem.';
$string['wrong_during_taking_screenshot'] = 'Algo deu errado ao capturar a screenshot.';
$string['youmustagree'] = 'Você deve concordar em validar sua identidade antes de continuar.';
