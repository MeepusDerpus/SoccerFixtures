<html>
	<head >
		<title>Player Fixtures</title>	
	</head>
	<body>
		<?php

      $db = include('includes\config.php');
      $EditPlayerID = null;
      $EditPlayerName = "";
      $EditFixID = null;
      $EditGoals = null;
      $Delete = 0;   
      $Edit = 0;  

      Menu();
      Initialize();
      if (count($_POST) > 0) {  edit(); }
      Display($EditFixID, $EditPlayerID, $EditPlayerName, $EditGoals, $Delete,  $Edit);

      function Menu()
      {
        echo ("<div>
             <a href=\"competitions.php\">Competitions</a>        | 
             <a href=\"teams.php\">Teams</a>                      | 
             <a href=\"fixtures.php\">Fixtures</a>                | 
             <a href=\"player_position.php\">Player Positions</a> | 
             <a href=\"players.php\">Players</a>                  | 
             <a href=\"player_fixtures.php\">Player Fixtures</a>  | 
             <a href=\"reports.php\">Reports</a>                  | 
             </div>");
      }

      function Initialize()
      {
        //Sets the textbox based on which link is clicked
        if (count($_GET) > 0)
        {
           global $EditPlayerName, $EditFixID, $EditGoals, $EditPlayerID, $Delete, $Edit;
           $EditPlayerID= $_GET['EditPlayerID'];
           $EditPlayerName = $_GET['EditPlayerName'];
           $EditFixID = $_GET['EditFixID'];
           $EditGoals = $_GET['EditGoals'];
           $Delete = $_GET['Delete'];
           $Edit = $_GET['Edit'];
        }
      }

      function Display($EditFixID, $EditPlayerID, $EditPlayerName,  $EditGoals, $Delete, $Edit)
      {
        global $db, $NewFixID;
        $con = mysqli_connect($db['host'],$db['user'],$db['pwd'],$db['dbname']);

			  // Check connection
			  if ($con == false)
  			{
  			  die("ERROR: Could not connect. " . mysqli_connect_error());
  			}
  			else
  			{
          $query = "SELECT  player_id,(SELECT player_name FROM players WHERE players.player_id = playerfixtures.player_id), fixture_id, goals_scored FROM playerfixtures Order By fixture_id;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        //display table


        $FixtureArray = Array();
        $PlayerArray = Array();
        $query = "SELECT DISTINCT fixture_id from fixtures;";
        $ArrayResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($Fixtureval) = $ArrayResult->fetch_row())
        {
          array_push($FixtureArray, $Fixtureval);
        }

        $query = "SELECT DISTINCT player_name from players;";
        $PlayerResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($Playerval) = $PlayerResult->fetch_row())
        {
          array_push($PlayerArray, $Playerval);
        }
        echo ("<table>");
        echo ("<tr>
                <th>Player</th>
                <th>Fixture</th>
                <th>Goals Scored</th>
                <th>Actions</th>");
        while(list($PlayerID, $PlayerName, $FixtureID, $GoalsScored) = $result->fetch_row())
        {
          echo ("<tr>
                 <th>$PlayerName</th>
                 <th>$FixtureID</th>
                 <th>$GoalsScored</th>
                 <th>
                   <a href=\"player_fixtures.php?EditPlayerID=$PlayerID&EditPlayerName=$PlayerName&EditFixID=$FixtureID&EditGoals=$GoalsScored&Delete=0&Edit=1\">EDIT</a> 
                   <a href=\"player_fixtures.php?EditPlayerID=$PlayerID&EditPlayerName=$PlayerName&EditFixID=$FixtureID&EditGoals=$GoalsScored&Delete=1&Edit=0\">DELETE</a>
                 </th>
                 </tr>");
        }  
        echo ("</table></br>"); 


        if(($Delete == 0 && $Edit == 0) || $EditPlayerID == null)
        {
          echo ("<form method=\"post\" action=\"player_fixtures.php\"> 
               <fieldset>
               <legend>Add Player Fixture:</legend>");                 
               
            echo ("Player ");
            echo ("<select name=\"SelectPlayerName\">");
            foreach($PlayerArray as $element)
            {
              echo ("<option value=\"$element\">$element</option>");
            }
            echo ("</select> </br></br>");

            echo ("Fixture ");
            echo ("<select name= \"SelectFixture\">");
            foreach($FixtureArray as $element)
            {
              echo ("<option value=\"$element\">$element</option>");
            }
            echo ("</select> </br></br>");


           echo(" 
               Goals: <input type=\"text\" name=\"EditGoals\"value=\"$EditGoals\" ></br>
               <input type=\"hidden\" name=\"Add\"value=1>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
        }

        if($Delete == 0 && $Edit == 1 && $EditPlayerID != null)
        {
          echo ("<form method=\"post\" action=\"player_fixtures.php\"> 
               <fieldset>
               <legend>Edit Player Fixture: $EditFixID</legend>");                 
               
            echo ("Player ");
            echo ("<select name=\"SelectPlayerName\">");
            foreach($PlayerArray as $element)
            {
              if ($element == $EditPlayerName) {echo ("<option selected=\"selected\" value=\"$element\">$element</option>");}
              else {echo ("<option value=\"$element\">$element</option>");}
            }
            echo ("</select> </br></br>");

            echo ("Fixture ");
            echo ("<select name= \"SelectFixture\">");
            foreach($FixtureArray as $element)
            {

              if ($element == $EditFixID) {echo ("<option selected=\"selected\" value=\"$element\">$element</option>");}
              else {echo ("<option value=\"$element\">$element</option>");}
            }
            echo ("</select> </br></br>");


           echo("Goals: <input type=\"text\" name=\"EditGoals\"value=\"$EditGoals\" ></br> 
               <input type=\"hidden\" name=\"OldFixID\"value=$EditFixID>
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>");
        }        
        
        if($Delete == 1 && $EditPlayerID != null)
        {
        echo ("<form method=\"post\" action=\"player_fixtures.php\"> 
               Are you sure you want to Delete Player Fixture $EditFixID $EditPlayerName ? </br></br>
               <input type=\"hidden\" name=\"DelPlayerName\"value=$EditPlayerName>
               <input type=\"hidden\" name=\"OldFixID\"value=$EditFixID>
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" >
               <input type=\"submit\" value=\"Delete\"> 
               </form>"); 
        }        
          mysqli_close($con); 

        }//function


      function edit()
      {
        if (count($_POST) > 0)
        {
              
          global $db, $Delete;
          $con = mysqli_connect($db['host'],$db['user'],$db['pwd'],$db['dbname']);

            // Check connection
          if ($con == false)
          {
            die("ERROR: Could not connect. " . mysqli_connect_error());
          }
          else
          {
            $Delete = (int)$_POST['Delete'];
            $Edit = (int)$_POST['Edit'];
            $Add = (int)$_POST['Add'];

            if($Edit || $Delete){$OldFixID = (int)$_POST['OldFixID'];}             
            


            if(!$Delete)
            {
              $FixID = (int)$_POST['SelectFixture'];
              $PlayerID = $_POST['SelectPlayerName'];
              $Goals = (int)$_POST['EditGoals'];
       //       echo ("Current $FixID Old $OldFixID </br>");
            }
            else {$DelPlayerName = $_POST['DelPlayerName'];}
            


            if($Delete == 0 && $Edit == 1)
            {

              $query = "UPDATE playerfixtures SET 
                        player_id = (SELECT player_id FROM players WHERE player_name = '$PlayerID'), 
                        fixture_id = $FixID,
                        goals_scored = $Goals WHERE fixture_id = $OldFixID";
              echo ($query);
              mysqli_query($con, $query);
            }
            elseif ($Delete == 1 && $Edit == 0 && $Add == 0) 
            {
              $query = "DELETE FROM playerfixtures WHERE (fixture_id = $OldFixID AND player_id = (SELECT player_id FROM players WHERE player_name = '$DelPlayerName'))";
              echo($query);
              mysqli_query($con, $query);
            }
          }  
        }

        if($Add)     
        {
          //todo      
          $query = "INSERT INTO playerfixtures VALUES($FixID,(SELECT player_id FROM players WHERE player_name = '$PlayerID'),$Goals);";
          echo($query);
          mysqli_query($con, $query);
        }
        mysqli_close($con); 
      }//function
		?>
	</body>
<footer>
  <a href="Index.php">Back to Index</a>
</footer>
</html>