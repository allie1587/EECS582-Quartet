
<?php
$server = "sql312.infinityfree.com";
$user = "if0_38323969";
$pass = "Quartet44";
$dbname = "if0_38323969_quartet";
$conn = "";

// Check connection
try{
$conn = mysqli_connect($server, $user, $pass, $dbname);
}
catch(mysqli_sql_exception){
    echo"could not connect";
}

if ($conn) {
    echo"you are connected!";
}
?>