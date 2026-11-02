
<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

$nim = $_GET["nim"] ?? $_POST["nim"] ?? "";

if ($nim == "") {
    header("Location: student_view.php");
    exit;
}

$nim = mysqli_real_escape_string($connection, $nim);
$error_message = "";
$message = "";

// Mengambil data mahasiswa
$query = "SELECT * FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data mahasiswa tidak ditemukan.");
}

$name = $data["name"];
$birth_city = $data["birth_city"];
$birth_date = $data["birth_date"];
$faculty = $data["faculty"];
$department = $data["department"];
$gpa = $data["gpa"];

// Menyimpan perubahan
if (isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $birth_city = trim($_POST["birth_city"]);
    $birth_date = $_POST["birth_date"];
    $faculty = $_POST["faculty"];
    $department = trim($_POST["department"]);
    $gpa = $_POST["gpa"];

    if ($name == "" || $birth_city == "" ||
        $birth_date == "" || $department == "" ||
        !is_numeric($gpa) || $gpa < 0 || $gpa > 4) {
        $error_message = "Data tidak valid. Periksa kembali isian.";
    } else {
        $name = mysqli_real_escape_string($connection, $name);
        $birth_city = mysqli_real_escape_string($connection, $birth_city);
        $birth_date = mysqli_real_escape_string($connection, $birth_date);
        $faculty = mysqli_real_escape_string($connection, $faculty);
        $department = mysqli_real_escape_string($connection, $department);
        $gpa = (float) $gpa;

        $query = "UPDATE student SET
            name='$name',
            birth_city='$birth_city',
            birth_date='$birth_date',
            faculty='$faculty',
            department='$department',
            gpa=$gpa
            WHERE nim='$nim'";

        if (mysqli_query($connection, $query)) {
            header("Location: student_view.php?message=" .
                urlencode("Data mahasiswa berhasil diedit."));
            exit;
        } else {
            $error_message = "Gagal mengedit data mahasiswa.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 id="logo">Data Mahasiswa</h1>
    <hr>

    <nav>
        <ul>
            <li><a href="student_view.php">Tampil</a></li>
            <li><a href="student_add.php">Tambah</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h2>Edit Data Mahasiswa</h2>

    <?php
    if ($error_message != "") {
        echo "<div class='error'>" .
            htmlspecialchars($error_message) . "</div>";
    }
    ?>

    <form action="student_edit.php" method="post">
        <fieldset>
            <legend>Data Mahasiswa</legend>

            <p>
                <label>NIM:</label>
                <input type="text" value="<?php echo htmlspecialchars($nim); ?>"
                       disabled>
                <input type="hidden" name="nim"
                       value="<?php echo htmlspecialchars($nim); ?>">
            </p>

            <p>
                <label>Nama:</label>
                <input type="text" name="name"
                       value="<?php echo htmlspecialchars($name); ?>" required>
            </p>

            <p>
                <label>Tempat Lahir:</label>
                <input type="text" name="birth_city"
                       value="<?php echo htmlspecialchars($birth_city); ?>" required>
            </p>

            <p>
                <label>Tanggal Lahir:</label>
                <input type="date" name="birth_date"
                       value="<?php echo htmlspecialchars($birth_date); ?>" required>
            </p>

            <p>
                <label>Fakultas:</label>
                <select name="faculty">
                    <option value="FTIB"
                        <?php if ($faculty == "FTIB") echo "selected"; ?>>
                        FTIB
                    </option>
                    <option value="FTEIC"
                        <?php if ($faculty == "FTEIC") echo "selected"; ?>>
                        FTEIC
                    </option>
                </select>
            </p>

            <p>
                <label>Jurusan:</label>
                <input type="text" name="department"
                       value="<?php echo htmlspecialchars($department); ?>" required>
            </p>

            <p>
                <label>IPK:</label>
                <input type="number" name="gpa" step="0.01"
                       min="0" max="4"
                       value="<?php echo htmlspecialchars($gpa); ?>" required>
            </p>
        </fieldset>

        <p>
            <button type="submit" name="submit">Simpan Perubahan</button>
            <a href="student_view.php">Batal</a>
        </p>
    </form>
</div>
</body>
</html>

<?php mysqli_close($connection); ?>
