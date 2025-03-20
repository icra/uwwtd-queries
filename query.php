<!doctype html><html><head>
  <meta charset=utf8>
  <title>query</title>
  <style>
    body{
      background:white;
    }
    td{
      white-space:nowrap;
      font-family:monospace;
      font-size:smaller;
    }
    th{
      text-align:left;
    }
  </style>
</head><body>

<nav><a href="index.php">&larr; Home</a></nav><hr>
<h2>UWWTD query</h2><hr>

<?php
  //GET ?query=query default value
  $query   = isset($_GET["query"])   ? $_GET["query"]   : false;
  $db_name = isset($_GET["db_name"]) ? $_GET["db_name"] : "UWWTD_TreatmentPlants.gpkg.sqlite";

  if(!$query){
    //default query
    $query="
      SELECT *
      FROM UWWTD_TreatmentPlants
      WHERE uwwBeginLife LIKE '19%'
      LIMIT 10;
    ";
  }

  //display sql query text
  echo "<code>$query</code>";

  //connect with database
  $db  = new SQLite3($db_name,SQLITE3_OPEN_READONLY);

  //execute actual query
  $res = $db->query($query) or die(print_r($db->lastErrorMsg(), true));

  //count and display number of results
  function num_rows($res){
    $nrows=0;
    $res->reset();
    while($res->fetchArray()) $nrows++;
    $res->reset();
    return $nrows;
  }
  $n = num_rows($res);
  echo "
    <hr>$n results
  ";
?>

<table border=1><?php
  //render table with results
  $i=0;
  while($row=$res->fetchArray(SQLITE3_ASSOC)){
    if($i==0){
      echo "<tr style='position:sticky;top:0;background:white'>";
      foreach(array_keys($row) as $key){
        echo "<th>$key</th>";
      }
      echo "</tr>";
    }
    echo "<tr>";
    foreach($row as $key=>$val){
      echo "<td>$val</td>";
    }
    echo "</tr>";
    $i++;
  }
?></table>
