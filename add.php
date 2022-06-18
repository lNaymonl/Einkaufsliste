<?php
  // This script adds a new item to the list.
  // The item comes with the post command as parameter 'name'.
  // The item also comes with an optional amount 'quantity'.
  //
  // Last change: 2021/01/10

  // Get parameter "added item" from post command and sanitise it
  // to prevent injection attack. Do same for 'quantity'.
  $newitem = htmlentities($_POST['name'],ENT_QUOTES | ENT_IGNORE, "UTF-8");
  $newitem = trim($newitem);
  $newquant = htmlentities($_POST['quantity'],ENT_QUOTES | ENT_IGNORE, "UTF-8");
  $newquant = trim($newquant);

  // Add the item if something remained
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

    // If item does not exist in the list we add it:
    //  - first, check if there is an item in the data base
    //  - second, if not found, add it to the data base.
    $stmt = $db->prepare('SELECT * FROM "liste" WHERE "item" = :item');
    $stmt->bindValue(':item',$newitem);
    $result = $stmt->execute();

    // If item already exists then we update the quantity.
    // Otherwise, we add it.
    if ($res = $result->fetchArray(SQLITE3_ASSOC))
    {
      if (strlen($newquant) > 0)
      {
        // update corresponding table row
        $stmt = $db->prepare('UPDATE "liste" SET "quantity" = :quant WHERE "item" = :item');
        $stmt->bindValue(':item',$newitem);
        $stmt->bindValue(':quant',$newquant);
        $stmt->execute();
      }
    }
    else
    {
      $stmt = $db->prepare('INSERT INTO "liste" ("item", "quantity", "check") VALUES (:item, :quant, :check)');
      $stmt->bindValue(':item',$newitem);
      $stmt->bindValue(':quant',$newquant);
      $stmt->bindValue(':check',1);
      $stmt->execute();
    }

    // If item does not exist in the 'added-items' list we add it here, too
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
  header("Location: index.php");
  exit;
?> 
