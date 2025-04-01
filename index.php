<!doctype html><head>
  <meta charset=utf8>
  <title>UWWTD queries</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
</head><body>
<nav>
  <a href="//github.com/icra/uwwtd-queries">github.com/icra/uwwtd-queries</a>
</nav>
<h1>UWWTD queries</h1>

<div id=app>
  <table border=1>
    <thead>
      <tr>
        <td>default test query</td>
        <td>(displays all columns, first 10 results)</td>
        <td>
          <a href="query.php">GO!</a>
        </td>
      </tr>
    </thead>

    <tr v-for="val,key in queries">
      <td>{{key}}</td>
      <td>
        <textarea
          v-model="queries[key]"
          style="field-sizing:content"
        ></textarea>
      </td>
      <td>
        <a :href="`query.php?query=${encodeURI(val)}`">GO!</a>
      </td>
    </tr>
    <tr v-for="val,key in queries_distance">
      <td>{{key}}</td>
      <td>
        <textarea
          v-model="queries_distance[key]"
          style="field-sizing:content"
        ></textarea>
      </td>
      <td>
        <a :href="`distance.php?query=${encodeURI(val)}`">GO!</a>
      </td>
    </tr>
  </table>
</div>

<script>let app = Vue.createApp({
  data(){return{
    queries:{
      "Plants that need renovations":`
        SELECT
          uwwCode,
          uwwName,
          uwwState,
          uwwCapacity,
          uwwBeginLife,
          uwwEndLife,
          uwwDateClosing,
          rptMStateKey,
          uwwHistorie,
          uwwInformation
        FROM UWWTD_TreatmentPlants 
        WHERE
          uwwState=1 AND
          uwwCapacity < 5000 AND
          uwwBeginLife LIKE '19%'
        ORDER BY uwwBeginLife ASC;
      `,
      "Plants that are overloaded":`
        SELECT
          uwwCode,
          uwwName,
          uwwState,
          uwwCapacity,
          uwwLoadEnteringUWWTP,
          uwwBeginLife,
          uwwEndLife,
          uwwDateClosing,
          rptMStateKey,
          uwwHistorie,
          uwwInformation
        FROM UWWTD_TreatmentPlants 
        WHERE
          uwwState = 1 AND
          uwwLoadEnteringUWWTP > uwwCapacity
        ORDER BY uwwBeginLife ASC;
      `,
    },
    queries_distance:{
      "Distance between UWWTPS and Aglommerations":`
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
      `,
    },
  }},
}).mount("#app");</script>
