<?php
require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__ .'/lib.php');
echo "success";
echo "<br/>";
$image1 = "http://localhost/moodle310/pluginfile.php/130/quizaccess_proctoring/picture/364/webcam-364-2-2-1626172597217.png";
$image2 = "http://localhost/moodle310/pluginfile.php/130/quizaccess_proctoring/picture/364/webcam-364-2-2-1626172604319.png";
$checkSimilarityBS = check_similarity_bs($image1,$image2);

$jsonarray = json_decode($checkSimilarityBS,true);
$process = $jsonarray["process"];
$facematched = $jsonarray["facematched"];

echo $facematched;
echo "<br/>";
var_dump($jsonarray);


//$checkSimilarityAws = check_similarity_aws($image1,$image2);
//var_dump($checkSimilarityBS);
//echo "<br/>";
//var_dump($checkSimilarityAws);

//$fs = get_file_storage();
//echo "<pre>";
//print_r($fs);
//echo "</pre>";