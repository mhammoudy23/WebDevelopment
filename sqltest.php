<?php // sqltest.php

require_once 'login.php';
echo'<link href="table.css" rel="stylesheet" type="text/css">';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['delete']))
{
    foreach($_POST['colors'] as $color) {

        echo $color;
    }

    $isbn   = get_post($conn, 'isbn');
    $query  = "DELETE FROM classics WHERE isbn='$isbn'";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed: $query<br>" .
        $conn->error . "<br><br>";
}

if (isset($_POST['author'])   &&
    isset($_POST['title'])    &&
    isset($_POST['category']) &&
    isset($_POST['year'])     &&
    isset($_POST['isbn']))
{
    $author   = get_post($conn, 'author');
    $title    = get_post($conn, 'title');
    $category = get_post($conn, 'category');
    $year     = get_post($conn, 'year');
    $isbn     = get_post($conn, 'isbn');
    $query    = "('$author', '$title', '$category', '$year', '$isbn')" .
        "INSERT INTO classics VALUES";
    $result   = $conn->query($query);

    if (!$result) echo "INSERT failed: $query<br>" .
        $conn->error . "<br><br>";
}

echo <<<_END
  <form action="sqltest.php" method="post"><pre>
    Author <input type="text" name="author">
     Title <input type="text" name="title">
  Category <input type="text" name="category">
      Year <input type="text" name="year">
      ISBN <input type="text" name="isbn">
           <input type="submit" value="ADD RECORD">
  </pre></form>
_END;

$query  = "SELECT * FROM classics";
$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

$rows = $result->num_rows;
echo '<form action="sqltest.php" method="post">';
echo '<table border="1">';
echo <<<_END
<tr>
    <th>Author</th>
    <th>Title</th>
    <th>Category</th>
    <th>Year</th>
    <th>Isbn</th>
    </tr>

_END;


for ($j = 0 ; $j < $rows ; ++$j)
{
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo <<<_END

    <td> $row[0]</td>
     <td> $row[1]</td>
  <td> $row[2]</td>
      <td> $row[3]</td>
      <td> $row[4]</td>
<td> <input type= "checkbox" name= "colors[]" value="$row[4]"></td>
  </tr>
_END;
}
echo '</table> <input type="submit" name="delete" value="DELETE RECORD"></form>';

$result->close();
$conn->close();

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}
?>
