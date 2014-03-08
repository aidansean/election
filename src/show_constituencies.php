<?php
$debug = false ;

// Connect to database
$mySQL_connection = mysql_connect('localhost', $mysql_username, $mysql_password) or die('Could not connect: ' . mysql_error()) ;
mysql_select_db($mysql_database) or die('Could not select database') ;

// List of parties
$partyTitles = array() ; $partyColors = array() ; $includeParty = array() ; $partyNames = array() ;
$partyTitles[0] = 'Conservative'              ; $partyColors[0] = '#ddddff' ;
$partyTitles[1] = 'Labour'                    ; $partyColors[1] = '#ffdddd' ;
$partyTitles[2] = 'Liberal Democrat'          ; $partyColors[2] = '#ffffdd' ;
$partyTitles[3] = 'Scottish National Party'   ; $partyColors[3] = '#ddffff' ;
$partyTitles[4] = 'Plaid Cymru'               ; $partyColors[4] = '#ddffff' ;
$partyTitles[5] = 'Democratic Unionist Party' ; $partyColors[5] = '#ffeeee' ;
$partyTitles[6] = 'Sinn Fein'                 ; $partyColors[6] = '#ddffff' ;
$partyTitles[7] = 'Green'                     ; $partyColors[7] = '#ddffdd' ;
$partyTitles[8] = 'Independent'               ; $partyColors[8] = '#dddddd' ;
$partyTitles[9] = 'Other'                     ; $partyColors[9] = '#ffffff' ;
for($i=0 ; $i<count($partyTitles) ; $i++){
  $partyNames[$i] = strtolower(str_replace(' ','_',$partyTitles[$i])) ;
  $includeParty[$i] = 1-(isset($_GET['search'])) ;
  if(isset($_GET['include_party_' . $partyNames[$i]])) $includeParty[$i] = ($_GET['include_party_' . $partyNames[$i]]=='on') ? 1 : 0 ;
}

// Get sort parameters
$sortField = array() ; $sortTitle = array() ;
$sortField[] = 'name'         ; $sortTitle[] = 'Name'                 ;
$sortField[] = 'turnout'      ; $sortTitle[] = 'Turnout'              ;
$sortField[] = 'winningVotes' ; $sortTitle[] = 'Winning vote'         ;
$sortField[] = 'majority'     ; $sortTitle[] = 'Majority'             ;
$sortField[] = 'lead'         ; $sortTitle[] = 'Lead'                 ;
$sortField[] = 'party'        ; $sortTitle[] = 'Party'                ;
$sortField[] = 'nCandidates'  ; $sortTitle[] = 'Number of candidates' ;

$sort = 'name' ; $order = 'ASC' ;
// Protect SQL from injection
if(isset($_GET['sort'])){
  switch($_GET['sort']){
    case 'turnout'      : $sort = 'turnout'      ; break ;
    case 'winningVotes' : $sort = 'winningVotes' ; break ;
    case 'majority'     : $sort = 'majority'     ; break ;
    case 'lead'         : $sort = 'lead'         ; break ;
    case 'party'        : $sort = 'party'        ; break ;
    case 'nCandidates'  : $sort = 'nCandidates'  ; break ;
    default : break ;
  }
}
if(isset($_GET['order'])) $order = ( $_GET['order']=='DESC' ) ? 'DESC' : 'ASC' ;

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
<head>
<title>2010 Election Results</title>
<link rel="stylesheet" type="text/css" title="Styles" href="style.css"/>
</head>
<body>

<?php if($debug==1){ echo '<pre>' ; print_r($_GET) ; print_r($includeParty) ; echo '</pre>' ; } ?>

<div id="maincontainer">
  <div id="topsection">
    <div class="innertube"><h1>2010 Election Results, by constituencies</h1></div>
  </div>
  <div id="contentwrapper">
    <div id="contentcolumn">
      <div class="innertube">
        <table class="results">
          <thead>
            <tr>
              <th>Name</th>
              <th>Total votes</th>
              <th>Turnout</th>
              <th>Winner</th>
              <th>Winning votes</th>
              <th>Majority</th>
              <th>Lead</th>
              <th>Number of candidates</th>
            </tr>
          </thead>
          <tbody>
