<?php
function dbConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "qirqleco_olocal";
    $conn = mysqli_connect($servername, $username, $password, $database);
    return $conn;
}

function excuteQuery($sql)
{
    $result = mysqli_query(dbConnection(), $sql);
    $result = mysqli_fetch_assoc($result);
    return $result;
}
