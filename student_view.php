<?php
  session_start();
  if (!isset($_SESSION["username"])) {
     header("Location: login.php");
  }

  include("connection.php");
  
  if (isset($_GET["message"])) {
      $message = $_GET["message"];
  }
     
  $query = "SELECT * FROM student ORDER BY nim ASC";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Mahasiswa</title>
  <link href="assets/style.css" rel="stylesheet" >
</head>
<body>
  <div class="container">
    <div id="header">
      <h1 id="logo">Data Mahasiswa</h1>
    </div>
    <hr>
    <nav>
      <ul>
        <li><a href="student_view.php">Tampil</a></li>
        <li><a href="student_add.php">Tambah</a>
        <li><a href="logout.php">Logout</a>
      </ul>
    </nav>
    <?php
      if (isset($message)) {
          echo "<div class='pesan'>$message</div>";
      }
    ?>
    
<table border="1">
    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Fakultas</th>
        <th>Jurusan</th>
        <th>IPK</th>
        <th>Aksi</th>
    </tr>

    <?php
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($connection));
    }

    while ($data = mysqli_fetch_assoc($result)) {
        $birth_date = strtotime($data["birth_date"]);
        $formatted_date = date("d-m-Y", $birth_date);

        echo "<tr>";
        echo "<td>" . htmlspecialchars($data["nim"]) . "</td>";
        echo "<td>" . htmlspecialchars($data["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($data["birth_city"]) . "</td>";
        echo "<td>$formatted_date</td>";
        echo "<td>" . htmlspecialchars($data["faculty"]) . "</td>";
        echo "<td>" . htmlspecialchars($data["department"]) . "</td>";
        echo "<td>" . htmlspecialchars($data["gpa"]) . "</td>";

        echo "<td>
            <a href='student_edit.php?nim=" .
            urlencode($data["nim"]) . "'>Edit</a> |

            <form action='student_delete.php' method='post'
                  style='display:inline;'
                  onsubmit='return confirm(\"Yakin ingin menghapus data ini?\")'>
                <input type='hidden' name='nim' value='" .
                htmlspecialchars($data["nim"], ENT_QUOTES) . "'>
                <button type='submit'>Hapus</button>
            </form>
        </td>";

        echo "</tr>";
    }

    mysqli_free_result($result);
    mysqli_close($connection);
    ?>
</table>

  </div>
</body>
</html>