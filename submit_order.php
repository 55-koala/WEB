<?php
header('Content-Type: application/json; charset=utf-8');

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kirby cafe";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => '資料庫連線失敗: ' . $conn->connect_error
    ]));
}

// 設定字元編碼
$conn->set_charset("utf8mb4");

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 接收 JSON 資料
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // 驗證必要欄位
    if (!isset($data['name']) || !isset($data['items']) || !isset($data['payment_method'])) {
        echo json_encode([
            'success' => false,
            'message' => '缺少必要資料'
        ]);
        exit;
    }
    
    $name = $conn->real_escape_string(trim($data['name']));
    $items = $data['items']; // 購物車商品陣列
    $payment_method = $conn->real_escape_string($data['payment_method']);
    
    // 計算總價格並組合商品資訊
    $total_price = 0;
    $food_list = [];
    $note_list = [];
    
    foreach ($items as $item) {
        $item_total = intval($item['price']) * intval($item['quantity']);
        $total_price += $item_total;
        
        // 組合商品名稱和數量
        $food_list[] = $item['name'] . ' x' . $item['quantity'];
        
        // 組合備註資訊
        $notes = [];
        if (!empty($item['ice'])) {
            $notes[] = 'Ice: ' . $item['ice'];
        }
        if (!empty($item['sugar'])) {
            $notes[] = 'Sugar: ' . $item['sugar'];
        }
        if (!empty($item['note'])) {
            $notes[] = $item['note'];
        }
        if (!empty($notes)) {
            $note_list[] = $item['name'] . ' - ' . implode(', ', $notes);
        }
    }
    
    $food_str = implode('; ', $food_list);
    $note_str = implode(' | ', $note_list);
    
    if (empty($note_str)) {
        $note_str = 'No special notes';
    }
    
    // 插入訂單到資料庫
    $sql = "INSERT INTO order_list (name, food, total_price, note, payment_method, order_date) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode([
            'success' => false,
            'message' => '準備 SQL 語句失敗: ' . $conn->error
        ]);
        exit;
    }
    
    $stmt->bind_param("ssiss", $name, $food_str, $total_price, $note_str, $payment_method);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        
        echo json_encode([
            'success' => true,
            'message' => '訂單提交成功！',
            'order_id' => $order_id,
            'total_price' => $total_price
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '訂單提交失敗: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    
} else {
    echo json_encode([
        'success' => false,
        'message' => '無效的請求方法'
    ]);
}

$conn->close();
?>