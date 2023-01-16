<?php
/**
 * RSb Parser mainscript
 *
 * @license    GPLv3 (https://www.gnu.org/licenses/gpl-3.0.en.html)
 * @author     Christian Trenkwalder <christian@karatemuffin.it>
 *
 */
 
require_once('config.php');
require_once(__DIR__ . '/vendor/autoload.php');



use Mpdf\Mpdf;


$json_data = json_decode($_POST['data']);

ini_set('auto_detect_line_endings',FALSE);

$mpdf = new Mpdf([
	'format' => 'A4',
	'margin_left' => 0,
	'margin_right' => 0,
	'margin_top' => 0,
	'margin_bottom' => 0,
	'margin_header' => 0,
	'margin_footer' => 0,
]);

$mpdf->SetCreator($conf['creator']);
$mpdf->SetCreator($conf['title']);

$pagecount = $mpdf->SetSourceFile('data/template1.pdf');
$tplId = $mpdf->ImportPage($pagecount);
$mpdf->SetPageTemplate($tplId);

//@param x left start
//@param y top start
//@param w width
//@param txt text to print
function writeFieldDiv($x,$y,$w,$txt,$size=-1){
	global $mpdf, $conf;
	if ($size >0){
		$mpdf->WriteHTML('<div style="border-width: '.$conf['border-width'].'; border-style: '.$conf['border-style'].'; font-size: '.$size.'; position: absolute; top: '.$y.'mm; left: '.$x.'mm; width: '.$w.'mm;">'.$txt.'</div>');
	} else {
		$mpdf->WriteHTML('<div style="border-width: '.$conf['border-width'].'; border-style: '.$conf['border-style'].'; font-size: '.$conf['font-size'].'; position: absolute; top: '.$y.'mm; left: '.$x.'mm; width: '.$w.'mm;">'.$txt.'</div>');
	}
}

//@param $txt the class in the form 2AHET
//@return calculated inscription year with WS as prefix
function getInscriptionYear($txt){
	$txt = (int)substr($txt,0,1);
	return 'WS'.date("Y")-$txt;
}

//@param $txt the class in the form 2AHET
//@return calculated inscription year with WS as prefix
function getYearOffset($txt){
	$txt = (2*(int)substr($txt,0,1))-4;
	if(date("m") > 8){
		return $txt-1;
	}else if(date("m") < 3){
		return $txt;
	}
	return $txt+1;
}

//@return the actual schoolyear, where we assume that we only use this in the SS
function getSchoolYear(){
	return (date("Y")-1).'/'.date("Y");
}

//@return the actual semester, where we say before march = WS later = SS
function getActualSemester(){
	if(date("m") > 8){
		return 'WS'.date("Y");
	}else if(date("m") < 3){
		return 'WS'.date("Y")-1;
	}
	return 'SS'.date("Y");
}

//@param txt the class in form 2AHET
//@return the department name
function getDepartment($txt){
	global $conf;
	foreach ($conf['department'] as $key => $value) {
		if (strpos($txt, $key) !== FALSE) {
			return $value;
		}
	}
	return 'Department not found.';
}


foreach ($json_data as $offset => $item) {
// Add First page
	$mpdf->AddPage();
//Nachname und Nachname
	writeFieldDiv(55,47,70,strtoupper($item[0]));
//Schuljahr
	writeFieldDiv(150,47,90,getSchoolYear()); 
//Gegenstand Lang
	writeFieldDiv(75,60,87,$conf['course'][$item[2]],10);
//Gegenstand Kurz
	writeFieldDiv(180,60,20,$item[2]);
//Klasse
	writeFieldDiv(35,82,30,$item[1]);
//Jahrgang
	writeFieldDiv(63+(getYearOffset($item[1])*17.5),82,10,'<b>X</b>',24);
//Prüfer
	writeFieldDiv(50,67,100,$item[3]);
//Kürzel
	writeFieldDiv(180,67,20,$item[4]);
}

$mpdf->Output($conf['title'].'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
