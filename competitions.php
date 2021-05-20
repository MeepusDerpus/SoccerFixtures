<html>
  <head >
    <title>Competitions</title> 
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
      if (count($_POST) > 0) {  UpdateDb(); }  
      Display($EditPos, $EditIndex, $Delete, $Edit);

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
          $query = "Select * From competitions Order By comp_id;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        }
        //display table
        echo ("<table>");
        echo ("<tr>
                <th>Competition</th>
                <th>Actions</th>");
        while(list($comp_id, $comp_name) = $result->fetch_row())
        {
          echo ("<tr>
                 <td>$comp_name</td>
                 <td>
                 <a href=\"competitions.php?EditIndex=$comp_id&EditPos=$comp_name&Delete=0&Edit=1\">EDIT</a> 
                 <a href=\"competitions.php?EditIndex=$comp_id&EditPos=$comp_name&Delete=1&Edit=0\"> DELETE</a> 
            </td>
            </tr>");
        }  
        echo ("</table></br>");  

        //Displays Add form based on flags
        if(($Delete == 0 && $Edit == 0) || $EditPos == null)
        {
          echo ("<form method=\"post\" action=\"competitions.php\"> 
               <fieldset>
               <legend>Add Competition:</legend>
               Competition Name </br>
               <input type=\"text\" name=\"EditCompName\"value=\"$EditPos\" ><br> 
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=1>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </fieldset>
               </form>"); 
          
        }
        //Displays Edit form based on flags
        if($Delete == 0 && $Edit == 1 && $EditPos != null)
        {
        echo ("<form method=\"post\" action=\"competitions.php\"> 
               <fieldset>
               <legend>Edit Competition: $EditPos </legend> 
               <input type=\"text\" name=\"EditCompName\"value=\"$EditPos\" ><br> 
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ><br>
               <input type=\"submit\" value=\"Submit\"> 
               </legend>
               </form>"); 

        }   
        //Displays Delete form based on flags     
        if($Delete == 1 && $EditPos != null)
        {
          echo ("<form method=\"post\" action=\"competitions.php\"> 
               Are you sure you want to delete competition $EditPos? </br> 
               <input type=\"hidden\" name=\"EditCompName\"value=\"$EditPos\">
               <input type=\"hidden\" name=\"EditIndexVal\"value=\"$EditIndex\" >
               <input type=\"hidden\" name=\"Add\"value=0>
               <input type=\"hidden\" name=\"Edit\"value=\"$Edit\">
               <input type=\"hidden\" name=\"Delete\"value=\"$Delete\" ></br>
               <input type=\"submit\" value=\"Delete\"> 
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
            $competition= $_POST['EditCompName'];
            $id = (int)$_POST['EditIndexVal'];
            $Add = (int)$_POST['Add'];

            //checks Update flag
            if($Delete == 0 && $Edit == 1)
            {
              $query = "UPDATE competitions SET comp_name = '$competition' WHERE comp_id = $id";
              mysqli_query($con, $query);
            }
            //checks Delete Flag
            elseif ($Delete == 1 && $Edit == 0) 
            {
              $query = "DELETE FROM competitions WHERE comp_id = '$id'";
              mysqli_query($con, $query);
            }
          }  
        }
        //Checks Add flag
        if($Add)
        {
          $query = "Select MAX(comp_id) FROM competitions;";
          $result = $con->query($query,MYSQLI_STORE_RESULT);
        
          while($count = $result->fetch_row())
          {
            $Index = $count[0]+1;
          }          

          $query = "Insert Into competitions values($Index,'$competition');";
          echo ("$query </br>");
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