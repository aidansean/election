<?php
include_once($_SERVER['FILE_PREFIX']."/project_list/project_object.php") ;
$github_uri   = "https://github.com/aidansean/election" ;
$blogpost_uri = "http://aidansean.com/projects/?tag=election" ;
$project = new project_object("election", "General election data", "https://github.com/aidansean/election", "http://aidansean.com/projects/?tag=election", "election/images/project.jpg", "election/images/project_bw.jpg", "This project displays the results of the 2010 UK General Electrion, and it was to be the first of many that investigate different data sets.  One of my planned meta projects is to make the acquisition, storage, and analysis of public domain data simpler and easier.  This was an experimental project, and created in a hurry.", "Data analysis", "CSS,HTML,MySQL,PHP,YouTube") ;
?>