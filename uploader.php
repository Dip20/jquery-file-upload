<?php

$allowed = array('jpg', 'jpeg', 'png', 'sql');
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($extension), $allowed)) {
        echo json_encode(["status" => "error", "msg" => "Uploaded file type now allowed"]);
        exit;
    }
    $file_name = time() .  "." . $extension;
    $fileSize = $_FILES['file']['size'];

    // Convert the size to a more human-readable format (e.g., KB or MB)
    $humanReadableSize = formatBytes($fileSize);

    if (move_uploaded_file($_FILES['file']['tmp_name'], "./" . $file_name)) {
        echo json_encode(["status" => "success", "data" => ["filename" => $file_name, "file_type" => $extension, "file_size" => $humanReadableSize, "date" => date("d-m-Y H:i:s")]]);
        exit;
    }
    echo '{"status":"error"}';
}



function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    return round($bytes, $precision) . ' ' . $units[$pow];
}
exit();
