<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    
    if (is_array($data)) {
        $conn = new mysqli("db", "root", "password", "xosid");
        if ($conn->connect_error) {
            die(json_encode(['status' => false, 'message' => 'DB error']));
        }
        
        $stmt = $conn->prepare("UPDATE widget SET urut = ? WHERE id = ?");
        foreach ($data as $index => $id) {
            $urut = $index + 1;
            $stmt->bind_param("ii", $urut, $id);
            $stmt->execute();
        }
        $stmt->close();
        $conn->close();
        
        echo json_encode(['status' => true]);
    } else {
        echo json_encode(['status' => false]);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid Method']);
}
