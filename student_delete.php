
<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nim"])) {
    $nim = trim($_POST["nim"]);
    $nim = mysqli_real_escape_string($connection, $nim);

    $query = "DELETE FROM student WHERE nim='$nim'";

    if (mysqli_query($connection, $query)) {
        if (mysqli_affected_rows($connection) > 0) {
            $message = "Data mahasiswa berhasil dihapus.";
        } else {
            $message = "Data mahasiswa tidak ditemukan.";
        }

        header("Location: student_view.php?message=" .
            urlencode($message));
        exit;
    } else {
        die("Gagal menghapus data: " . mysqli_error($connection));
    }
} else {
    header("Location: student_view.php");
    exit;
}

mysqli_close($connection);
?>
