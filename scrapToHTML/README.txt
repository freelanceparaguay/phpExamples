--------------------------------------------
http://otroblogdetecnologias.blogspot.com
--------------------------------------------

Muchas veces se desea realizar extracción de información desde sitios web, a esta tarea se la denomina web scraping.

El web scraping consiste en extraer información desde páginas web con software especializado simulando la navegación como si lo hiciera un usuario de un navegador web. Ver Web Scrap.

Aquí copio un código en lenguaje PHP, el cual se dirige a una página para extraer información, la procesa y la traspasa a un archivo html como resultado. La idea básica es obtener una tabla y procesar sus filas.

Las intrucciones principales a tener en cuenta y sobre las cuales se podrá verificar su utilización en el manual de ayuda en PHP son:


$doc = new DOMDocument();$doc->loadHTMLFile($fileParsear);$elementsTabla = $doc->getElementsByTagName('table');
        if (!is_null($elementsTabla)) {              foreach ($elementsTabla as $elementFila) {              echo $contador." <<<< ";              echo $elementFila->nodeValue. "\n";


El ejemplo extrae datos desde una página de apuestas. Para pruebas ofline, se incluyen dos archivos de ejemplo.

    archivo_ejemplo_a_parsear.html -> ejemplo para parsear.
    archivo_ejemplo_resultado.html -> ejemplo para ver el resultado obtenido.

El script se ejecutó desde línea de comandos, colocar los permisos de ejecución con chmod 755 script.



