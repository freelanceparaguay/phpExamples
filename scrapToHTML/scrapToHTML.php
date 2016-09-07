#!/usr/bin/php
<?php
/*
******************************************************************************************************
# Septiembre 2016
# http://otroblogdetecnologias.blogspot.com


SCRAPPING HTML a HTML
---------------
Este script permite realizar un scrap desde una página html y descargar
los resultados maquetados en un archivo html
El proceso se compone de los siguientes pasos:

1)Descargar la página html.
2)Procesar el archivo descargado para emitir un resultado


Se incluye un archivo de prueba con una tabla, de la cual se pretende obtener las etiquetas <tr>
******************************************************************************************************
*/

error_reporting(E_ERROR);

/* variables globales de configuracion */
//Se configuran los nombres de archivos
$paginaParsear="http://americanbet.com.py/";
$fileParsear="descarga.html";
$fileTextAlert="apuesta.html";

/*
	Función para obtener la página para procesar
	Crea un archivo tomando información desde una página web
*/
function getHTML() {
	global $paginaParsear,$fileParsear;
	//inicializa los parametros de la pagina a descargar
	$ch = curl_init($paginaParsear);
	$fp = fopen($fileParsear, "w"); //apertura del archivo en modo escritura

	curl_setopt($ch, CURLOPT_FILE, $fp); //parametro para escritura
	curl_setopt($ch, CURLOPT_HEADER, 0); //inclusion del header del archivo
	
	curl_exec($ch); //descarga de la pagina
	curl_close($ch); //cierre del flujo
	fclose($fp); //cierre del archivo descargado
}//fin getHTML


/*
	Procesa el archivo html descargado. Realiza un parsing
	Es un proceso adaptado a la información de la página.
	1) Inserta etiquetas html en las cadenas php
	2) Se seleccionan etiquetas html del documento
	3) Se buscan las etiquetas dependientes.		
*/
function parseHTMLPageAlert(){
		//recibe las variables globales
		global $fileParsear, $fileTextAlert;
		
		$contador=1; //saber la fila de parseado		
		//datos especificos del alerta
		
		$contadorTD=1;		
		$contadorCab=0;
		
		$linea="";		
		$lineaHTML=""; //variables temporales donde se colocan las etiquetas html
		
		//documento a parsear
		$doc = new DOMDocument();
		$doc->loadHTMLFile($fileParsear);

		//inicio de apertura de archivo
		$fpAlert = fopen($fileTextAlert, "w");
      //inicio html
		$lineaHTML='<!DOCTYPE html> <html> <head> <style>table, th, td {border: 1px solid black;} </style> <meta charset="utf-8" /> <title></title> </head> <body>';		
		fwrite($fpAlert, $lineaHTML.PHP_EOL); //escritura de la linea en archivo		

		$lineaHTML='<table>';
		fwrite($fpAlert, $lineaHTML.PHP_EOL); //escritura de la linea en archivo		
				
		//obtiene un elemento table y lo procesa 		
		$elementsTabla = $doc->getElementsByTagName('table');
		//Si la tabla no es nula busca cada uno de los elementos de <tr> la tabla		
		if (!is_null($elementsTabla)) {
	  		foreach ($elementsTabla as $elementFila) {
			//descomentar los echo para debug en caso de que sea necesario
//		      echo $contador." <<<< ";
//		      echo $elementFila->nodeValue. "\n";		      

		      //-------------------------------------------------
		      //separa los elementos con tag <tr>		      
		      $elementsTR = $elementFila->getElementsByTagName('tr');		      

//		      echo "--- INICIO FILA TR \n";		      
				$lineaHTML='<tr>';		
				fwrite($fpAlert, $lineaHTML.PHP_EOL);		

				$contadorCab=0;
				//si existen elementos <tr> los desgloza
		      if (!is_null($elementsTR)) {
			      foreach ($elementsTR as $elementtr) {			      	
//			      	echo $elementtr->nodeValue. "\n";			      
						$linea="";
	    				$nodes = $elementtr->childNodes;
						//recorre los nodos obtenidos y los inserta en una línea	    				
			      	if (!is_null($nodes)) {	    				
	    					foreach ($nodes as $node) {
//		      				echo "--->".$contadorTD."a parsear ->>>".$node->nodeValue. "\n";
	      					$contadorTD=$contadorTD+1;
	      					//guardar en una linea lo obtenido
	      					$linea=$linea.trim($node->nodeValue)." ";	      						      							      						      					
		      			}//for
		      		}//fin nodes
		      		
//		      	  echo " --->".$linea." \n";
					//utiliza contador para insertar tag de encabezado 						
					$contadorCab=$contadorCab+1;					
					if($contadorCab==1){
						$lineaHTML='<h1>'.$linea.'</h1>';
						fwrite($fpAlert, $lineaHTML.PHP_EOL);					
					}else{
						$lineaHTML='<p>'.$linea.'</p>';
						fwrite($fpAlert, $lineaHTML.PHP_EOL);
					}//					
								      				      			      			      	
			      }//for each
		      }//fin if		      
//		      echo "--- FIN FILA TR \n";		      
				$lineaHTML='</tr>';		
				fwrite($fpAlert, $lineaHTML.PHP_EOL);		
		      //-------------------------------------------------
				//contador de filas		      
//				$contador=$contador+1;	  	
	  		}//foreach
		}//isnull

		//escritura en archivo de los resultados obtenidos
		$lineaHTML='</body></html>';	//cierre de los tag html
		fwrite($fpAlert, $lineaHTML.PHP_EOL);		
		$lineaHTML='</table>'; //cierre de los tag html
		fwrite($fpAlert, $lineaHTML.PHP_EOL);				
		//cerrar el archivo
		fclose($fpAlert);
}//parseHTMLPage



//---------------- inicio principal-------
getHTML();
parseHTMLPageAlert();
//--------------- fin principal ----------

?>

