<?php
  // This script removes all unchecked items from the added-items list.

  // Open database for reading/writing.
  $db = new SQLite3('main_db.sqlite', SQLITE3_OPEN_READWRITE);
  if(!$db)
  {
    echo "Error while openening database:";
    echo $db->lastErrorMsg();
    echo "<br/>\n";
    exit;
  }

  $stmt = $db->prepare('DELETE FROM "items" WHERE "check" = 0');
  $stmt->execute();

  // Close database (happens anyway when script terminates)
  $db->close();

  // Forwarding back to index.php
  header("Location: items_index.php");
  exit;
?> 
