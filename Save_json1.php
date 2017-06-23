<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php



$timezone = date_default_timezone_get();

//echo phpinfo();

//echo "The current server timezone is: " . $timezone;
$date = date('m/d/Y h:i:s a', time());
echo $date;

$pagina_inicio = file_get_contents('http://siga.jalisco.gob.mx/calidadaire/imecashoras2.svc/Meteoro/');
echo $pagina_inicio;
echo "\n";

$dia = date('d', time());
$mes = date('m', time());
$hora = date('h', time());
$am_pm = date('a', time());

$fichero = '/root/downloaddata/Data/Meteoro_'.$dia.'_'.$mes.'_'.$hora.'_'.$am_pm.'.txt';
// Abre el fichero para obtener el contenido existente
//$actual = file_get_contents($fichero);
// Añade una nueva persona al fichero

// Escribe el contenido al fichero
file_put_contents($fichero, $pagina_inicio);


$pagina_inicio = file_get_contents('http://siga.jalisco.gob.mx/calidadaire/imecashoras2.svc/imecas/');
echo $pagina_inicio;
echo "\n";

$dia = date('d', time());
$mes = date('m', time());
$hora = date('h', time());
$am_pm = date('a', time());

$fichero = '/root/downloaddata/Data/imecas_'.$dia.'_'.$mes.'_'.$hora.'_'.$am_pm.'.txt';
// Abre el fichero para obtener el contenido existente
//$actual = file_get_contents($fichero);
// Añade una nueva persona al fichero

// Escribe el contenido al fichero
file_put_contents($fichero, $pagina_inicio);


$pagina_inicio = file_get_contents('http://siga.jalisco.gob.mx/calidadaire/imecashoras2.svc/particulas/');

$dia = date('d', time());
$mes = date('m', time());
$hora = date('h', time());
$am_pm = date('a', time());

$fichero = '/root/downloaddata/Data/Part_'.$dia.'_'.$mes.'_'.$hora.'_'.$am_pm.'.txt';
// Abre el fichero para obtener el contenido existente
//$actual = file_get_contents($fichero);
// Añade una nueva persona al fichero

// Escribe el contenido al fichero
file_put_contents($fichero, $pagina_inicio);

//	-------------------------- Codigo para generar el arhivo para predecciones

$json_part = file_get_contents('http://siga.jalisco.gob.mx/calidadaire/imecashoras2.svc/particulas/');

$json_met = file_get_contents('http://siga.jalisco.gob.mx/calidadaire/imecashoras2.svc/Meteoro/');

$json_part = str_replace(':"[{',":[{",$json_part);
$json_part = str_replace('}]"}',"}]}",$json_part);
$json_part = str_replace('\\', '', $json_part);

$json_met  = str_replace(':"[{',":[{",$json_met);
$json_met  = str_replace('}]"}',"}]}",$json_met);
$json_met  = str_replace('\\', '',$json_met);

$jsondecoded = json_decode($json_part, true);
//echo json_last_error();
$jsondecodedmet = json_decode($json_met, true);

//echo json_last_error();
$casetas = array_values($jsondecoded["ParticulasHorarioResult"]);

$casetas_met = array_values($jsondecodedmet["MeteoroHorarioResult"]);

$lista = array ();
for ($i = 0; $i < count($casetas); $i++) {
	
	array_push($lista, array ($casetas[$i]["CASETA"],$casetas[$i]["O3"],$casetas[$i]["NO2"],$casetas[$i]["CO"],$casetas[$i]["SO2"],$casetas[$i]["PM10"]));
}

$met = array();
for ($i = 0; $i < count($casetas_met); $i++) {
	array_push($met, array ($casetas_met[$i]["CASETA"],$casetas_met[$i]["TMP"],$casetas_met[$i]["RH"],$casetas_met[$i]["WDR"],$casetas_met[$i]["WSP"]));
}


$fp = fopen('/var/www/html/kml/Data/tabla'.$dia.'_'.$mes.'_'.$hora.'_'.$am_pm.'.csv', 'w'); // para quitar el nombre de las estaciones, solo poner j = 1
for ($j = 0; $j < 6; $j++) {
	fputcsv($fp, array($lista[0][$j],$lista[1][$j],$lista[2][$j],$lista[3][$j],$lista[4][$j],$lista[5][$j],$lista[6][$j],$lista[7][$j],$lista[8][$j],$lista[9][$j]));
	
}

for ($j = 1; $j < 5; $j++) {
	fputcsv($fp, array($met[0][$j],$met[1][$j],$met[2][$j],$met[3][$j],$met[4][$j],$met[5][$j],$met[6][$j],$met[7][$j],$met[8][$j],$met[9][$j]));
	
}



?>
</body>
</html>
