<?php
    include('recursos.php');

    $textRef = GetDatos("textRef","");
    $link = GetDatos("link","");
    $tipoReferencia = GetDatos("referencia","");

    switch ($tipoReferencia) {
        case 'ris':
            saveRIS($textRef, $link);
            break;
        case 'bibtex':
            saveBibtex($textRef, $link);
            break;
        case 'txt':
            saveText($textRef, $link);
            break;
    }

    function buscar($array, $cadena) {
        $res = FALSE;
        
        $cadena = substr($cadena, 0, strlen($cadena) - 1);

        for($i = 0; $i < count($array); $i++) {
            if($array[$i] == $cadena) {
                $res = TRUE;
                break;
            }
        }

        return $res;
    }

    function extraerInformacion($txtRef) {
        $array = explode(", ", $txtRef);

        $autores = ""; $contAutores = 0;
        foreach ($array as $elemento) {
            if(strlen($elemento) <= 20) {
                $autores = $autores . $elemento . ",";
                $contAutores++;
            } else {
                break;
            }
        }
        $res['contAutores'] = $contAutores;
        $res['autores'] = $autores;

        $titulo = $array[$contAutores];
        $res['titulo'] = $titulo;

        $diario = $array[$contAutores + 1];
        $res['diario'] = $diario;

        $volumen = $array[$contAutores + 2];
        $volumen = str_replace("Volume ", "", $volumen);
        $res['volumen'] = $volumen;
    
        $year = $array[$contAutores + 3];
        $res['year'] = $year;
    
        $paginas = $array[$contAutores + 4];
        $paginas = str_replace("Pages ", "", $paginas);
        $res['paginas'] = $paginas;

        $issn = $array[$contAutores + 5];
        $issn = str_replace("ISSN ", "", $issn);
        $res['issn'] = $issn;

        $hastaISSN = $contAutores + 5;
        $textTemp = ""; $cont = 0; $posISSNFin = 0;
        for ($i=0; $i < strlen($txtRef); $i++) { 
            $textTemp = $textTemp . substr($txtRef, $i, 1);
            if(substr($txtRef, $i, 1) == ",") {
                if($cont == $hastaISSN) {
                    $posISSNFin = $i + 2;
                    break;
                }
                $cont++;
            }
        }
    
        $textReferencia = substr($txtRef, $posISSNFin, strlen($txtRef));
    
        $array = explode(" ", $textReferencia);
    
        $url = $array[0];
        $url = substr($array[0], 0, strlen($url) - 1); 
        $res['url'] = $url;
    
        $doi = $array[1];
        $doi = str_replace("(","",$doi);
        $doi = str_replace(")","",$doi);
        $res['doi'] = $doi;

        $pos = strlen($array[0]) + strlen($array[1]) + 1;
    
        $textReferencia = substr($textReferencia, $pos, strlen($textReferencia));
    
        $textReferencia = str_replace(" Keywords: ", "|", $textReferencia);
    
        $array = explode("|", $textReferencia);
    
        $abstract = $array[0];
        $abstract = str_replace("Abstract: ", "", $abstract);
        $res['abstract'] = $abstract;
    
        $keywords = $array[1];
        $keywords = str_replace("; ",",",$keywords);
        $res['keywords'] = $keywords;

        return $res;
    }

    //ctype_upper(substr($txtRef, $i, 1))
    function saveRIS($txtRef, $lnk) {
        $fragmentos = extraerInformacion($txtRef);

        $ris = "TY  - JOUR" . PHP_EOL;
        $ris = $ris . "T1  - " . substr($fragmentos['titulo'], 1, strlen($fragmentos['titulo'])) . PHP_EOL;

        $array = explode(",", $fragmentos['autores']);

        foreach ($array as $autor) {
            if($autor != "") {
                $datosAutor = explode(" ", $autor);
                $ris = $ris . "AU  - " . $datosAutor[1] . ", " . $datosAutor[0] . PHP_EOL;
            }
        }

        $ris = $ris . "JO  - " . $fragmentos['diario'] . PHP_EOL;
        $ris = $ris . "VL  - " . $fragmentos['volumen'] . PHP_EOL;

        $array = explode("-",$fragmentos['paginas']);
        $ris = $ris . "SP  - " . $array[0] . PHP_EOL;
        $ris = $ris . "EP  - " . $array[1] . PHP_EOL;

        $ris = $ris . "PY  - " . $fragmentos['year'] . PHP_EOL;
        $ris = $ris . "DA  - " . PHP_EOL;
        $ris = $ris . "T2  - " . PHP_EOL;
        $ris = $ris . "SN  - " . $fragmentos['issn'] . PHP_EOL;
        $ris = $ris . "DO  - " . $fragmentos['doi'] . PHP_EOL;
        $ris = $ris . "UR  - " . $lnk . PHP_EOL;
        
        $array = explode(",",$fragmentos['keywords']);

        foreach ($array as $key) {
            $ris = $ris . "KW  - " . $key . PHP_EOL;
        }

        $ris = $ris . "AB  -" . $fragmentos['abstract'] . PHP_EOL;

        $ris = $ris . "ER  - " . PHP_EOL;

        $archivo = "referencia.ris";
        $crear = fopen($archivo, "w");
        fwrite($crear, $ris);
        fclose($crear);

        $fileName = basename('referencia.ris');
        $filePath = $fileName;

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        readfile($filePath);

    }

    function saveBibtex($txtRef, $lnk) {
        $fragmentos = extraerInformacion($txtRef);

        $bib = "@article{";
        
        $array = explode(",", $fragmentos['autores']);

        $fstAutor = $array[0];
        $array = explode(" ", $fstAutor);
        $fstAutor = strtoupper($array[1]);
        
        $array = explode("-",$fragmentos['paginas']);

        $fstPagina = $array[0];

        $bib = $bib . $fstAutor . $fragmentos['year'] . $fstPagina . "," . PHP_EOL;
        $bib = $bib . "title = \"" . substr($fragmentos['titulo'], 1, strlen($fragmentos['titulo'])) . "\"," . PHP_EOL;
        $bib = $bib . "journal = \"" . $fragmentos['diario'] . "\"," . PHP_EOL;
        $bib = $bib . "volume = \"" . $fragmentos['volumen'] . "\"," . PHP_EOL;
        $bib = $bib . "pages = \"" . $fragmentos['paginas'] . "\"," . PHP_EOL;
        $bib = $bib . "year = \"" . $fragmentos['year'] . "\"," . PHP_EOL;
        $bib = $bib . "note = " . PHP_EOL;
        $bib = $bib . "issn = \"" . $fragmentos['issn'] . "\"," . PHP_EOL;
        $bib = $bib . "doi = \"" . $fragmentos['doi'] . "\"," . PHP_EOL;
        $bib = $bib . "url = \"" . $lnk . "\"," . PHP_EOL;

        $array = explode(",", $fragmentos['autores']);

        $autores = $array[0];

        $contAutores = $fragmentos['contAutores'];
        if($contAutores > 1) {
            if($contAutores == 2) {
                $autores = $array[0] . " and " . $array[1];
            }
            else {
                $autores = "";
                for ($i=0; $i < count($array) - 1; $i++) { 
                    if($i == count($array) - 2) {
                        $autores = $autores . " and " . $array[$i];
                    } else {
                        if($i == count($array) - 3) {
                            $autores = $autores . $array[$i];
                        } else {
                            $autores = $autores . $array[$i] . ", ";
                        }
                        
                    }
                }
            }
        }

        $bib = $bib . "author = \"" . $autores . "\"," . PHP_EOL;

        $keywords = str_replace(",",", ",$fragmentos['keywords']);
        $bib = $bib . "keywords = \"" . $keywords . "\"," . PHP_EOL;

        $abstract = substr($fragmentos['abstract'], 1, strlen($fragmentos['abstract']));
        $bib = $bib . "abstract = \"" . $abstract . "\"}" . PHP_EOL;

        echo $bib;

        $archivo = "referencia.bib";
        $crear = fopen($archivo, "w");
        fwrite($crear, $bib);
        fclose($crear);

        $fileName = basename('referencia.bib');
        $filePath = $fileName;

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        readfile($filePath);

    }

    function saveText($txtRef, $lnk) {
        $array = explode(", ", $txtRef);

        $autores = ""; $contAutores = 0;
        foreach ($array as $elemento) {
            if(strlen($elemento) <= 20) {
                $autores = $autores . $elemento . ", ";
                $contAutores++;
            } else {
                break;
            }
        }
    
        $titulo = $array[$contAutores];
        $diario = $array[$contAutores + 1];
        $volumen = $array[$contAutores + 2];
        $year = $array[$contAutores + 3];
        $paginas = $array[$contAutores + 4];
        $issn = $array[$contAutores + 5];
    
        $hastaISSN = $contAutores + 5;
        $textTemp = ""; $cont = 0; $posISSNFin = 0;
        for ($i=0; $i < strlen($txtRef); $i++) { 
            $textTemp = $textTemp . substr($txtRef, $i, 1);
            if(substr($txtRef, $i, 1) == ",") {
                if($cont == $hastaISSN) {
                    $posISSNFin = $i + 2;
                    break;
                }
                $cont++;
            }
        }
    
        $textReferencia = substr($txtRef, $posISSNFin, strlen($txtRef));
    
        $array = explode(" ", $textReferencia);
    
        $url = $array[0];
    
        $doi = $array[1];
    
        $pos = strlen($array[0]) + strlen($array[1]) + 1;
    
        $textReferencia = substr($textReferencia, $pos, strlen($textReferencia));
    
        $textReferencia = str_replace(" Keywords: ", "|", $textReferencia);
    
        $array = explode("|", $textReferencia);
    
        $abstract = $array[0];
        $abstract = substr($abstract,1,strlen($abstract));
    
        $keywords = $array[1];
        //---------------------------------------------------------------------------

        $txt = $autores . PHP_EOL;
        $txt = $txt . substr($titulo, 1, strlen($titulo)) . "," . PHP_EOL;
        $txt = $txt . $diario . "," . PHP_EOL;
        $txt = $txt . $volumen . "," . PHP_EOL;
        $txt = $txt . $year . "," . PHP_EOL;
        $txt = $txt . $paginas . "," . PHP_EOL;
        $txt = $txt . $issn . "," . PHP_EOL;
        $txt = $txt . $lnk . " ";
        $txt = $txt . $doi . PHP_EOL;
        $txt = $txt . $abstract . PHP_EOL;
        $txt = $txt . "Keywords: " . $keywords;


        $archivo = "referencia.txt";
        $crear = fopen($archivo, "w");
        fwrite($crear, $txt);
        fclose($crear);

        $fileName = basename('referencia.txt');
        $filePath = $fileName;

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        readfile($filePath);
    }

?>