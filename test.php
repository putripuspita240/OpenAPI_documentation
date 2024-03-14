<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "api_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET method logic
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM items WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo json_encode(array('message' => 'Item not found'));
        }
    } else {
        $sql = "SELECT * FROM items";
        $result = $conn->query($sql);
        $items = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        echo json_encode($items);
    }
}

// POST method logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = $data['price'] ?? '';

    $sql = "INSERT INTO items (name, description, price) VALUES ('$name', '$description', $price)";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Item created successfully'));
    } else {
        echo json_encode(array('message' => 'Error: ' . $sql . '<br>' . $conn->error));
    }
}

// PUT method logic
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = $data['price'] ?? '';

    $sql = "UPDATE items SET name='$name', description='$description', price=$price WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Item updated successfully'));
    } else {
        echo json_encode(array('message' => 'Error updating item: ' . $conn->error));
    }
}

// DELETE method logic
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? '';

    $sql = "DELETE FROM items WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Item deleted successfully'));
    } else {
        echo json_encode(array('message' => 'Error deleting item: ' . $conn->error));
    }
}

// Close database connection
$conn->close();
?>
