<?php

// Connect to database
$mySQL_connection = mysql_connect('localhost', $mysql_username, $mysql_password) or die('Could not connect: ' . mysql_error()) ;
mysql_select_db($mysql_database) or die('Could not select database') ;

include_once('classes.php') ;
include_once('constituencies.php') ;
//exit() ;

//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD winner       INT' ; $result = mysql_query($query) or die(mysql_error()) ;
//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD party        INT' ; $result = mysql_query($query) or die(mysql_error()) ;
//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD majority     INT' ; $result = mysql_query($query) or die(mysql_error()) ;
//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD lead         INT' ; $result = mysql_query($query) or die(mysql_error()) ;
//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD nCandidates  INT' ; $result = mysql_query($query) or die(mysql_error()) ;
//$query = 'ALTER TABLE ' . $mysql_prefix . 'election_constituencies ADD winningVotes INT' ; $result = mysql_query($query) or die(mysql_error()) ;

$query = 'SELECT * FROM ' . $mysql_prefix . 'election_constituencies ORDER BY name' ;
$result = mysql_query($query) ;
while($constituency = mysql_fetch_assoc($result)){
  $query2 = 'SELECT * FROM ' . $mysql_prefix . 'election_candidates WHERE constituency=' . $constituency['id'] . ' ORDER BY votes DESC' ;
  $result2 = mysql_query($query2) or die(mysql_error() . ' ' . $query2) ;
  $firstResult  = -1 ;
  $secondResult = -1 ;
  $nCandidates  = 0 ;
  while($candidate = mysql_fetch_assoc($result2)){
    if($firstResult!=-1 AND $secondResult==-1) $secondResult = $candidate['votes'] ;
    if($firstResult==-1){
      $firstResult = $candidate['votes'] ;
      if($constituency['totalVotes']>0){
        $winner       = $candidate['id']    ;
        $winningVotes = $candidate['votes'] ;
        $party        = $candidate['party'] ;
      }
    }
    $nCandidates++ ;
  }
  $majority = $firstResult - $secondResult ;
  $lead = ($constituency['totalVotes']>0) ? 100*$majority/$constituency['totalVotes'] : 0 ;
  $query3 = 'UPDATE election_constituencies SET winner='       . $winner       .  ' WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) or die(mysql_error() . ' ' . $query3) ;
  $query3 = 'UPDATE election_constituencies SET party="'       . $party        . '" WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) ;
  $query3 = 'UPDATE election_constituencies SET majority='     . $majority     .  ' WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) ;
  $query3 = 'UPDATE election_constituencies SET lead='         . $lead         .  ' WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) ;
  $query3 = 'UPDATE election_constituencies SET nCandidates='  . $nCandidates  .  ' WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) ;
  $query3 = 'UPDATE election_constituencies SET winningVotes=' . $winningVotes .  ' WHERE id=' . $constituency['id'] ; $result3 = mysql_query($query3) ;
}

exit() ;

//$query = 'CREATE TABLE IF NOT EXISTS ' . $mysql_prefix . 'election_constituencies ( id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) UNIQUE, totalVotes INT, url VARCHAR(255), year INT )' ;
//echo $query , ' ' , $result , PHP_EOL ;

//$query = 'DROP TABLE ' . $mysql_prefix . 'election_candidates' ;
//$result = mysql_query($query) or die(mysql_error() . ' ' . $query) ;

//$query = 'CREATE TABLE IF NOT EXISTS ' . $mysql_prefix . 'election_candidates ( id INT AUTO_INCREMENT PRIMARY KEY, constituency INT, name VARCHAR(255) , party VARCHAR(255), votes INT, year INT, status INT )' ;
//$result = mysql_query($query) or die(mysql_error()) ;
//echo $query , ' ' , $result , PHP_EOL ;

//$query = 'DELETE FROM election_candidates WHERE TRUE' ;
//$result = mysql_query($query) or die(mysql_error() . ' ' . $query) ;
//for($i=0 ; $i<count($constituencies) ; $i++)
//{
  //$constituency = $constituencies[$i] ;
  //$query = 'INSERT INTO ' . $mysql_prefix . 'election_constituencies (name,totalVotes,url,year) VALUES ("' . $constituency->name . '",' . $constituency->totalVotes . ',"' . $constituency->url . '",2010)' ;
  //$query = 'UPDATE ' . $mysql_prefix . 'election_constituencies SET turnout=' . $constituency->turnout . ' WHERE id=' . $i ;
  //$result = mysql_query($query) ;
  //echo $query , PHP_EOL ;
  //for($j=0 ; $j<count($constituency->candidates) ; $j++)
  //{
    //$candidate = $constituency->candidates[$j] ;
    //$query = 'INSERT INTO ' . $mysql_prefix . 'election_candidates (constituency,name,party,votes,year,status) VALUES (' . ($i+1) . ',"' . str_replace("\"","\\\"",$candidate->name) . '","' . $candidate->party . '",' . $candidate->votes . ',2010,' . $candidate->status . ')' ;
    //$result = mysql_query($query) or die(mysql_error() . ' ' . $query) ;
    //echo $result , PHP_EOL ;
  //}
//}

exit() ;

$query = 'SELECT * FROM ' . $mysql_prefix . 'election_constituencies' ;
$result = mysql_query($result) ;
while($row = mysql_fetch_assoc($result)) print_r($row) ;

$query = 'SELECT * FROM ' . $mysql_prefix . 'election_candidates' ;
$result = mysql_query($result) ;
while($row = mysql_fetch_assoc($result)) print_r($row) ;

?>