<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="main" style="background-color: #1B998B;">
      <h3>Artikel-Liste</h3>

      <!-- Liste -->
      <form action="items_check.php" method="post" accept-charset="utf-8">
<?php
  // Open database for reading/writing.
  $db = new SQLite3('main_db.sqlite',SQLITE3_OPEN_READWRITE);
  if(!$db)
  {
    echo "Error while openening database:";
    echo $db->lastErrorMsg();
    echo "<br/>\n";
    exit;
  } 

  // Fetch items and write them to the form.
  $stmt = $db->prepare('SELECT * FROM "items" ORDER BY "item" COLLATE NOCASE ASC');
  $result = $stmt->execute();
  while ($res = $result->fetchArray(SQLITE3_ASSOC))
  {
    $item = $res["item"];
    $checked = $res["check"];

    if ($checked == "1")
    {
      $buttonclass = "itembuttonon itembutton";
      $checkmark = "";
    }
    else
    {
      $buttonclass = "itembuttonoff itembutton";
      $checkmark = "&checkmark;";
    }

    echo "        <div class=\"item\">\n";
    echo "          ".$checkmark;
    echo " <input class=\"".$buttonclass."\" type=\"submit\" name=\"check\" value=\"". $item."\" />\n";
    echo "        </div>\n";
  }
?>
      </form>

      <!-- delete button -->
      <div class="delete">
        <form action="items_delete.php" method="post" accept-charset="utf-8">
          <div class="itemsubmit">
            <input class="deletebutton button" type="submit" value="L&ouml;sche markierte"/>
          </div>
        </form>
      </div> <!-- delete -->

      <!-- item edits for addition -->
      <div class="add">
        <form action="items_add.php" method="post" accept-charset="utf-8">
          <input class="addfield inputfield" type="text" name="name" />
          <input class="addbutton button" type="submit" value="Hinzu"/>
        </form>
      </div> <!-- add -->

      <!-- Finish editing added-items -->
      <div class="finish">
        <form action="index.php" method="post">
          <div class="itemsubmit">
            <input class="finishbutton button" type="submit" value="Artikelliste beenden"/>
          </div>
        </form>
      </div> <!-- edit -->
    </div> <!-- main -->
  </body>
</html>
