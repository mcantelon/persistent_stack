<?php

// LIFO stack implemented with PDO-driven SQLite3, by Mike Cantelon (http://mikecantelon.com)
// Licensed under the GPL: http://www.gnu.org/copyleft/gpl.html

class PersistentStack
{
  public $link = NULL;

  function __construct($filename = "stack.db") {

    $this->link = $this->_open($filename);
  }

  function push($name, $data) {

    // Add data to stack
    $query = $this->link->prepare("INSERT INTO stack (name, data) VALUES (:name, :data)");
    $query->bindParam(':name', $name, PDO::PARAM_STR, 255);
    $query->bindParam(':data', $data, PDO::PARAM_LOB);

    return $query->execute();
  }

  function pop($name) {

    $result = $this->_last_row($name);

    if (!$result) {

      return FALSE;
    }
    else {

      // Delete last data added to stack
      $query = $this->link->prepare("DELETE FROM stack WHERE id=:id");
      $query->bindParam(':id', $result['id'], PDO::PARAM_INT);
      $query->execute();

      return $result['data'];
    }
  }

  function last($name) {

    $result = $this->_last_row($name);

    if (!$result) {

      return FALSE;
    }
    else {

      return $result['data'];
    }
  }

  function clear($name = FALSE) {

    if ($name) {

      $query = $this->link->prepare("DELETE FROM stack WHERE name=:name");
      $query->bindParam(':name', $name, PDO::PARAM_STR, 255);
      $query->execute();
    }
    else {

      $this->link->execute('DELETE FROM stack');
    }
  }

  function size($name = FALSE) {

    if ($name) {

      $query = $this->link->prepare("SELECT COUNT(1) FROM stack WHERE name=:name");
      $query->bindParam(':name', $name, PDO::PARAM_STR, 255);
      $query->execute();

      $result = $query->fetch();
    }
    else {

      $query = $this->link->query("SELECT COUNT(1) FROM stack");
      $result = $query->fetch();
    }

    return $result['COUNT(1)'];
  }

  function _open($filename) {

    try {

      $link = new PDO('sqlite:'. $filename);

    } catch (PDOException $e) {

      exit($e->getMessage() ."\n");
    }

    $link->query("CREATE TABLE stack (id INTEGER PRIMARY KEY, name VARCHAR(255), data BLOB)");

    return $link;
  }

  function _last_row($name) {

    // Get last data added to stack
    $query = $this->link->prepare("SELECT id, data FROM stack WHERE name=:name ORDER BY id DESC LIMIT 1");
    $query->bindParam(':name', $name, PDO::PARAM_STR, 255);
    $query->execute();

    return $query->fetch();
  }
}

/*

// Usage example:
$stack = new PersistentStack();
$stack->push('rick', 'goatlord');
$stack->push('rick', 1234567890);

print 'Stack size: '. $stack->size() ."\n";
print 'Retrieved: '. $stack->pop('rick') ."\n";
print 'Stack size: '. $stack->size() ."\n";

*/

?>
