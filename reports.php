<html>
  <head >
    <title>Player Information</title> 
  </head>
  <body>
    <?php
      $db = include('includes\config.php');

  
      $Option = 0;
      $TeamID = null;  

      Menu();
      Initialize();

      if (count($_POST) > 0) {  DisplayOptions(); } 
      Display($Option);
      

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
             </div> </br>");
      }      

      function Initialize()
      {
        //Sets the textbox based on which link is clicked
        if (count($_GET) > 0)
        {
           global $Option ,$TeamID;
           $Option = $_POST['SelectOption']; 
       //    $TeamID = $_POST['SelectTeam'];
           //$Option = 1;
        }
      }
      function Display( $Option)
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
          $query = "Select *  From fixtures";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        $PlayerArray = Array();
        $PlayerIDArray = Array();

        $TeamArray = Array();
        $TeamIDArray = Array();
        $query = "Select player_id, player_name from players;";
        $ArrayResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($PlayerIDVal ,$Playerval) = $ArrayResult->fetch_row())
        {
          array_push($PlayerArray, $Playerval);
          array_push($PlayerIDArray, $PlayerIDVal);
        } 

        $query = "Select team_id, team_name from teams;";
        $TeamResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($TeamIDVal ,$Teamval) = $TeamResult->fetch_row())
        {
          array_push($TeamArray, $Teamval);
          array_push($TeamIDArray, $TeamIDVal);
        }                
        //do display things

        echo ("<form method=\"post\" action=\"reports.php\"> 
               <fieldset>
               <legend>Choose Report:</legend>        
               Select Option 
               <select name= \"SelectOption\">
               <option selected=\"selected\" value=1>Option 1</option>
               <option value=2>Option 2</option>
               <option value=3>Option 3</option>                                
               <option value=4>Option 4</option>");
        echo ("</select> ");      

        echo("<input type=\"hidden\" name=\"SelectPlayer\"value='null' >
              <input type=\"hidden\" name=\"DisplayPlayer\"value=0 ><br>
              <input type=\"submit\" value=\"Display\"> 
              </fieldset>
              </form>");           

        if($Option == 1)
        {

            echo ("<form method=\"post\" action=\"reports.php\"> 
                  <fieldset>
                  <legend>Choose Player:</legend>");

            echo ("Select Player </br> ");
            echo ("<select name=\"SelectPlayer\">");

            for($i = 0; $i < count($PlayerArray); $i++)
            {
              echo ("<option value=\"$PlayerIDArray[$i]\">Player $PlayerArray[$i]</option>");
            }
            echo ("</select> </br>");  

            echo("<input type=\"hidden\" name=\"SelectOption\"value=1>
                  <input type=\"hidden\" name=\"DisplayTeam\"value=0>
                  <input type=\"hidden\" name=\"DisplayPlayer\"value=1><br>
                  <input type=\"submit\" value=\"Display\"> 
                  </fieldset>
                  </form>");  

                             
        }
        if($Option == 2)
        {
            echo ("<form method=\"post\" action=\"reports.php\"> 
                  <fieldset>
                  <legend>Choose Team:</legend>");

            echo ("Select Player </br> ");
            echo ("<select name=\"SelectTeam\">");

            for($i = 0; $i < count($TeamArray); $i++)
            {
              echo ("<option value=\"$TeamIDArray[$i]\">Team $TeamArray[$i]</option>");
            }
            echo ("</select> </br>");  

            echo("<input type=\"hidden\" name=\"SelectOption\"value=2>
                  <input type=\"hidden\" name=\"DisplayTeam\"value=1>
                  <input type=\"hidden\" name=\"DisplayPlayer\"value=0 ><br>
                  <input type=\"submit\" value=\"Display\"> 
                  </fieldset>
                  </form>");              

        } 
        if($Option == 3)
        {
            
        }
        if($Option == 4)
        {
            
        }          

      }//function

      function DisplayOptions()
      {
        if (count($_POST) > 0)
        {
              
          global $db, $Option;
          $con = mysqli_connect($db['host'],$db['user'],$db['pwd'],$db['dbname']);

            // Check connection
          if ($con == false)
          {
            die("ERROR: Could not connect. " . mysqli_connect_error());
          }
          else
          {
            $Option = $_POST['SelectOption'];
            if($Option == 1){$PlayerID = $_POST['SelectPlayer'];}  

            if($Option == 2 && isset($_POST['SelectTeam'])){$TeamID= $_POST['SelectTeam'];}
            else {$TeamID = null;}

            if($Option == 1 && $PlayerID != 'null')
            {
                    
              $query = "SELECT player_name, 
                             (Select team_name FROM teams WHERE players.team_id = teams.team_id),
                             (SELECT SUM(goals_scored) FROM playerfixtures WHERE player_id =$PlayerID) 
                              FROM players WHERE player_id = $PlayerID;";

              $result = $con->query($query,MYSQLI_STORE_RESULT);
         //  echo($query);

              //display table    
              echo ("<table>");
              echo ("<tr>
                  <th>Player</th>
                  <th>Team</th>
                  <th>Goals</th>");
       
              while(list($PlayerName, $TeamName, $TotalGoals) = $result->fetch_row())
              {
                echo ("<tr>
                       <td>$PlayerName</td>
                       <td>$TeamName</td>
                       <td>$TotalGoals</td>
                       </tr>");
              }  
              echo ("</table></br>");             
              echo("Total goals per fixture </br></br>");

              $query = "SELECT fixture_id,
                        (SELECT player_name FROM players WHERE playerfixtures.player_id = players.player_id), 
                               goals_scored FROM playerfixtures WHERE player_id = $PlayerID;";
              $result = $con->query($query,MYSQLI_STORE_RESULT);;


              //display table         
              echo ("<table>");
              echo ("<tr>
                  <th>Player</th>
                  <th>Fixture</th>
                  <th>Goals</th>");
         
              while(list($Fixture,$PlayerName, $TotalGoals) = $result->fetch_row())
              {
                echo ("<tr>
                       <td>$PlayerName</td>
                       <td>$Fixture</td>
                       <td>$TotalGoals</td>
                       </tr>");
              }  
              echo ("</table></br>");               
            }

            //option 2
            if($Option == 2 && $TeamID != null)
            {     
              $query = "SELECT team_name, (SELECT SUM(goals_scored) from playerfixtures where player_id IN (SELECT player_id FROM players WHERE team_id = $TeamID)) FROM teams WHERE team_id = $TeamID;";
              $result = $con->query($query,MYSQLI_STORE_RESULT);

              //display table          
              echo ("<table>");
              echo ("<tr>
                  <th>Team</th>
                  <th>Goals</th>");

              
              while(list( $TeamName, $TotalGoals) = $result->fetch_row())
              {
                if($TotalGoals == null){$TotalGoals = 0;}  
                echo ("<tr>
                       <td>$TeamName</td>
                       <td>$TotalGoals</td>
                       </tr>");
              }  
              echo ("</table></br>");                       
            }
            //Option 3 todo
            if($Option == 3)
            {

            }
            //Option 4 todo
            if($Option == 4)
            {
                  
            }
          }  
        }
        mysqli_close($con); 
      }//function



    ?>
  </body>
<footer>
  <a href="Index.php">Back to Index</a>
</footer>  
</html>