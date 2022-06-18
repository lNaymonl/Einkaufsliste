<?php
  // Get parameter "check item" from post command and sanitise it
  // to prevent injection attack.
  $checkitem = htmlentities($_POST['check'],ENT_QUOTES | ENT_IGNORE, "UTF-8");
  $checkitem = trim($checkitem);

  // Toggle the item's checked state is something remained from sanitising
  if (strlen($checkitem) > 0)
  {
    // Open database for reading/writing.
    $db = new SQLite3('main_db.sqlite', SQLITE3_OPEN_READWRITE);
    if(!$db)
    {
      echo "Error while openening database:";
      echo $db->lastErrorMsg();
      echo "<br/>\n";
      exit;
    }

    // if item exists we toggle it
    $stmt = $db->prepare('SELECT * FROM "items" WHERE "item" = :item');
    $stmt->bindValue(':item',$checkitem);
    $result = $stmt->execute();

    if ($res = $result->fetchArray(SQLITE3_ASSOC))
    {
      $checked = $res["check"];
      if ($checked == 0)
      {
        $checked = 1;
      }
      else
      {
        $checked = 0;
      }

      $stmt = $db->prepare('UPDATE "items" SET "check" = :check WHERE "item" = :item');
      $stmt->bindValue(':item',$checkitem);
      $stmt->bindValue(':check',$checked);
      $stmt->execute();
    }

    // Close database (happens anyway when script terminates)
    $db->close();
  }

  // Forwarding back to index.php
  header("Location: items_index.php");
  exit;
?> 
