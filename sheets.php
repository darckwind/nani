<?php
require __DIR__ . '/vendor/autoload.php';


//coneccion
function getClient()
{
    $client = new Google_Client();
    $client->setDeveloperkey('AIzaSyCpVYAzwzx3e3u28axKvfViY9g0pMiYmeU');
    $client->setAccessType('offline');   
    return $client;
}


getData();

function getData()
{
    $client = getClient();
    $service = new Google_Service_Sheets($client);
    $arrSedes = array();
    $arrZonas = array();
    $puntofinal= 'evaluaciontecnica';
   
    // id de sheet
    $spreadsheetId = '1QdxeYBT3zSQtgv2JECFyiobT8zbxJhTs_DU7-5PGciA';
    //rango de busqueda
    $range = 'Base!A2:I';
    //obtencion de data
    $response = $service->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);
    $test = json_encode($response);
    if (empty($test)) {
        echo "planilla vacia";
    } else {
        foreach ($response['values'] as $row) {
            //region
            if (!array_key_exists($row[1], $arrSedes)) {
                $arrSedes[$row[1]] = array();
            }
            //comuna
            if (!array_key_exists($row[2], $arrSedes[$row[1]])) {
                $arrSedes[$row[1]][$row[2]] = array();
            }    
            $arrSedes[$row[1]][$row[2]][] = array(
                "universidad" => $row[3],
                "sede" => $row[4],
                "zona" => $row[5]
            );                    
        }
        foreach ($response['values'] as $row) {

            if (!array_key_exists($row[0], $arrZonas)) {
                $arrZonas[$row[0]] = array(
                'codigo' => $row[7],
                'url' =>$row[8].'/'.$puntofinal,
                'url_final' => $row[8],
            );
            }
            
            

        }

    }

    var_dump($arrZonas);
    salveDataSedes($arrSedes);
    salveDataZonas($arrZonas);
}

function salveDataSedes($arrSedes){
    $fichero = 'insumoSedes.php';
    file_put_contents($fichero, print_r($arrSedes, true));
}

function salveDataZonas($arrZonas){
    $fichero = 'insumoZonas.php';
    file_put_contents($fichero, print_r($arrZonas, true));
}
