<?php

$turnout      = array() ;
$totalVotes   = array() ;
$winningVotes = array() ;
$majority     = array() ;
$party        = array() ;
$lead         = array() ;
for($i=0 ; $i<count($constituencies) ; $i++){
  $turnout[$i]      = $constituencies[$i]->turnout ;
  $totalVotes[$i]   = $constituencies[$i]->totalVotes ;
  $winningVotes[$i] = $constituencies[$i]->candidates[0]->votes ;
  $majority[$i]     = $constituencies[$i]->candidates[0]->votes - $constituencies[$i]->candidates[1]->votes ;
  $party[$i]        = $constituencies[$i]->candidates[0]->party ;
  $lead[$i]         = ($totalVotes[$i]>0) ? $majority[$i]/$totalVotes[$i] : 0 ;
}

?>