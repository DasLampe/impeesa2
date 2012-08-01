<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

ob_start();
header("Content-Type: text/html;charset=UTF-8");

heading('ScoutNet API starten');

eval(code('
require "../src/scoutnet.php";
$sn = scoutnet();
'));

heading('Gruppe finden');

eval(code('
$group = $sn->group("name = ?",array("Stamm Tenkterer"));
echo $group;
'));

eval(code('
$group = $sn->group("name LIKE ?",array("%Tenkterer"));
echo $group->name;
'));

heading('URLs der Gruppe');

eval(code('
foreach( $group->urls() as $url ){
  echo $url->url . "\n";
}
'));

heading('Zwei Gruppen anhand der ids');

eval(code('
$groups = $sn->groups( array(5,6) );
foreach( $groups as $group ){
  echo $group->name() . "\n";
}
$group = $groups->first();
'));

heading('Termin finden');

eval(code('
$event = $sn->event( "137981" );
'));

heading('Attribut-Zugriff für verschiedene Geschmäcker!');

eval(code('
echo $event->title(); // Für code completion Liebhaber
echo $event->title;   // Für schnelle Tipper
echo $event["title"]; // Für array addicts
'));

heading('Der komplette Termin');

eval(code('echo $event;'));

heading('Die dazugehörige Gruppe');

eval(code('
$group = $event->group;
echo $group->name;
'));

heading('All Termine der Gruppe');

eval(code('
$events = $group->events();

foreach( $events as $event ){
  print $event->kind.": ".$event->start_date." - ".$event->title;
  print "<br />";
}
'));

heading("Die übergeordnete Gruppe");

eval(code('
$parent = $group->parent();
echo $parent->name();
'));

heading("Alle übergeordneten Gruppen");

eval(code('
$parents = $group->parents();

foreach( $parents as $parent ){
  print $parent->layer.": ".$parent->name;
  print "<br />";
}
'));

heading("Alle Stämme in Bezirk Bergisch Land");

eval(code('
print $sn->group("name = ?",array("Bezirk Bergisch Land"))->children()->select_one("name")->implode("<br />");
'));

heading("Eine URL und zugehörige Gruppe");

eval(code('
$url = $sn->url("url LIKE ?", array("%www.dpsg.de%"));
echo $url;
echo $url->group;
'));

heading("Suche nach Rover und Wölflings Ausbildungsterminen der DPSG im Jahr ".date('Y'));

eval(code('
$events = $sn->group(3)->events(\'
  start_date >= "'.date('Y').'-01-01"
  AND end_date <= "'.date('Y').'-12-31"
  AND "Schulung/Kurs" IN keywords
  AND ("Rover" IN sections OR "Wölflinge" IN sections)
\');

foreach( $events as $event ){
  print $event->kind.": ";
  print "(".$event->location.") ".$event->title;
  print "<br />";
}
'));

heading("Ausbildungstermine der DPSG, Regionen und Diözesen");

eval(code('
$regionen = $sn->group(3)->children();
function _region2dioezesen( $region ){ return $region->children(); }
$dioezesen = $regionen->map( "_region2dioezesen" )->flatten();
$events = $sn->events(
  "\'Schulung/Kurs\' IN keywords
   AND (group_id = 3 OR group_id IN (?) OR group_id IN (?))
   AND start_date > ?"
  , array( $regionen->select_one("global_id"), $dioezesen->select_one("global_id"), "'.date('Y').'-01-01" )
);

print "<table><tr><th>kind</th><th>Datum</th><th>Titel</th><th>ID der Gruppe</th></tr><tr>";
foreach( $events as $event ){
  print "<td>".$event->kind."</td>";
  print "<td>".$event->start_date."</td><td>".$event->title."</td><td>".$event->group_id."</td>";
  print "</tr><tr>";
}
print "</tr></table>";
'));

##############################################
## embed into HTML
##############################################
$body = ob_get_clean();
?>
<html>
<head>
  <script src="highlight/highlight.pack.js"></script>
  <script>
    hljs.tabReplace = '    ';
    hljs.initHighlightingOnLoad();
  </script>
  <link rel="stylesheet" title="default" href="highlight/styles/idea.css">
  <style>
    pre code {
      background: #f0f0f0;
      padding:5px;
    }
    .inline pre, .inline pre code {
      display: inline !important;
      padding:1px;
    }
    .inline pre{
    }
    body{
      white-space: pre;
      font-family: monospace;
    }
    td, th{
      padding: 0 5px;
    }
    th{
      text-align:left;
    }
  </style>

</head>
<body>
<?php echo $body ?>

<!-- using API uri <?php echo SN_API_URL ?> -->
</body>
</html><?php
function heading($heading){
  ?><h2><?php echo $heading?></h2><?php
}
function code($code){
  print
    "<pre><code>"
    .trim(htmlentities($code,ENT_COMPAT,'UTF-8'))
    ."</code></pre>";
  return $code.';';
}
