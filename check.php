<?php
  // This script toggles the checked state of the item given by parameter 'check'.

  // Get parameter "check" item from post command and sanitise it
  // to prevent injection attack.
  $checkitem = htmlentities($_POST['check'],ENT_QUOTES | ENT_IGNORE, "UTF-8");
  $checkitem = trim($checkitem);

  // Toggle the item's checked state if something remained from sanitising
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

    // if item exists we toggle its state
    $stmt = $db->prepare('SELECT * FROM "liste" WHERE "item" = :item');
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

      // update corresponding table row
      $stmt = $db->prepare('UPDATE "liste" SET "check" = :check WHERE "item" = :item');
      $stmt->bindValue(':item',$checkitem);
      $stmt->bindValue(':check',$checked);
      $stmt->execute();
    }

    // Close database (happens anyway when script terminates)
    $db->close();
  }

  // Forwarding back to index.php
  header("Location: index.php");
  exit;
?> 
