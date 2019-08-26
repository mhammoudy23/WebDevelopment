<?php // sectiona.php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['delete']) && isset($_POST['isbn']))
{
    $isbn   = get_post($conn, 'isbn');
    $query  = "DELETE FROM classics WHERE isbn='$isbn'";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed: $query<br>" .
        $conn->error . "<br><br>";
}


echo <<<_END
  <form action="sectiona.php" method="post"><pre>
    First name: <input type="text" name="fname">
    Last name: <input type="text" name="lname">
    User Type: <select name="usercode">
               <option value="user">User</option>
               <option value="dev">Developer</option>
               </select>
    E-mail: <input type="text" name="email">
    Password: <input type="text" name="password">
           <input type="submit" value="Submit">
  </pre></form>
_END;
if (isset($_POST['fname'])   &&
    isset($_POST['lname'])    &&
    isset($_POST['usercode']) &&
    isset($_POST['created_date'])     &&
    isset($_POST['password']))
{
    $fname= get_post($conn, 'fname');
    $lname= get_post($conn, 'lname');
    $usercode= get_post($conn, 'usercode');
    $created_date= get_post($conn, 'created_date');
    $password= get_post($conn, 'password');
    $query    = "INSERT INTO user_profiles VALUES" .
        "('$fname', '$lname', '$usercode', '$created_date', '$password')";
    $result   = $conn->query($query);

    if (!$result) echo "INSERT failed: $query<br>" .
        $conn->error . "<br><br>";
}

$query  = "SELECT * FROM user_profiles ";
$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

$rows = $result->num_rows;

for ($j = 0 ; $j < $rows ; ++$j)
{
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
    First Name $row[0]
    Last Name $row[1]
    User Type $row[2]
    E-mail $row[3]
    Password $row[4]
  </pre>
  <form action="sectiona.php" method="post">
  <input type="hidden" name="delete" value="yes">
  <input type="hidden" name="isbn" value="$row[4]">
  <input type="submit" value="DELETE RECORD"></form>
_END;
}

$result->close();
$conn->close();

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}
?>
