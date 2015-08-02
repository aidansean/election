<?php

include('classes.php') ;
$source = file_get_contents('listOfConstituencies.html') ;
$constituencies = array() ;
$parts = explode('title="',$source) ;
for($i=1 ; $i<count($parts) ; $i++){
  $constituency = new constituency() ;
  $linkParts = explode('"',$parts[$i]) ;
  $constituency->name = $linkParts[0] ;
  $constituency->url  = 'http://news.bbc.co.uk' . $linkParts[2] ;
  $constituencies[] = $constituency ;
  
  $theSource = file_get_contents($constituency->url) ;
  //$theSource = file_get_contents('sample.txt') ;
  $table = retrieveLong($theSource,'<table class="candidate-detail"','</table>') ;
  $candidates = array() ;
  $candidatesParts = explode('<tr class="party-',$table) ;
  $constituency->totalVotes = 0 ;
  for($j=1 ; $j<count($candidatesParts) ; $j++){
    $candidate = new candidate() ;
    $candidate->constituency = $i ;
    $candidate->name = trim(retrieveShort($candidatesParts[$j],'<span class="party-colour">','</span>')) ;
    $cells = explode('<td',$candidatesParts[$j]) ;
    $candidate->party = retrieveShort($cells[2],'>','</td') ;
    $candidate->votes = str_replace(',','',retrieveShort($cells[3],'"val">','</td')) ;
    $candidate->status = ($j==1) ? 1 : 0 ;
    $candidates[] = $candidate ;
    $constituency->totalVotes += $candidate->votes ;
  }
  $turnoutPart = retrieveShort($table,'<tbody class="totals">','</tbody>') ;
  $turnoutParts = explode('<td class="val"',$turnoutPart) ;
  $constituency->turnout = retrieveShort($turnoutParts[4],'>','</td') ;
  $constituency->candidates = $candidates ;
}

echo '<?php' , PHP_EOL , PHP_EOL ;
echo 'include(\'classes.php\') ;' , PHP_EOL ;
echo '$constituencies = array() ;' , PHP_EOL , PHP_EOL ;
for($i=0 ; $i<count($constituencies) ; $i++){
  echo '$constituencies[' , $i ,'] = new constituency() ;' ,PHP_EOL ;
  echo '$constituencies[' , $i ,']->name = '       , $constituencies[$i]->name       , ' ;'   , PHP_EOL ;
  echo '$constituencies[' , $i ,']->url  = \''     , $constituencies[$i]->url        , '\' ;' , PHP_EOL ;
  echo '$constituencies[' , $i ,']->turnout  = '   , $constituencies[$i]->turnout    , ' ;'   , PHP_EOL ;
  echo '$constituencies[' , $i ,']->totalVotes = ' , $constituencies[$i]->totalVotes , ' ;'   , PHP_EOL ;
  echo '$candidates = array() ;' , PHP_EOL ;
  for($j=0 ; $j<count($constituencies[$i]->candidates) ; $j++){
    echo '$candidates[' , $j , '] = new candidate() ;' , PHP_EOL ;
    echo '  $candidates[' , $j , ']->name = \''        , $constituencies[$i]->candidates[$j]->name         , '\' ;' , PHP_EOL ; 
    echo '  $candidates[' , $j , ']->votes = '         , $constituencies[$i]->candidates[$j]->votes        , ' ;'   , PHP_EOL ; 
    echo '  $candidates[' , $j , ']->party = \''       , $constituencies[$i]->candidates[$j]->party        , '\' ;' , PHP_EOL ; 
    echo '  $candidates[' , $j , ']->status = '        , $constituencies[$i]->candidates[$j]->status       , ' ;'   , PHP_EOL ; 
    echo '  $candidates[' , $j , ']->constituency = '  , $constituencies[$i]->candidates[$j]->constituency , ' ;'   , PHP_EOL ; 
  }
  echo '$constituencies[' , $i , ']->candidates = $candidates ;' , PHP_EOL ;
  echo 'unset($candidates) ;' , PHP_EOL , PHP_EOL ;
}
echo '?>' , PHP_EOL ;

function retrieveLong($haystack,$start,$end){ return $start . retrieveShort($haystack,$start,$end) . $end ; }
function retrieveShort($haystack,$start,$end){
  $parts = explode($start,$haystack) ;
  $parts = explode($end,$parts[1]) ;
  return $parts[0] ;
}

?>