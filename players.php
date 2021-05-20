<html>
  <head >
    <title>Player Information</title> 
  </head>
  <body>
    <?php
      $db = include('includes\config.php');
      $EditPlayerID = null;
      $EditPlayerName = "";
      $EditTeamID = null;
      $EditPlayerSquad = null;
      $EditPlayerPosition = null;

      $Delete = 0;   
      $Edit = 0;
      $OrderByTeam = -1;         

      Menu();
      Initialize();
      if (count($_POST) > 0) {  UpdateDb(); }
      display($EditPlayerID, $EditPlayerName, $EditTeamID, $EditPlayerSquad, $EditPlayerPosition, $OrderByTeam, $Delete, $Edit);

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
        if (count($_GET) > 0 )
        {
          global $EditPlayerID, $EditPlayerName, $EditTeamID, $EditPlayerSquad, $EditPlayerPosition, $OrderByTeam, $Delete, $Edit;

          $OrderByTeam        = $_GET['OrderByTeam'];
          
          if(isset($_GET['EditPlayerID']))      {$EditPlayerID       = $_GET['EditPlayerID'];}
          if(isset($_GET['EditPlayerName']))    {$EditPlayerName     = $_GET['EditPlayerName'];}
          if(isset($_GET['EditTeamID']))        {$EditTeamID         = $_GET['EditTeamID'];}
          if(isset($_GET['EditPlayerSquad']))   {$EditPlayerSquad    = $_GET['EditPlayerSquad'];}
          if(isset($_GET['EditPlayerPosition'])){$EditPlayerPosition = $_GET['EditPlayerPosition'];}                   
          if(isset($_GET['Delete']))             {$Delete            = $_GET['Delete'];}
          if(isset($_GET['Edit']))               {$Edit              = $_GET['Edit']; }     
        }
      }

      function display($EditPlayerID, $EditPlayerName, $EditTeamID, $EditPlayerSquad, $EditPlayerPosition, $OrderByTeam, $Delete, $Edit)
      {
        global $db;
        $con = mysqli_connect($db['host'],$db['user'],$db['pwd'],$db['dbname']);
  
        // Check connection
        if ($con == false)
        {
          die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        else
        {
          if($OrderByTeam)
          {
            $query = "SELECT player_id, 
                             player_name, 
                             (Select team_name from teams where players.team_id = teams.team_id), 
                             player_sqd_num,
                             (SELECT position_descr FROM playerposition WHERE playerposition.position_id = players.position_id) FROM players Order By team_id;";
          }
          else
          {
            $query = "SELECT player_id, 
                             player_name, 
                             (Select team_name from teams where players.team_id = teams.team_id), 
                             player_sqd_num,
                             (SELECT position_descr FROM playerposition WHERE playerposition.position_id = players.position_id) FROM players;";
          }
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }

        $TeamsArray      = Array();
        $TeamsIDArray    = Array();
        $PositionArray   = Array();
        $PositionIDArray = Array();

        $query = "SELECT team_id, team_name FROM teams;";
        $ArrayResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($TeamIDVal ,$Teamval) = $ArrayResult->fetch_row())
        {
          array_push($TeamsArray, $Teamval);
          array_push($TeamsIDArray, $TeamIDVal);
        }   
        $query = "SELECT position_id, position_descr FROM playerposition Order By position_id;";
        $PositionResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($PositionIDVal ,$Positionval) = $PositionResult->fetch_row())
        {
          array_push($PositionArray, $Positionval);
          array_push($PositionIDArray, $PositionIDVal);
        }   

        //display table
        echo ("<table>");
        echo ("<tr>
               <th><a href=\"players.php?OrderByTeam=0\">Player</a></th>
               <th><a href=\"players.php?OrderByTeam=1\">Team</a></th>
               <th>Shirt Number</th>
               <th>Position</th>
               <th>Actions</th>");
        while(list($Player_ID, $Player_Name, $Team_ID, $Player_Squad, $Position_ID) = $result->fetch_row())
        {
          echo ("<tr>
                 <th>$Player_Name</th>
                 <th>$Team_ID</th>
                 <th>$Player_Squad</th>
                 <th>$Position_ID</th>
                 <th>
                   <a href=\"players.php?EditPlayerID=$Player_ID&EditPlayerName=$Player_Name&EditTeamID=$Team_ID&EditPlayerSquad=$Player_Squad&EditPlayerPosition=$Position_ID&OrderByTeam=$OrderByTeam&Delete=0&Edit=1\">EDIT</a> 
                   <a href=\"players.php?EditPlayerID=$Player_ID&EditPlayerName=$Player_Name&EditTeamID=$Team_ID&EditPlayerSquad=$Player_Squad&EditPlayerPosition=$Position_ID&OrderByTeam=$OrderByTeam&Delete=1&Edit=0\">DELETE</a></th>
                 </tr>");
        }  
        echo ("</table></br>");  


        if(($Delete == 0 && $Edit == 0) || $EditPlayerName == null)
        {
         echo ("<form method=\"post\" action=\"players.php\"> 
               <fieldset>
               <legend>Add Player: </legend>   
               Surname Name  </br> <input type=\"text\" name=\"EditPlayerName\"value=\"$EditPlayerName\" ></br></br>");

               echo ("Select Team </br>");
               echo ("<select name=\"SelectTeam\">");
               foreach($TeamsArray as $element)
               {
                 if ($element == $EditTeamID) {echo ("<option selected=\"selected\" value=\"$element\">Team $element</option>");}
                 else {echo ("<option value=\"$element\">Team $element</option>");}
               }
 
               echo ("</select> </br></br>

               Number </br> <input type=\"text\" name=\"EditPlayerSquad\"value=\"$EditPlayerSquad\" ></br></br>"); 
               echo("<table>
                     <tr> 
                       <th>Position</th>
                       <th>$EditPlayerPosition</th>
                     </tr>");
               for ($i = 0; $i < 7; $i++)
               {
                 echo("</tr>");
                 $j = $i+7;
                 if($EditPlayerPosition == $PositionArray[$i])
                 {
                   echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\" checked=\"checked\"> $PositionArray[$i]<br></td> 
                         <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\"> $PositionArray[$i]<br></td>");
                 }
                 elseif($EditPlayerPosition == $PositionArray[$j])
                 {
                    echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\"> $PositionArray[$i]<br></td> 
                          <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$j]\" checked=\"checked\"> $PositionArray[$j]<br></td>");
                 }
                 else
                 {
                    echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\"> $PositionArray[$i]<br></td> 
                          <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$j]\"> $PositionArray[$j]<br></td>"); 
                 }

                 echo("</tr>");   
               }           
               echo("</table>");

               echo("
                     <input type=\"hidden\" name=\"EditPlayerID\"value=\"$EditPlayerID\" >
                     <input type=\"hidden\" name=\"EditTeamID\"value=\"$EditTeamID\" >                     
                     <input type=\"hidden\" name=\"Add\"value=1>
                     <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
                     <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
                     <input type=\"submit\" value=\"Submit\"> 
                     </fieldset>
                     </form>");  
 
        }
        if($Delete == 0 && $Edit == 1 && $EditPlayerName != null)
        {
          echo ("<form method=\"post\" action=\"players.php\"> 
               <fieldset>
               <legend>Edit Player: $EditPlayerName</legend>   
               Surname Name  </br> <input type=\"text\" name=\"EditPlayerName\"value=\"$EditPlayerName\" ></br></br>");

               echo ("Select Team </br>");
               echo ("<select name=\"SelectTeam\">");
               foreach($TeamsArray as $element)
               {
                 if ($element == $EditTeamID) {echo ("<option selected=\"selected\" value=\"$element\">Team $element</option>");}
                 else {echo ("<option value=\"$element\">Team $element</option>");}
               }
 
               echo ("</select> </br></br>

               Number </br> <input type=\"text\" name=\"EditPlayerSquad\"value=\"$EditPlayerSquad\" ></br></br>"); 
               echo("<table>
                     <tr> 
                       <th>Position</th>
                       <th>$EditPlayerPosition</th>
                     </tr>");
               for ($i = 0; $i < 7; $i++)
               {
                 echo("</tr>");
                 $j=$i+7;
                 if($EditPlayerPosition == $PositionArray[$i])
                 {
                   echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\" checked=\"checked\"> $PositionArray[$i]<br></td> 
                         <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$j]\"> $PositionArray[$j]<br></td>");
                 }
                 elseif($EditPlayerPosition == $PositionArray[$j])
                 {
                    echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\"> $PositionArray[$i]<br></td> 
                          <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$j]\" checked=\"checked\"> $PositionArray[$j]<br></td>");
                 }
                 else
                 {
                    echo("<td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$i]\"> $PositionArray[$i]<br></td> 
                          <td><input type=\"radio\" name=\"Position\" value=\"$PositionIDArray[$j]\"> $PositionArray[$j]<br></td>"); 
                 }

                 echo("</tr>");   
               }           
               echo("</table>");

               echo("
                     <input type=\"hidden\" name=\"EditPlayerID\"value=\"$EditPlayerID\" >
                     <input type=\"hidden\" name=\"EditTeamID\"value=\"$EditTeamID\" >
                     <input type=\"hidden\" name=\"Add\"value=0>
                     <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
                     <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
                     <input type=\"submit\" value=\"Submit\"> 
                     </fieldset>
                     </form>");  
        }
        if($Delete == 1 && $EditPlayerName != null)
        {
        echo ("<form method=\"post\" action=\"players.php\">
               <fieldset> 
               Are you sure you want to Delete player $EditPlayerName?  </br></br>
               <input type=\"hidden\" name=\"EditPlayerName\"value=\"$EditPlayerName\" >
               <input type=\"hidden\" name=\"EditPlayerID\"value=\"$EditPlayerID\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" >
               <input type=\"submit\" value=\"Delete\"> 
               </fieldset>
               </form>"); 
        }        
          mysqli_close($con); 
      }//function

      function UpdateDb()
      {
        if (count($_POST) > 0)
        {
              
          global $db, $Delete, $Edit;
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

            $PlayerID       = $_POST['EditPlayerID'];
            $PlayerName     = $_POST['EditPlayerName'];
            if(!$Delete)
            {
              $TeamID         = $_POST['SelectTeam'];
              $PlayerSquad    = $_POST['EditPlayerSquad'];
              $PlayerPosition = $_POST['Position'];            
            }


            if($Delete == 0 && $Edit == 1)
            {
              $query = "UPDATE players SET team_id = (SELECT team_id FROM teams WHERE teams.team_name ='$TeamID'), player_name = '$PlayerName', player_sqd_num = $PlayerSquad, position_id = $PlayerPosition WHERE player_id = $PlayerID";
              mysqli_query($con, $query);
            }
            elseif ($Delete == 1 && $Edit == 0) 
            {
              $query = "DELETE FROM players WHERE player_id = $PlayerID";
              mysqli_query($con, $query);
            }
          }  
        }
        if ($Add)
        {  
          $query = "Select MAX(player_id) FROM players;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        
          while($count = $result->fetch_row())
          {
            $Index = $count[0]+1;
          }          
          $query = "INSERT INTO players VALUES($Index,(SELECT team_id FROM teams WHERE teams.team_name ='$TeamID'),'$PlayerName',$PlayerSquad,$PlayerPosition)";
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