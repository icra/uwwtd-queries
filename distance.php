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
  /*
    distance between two coordinates
    converted from javascript to php
    extracted from 'https://cdn.jsdelivr.net/npm/geodesy@2/latlon-spherical.js';
  */
  function to_radians($n){return $n*M_PI/180;}
  function distance($lat1,$lon1, $lat2,$lon2){
    if(is_null($lat1)) return false;
    if(is_null($lon1)) return false;
    if(is_null($lat2)) return false;
    if(is_null($lon2)) return false;

    $R = 6371e3;//metres
    $φ1 = to_radians($lat1);
    $φ2 = to_radians($lat2);
    $Δφ = to_radians($lat2-$lat1);
    $Δλ = to_radians($lon2-$lon1);
    $a = sin($Δφ/2) * sin($Δφ/2) + cos($φ1) * cos($φ2) * sin($Δλ/2) * sin($Δλ/2);
    $c = 2*atan2(sqrt($a), sqrt(1-$a));
    $d = $R*$c/1000; //km
    return $d;
  }
  //test: distance(40.6,2.0, 40.3,2.1); //34.415 km
?>

<?php
  //connect with database
  $db = new SQLite3("v9.accdb.sqlite",SQLITE3_OPEN_READONLY);

  $query = isset($_GET["query"]) ? $_GET["query"] : false;
  if(!$query){
    //default query
    $query="
      SELECT
        T_UWWTPS.uwwCode,
        T_UWWTPS.uwwLongitude,
        T_UWWTPS.uwwLatitude,
        T_Agglomerations.aggCode,
        T_Agglomerations.aggLongitude,
        T_Agglomerations.aggLatitude,
        T_UWWTPS.uwwBeginLife,
        T_UWWTPS.uwwLoadEnteringUWWTP,
        T_UWWTPS.uwwCapacity
      FROM
        T_UWWTPS, T_Agglomerations, T_UWWTPAgglos
      WHERE
        T_UWWTPAgglos.aucUwwCode = T_UWWTPS.uwwCode AND
        T_UWWTPAgglos.aucAggCode = T_Agglomerations.aggCode AND
        T_UWWTPS.uwwState=1 AND
        (
          T_UWWTPS.uwwBeginLife LIKE '19%'
          OR
          T_UWWTPS.uwwLoadEnteringUWWTP > uwwCapacity
        );
    ";
  }

  //distance to filter results
  $distance_kms = isset($_GET["distance_kms"])? $_GET["distance_kms"] : 20;

  //display sql query text
  echo "<code><pre>$query</pre></code>";

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
    (<span id=n_results></span> results > $distance_kms kms)
  ";
  for($kms=10;$kms<100;$kms+=5){
    if($kms==$distance_kms) echo "<b>";
    echo"<a href='distance.php?distance_kms=$kms'>$kms km</a> | ";
    if($kms==$distance_kms) echo "</b>";
  }
?>

<table border=1><?php
  //render table with results
  $i=0;
  $j=1;
  while($row=$res->fetchArray(SQLITE3_ASSOC)){
    if($i==0){
      echo "<tr style='position:sticky;top:0;background:white'>";
      echo "<th>num</th>";
      foreach(array_keys($row) as $key){
        echo "<th>$key</th>";
      }
      echo "<th>distance (kms)</th>";
      echo "</tr>";
    }
    $lat1 = $row["uwwLatitude"];
    $lat2 = $row["aggLatitude"];
    $lon1 = $row["uwwLongitude"];
    $lon2 = $row["aggLongitude"];
    $dist_kms = distance($lat1,$lon1, $lat2,$lon2);

    if($dist_kms > $distance_kms){
      echo "<tr>";
      echo "<td>$j</td>";
      foreach($row as $key=>$val){
        echo "<td>$val</td>";
      }
      echo "<td>$dist_kms</td>";
      echo "</tr>";
      $j++;
    }
    $i++;
  }
  $j--;

  echo"
    <script>
      document.querySelector('#n_results').innerHTML=$j;
    </script>
  ";
?></table>
