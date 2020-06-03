<?php

    function execute($filename){
        $location = "{$filename}";
        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $file = substr($filename, 0, -3);

        if($extension === "py"){
            $command = escapeshellcmd("python scripts/{$file}.py");
            $output = shell_exec($command);
            $array = explode("',", $output);

            return $array;
        }
        else if($extension === 'js'){
            $prefix = 'node';
            // $command = escapeshellcmd("node scripts/{$file}.js");
            // $output = shell_exec($command);
            // $output = exec("node scripts/{$file}.js", $out, $err);
            // $array = explode("',", $output);
            // echo $output;
            // print_r($array);
            // return $array;
        }
        else if($extension === 'php'){
            return;
        }
        else if($extension === 'java'){
            return;
        }
    };

    function parseArray($array){
        $array[0] = substr($array[0], 2);
        $array[1] = substr($array[1], 2);
        $array[2]= substr($array[2], 2);
        $array[3] = substr($array[3], 2);
        $array[4]= substr($array[4], 2, -3);

        return $array;
    };

    function test($array){
        $match = "Hello World, this is {$array[1]} with HNGi7 ID {$array[2]} using {$array[4]} for stage 2 task";
        if ($array[0] == $match){
            return 'Pass';
        }else{
            return 'Fail';
        }
    }
    
    // ------------------------------------------------------------------------------------------------------------------

    $fileLoction = './scripts';
    $filesArray = scandir($fileLoction);
    $tempArray;

    foreach($filesArray as $currentFile){
        if (substr($currentFile, 0, 1) !== ".") {
           $arr = execute($currentFile);
           $arr = parseArray($arr);
           $status = test($arr);

           $final = array(
               "file" => $currentFile,
               "output" => $arr[0],
               "name" => $arr[1],
               "id" => $arr[2],
               "email" => $arr[3],
               "language" => $arr[4],
               "status" => $status
           );

           $tempArray[] = $final;
        }
    }

    $json = json_encode($tempArray, JSON_PRETTY_PRINT);
    file_put_contents('dump.json', $json);   

    if ($_SERVER['QUERY_STRING'] == "json") {
        echo '<pre>';
        echo $json;
        echo '<pre>';        
    }
    else{
        foreach($tempArray as $pers){
            echo '<pre>';
            echo $pers['name']." => ".$pers['output']." => "."<b>".$pers['status']."</b>";
            echo '</pre>';
        };
    }
?>