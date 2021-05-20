<html>
  <head >
    <title>Fixtures</title> 
  </head>
  <body>
    <?php

      $db = include('includes\config.php');
      include('includes\functions.php');
      $EditDate = "";
      $EditTime = "";
      $EditHome = "";
      $EditAway = "";
      $EditComp = "";

      $EditIndex = null;
      $Delete = 0;   
      $Edit = 0;   

      Menu();
      Initialize();

      if (count($_POST) > 0) {  UpdateDb(); }    
      Display($EditDate, $EditTime, $EditHome, $EditAway, $EditComp, $EditIndex, $Delete, $Edit);

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
           global $EditDate, $EditTime, $EditHome, $EditAway, $EditComp, $EditIndex, $Delete, $Edit;
           $EditDate= $_GET['EditDate'];
           $EditTime = $_GET['EditTime'];
           $EditHome= $_GET['EditHome'];
           $EditAway = $_GET['EditAway'];
           $EditComp = $_GET['EditComp'];
           $EditIndex = $_GET['EditIndex'];
           
           $Delete = $_GET['Delete'];
           $Edit = $_GET['Edit']; 

        }
      }

      function Display($EditDate, $EditTime, $EditHome, $EditAway, $EditComp, $EditIndex, $Delete, $Edit)
      {
        global $db;
        $con = mysqli_connect($db['host'],$db['user'],$db['pwd'],$db['dbname']);

       // echo(" date $EditDate time $EditTime edit $Edit del $Delete </br>");
        // Check connection
        if ($con == false)
        {
          die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        else
        {
          $query = "Select fixture_id, fixture_date, fixture_time, (Select team_name from teams where fixtures.home_teamID = teams.team_id), (Select team_name from teams where fixtures.away_teamID = teams.team_id), (Select comp_name from competitions where fixtures.comp_ID = competitions.comp_id)  From fixtures";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        $TeamsArray = Array();
        $TeamsIDArray = Array();
        $CompArray = Array();
        $CompIDArray = Array();
        $query = "Select team_id, team_name from teams;";
        $ArrayResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($TeamIDVal ,$Teamval) = $ArrayResult->fetch_row())
        {
          array_push($TeamsArray, $Teamval);
          array_push($TeamsIDArray, $TeamIDVal);
        }

        $query = "Select comp_id, comp_name from competitions;";
        $CompResult = $con->query($query,MYSQLI_STORE_RESULT);

        while(list($CompIDVal, $Compval) = $CompResult->fetch_row())
        {
          array_push($CompArray, $Compval);
          array_push($CompIDArray, $CompIDVal);
        }

        //display table
        echo ("<table>");
        echo ("<tr>
                <th>Fixture</th>
                <th>Date</th>
                <th>Time</th>
                <th>Home Team</th>
                <th>Away Team</th>
                <th>Comp</th>
                <th>Actions</th>");
        
        while(list($fixture_id, $fixture_date, $fixture_time, $home_teamID, $away_teamID, $comp_ID) = $result->fetch_row())
        {
          
          echo ("<tr>
                  <th>$fixture_id</th>
                  <th>$fixture_date</th>
                  <th>$fixture_time</th>
                  <th>$home_teamID</th>
                  <th>$away_teamID</th>
                  <th>$comp_ID</td>
                  <th>
                    <a href=\"fixtures.php?EditIndex=$fixture_id&EditDate=$fixture_date&EditTime=$fixture_time&EditHome=$home_teamID&EditAway=$away_teamID&EditComp=$comp_ID&Delete=0&Edit=1\">EDIT</a> 
                    <a href=\"fixtures.php?EditIndex=$fixture_id&EditDate=$fixture_date&EditTime=$fixture_time&EditHome=$home_teamID&EditAway=$away_teamID&EditComp=$comp_ID&Delete=1&Edit=0\">DELETE</a> 
                 </th>
                </tr>");
        }  
        echo ("</table></br>");  

        //displays Add Form
        if(($Delete == 0 && $Edit == 0) || $EditDate == null)
        {
            echo ("<form method=\"post\" action=\"fixtures.php\"> 
               <fieldset>
               <legend>Add Fixture:</legend>
               Date: <input type=\"text\" name=\"EditDate\"value=\"$EditDate\" ></br></br>
               Time: <input type=\"text\" name=\"EditTime\"value=\"$EditTime\" ></br> </br>");


            echo ("Select Home Team ");
            echo ("<select name=\"SelectHome\">");
            foreach($TeamsArray as $element)
            {
              echo ("<option value=\"$element\">Team $element</option>");
            }
            echo ("</select> </br></br>");

            echo ("Select Away Team ");
            echo ("<select name=\"SelectAway\">");
            foreach($TeamsArray as $element)
            {
              echo ("<option value=\"$element\">Team $element</option>");
            }
            echo ("</select> </br></br>");

            echo ("Select Comp ");
            echo ("<select name= \"SelectComp\">");
            foreach($CompArray as $element)
            {
              echo ("<option value=\"$element\">Comp $element</option>");
            }
            echo ("</select>");

            echo("<input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\">
                   <input type=\"hidden\" name=\"Add\"value=1>
                   <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
                   <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
                   <input type=\"submit\" value=\"Submit\"> 
                   </fieldset>
                   </form>"); 
        }
        //Displays Edit Form
        if($Delete == 0 && $Edit == 1 && $EditDate != null)
        {
            echo ("<form method=\"post\"action='fixtures.php'> 
                   <fieldset>
                   <legend>Edit Fixture: $EditIndex</legend>
                   Date: <input type=\"text\" name=\"EditDate\"value=\"$EditDate\" ></br></br>
                   Time: <input type=\"text\" name=\"EditTime\"value=\"$EditTime\" ></br> </br>");


            echo ("Select Home Team ");
            echo ("<select name=\"SelectHome\">");
            foreach($TeamsArray as $element)
            {
              if ($element == $EditHome) {echo ("<option selected=\"selected\" value=\"$element\">Team $element</option>");}
              else {echo ("<option value=\"$element\">Team $element</option>");}
            }
            echo ("</select> </br></br>");

            echo ("Select Away Team ");
            echo ("<select name=\"SelectAway\">");
            foreach($TeamsArray as $element)
            {
              if ($element == $EditAway) {echo ("<option selected=\"selected\" value=\"$element\">Team $element</option>");}
              else {echo ("<option value=\"$element\">Team $element</option>");}              
            }
            echo ("</select> </br></br>");

            echo ("Select Comp ");
            echo ("<select name= \"SelectComp\">");
            foreach($CompArray as $element)
            {
              if($element == $EditComp) {echo ("<option selected=\"selected\" value=\"$element\">Comp $element</option>");}
              else {echo ("<option value=\"$element\">Comp $element</option>");}
            }
            echo ("</select>");

            echo("<input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\">
                  <input type=\"hidden\" name=\"Add\"value=0>
                  <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
                  <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
                  <input type=\"submit\" value=\"Submit\"> 
                  </fieldset>
                  </form>");         
        }
        //Displays Delete Form
        if($Delete == 1 && $EditDate != null)
        {
          echo ("<form method=\"post\" action=\"fixtures.php\"> 
                 Are you sure you want to Delete Fixture $EditIndex? </br> 
                 <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\">
                 <input type=\"hidden\" name=\"Add\"value=0>
                 <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
                 <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
                 <input type=\"submit\" value=\"Submit\"> 
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
            $id = (int)$_POST['EditIndexVal'];
            $Add = (int)$_POST['Add'];

            if (!$Delete)
            {
              $fixture_date = $_POST['EditDate'];
              $fixture_time = $_POST['EditTime'];
              $HomeID = $_POST['SelectHome'];
              $AwayID = $_POST['SelectAway'];
              $CompID = $_POST['SelectComp'];
            }
            //update based on flags
            if($Delete == 0 && $Edit == 1 && validateDate($fixture_date) && validateTime($fixture_time))
            {
              $query = "UPDATE fixtures SET 
                        fixture_date = '$fixture_date', 
                        fixture_time = '$fixture_time', 
                        home_teamID = (SELECT team_id FROM teams WHERE team_name = '$HomeID'), 
                        away_teamID = (SELECT team_id FROM teams WHERE team_name = '$AwayID'), 
                        comp_ID = (SELECT comp_id FROM competitions WHERE comp_name = '$CompID') 
                        WHERE fixture_id = $id";
              mysqli_query($con, $query);
            }
            //Delete based on flags
            elseif ($Delete == 1 && $Edit == 0) 
            {
              $query = "DELETE FROM fixtures WHERE fixture_id = '$id';";
              mysqli_query($con, $query);
            }
          }  
        }

        if($Add &&(validateDate($fixture_date) && validateTime($fixture_time)))
        {
          $query = "Select MAX(fixture_id) FROM fixtures;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        
          while($count = $result->fetch_row())
          {
            $Index = $count[0]+1;
          }          

          $query = "INSERT INTO fixtures VALUES($Index,'$fixture_date','$fixture_time',
                    (SELECT team_id FROM teams WHERE team_name = '$HomeID'),
                    (SELECT team_id FROM teams WHERE team_name = '$AwayID'),
                    (SELECT comp_id FROM competitions WHERE comp_name = '$CompID'));";
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