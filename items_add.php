<?php
  // This script adds a new item to the added-items list.
  // The item comes with the post command as parameter 'name'.

  // Get parameter "added item" from post command and sanitise it
  // to prevent injection attack.
  $newitem = htmlentities($_POST['name'],ENT_QUOTES | ENT_IGNORE, "UTF-8");
  $newitem = trim($newitem);

  // Add the item is something remained
  if (strlen($newitem) > 0)
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

    // if item does not exist in the added-item list we add it
    $stmt = $db->prepare('SELECT * FROM "items" WHERE "item" = :item');
    $stmt->bindValue(':item',$newitem);
    $result = $stmt->execute();
    if ($res = $result->fetchArray(SQLITE3_ASSOC))
    {
//      $found = 1;
    }
    else
    {
      $stmt = $db->prepare('INSERT INTO "items" ("item", "check") VALUES (:item, :check)');
      $stmt->bindValue(':item',$newitem);
      $stmt->bindValue(':check',1);
      $stmt->execute();
    }

    // Close database (happens anyway when script terminates)
    $db->close();
  }

  // Forwarding back to index.php
  header("Location: items_index.php");
  exit;
?> 
