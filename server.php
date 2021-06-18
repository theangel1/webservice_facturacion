<?php
/*$serverName = "sisgenchile.database.windows.net"; //serverName\instanceName
$connectionInfo = array("Database" => "APISISGEN", "UID" => "sisgen_dbmanager", "PWD" => "g#Qu^g7b5eoZ");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    echo "Conexión establecida.<br />";
    $sql = "select * from clientes";

    $stmt = sqlsrv_query($conn, $sql);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo $row['RazonSocial'];
    }
    sqlsrv_free_stmt($stmt);
} else {
    echo "Conexión no se pudo establecer.<br />";
    die(print_r(sqlsrv_errors(), true));
}
*/

$serverName = "(localdb)\MSSQLLocalDB";
$connectionInfo = array("Database" => "aspnet-Innova-09A47401-7E86-4A72-A231-D502CE863D4A", "UID" => "", "PWD" => "");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    $name = "angel.xml";
    $id = 18;
    echo "Conexión establecida.\n";
    $sql = "update Dte set NombreXml =(?) where Id=(?)";
    $params = array($name, $id);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false)
        die(print_r(sqlsrv_errors(), true));

    sqlsrv_free_stmt($stmt);
} else {
    echo "Conexión no se pudo establecer.<br />";
    die(print_r(sqlsrv_errors(), true));
}
