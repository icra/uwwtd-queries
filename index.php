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
    <tr>
      <td>default test query</td>
      <td>(displays all columns, first 10 results)</td>
      <td>
        <a href="query.php">GO!</a>
      </td>
    </tr>
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
    <tr>
      <td>distance</td>
      <td>distance between UWWTPS and Aglommerations</td>
      <td>
        <a href="distance.php">GO!</a>
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
  }},
}).mount("#app");</script>
