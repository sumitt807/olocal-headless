<?php
function dbConnection()
{
    if ($_SERVER['SERVER_ADDR'] == '104.144.219.2'){
        $servername = "localhost";
        $username = "qirqleco_olocal";
        $password = "Gami10diiPrD";
        $database = "qirqleco_olocal";
    }else{
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "qirqleco_olocal";
    }
   
    $conn = mysqli_connect($servername, $username, $password, $database);
    return $conn;
}

function excuteQuery($sql)
{
    $result = mysqli_query(dbConnection(), $sql);
    $result = mysqli_fetch_assoc($result);
    return $result;
}
