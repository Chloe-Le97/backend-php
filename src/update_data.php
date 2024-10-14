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

			// Fetch the updated row
			$stmt = $pdo->prepare("SELECT * FROM alkos WHERE number = :number");
			$stmt->execute([':number' => $number]);
			$updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

			// Return the updated row data
			echo json_encode($updatedRow);
        } elseif ($action === 'clear') {
            $stmt = $pdo->prepare("UPDATE alkos SET orderamount = 0 WHERE number = :number");
            $stmt->execute([':number' => $number]);
			
			// Fetch the updated row
			$stmt = $pdo->prepare("SELECT * FROM alkos WHERE number = :number");
			$stmt->execute([':number' => $number]);
			$updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

			// Return the updated row data
			echo json_encode($updatedRow);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}