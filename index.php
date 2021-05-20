<html>
	<head >
		<title>This is the title of my website</title>	
	</head>
	<body>
	<?php
        echo ("<div>
             <a href=\"competitions.php\">Competitions</a>        | 
             <a href=\"teams.php\">Teams</a>                      | 
             <a href=\"fixtures.php\">Fixtures</a>                | 
             <a href=\"player_position.php\">Player Positions</a> | 
             <a href=\"players.php\">Players</a>                  | 
             <a href=\"player_fixtures.php\">Player Fixtures</a>  | 
             <a href=\"reports.php\">Reports</a>                  | 
             </div></br>");
		

        echo("Welcome To my portfolio, Mansoor Rahman 74533703 </br><br>
             
             I'm a 24 year old CS student, & I'm a part time developer. </br>
             I quite enjoyed this php assignment, and I think it's something I'll continue to work on. </br>
             Unfortunately I've shot myself in the foot as I'm in the middle of exams and have other work commitments. </br></br> 
             This is a bit incomplete, I'm lacking the last 2 Report options and I would've liked to have squashed some bugs. </br>
             My Player Fixtures page also behaves unusually when updating, It's probably my SQL queries. </br></br> 

             For Added functionality I've created validation methods for the date & time fields. </br>
             I would've liked to have gone a further step and implemented a dropdown/datetime picker. </br>
             I've used the <b>th</b> tag for some of my tables as the results didn't look very good without CSS. </br>
             Other Nice functionality would being able to edit values in tables by directly clicking on them instead of a form </br></br>             

             For Added functionality requiring DB changes, I propose: </br>
             Surrogate key for playerfixtures table, at the moment I'm using a composite key of fixture_id & player_id, this creates a restriction</br>
             Meaning only one record is stored per player in a fixture. </br></br>

             One Restriction of my program is that is reverse lookups id's based on names, this is a problem when you multiple entries of the same name.</br>
             This can be easily overcome by passing ids along with names, but I didn't think of it till it was too late. </br>
             I have included a fms.sql dump if you'd like to see my database schema for any reason. </br>
             Perhaps as a side note, a set of testing data would be nice for thorough debugging, but as everyone has a unique database, this is tricky. </br>
             Thank You </br>
             Mansoor Rahman </br>


            ");
      ?>      
	</body>
</html>