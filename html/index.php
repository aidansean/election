<?php
$title = '2010 election results' ;
include_once('project.php') ;
include_once($_SERVER['FILE_PREFIX'] . '/_core/preamble.php') ;
?>
  <div class="right">
    <h3><a href="show_constituencies.php">Election results sortable by constituency</a></h3>
    <div class="blurb_with_icon">
      <div class="image">
        <a href="show_constituencies.php"><img class="icon" src="<?=$_SERVER['HTTP_PREFIX']?>/images/elections.png" alt="Election results 2010" /></a>
	  </div>
      <p>Sort the results by constituency.  <a href="show_constituencies.php">See for yourself</a>!</p>
    </div>
  </div>
  
  <h3><a href="show_constituencies.php">Election results sortable by candidate</a></h3>
    <div class="blurb_with_icon">
      <div class="image">
        <a href="show_candidates.php"><img class="icon" src="<?=$_SERVER['HTTP_PREFIX']?>/images/elections.png" alt="Election results 2010" /></a>
	  </div>
      <p>Sort the results by candidates.  <a href="show_candidates.php">See for yourself</a>!</p>
    </div>
  </div>


<?php foot() ; ?>