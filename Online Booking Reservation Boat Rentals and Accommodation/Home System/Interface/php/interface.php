<?php
// Check if user is coming from loading screen
if (!isset($_GET['loaded']) && !isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../../Loading-Screen/loading.html");
    exit;
}

require_once('../config/connect.php');
// Hindi na muna gagamitin ang existing header
// include '../../../php/includes/header.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore the world with our travel services, featuring boat rentals and accommodation packages.">
    <title>Timbook Carles Tourism</title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/interface.css">
</head>
<body>
    <!-- Rest of the file remains unchanged -->
</body>
</html> 