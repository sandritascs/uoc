<?php

ini_set('display_errors', 0);

require_once 'config/includes.php';

date_default_timezone_set('Europe/Madrid');

$row = 1;
$vacunas_spain = new stdClass();
$vacunas_spain->total = array();
$vacunas_spain->una = array();
$vacunas_spain->full = array();
$total_casos_confirmados = array();
$poblacion = array();
$tests = array();
$covid = array();
$pobreza = array();
$limpieza = array();
$gdp = array();

if (($handle = fopen("data/covid-europe.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
        /*if($row==1){
            echo "<pre>";echo print_r($data);echo "</pre>";
        }*/

        if($row>=2){
            
            $date = $data[2]; // date
            $aux = new stdClass();

            $aux->name = str_replace("'", "", $data[1]); // location
            $aux->value = $data[16]; //  new_cases_per_million -> nuevos casos por millón de habitantes
            $data_poblacion = $data[11]; // population
            $data_tests = $data[20]; //new_tests_per_thousand
            $data_pobreza = $data[13]; //extreme_poverty
            $data_limpieza = $data[14]; //handwashing_facilities
            $data_gdp = $data[24]; //gdp_per_capita
            
            $new_date = explode('-', $date);
            $year = $new_date[0];

            if (empty($covid[$year])) {

                $covid[$year] = new stdClass();
                $covid[$year]->date = $year;
                $covid[$year]->dataSet = array();
            }

            if (empty($covid[$year]->dataSet[$aux->name])) {

                $covid[$year]->dataSet[$aux->name] = new stdClass();
                $covid[$year]->dataSet[$aux->name]->name = $aux->name;
                $covid[$year]->dataSet[$aux->name]->value = floatval($aux->value);
            }

            $covid[$year]->dataSet[$aux->name]->value += floatval($aux->value);

            // VACUNAS
            if (empty($vacunas_spain->total[$year])) {

                $vacunas_spain->total[$year] = 0;
            }

            if (empty($vacunas_spain->una[$year])) {

                $vacunas_spain->una[$year] = 0;
            }

            if (empty($vacunas_spain->full[$year])) {

                $vacunas_spain->full[$year] = 0;
            }

            if ($aux->name == 'Spain') {

                $vacunas_spain->total[$year] += intval($data[21]);
                $vacunas_spain->una[$year] += intval($data[22]);
                $vacunas_spain->full[$year] += intval($data[23]);
            }

            // total casos confirmados
            if (empty($total_casos_confirmados[$year])) {

                $total_casos_confirmados[$year] = new stdClass();
                $total_casos_confirmados[$year]->date = $year;
                $total_casos_confirmados[$year]->dataSet = array();
            }

            if (empty($total_casos_confirmados[$year]->dataSet[$aux->name])) {

                $total_casos_confirmados[$year]->dataSet[$aux->name] = new stdClass();
                $total_casos_confirmados[$year]->dataSet[$aux->name]->name = $aux->name;
                $total_casos_confirmados[$year]->dataSet[$aux->name]->value = floatval($data['15']); // total_cases_per_million
            }

            $total_casos_confirmados[$year]->dataSet[$aux->name]->value = floatval($data['15']); // total_cases_per_million
            //
            //poblacion
            if (empty($poblacion[$year])) {

                $poblacion[$year] = new stdClass();
                $poblacion[$year]->date = $year;
                $poblacion[$year]->dataSet = array();
            }

            if (empty($poblacion[$year]->dataSet[$aux->name])) {

                $poblacion[$year]->dataSet[$aux->name] = new stdClass();
                $poblacion[$year]->dataSet[$aux->name]->name = $aux->name;
            }
            $poblacion[$year]->dataSet[$aux->name]->value = intval($data_poblacion);

            // tests
            if (empty($tests[$year])) {

                $tests[$year] = new stdClass();
                $tests[$year]->date = $year;
                $tests[$year]->dataSet = array();
            }

            if (empty($tests[$year]->dataSet[$aux->name])) {

                $tests[$year]->dataSet[$aux->name] = new stdClass();
                $tests[$year]->dataSet[$aux->name]->name = $aux->name;
                $tests[$year]->dataSet[$aux->name]->value = floatval($data_tests);
            }

            $tests[$year]->dataSet[$aux->name]->value += floatval($data_tests);

            
        }

        $row++;
    }
    fclose($handle);
}

$row = 1;
$pobreza_spain = 0;
if (($handle2 = fopen("data/df_poverty_2023.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle2, 1000, ",")) !== FALSE) {

        
        if ($row >= 2) {

            $date = $data[2]; // date
            $aux = new stdClass();
            $aux->pais = str_replace("'", "", $data[0]); // location
            $aux->value = round(floatval($data[1]),2);
            array_push($pobreza, $aux);
            
            if($aux->pais=='Spain'){
                $pobreza_spain = $aux->value;
            }
        }

        $row++;
    }
    fclose($handle2);
}

$row = 1;
if (($handle3 = fopen("data/df_clean_2023.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle3, 1000, ",")) !== FALSE) {
        
        if ($row >= 2) {

            $date = $data[2]; // date
            $aux = new stdClass();
            $aux->pais = str_replace("'", "", $data[0]); // location
            $aux->value = round(floatval($data[1]),2);
            array_push($limpieza, $aux);
        }

        $row++;
    }
    fclose($handle3);
}

$row = 1;
if (($handle4 = fopen("data/df_gdp_2023.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle4, 1000, ",")) !== FALSE) {
        
        if ($row >= 2) {

            $date = $data[2]; // date
            $aux = new stdClass();
            $aux->pais = str_replace("'", "", $data[0]); // location
            $aux->value = round(floatval($data[1]),2);
            array_push($gdp, $aux);
        }

        $row++;
    }
    fclose($handle4);
}


$spain_covid_new_2020 = number_format($covid['2020']->dataSet['Spain']->value, 2);
$spain_covid_new_2021 = number_format($covid['2021']->dataSet['Spain']->value, 2);
$spain_covid_new_2022 = number_format($covid['2022']->dataSet['Spain']->value, 2);
$spain_covid_new_2023 = number_format($covid['2023']->dataSet['Spain']->value, 2);

$spain_covid_total_2020 = number_format($total_casos_confirmados['2020']->dataSet['Spain']->value, 2);
$spain_covid_total_2021 = number_format($total_casos_confirmados['2021']->dataSet['Spain']->value, 2);
$spain_covid_total_2022 = number_format($total_casos_confirmados['2022']->dataSet['Spain']->value, 2);
$spain_covid_total_2023 = number_format($total_casos_confirmados['2023']->dataSet['Spain']->value, 2);

$spain_poblacion_2020 = number_format($poblacion['2020']->dataSet['Spain']->value);
$spain_poblacion_2021 = number_format($poblacion['2021']->dataSet['Spain']->value);
$spain_poblacion_2022 = number_format($poblacion['2022']->dataSet['Spain']->value);
$spain_poblacion_2023 = number_format($poblacion['2023']->dataSet['Spain']->value);

$spain_tests_2020 = number_format($tests['2020']->dataSet['Spain']->value);
$spain_tests_2021 = number_format($tests['2021']->dataSet['Spain']->value);
$spain_tests_2022 = number_format($tests['2022']->dataSet['Spain']->value);
$spain_tests_2023 = number_format($tests['2023']->dataSet['Spain']->value);

$total_tests = $spain_tests_2020 + $spain_tests_2021 + $spain_tests_2022 + $spain_tests_2023;

foreach($covid as $key => $co){
    
    $covid[$key]->dataSet = array_values($co->dataSet);
    
    usort($covid[$key]->dataSet, object_sorter('value', 'DESC'));
    
    $covid[$key]->dataSet = array_slice($covid[$key]->dataSet, 0, 20); // cogemos los 20 mayores
}

usort($pobreza, object_sorter('value', 'DESC'));
    
$pobreza = array_slice($pobreza, 0, 10); // cogemos los 20 mayores


$labels_pobreza = array();
$bar_pobreza = array();
foreach($pobreza as $po){
    
    array_push($labels_pobreza,$po->pais);
    array_push($bar_pobreza,$po->value);
}

usort($limpieza, object_sorter('value', 'DESC'));    
$limpieza = array_slice($limpieza, 0, 10); 

$labels_limpieza = array();
$bar_limpieza = array();
foreach($limpieza as $po){
    
    array_push($labels_limpieza,$po->pais);
    array_push($bar_limpieza,$po->value);
}

usort($gdp, object_sorter('value', 'DESC'));    
$gdp = array_slice($gdp, 0, 10); 

$labels_gdp = array();
$bar_gdp = array();
foreach($gdp as $po){
    
    array_push($labels_gdp,$po->pais);
    array_push($bar_gdp,$po->value);
}

//echo "<pre>";echo print_r($pobreza);echo "</pre>";
//echo json_encode(array_values($covid));

function object_sorter($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
          $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
          return $result;
    };
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Practica 2 - Visualización de datos</title>      
        <link rel="stylesheet" href="vendor/components/jqueryui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/all.min.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/solid.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="src/style-bar.css" media="all">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
        
        <script src="vendor/components/jquery/jquery.min.js"></script>
        <script src="vendor/components/jqueryui/jquery-ui.min.js"></script>
        <script>
        
        const dataSet = $.parseJSON('<?php echo json_encode(array_values($covid)); ?>');
        const vacunas_una = $.parseJSON('<?php echo json_encode(array_values($vacunas_spain->una)); ?>');
        const vacunas_full = $.parseJSON('<?php echo json_encode(array_values($vacunas_spain->full)); ?>');
        const vacunas_total = $.parseJSON('<?php echo json_encode(array_values($vacunas_spain->total)); ?>');
        const labels_pobreza = $.parseJSON('<?php echo json_encode(array_values($labels_pobreza)); ?>');
        const labels_limpieza = $.parseJSON('<?php echo json_encode(array_values($labels_limpieza)); ?>');
        const labels_gdp = $.parseJSON('<?php echo json_encode(array_values($labels_gdp)); ?>');
        const bar_pobreza = $.parseJSON('<?php echo json_encode(array_values($bar_pobreza)); ?>');
        const bar_limpieza = $.parseJSON('<?php echo json_encode(array_values($bar_limpieza)); ?>');
        const bar_gdp = $.parseJSON('<?php echo json_encode(array_values($bar_gdp)); ?>');
        </script>
    </head>

    <body>
        
        <section class="mb-4">
            
            <div class="container-lg">
                
                <div class="row mt-4">
                    
                    <div class="col-md-4">
                        <div class="div-title h-100 d-flex justify-content-center align-items-center font-Playfair">
                            <strong>Progreso del <span class="color-blue">COVID-19</span>. <br>¿Hemos controlado el virus?</strong>
                        </div>
                    </div>
                    
                    <div class="col-md-8 text-center">
                        <img class="img-covid" src="img/COVID-19.png">
                    </div>
                    
                </div>
                
                <div class="row mt-4">
                    
                    <div class="col-md-12">
                        <p class="description">
                        Desde que se inició la pandamia, los gobiernos de todo el mundo procedieron a realizar 
                        una serie de acciones e implementar medidas con el objetivo de reducir el impacto de la pandemia.
                        Esta visualización trata de mostrar si hemos progresado gracias a todas las actuaciones desarrolladas en estos años.
                        
                        </p>
                        
                    </div>
                </div>

                <div class="row mt-2">

                    <div class="col-md-8 text-center">

                        <div class="card">

                            <div class="card-body">
                                <button>Parar</button>
                                <svg id="bar-chart-race" width='100%'>
                                <g class="chart-container">
                                <text class="chart-title"></text>
                                <g class="x-axis"></g>
                                <g class="y-axis"></g>
                                <g class="columns"></g>
                                <text class="current-date"></text>
                                </g>
                                </svg>
                            </div>

                        </div>

                    </div>
                    
                    <div class="col-md-4 text-center">
                        
                        <div class="div-Spain font-Playfair">
                            Evolución en España
                        </div>
                        <div>Nº de nuevos casos por cada millón de habitantes</div>
                        <div>(<?php echo $spain_poblacion_2020 ?> de habitantes)</div>
                        
                        <div class="font-Playfair year-Spain mt-2">
                            2020 
                        </div>
                        
                        <div>
                            
                            <div><span><?php echo $spain_covid_new_2020 ?></span></div>
                        </div>
                        
                        <div class="font-Playfair year-Spain mt-2">
                            2021
                        </div>
                        
                        <div>
                            <div><span><?php echo $spain_covid_new_2021 ?></span></div>
                        </div>
                        
                        <div class="font-Playfair year-Spain mt-2">
                            2022
                        </div>
                        
                        <div>
                            <div><span><?php echo $spain_covid_new_2022 ?></span></div>
                        </div>
                        
                        <div class="font-Playfair year-Spain mt-2">
                            2023
                        </div>
                        
                        <div>
                            <div><span><?php echo $spain_covid_new_2023 ?></span></div>
                        </div>
                        
                    </div>

                </div>
                
                <div class="card  mt-4">
                    <div class="card-body">
                        
                    
                <div class="row">
                    
                    <div class="col-md-2">
                        
                        <div class="float-left">
                            <i class="fa-solid fa-vial-virus icono-tests"></i>
                        </div>                        
                        
                    </div>
                    
                    <div class="col-md-10 float-none text-center">
                        
                        <div class="text-25 color-green font-Playfair">
                            En España se han realizado un total de <span class="text-45 color-blue"><?php echo $total_tests?></span> pruebas de Covid-19
                            por cada 1.000 habitantes.
                        </div>
                        
                    </div>
                    
                </div>
                        </div>
                    </div>
                
                <div class="row mt-4 mb-4">
                    
                    <div class="col-md-12">
                        
                        <div class="font-Playfair year-Spain text-center">
                            <strong>Vacunaciones en España por cada 100 habitantes</strong>
                        </div>
                        <div class="text-center">
                            <strong>(Una dosis - dosis recomendadas)</strong>
                        </div>
                        
                        <div class="icono-jeringa text-center">
                            <i class="fa-solid fa-syringe"></i>
                        </div>
               
                        <canvas class="chart" id="chart-vacunas" height="200"></canvas>
                    </div>
                    
                                        
                </div>
                
                <div class="row mt-4">
                    
                    <div class="col-md-12">
                        <p class="font-Playfair text-45 text-center texto-pobreza">
                            <strong>¿Influye el <span class="color-green">nivel de pobreza</span> de un país con respecto al número de muertes?</strong>
                        </p>
                    </div>
                    
                </div>

                
                <div class="row mt-4 mb-4">
                    
                    <div class="col-md-4 d-flex justify-content-center align-items-center font-Playfair">
                        
                        <div class="">
                        <div class="text-center text-45">
                            Menos muertes por Covid-19 en el último año.
                        </div>
                        
                        <div class="text-center">
                            <i class="fas fa-viruses icono-tests"></i>
                        </div>
                            
                            <div class="text-center text-25">
                                La disminución del número de muertes en España es menos significativa que en otros países.
                            </div>
                        </div>
                        
                        
                    </div>

                    <div class="col-md-8">
                        
                        <div class="card">
                            
                            <div class="card-header">
                                Número de muertes por millón de habitantes
                            </div>

                            <div class="card-body">
                                <div id="canvas-map-2020" class="eventMap">

                                    <button onclick="showMap('2020')">2020</button>
                                    <button onclick="showMap('2021')" class="border-none">2021</button>
                                    <button onclick="showMap('2022')" class="border-none">2022</button>
                                    <button onclick="showMap('2023')" class="border-none">2023</button>
                                    <svg id="world-chart-2020" width="700" height="450"></svg>

                                </div>

                                <div id="canvas-map-2021" class="displayNone eventMap">

                                    <button onclick="showMap('2020')" class="border-none">2020</button>
                                    <button onclick="showMap('2021')">2021</button>
                                    <button onclick="showMap('2022')" class="border-none">2022</button>
                                    <button onclick="showMap('2023')" class="border-none">2023</button>
                                    <svg id="world-chart-2021" width="800" height="450"></svg>

                                </div>

                                <div id="canvas-map-2022" class="displayNone eventMap">

                                    <button onclick="showMap('2020')" class="border-none">2020</button>
                                    <button onclick="showMap('2021')" class="border-none">2021</button>
                                    <button onclick="showMap('2022')">2022</button>
                                    <button onclick="showMap('2023')" class="border-none">2023</button>
                                    <svg id="world-chart-2022" width="800" height="450"></svg>

                                </div>
                                
                                <div id="canvas-map-2023" class="displayNone eventMap">

                                    <button onclick="showMap('2020')" class="border-none">2020</button>
                                    <button onclick="showMap('2021')" class="border-none">2021</button>
                                    <button onclick="showMap('2022')" class="border-none">2022</button>
                                    <button onclick="showMap('2023')">2023</button>
                                    <svg id="world-chart-2023" width="800" height="450"></svg>

                                </div>
                            </div>

                        </div>



                    </div>

                </div>
                
                <div class="row mt-4">
                    
                    <div class="col-md-4">
                        
                        <div class="font-Playfair text-center text-25">
                            Porcentaje de población que vive en pobreza extrema en 2023
                        </div>
                        <canvas class="mt-4" id="chartPobreza"></canvas>

                        
                    </div>
                    
                    <div class="col-md-4 d-flex justify-content-center align-items-center font-Playfair">
                        
                        <div>
                        <div class="text-center">
                            <i class="fas fa-users icono-jeringa"></i>
                        </div>
                        <div class="text-center text-25">
                            En España el porcentaje de la población que vive en pobreza extrema en 2023 es del <span class="color-blue"><?php echo $pobreza_spain ?> %</span>
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="col-md-4">
                        
                        <div class="font-Playfair text-center text-25">
                            GDP (Renta) 2023
                        </div>
                        <div class="text-center">
                            (Países con mayor GDP)
                        </div>
                        <canvas class="mt-4" id="chart-gdp"></canvas>
                        
                    </div>

                    
                </div>
                
                
                
                
            </div>
            
        </section>

        <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/fortawesome/font-awesome/js/solid.min.js"></script>
        <script src="vendor/fortawesome/font-awesome/js/fontawesome.min.js"></script>
        <script src="http://d3js.org/d3.v4.js"></script>
        <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
        <script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
        <script src="src/Chart/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datasource@0.1.0"></script>
        <script src="src/BarChartRace.js"></script>
        <script src="src/index.js"></script>

  </body>
</html>