<?php
$query = 'SELECT * FROM ' . $mysql_prefix . 'election_constituencies ORDER BY ' . $sort . ' ' . $order ;
$result = mysql_query($query) ;
$counter = 0 ;
while($constituency = mysql_fetch_assoc($result) AND $counter<10000){
  $escape = true ;
  $mainParty = false ;
  for($i=0 ; $i<count($partyTitles)-1 ; $i++){
    if($constituency['party']==$partyTitles[$i]){
      $mainParty = true ;
      if($includeParty[$i]==1){
        $escape = false ;
        break ;
      }
    }
  }
  if($mainParty==false AND $includeParty[count($partyTitles)-1]==1) $escape = false ;
  if($escape) continue ;
  $counter++ ;
  $class = strtolower(str_replace(' ','_',$constituency['party'])) ;
  $query2 = 'SELECT * FROM ' . $mysql_prefix . 'election_candidates WHERE id=' . $constituency['winner'] . ' LIMIT 1' ;
  $result2 = mysql_query($query2) ;
  while($candidate=mysql_fetch_assoc($result2)){ $candidateName = $candidate['name'] ; }
  echo 
  '            <tr class="' , $class , '">' , PHP_EOL , 
  '              <td><a href="' , $constituency['url'] , '">' , $constituency['name'] , '</a>'       , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $constituency['totalVotes']                               , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $constituency['turnout'] , '%'                            , '</td>' , PHP_EOL ,
  '              <td>'                   , $candidateName , ' (' , $constituency['party'] , ')'      , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $constituency['winningVotes']                             , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $constituency['majority']                                 , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , sprintf('%.1f',$constituency['lead']) , '%'               , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $constituency['nCandidates']                              , '</td>' , PHP_EOL , 
  '            </tr>' , PHP_EOL ;
}

?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="leftcolumn">
    <div class="innertube">
      <form action="" method="get">
      <h2>Search options</h2>
      <h3>Order by</h3>
      <p class="center">
        <select class="search" name="sort">
<?php
for($i=0 ; $i<count($sortField) ; $i++){
  echo '        <option value="' , $sortField[$i] , '" ' ;
  if($sort==$sortField[$i]) echo 'selected="selected" ' ;
  echo '>' , $sortTitle[$i] , '</option>' , PHP_EOL ;
}
?>
        </select>
        <select class="search" name="order">
<?php
  echo ($order=='ASC') ? '<option value="ASC" selected="selected">Ascending</option><option value="DESC">Descending</option></select>' . PHP_EOL : '<option value="ASC">Ascending</option><option value="DESC" selected="selected">Descending</option></select>' . PHP_EOL ;
?>
      </p>
      <h3>Include</h3>
      <table class="search">
        <tbody>
<?php
for($i=0 ; $i<count($partyTitles) ; $i++){
  echo  '          <tr class="' , $partyNames[$i] , '"><td><input type="checkbox" ' ;
  if($includeParty[$i]==1) echo 'checked="checked"' ;
  echo '" name="include_party_' , $partyNames[$i] , '"/></td><td>' , $partyTitles[$i] , '</td></tr>' , PHP_EOL ;
}

?>
        </table>
      </table>
      <p class="center">
        <input type="submit" name="search" value="Search" />
      </p>
      </form>
      
      <p class="center">
        <a href="http://news.bbc.co.uk/1/shared/election2010/results/">BBC results 2010</a><br /> 
        <a href="http://news.bbc.co.uk/1/hi/uk_politics/vote_2005/constituencies/default.stm">BBC results 2005</a>
      </p>
    </div>
  </div>
  <div id="footer">
    <p class="center">&copy; 2010 <a href="http://www.aidansean.com">aidansean</a></p>
  </div>
</div>

</body>
</html>