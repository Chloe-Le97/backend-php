<?php
require_once 'config.php'; 

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Get the current page from the AJAX request (default to 1 if not set)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 20; // Number of records per page

    if($page == 1){
        $limit = 100; // Set number of initial load
    }
    $offset = ($page - 1) * $limit; // Calculate the offset

    // Prepare the SQL statement with LIMIT and OFFSET
    $stmt = $pdo->prepare("SELECT * FROM alkos LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
