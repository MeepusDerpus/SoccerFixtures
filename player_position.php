<html>
	<head >
		<title>Player Positions</title>	
	</head>
	<body>
		<?php

      $db = include('includes\config.php');
      $EditPos = "";
      $EditIndex = null;

      $Delete = 0;   
      $Edit = 0;  

      Menu();
      Initialize();
      if (count($_POST) > 0) {  UodateDb(); }
      Display($EditPos, $EditIndex, $Delete,  $Edit);

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
           global $EditPos, $EditIndex, $Delete, $Edit;
           $EditPos= $_GET['EditPos'];
           $EditIndex = $_GET['EditIndex'];
           $Delete = $_GET['Delete'];
           $Edit = $_GET['Edit'];
        }
      }

      function Display($EditPos, $EditIndex, $Delete, $Edit)
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
          $query = "Select * From playerposition Order By position_id;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        //display table
        echo ("<table>");
        echo ("<tr>
                <th>Player Position</th>
                <th>Actions</th>");
        while(list($position_id, $position_descr) = $result->fetch_row())
        {
          echo ("<tr>
                 <td>$position_descr</td>
                 <td>
                   <a href=\"player_position.php?EditIndex=$position_id&EditPos=$position_descr&Delete=0&Edit=1\">EDIT</a> 
                   <a href=\"player_position.php?EditIndex=$position_id&EditPos=$position_descr&Delete=1&Edit=0\"> DELETE</a> 
                 </td>
                 </tr>");
        }  
        echo ("</table></br>");  

        //displays Add form
        if(($Delete == 0 && $Edit == 0) || $EditPos == null)
        {
        echo ("<form method=\"post\" action=\"player_position.php\"> 
               <fieldset>
               <legend>Add Player Position:</legend>                 
               <input type=\"text\" name=\"EditPosName\"value=\"$EditPos\" ><br> 
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=1>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
        }
        //displays edit form
        if($Delete == 0 && $Edit ==1 && $EditPos != null)
        {
        echo ("<form method=\"post\" action=\"player_position.php\"> 
               <fieldset>
               <legend>Edit Player Position: $EditPos</legend>
               <input type=\"text\" name=\"EditPosName\"value=\"$EditPos\" ><br> 
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
        }
        //displays delete form
        if($Delete == 1 && $EditPos != null)
        {
        echo ("<form method=\"post\" action=\"player_position.php\"> 
               Are you sure you want to Delete player position $EditPos?  </br></br>
               <input type=\"hidden\" name=\"EditPosName\"value=\"$EditPos\" >
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" >
               <input type=\"submit\" value=\"Delete\"> 
               </form>"); 
        }        
          mysqli_close($con); 
        }//function


      function UodateDb()
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
            $position= $_POST['EditPosName'];
            $id = (int)$_POST['EditIndexVal'];
            $Add = (int)$_POST['Add'];

            if($Delete == 0 && $Edit == 1)
            {
              $query = "UPDATE playerposition SET position_descr = '$position' WHERE position_id = $id";
              mysqli_query($con, $query);
            }
            elseif ($Delete == 1 && $Edit == 0) 
            {
              $query = "DELETE FROM playerposition WHERE position_descr = '$position'";
              mysqli_query($con, $query);
            }
          }  
        }
        $Delete = (int)$_POST['Delete'];

        if($Add)        
        {

          $query = "Select * From playerposition Order By position_id;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
          $PositionArray = ["","","","","","","","","","","","","",""];

          while(list($position_id, $position_descr) = $result->fetch_row())
          {
              switch($position_id){
                case 1:
                  $PositionArray[1] = $position_descr;
                  break;
                case 2:
                  $PositionArray[2] = $position_descr;
                  break;
                case 3:
                  $PositionArray[3] = $position_descr;
                  break;
                case 4:
                  $PositionArray[4] = $position_descr;
                  break;
                case 5:
                  $PositionArray[5] = $position_descr;
                  break;
                case 6:
                  $PositionArray[6] = $position_descr;
                  break;
                case 7:
                  $PositionArray[7] = $position_descr;
                  break;
                case 8:
                  $PositionArray[8] = $position_descr;
                  break;
                case 9:
                  $PositionArray[9] = $position_descr;
                  break;
                case 10:
                  $PositionArray[10] = $position_descr;
                  break;
                case 11:
                  $PositionArray[11] = $position_descr;
                  break;
                case 12:
                  $PositionArray[12] = $position_descr;
                  break;
                case 13:
                  $PositionArray[13] = $position_descr;
                  break;
                case 14:
                  $PositionArray[14] = $position_descr;
                  break;                                                                          
              }
          }
          //Occasional undefined offset 14 error 
          $FreeIndex = -1;
          for($i = 1; $i < 15; $i++)
          {
            if ($PositionArray[$i] == "")
            {
              $FreeIndex = $i;
              break;
            }
          }
          //inserting into DB
          $query = "Select COUNT(position_id) FROM playerposition;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        
          while($count = $result->fetch_row())
          {
            $NumPositions = $count[0];
          }
          if ($NumPositions <= 14 && $FreeIndex > 0)
          {
            $query = "Insert Into playerposition values($FreeIndex,'$position')";
            echo ("$query </br>");
            mysqli_query($con, $query);
          }
          else {echo ("There are already 14 Positions, Delete a position or update an existing position.");}
        }
        mysqli_close($con); 
      }//function
		?>
	</body>
<footer>
  <a href="Index.php">Back to Index</a>
</footer>  
</html>