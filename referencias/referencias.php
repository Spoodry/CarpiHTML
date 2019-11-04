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
        $res['autores'] = $autores;

        $titulo = $array[$contAutores];
        $res['titulo'];

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
    
        $titulo = $array[$contAutores];
        $diario = $array[$contAutores + 1];
    
        $volumen = $array[$contAutores + 2];
        $volumen = str_replace("Volume ", "", $volumen);
    
        $year = $array[$contAutores + 3];
    
        $paginas = $array[$contAutores + 4];
        $paginas = str_replace("Pages ", "", $paginas);
        
        $issn = $array[$contAutores + 5];
        $issn = str_replace("ISSN ", "", $issn);
    
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
    
        $doi = $array[1];
        $doi = str_replace("(","",$doi);
        $doi = str_replace(")","",$doi);
    
        $pos = strlen($array[0]) + strlen($array[1]) + 1;
    
        $textReferencia = substr($textReferencia, $pos, strlen($textReferencia));
    
        $textReferencia = str_replace(" Keywords: ", "|", $textReferencia);
    
        $array = explode("|", $textReferencia);
    
        $abstract = $array[0];
        $abstract = str_replace("Abstract: ", "", $abstract);
    
        $keywords = $array[1];
        $keywords = str_replace("; ",",",$keywords);
        //------------------------------------------------------------------------------------
        $ris = "TY  - JOUR\n";
        $ris = $ris . "T1  - " . $titulo . "\n";

        $array = explode(",", $autores);

        foreach ($array as $autor) {
            if($autor != "") {
                $datosAutor = explode(" ", $autor);
                $ris = $ris . "AU  - " . $datosAutor[1] . ", " . $datosAutor[0] . "\n";
            }
        }

        $ris = $ris . "JO  - " . $diario . "\n";
        $ris = $ris . "VL  - " . $volumen . "\n";

        $array = explode("-",$paginas);
        $ris = $ris . "SP  - " . $array[0] . "\n";
        $ris = $ris . "EP  - " . $array[1] . "\n";

        $ris = $ris . "PY  - " . $year . "\n";
        $ris = $ris . "DA  - " . "\n";
        $ris = $ris . "T2  - " . "\n";
        $ris = $ris . "SN  - " . $issn . "\n";
        $ris = $ris . "DO  - " . $doi . "\n";
        $ris = $ris . "UR  - " . $url . "\n";
        
        $array = explode(",",$keywords);

        foreach ($array as $key) {
            $ris = $ris . "KW  - " . $key . "\n";
        }

        $ris = $ris . "AB  -" . $abstract . "\n";

        $ris = $ris . "ER  - " . "\n";

        $archivo = "referencia.ris";
        $crear = fopen($archivo, "w");
        fwrite($crear, $ris);
        fclose($crear);

        header('Location: ' . $archivo);

    }

    function saveBibtex($txtRef, $lnk) {
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
    
        $titulo = $array[$contAutores];
        $diario = $array[$contAutores + 1];
    
        $volumen = $array[$contAutores + 2];
        $volumen = str_replace("Volume ", "", $volumen);
    
        $year = $array[$contAutores + 3];
    
        $paginas = $array[$contAutores + 4];
        $paginas = str_replace("Pages ", "", $paginas);
        
        $issn = $array[$contAutores + 5];
        $issn = str_replace("ISSN ", "", $issn);
    
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
    
        $doi = $array[1];
        $doi = str_replace("(","",$doi);
        $doi = str_replace(")","",$doi);
    
        $pos = strlen($array[0]) + strlen($array[1]) + 1;
    
        $textReferencia = substr($textReferencia, $pos, strlen($textReferencia));
    
        $textReferencia = str_replace(" Keywords: ", "|", $textReferencia);
    
        $array = explode("|", $textReferencia);
    
        $abstract = $array[0];
        $abstract = str_replace("Abstract: ", "", $abstract);
    
        $keywords = $array[1];
        $keywords = str_replace("; ",",",$keywords);
        //----------------------------------------------------------------------------------------------
        $bib = "@article{";
        
        $array = explode(",", $autores);

        $fstAutor = $array[0];
        $array = explode(" ", $fstAutor);
        $fstAutor = strtoupper($array[1]);
        
        $array = explode("-",$paginas);

        $fstPagina = $array[0];

        $bib = $bib . $fstAutor . $year . $fstPagina . ",\n";
        $bib = $bib . "title = \"" . $titulo . "\",\n";
        $bib = $bib . "journal = \"" . $diario . "\",\n";
        $bib = $bib . "volume = \"" . $volumen . "\",\n";
        $bib = $bib . "pages = \"" . $paginas . "\",\n";
        $bib = $bib . "year = \"" . $year . "\",\n";
        $bib = $bib . "note = \n";
        $bib = $bib . "issn = \"" . $issn . "\",\n";
        $bib = $bib . "doi = \"" . $doi . "\",\n";
        $bib = $bib . "url = \"" . $url . "\",\n";

        $array = explode(",", $autores);

        $autores = $array[0];

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

        $bib = $bib . "author = \"" . $autores . "\",\n";

        $keywords = str_replace(",",", ",$keywords);
        $bib = $bib . "keywords = \"" . $keywords . "\",\n";

        $abstract = substr($abstract, 1, strlen($abstract));
        $bib = $bib . "abstract = \"" . $abstract . "\"\n}";

        echo $bib;

        $archivo = "referencia.bib";
        $crear = fopen($archivo, "w");
        fwrite($crear, $bib);
        fclose($crear);

        header('Location: ' . $archivo);

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

        $txt = $autores . "\n";
        $txt = $txt . $titulo . ",\n";
        $txt = $txt . $diario . ",\n";
        $txt = $txt . $volumen . ",\n";
        $txt = $txt . $year . ",\n";
        $txt = $txt . $paginas . ",\n";
        $txt = $txt . $issn . ",\n";
        $txt = $txt . $url . " ";
        $txt = $txt . $doi . "\n";
        $txt = $txt . $abstract . "\n";
        $txt = $txt . "Keywords: " . $keywords;


        $archivo = "referencia.txt";
        $crear = fopen($archivo, "w");
        fwrite($crear, $txt);
        fclose($crear);

        header('Location: ' . $archivo);
    }

?>