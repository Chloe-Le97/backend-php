<?php 
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $number = $_POST['number'];
    $action = $_POST['action'];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        if ($action === 'add') {
            $stmt = $pdo->prepare("UPDATE alkos SET orderamount = orderamount + 1 WHERE number = :number");
            $stmt->execute([':number' => $number]);
            echo "Order amount is updated";
        } elseif ($action === 'clear') {
            $stmt = $pdo->prepare("UPDATE alkos SET orderamount = 0 WHERE number = :number");
            $stmt->execute([':number' => $number]);
            echo "Order amount is cleared";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}