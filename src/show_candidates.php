<?php
$debug = false ;

// Connect to database
$mySQL_connection = mysql_connect('localhost', $mysql_username, $mysql_password) or die('Could not connect: ' . mysql_error()) ;
mysql_select_db($mysql_database) or die('Could not select database') ;


// List of parties
$partyTitles = array() ; $partyColors = array() ; $includeParty = array() ; $partyNames = array() ;
$partyTitles[0]  = 'Conservative'              ; $partyColors[0]  = '#ddddff' ;
$partyTitles[1]  = 'Labour'                    ; $partyColors[1]  = '#ffdddd' ;
$partyTitles[2]  = 'Liberal Democrat'          ; $partyColors[2]  = '#ffffdd' ;
$partyTitles[3]  = 'Scottish National Party'   ; $partyColors[3]  = '#ddffff' ;
$partyTitles[4]  = 'Plaid Cymru'               ; $partyColors[4]  = '#ddffff' ;
$partyTitles[5]  = 'Democratic Unionist Party' ; $partyColors[5]  = '#ffeeee' ;
$partyTitles[6]  = 'Sinn Fein'                 ; $partyColors[6]  = '#ddffff' ;
$partyTitles[7]  = 'Green'                     ; $partyColors[7]  = '#ddffdd' ;
$partyTitles[8]  = 'British National Party'    ; $partyColors[8]  = '#ffddff' ;
$partyTitles[9]  = 'UK Independence Party'     ; $partyColors[9]  = '#ffddff' ;
$partyTitles[10] = 'Independent'               ; $partyColors[10] = '#dddddd' ;
$partyTitles[11] = 'Other'                     ; $partyColors[11] = '#ffffff' ;
for($i=0 ; $i<count($partyTitles) ; $i++){
  $partyNames[$i] = strtolower(str_replace(' ','_',$partyTitles[$i])) ;
  $includeParty[$i] = 1-(isset($_GET['search'])) ;
  if(isset($_GET['include_party_' . $partyNames[$i]])) $includeParty[$i] = ($_GET['include_party_' . $partyNames[$i]]=='on') ? 1 : 0 ;
}

// Get sort parameters
$sortField = array() ; $sortTitle = array() ;
$sortField[] = 'name'  ; $sortTitle[] = 'Name'  ;
$sortField[] = 'votes' ; $sortTitle[] = 'Votes' ;
$sortField[] = 'party' ; $sortTitle[] = 'Party' ;

$sort = 'name' ; $order = 'ASC' ;
// Protect SQL from injection
if(isset($_GET['sort'])){
  switch($_GET['sort']){
    case 'votes' : $sort = 'votes' ; break ;
    case 'party' : $sort = 'party' ; break ;
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
    <div class="innertube"><h1>2010 Election Results, by candidates</h1></div>
  </div>
  <div id="contentwrapper">
    <div id="contentcolumn">
      <div class="innertube">
        <table class="results">
          <thead>
            <tr>
              <th>Name</th>
              <th>Party</th>
              <th>Votes</th>
              <th>Constituency</th>
              <th>Elected?</th>
            </tr>
          </thead>
          <tbody>
<?php
$query = 'SELECT * FROM ' . $mysql_prefix . 'election_candidates ORDER BY ' . $sort . ' ' . $order ;
$result = mysql_query($query) ;
$counter = 0 ;
while($candidate = mysql_fetch_assoc($result) AND $counter<10000){
  $escape = true ;
  $mainParty = false ;
  for($i=0 ; $i<count($partyTitles)-1 ; $i++){
    if($candidate['party']==$partyTitles[$i]){
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
  $class = strtolower(str_replace(' ','_',$candidate['party'])) ;
  $success = ($candidate['status']==1) ? 'Yes' : 'No' ;
  $query2 = 'SELECT * FROM ' . $mysql_prefix . 'election_constituencies WHERE id=' . $candidate['constituency'] . ' LIMIT 1' ;
  $result2 = mysql_query($query2) ;
  $constituency=mysql_fetch_assoc($result2) ;
  echo 
  '            <tr class="' , $class , '">' , PHP_EOL , 
  '              <td>'                   , $candidate['name']  , '</td>' , PHP_EOL ,
  '              <td>'                   , $candidate['party'] , '</td>' , PHP_EOL ,
  '              <td class="td_number">' , $candidate['votes'] , '</td>' , PHP_EOL ,
  '              <td><a href="' , $constituency['url'] , '">' , $constituency['name'] , '</a></td>' , PHP_EOL ,
  '              <td>'                   , $success  , '</td>' , PHP_EOL ,
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
      <p class="center">Showing <?php echo $counter ; ?> results.</p>
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