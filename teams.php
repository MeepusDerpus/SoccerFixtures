<html>
  <head >
    <title>Teams</title> 
  </head>
  <body>
    <?php

      $db = include('includes\config.php');
      $EditTeam = "";
      $EditTeamEmail = "";
      $EditIndex = null;
      $Delete = 0;   
      $Edit = 0;   

      Menu();
      Initialize();
      if (count($_POST) > 0) {  UpdateDb(); } 
      Display($EditTeam, $EditTeamEmail, $EditIndex, $Delete, $Edit);


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
           global $EditTeam, $EditTeamEmail, $EditIndex, $Delete, $Edit;
           $EditTeam= $_GET['EditTeam'];
           $EditTeamEmail = $_GET['TeamEmail'];
           $EditIndex = $_GET['EditIndex'];
           $Delete = $_GET['Delete'];
           $Edit = $_GET['Edit']; 

        }
      }

      function Display($EditTeam, $EditTeamEmail, $EditIndex, $Delete, $Edit)
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
          $query = "Select * From teams Order By team_id;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        //display table of values
        echo ("<table>");
        echo ("<tr>
                <th>Team</th>
                <th>Team Email</th>
                <th>Actions</th>");
        while(list($team_id, $team_name, $team_email) = $result->fetch_row())
        {
          echo ("<tr>
                 <td>$team_name</td>
                 <td><a href=\"mailto:$team_email\">$team_email</a></td>
                 <td>
                   <a href=\"teams.php?EditIndex=$team_id&EditTeam=$team_name&TeamEmail=$team_email&Delete=0&Edit=1\">EDIT</a> 
                   <a href=\"teams.php?EditIndex=$team_id&EditTeam=$team_name&TeamEmail=$team_email&Delete=1&Edit=0\">DELETE</a> 
                 </td>
                 </tr>");
        }  
        echo ("</table></br>");  

        //Displays Add form based on flags
        if(($Delete == 0 && $Edit == 0) || $EditTeam == null)
        {
        echo ("<form method=\"post\" action=\"teams.php\"> 
               <fieldset>
               <legend>Add Team:</legend>   
               Team Name  </br> <input type=\"text\" name=\"EditTeamName\"value=\"$EditTeam\" ></br></br>
               Team Email </br> <input type=\"text\" name=\"EditTeamEmail\"value=\"$EditTeamEmail\" ></br>  
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=1>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
        }
        //Displays Edit form based on flags
        if($Delete == 0 && $Edit == 1 && $EditTeam != null)
        {
        echo ("<form method=\"post\" action=\"teams.php\"> 
               <fieldset>
               <legend>Edit Team: $EditTeam</legend>  
               Team Name  </br> <input type=\"text\" name=\"EditTeamName\"value=\"$EditTeam\" ></br></br>
               Team Email </br> <input type=\"text\" name=\"EditTeamEmail\"value=\"$EditTeamEmail\" ></br>  
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
        }
        //Displays Delete form based on flags
        if($Delete == 1 && $EditTeam != null)
        {
        echo ("<form method=\"post\" action=\"teams.php\">
               <fieldset> 
               Are you sure you want to Delete Team $EditTeam? </br> 
               <input type=\"hidden\" name=\"EditTeamName\"value=\"$EditTeam\" >
               <input type=\"hidden\" name=\"EditTeamEmail\"value=\"$EditTeamEmail\" > 
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
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
            $TeamName= $_POST['EditTeamName'];
            $TeamEmail= $_POST['EditTeamEmail'];
            $id = (int)$_POST['EditIndexVal'];
            $Add = (int)$_POST['Add'];

            ////Checks Edit flag
            if($Delete == 0 && $Edit == 1)
            {
              $query = "UPDATE teams SET team_name = '$TeamName', team_email = '$TeamEmail' WHERE team_id = $id";
              mysqli_query($con, $query);
            }
            //Checks Delete flag
            elseif ($Delete == 1 && $Edit == 0) 
            {
              $query = "DELETE FROM teams WHERE team_id = '$id'";
              mysqli_query($con, $query);
            }             
          }   
        }
        //checks Add flag
        if($Add)  
        {
          $query = "Select MAX(team_id) FROM teams;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        
          while($count = $result->fetch_row())
          {
            $Index = $count[0]+1;
          }          

          $query = "Insert Into teams values($Index,'$TeamName','$TeamEmail')";
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