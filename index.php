<?php
session_start(); 
// ================== MySQL 連線 ==================
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "kirby cafe";   // ← 跟 phpMyAdmin 的資料庫名稱一模一樣

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed");
}

// ------------------ Logout 處理（新增） ------------------
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// ------------------ Login 處理（資料庫驗證） ------------------
$login_message = "";

if (isset($_POST["login"])) {

    $acc = trim($_POST["acc"] ?? "");
    $pwd = trim($_POST["pwd"] ?? "");

    if ($acc === "" || $pwd === "") {
        $login_message = "Please enter both account and password ❌";
    } else {

        $stmt = $conn->prepare(
            "SELECT password_hash FROM users WHERE username = ?"
        );
        $stmt->bind_param("s", $acc);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $row = $result->fetch_assoc();

            // 明碼密碼比對（依你目前需求）
            if ($pwd === $row["password_hash"]) {

                $_SESSION["logged_in"] = true;
                $_SESSION["user"] = $acc;

                $login_message = "Login successful! Welcome, $acc 😊";

            } else {
                $login_message = "Wrong password ❌";
            }

        } else {
            $login_message = "User not found ❌";
        }

        $stmt->close();
    }
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Kirby Café</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- W3CSS & Fonts -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- 你原本的外部檔 -->
  <link rel="stylesheet" href="newstyle.css">
  <script src="myscript.js" defer></script>

  <style>
    /* Lightbox 背景 */
    .lightbox {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* 大圖 */
    .lightbox img {
      max-width: 90%;
      max-height: 85%;
      border-radius: 12px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.4);
      animation: popIn 0.25s ease-out;
    }

    /* 關閉按鈕 */
    .lightbox .close {
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 40px;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }

    /* 漂亮彈出動畫 */
    @keyframes popIn {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    body,h1,h2,h3,h4,h5,h6,.w3-wide {
      font-family: 'Baloo 2', cursive;
    }
    body {
      background: #FFF0F5;
    }
    /* 讓購物車往左移，不影響搜尋 icon */
    .cart-wrapper {
      display: inline-block;
      margin-right: 20px;  
    }


    /* 平板尺寸：三張卡片一排 */
@media (max-width: 1024px) and (min-width: 601px) {
  .w3-main {
    margin-left: 250px;
  }

  .w3-row-padding {
    display: flex !important;
    flex-wrap: wrap !important;
    margin: 0 -8px !important;
  }

  .w3-row-padding > .w3-col {
    float: none !important;
    width: 25% !important;
    box-sizing: border-box;
    padding: 12px 8px !important;
    max-width: 33.333% !important;
    display: block !important;
  }

  .w3-row-padding .w3-container {
    height: 100%;
    margin-bottom: 0 !important;
  }

  .w3-row-padding .w3-container img {
    height: 200px;
  }

  .menu-card,
  .menu-card-inner {
    height: 200px;
  }
}
/* 小螢幕：兩張卡片一排 */
@media (max-width: 600px) {

/* 內容貼齊左邊，sidebar 收起來時不留空白 */
.w3-main {
  margin-left: 0 !important;
}

/* 一整排裝所有卡片，用 flex 排兩欄 */
.w3-row-padding {
  display: flex !important;
  flex-wrap: wrap !important;
  margin: 0 -4px !important;   /* 左右小小空隙 */
}

/* 每一個 .w3-col 變成 50% 寬 → 一排兩張 */
.w3-row-padding > .w3-col {
  float: none !important;
  width: 50% !important;
  box-sizing: border-box;
  padding: 8px 4px !important; /* 卡片之間距離 */
  max-width: 50% !important;
  display: block !important;
}

/* 卡片自己撐滿欄位高度 */
.w3-row-padding .w3-container {
  height: 100%;
  margin-bottom: 0 !important;
}
}

    .w3-sidebar {
      background-color: #FFDEE9;
    }
    .w3-sidebar a {
      font-family: 'Baloo 2', cursive;
      color: #FF6F61;
    }
    .w3-sidebar a:hover {
      color: #FFB347;
    }
    .w3-main header p {
      color: #FF69B4;
      font-weight: bold;
    }
    .w3-row-padding .w3-container img {
      border-radius: 15px;
      border: 3px solid #FFD700;
      width: 100%;
      height: 230px;
      object-fit: cover;
      display: block;
      margin-bottom: 10px;
    }
  
#maincourse .w3-col,
#dessert .w3-col,
#beverage .w3-col {
  display: flex;
}
    .w3-row-padding .w3-container {
      text-align: center;
      margin-bottom: 24px;
      background-color: #FFEAF2;
      border-radius: 18px;
      padding: 16px 10px 18px;

      /* 卡片撐滿整欄高度 */
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    /* 統一文字區高度，讓 Add to Cart 按鈕對齊 */
    .w3-row-padding .w3-container p {
      color: #FF4500;
      font-weight: bold;
      font-size: 16px;
      margin: 8px 0 10px;
      min-height: 3.8em;
    }
    

    /* 讓 Add to Cart 被推到卡片底部 */
    .w3-row-padding .w3-container .add-btn {
      margin-top: auto;
    }

    .subscribe-section {
      background-color: #FFE4B5;
      border-radius: 15px;
      padding: 32px;
      text-align: center;
      color: #FF4500;
      margin-top: 24px;
    }
    .subscribe-section input {
      width: 80%;
      padding: 12px;
      border-radius: 10px;
      border: 2px solid #FFB347;
      font-family: 'Baloo 2', cursive;
    }

    footer {
      background-color: #FFDEAD;
      border-top: 4px dashed #FF69B4;
      color: #8B0000;
    }
    footer a {
      color: #FF4500;
      text-decoration: none;
    }
    footer a:hover {
      color: #FF69B4;
    }
    footer h4 {
      font-weight: bold;
      margin-top: 16px;
      margin-bottom: 12px;
    }
    footer p {
      margin-bottom: 8px;
    }
    footer .w3-input,
    footer .w3-button {
      border-radius: 0;
    }
    footer .w3-large {
      margin-right: 5px;
      cursor: pointer;
    }

    button {
      border:none;
      border-radius:12px;
      padding:8px 16px;
      font-family:'Baloo 2', cursive;
      font-weight:bold;
      cursor:pointer;
      transition:0.3s;
    }
    button:hover {
      transform:scale(1.05);
    }
    button.add-btn {
      background: linear-gradient(45deg, #FF9AA2, #a9dae7);
      color:white;
    }
    button.cancel-btn,
    button.confirm-btn {
      background: linear-gradient(45deg, #FF6B6B, #a8d5f2);
      color:white;
    }

    #noteModal, #cartModal, #checkoutModal {
      box-sizing:border-box;
    }
    #noteModal {
  border:3px solid #bbe8f9;
  background:#fdfdfd;
  padding:30px;
  border-radius:20px;
  display:none;
  position:fixed;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%);
  z-index:1200;  /* 調高，讓 noteModal 蓋在 cartModal 前面 */
}

    #noteModal textarea {
      border-radius:12px;
      border:2px solid #FFB347;
      padding:10px;
      width:100%;
    }

    #cartModal {
      border:3px solid #FFB347;
      background:#FFF0F5;
      border-radius:20px;
      padding:15px;
      display:none;
      position:fixed;
      top:50%;
      left:50%;
      transform:translate(-50%, -50%);
      width:90%;
      max-width:500px;
      z-index:1000;
    }
    /* Cart Modal 底部按鈕排版 */
    .cart-actions {
      margin-top: 10px;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }

    .cart-actions button {
      min-width: 120px;
    }

    /* Checkout 的按鈕也一起微調 */
    .checkout-buttons {
      display: flex;
      gap: 10px;
      padding: 15px 20px;
      background: #FFF0F5;
      border-radius: 0 0 17px 17px;
      position: sticky;
      bottom: 0;
    }
    /* 手機版購物車控制按鈕調整 */
@media (max-width: 480px) {
  #cartList div.cart-item {
    flex-wrap: wrap;
    padding: 10px 8px;
  }

  #cartList .cart-item-info {
    flex: 1 1 100%;
    margin-left: 0;
    margin-top: 8px;
    font-size: 13px;
  }

  #cartList .cart-item-controls {
    flex: 1 1 100%;
    justify-content: space-between;
    margin-left: 0;
    margin-top: 8px;
    gap: 4px;
  }

  #cartList .qty-btn {
    width: 26px;
    height: 26px;
    font-size: 16px;
  }

  #cartList .qty-display {
    min-width: 20px;
    font-size: 14px;
  }

  #cartList .remove-btn {
    margin-left: auto;
    font-size: 13px;
  }

  #cartList .edit-note-btn {
    font-size: 13px;
    padding: 4px 6px;
  }

  #cartList img {
    width: 50px;
    height: 50px;
  }
}
    .checkout-buttons button {
      flex: 1;
      padding: 12px;
      font-size: 15px;
      border-radius: 10px;
    }

    /* ==================== Checkout Modal - 調整高度不遮住進度條 ==================== */

#checkoutModal {
  border: 3px solid #FFB347;
  background: #FFF7FB;
  border-radius: 24px;
  padding: 0;
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90%;
  max-width: 650px;
  max-height: 75vh; /* 從 90vh 改為 75vh */
  overflow: hidden;
  z-index: 1100;
  box-shadow: 0 20px 60px rgba(255, 179, 71, 0.5);
  animation: checkoutModalFadeIn 0.35s ease-out;
}

@keyframes checkoutModalFadeIn {
  from {
    opacity: 0;
    transform: translate(-50%, -48%) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}

/* Checkout Header */
.checkout-header {
  background: linear-gradient(135deg, #FF9AA2, #FFB347);
  padding: 18px 24px; /* 從 20px 改為 18px */
  border-radius: 21px 21px 0 0;
  color: white;
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 4px 12px rgba(255, 154, 162, 0.3);
}

.checkout-header h3 {
  margin: 0;
  font-size: 26px; /* 從 28px 改為 26px */
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

/* Checkout Content */
.checkout-content {
  padding: 20px; /* 從 24px 改為 20px */
  max-height: calc(75vh - 160px); /* 調整計算高度 */
  overflow-y: auto;
  overflow-x: hidden;
}

/* 自訂滾動條 */
.checkout-content::-webkit-scrollbar {
  width: 8px;
}

.checkout-content::-webkit-scrollbar-track {
  background: #FFE4E1;
  border-radius: 10px;
}

.checkout-content::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #FF9AA2, #FFB347);
  border-radius: 10px;
}

.checkout-content::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #FF7B84, #FFA500);
}

/* Checkout Section */
.checkout-section {
  margin-bottom: 20px; /* 從 24px 改為 20px */
  padding-bottom: 16px; /* 從 20px 改為 16px */
  border-bottom: 2px dashed #FFD4EA;
  animation: slideIn 0.4s ease-out backwards;
}

.checkout-section:nth-child(1) { animation-delay: 0.1s; }
.checkout-section:nth-child(2) { animation-delay: 0.2s; }
.checkout-section:nth-child(3) { animation-delay: 0.3s; }

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.checkout-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
}

.checkout-section h4 {
  color: #FF4500;
  margin: 0 0 14px 0; /* 從 16px 改為 14px */
  font-size: 17px; /* 從 18px 改為 17px */
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 700;
}

/* Checkout Items List */
#checkoutItemsList {
  max-height: 220px; /* 從 280px 改為 220px */
  overflow-y: auto;
  padding-right: 4px;
}

#checkoutItemsList::-webkit-scrollbar {
  width: 6px;
}

#checkoutItemsList::-webkit-scrollbar-track {
  background: #FFF0F5;
  border-radius: 10px;
}

#checkoutItemsList::-webkit-scrollbar-thumb {
  background: #FFB347;
  border-radius: 10px;
}

/* Checkout Item */
.checkout-item {
  background: linear-gradient(135deg, #FFFAF0, #FFE4B5);
  padding: 12px 14px; /* 從 14px 16px 改為 12px 14px */
  border-radius: 14px;
  margin-bottom: 10px; /* 從 12px 改為 10px */
  border: 2px solid #FFD4A3;
  display: flex;
  justify-content: space-between;
  gap: 12px;
  box-shadow: 0 3px 8px rgba(255, 212, 163, 0.3);
  transition: all 0.3s ease;
}

.checkout-item:hover {
  box-shadow: 0 5px 12px rgba(255, 212, 163, 0.5);
  transform: translateX(2px);
}

.checkout-item:last-child {
  margin-bottom: 0;
}

.checkout-item-main {
  flex: 1;
  min-width: 0;
}

.checkout-item-name {
  font-weight: bold;
  color: #8B4513;
  font-size: 15px; /* 從 16px 改為 15px */
  margin-bottom: 5px; /* 從 6px 改為 5px */
  line-height: 1.3;
}

.checkout-item-details {
  font-size: 13px; /* 從 14px 改為 13px */
  color: #666;
  margin-bottom: 3px; /* 從 4px 改為 3px */
  line-height: 1.4;
}

.item-options {
  font-size: 12px; /* 從 13px 改為 12px */
  color: #888;
  margin-top: 3px; /* 從 4px 改為 3px */
  padding: 3px 6px; /* 從 4px 8px 改為 3px 6px */
  background: rgba(255, 255, 255, 0.5);
  border-radius: 6px;
  display: inline-block;
}

.item-note {
  font-size: 12px; /* 從 13px 改為 12px */
  color: #888;
  margin-top: 3px; /* 從 4px 改為 3px */
  padding: 3px 6px; /* 從 4px 8px 改為 3px 6px */
  background: rgba(255, 228, 181, 0.4);
  border-radius: 6px;
  font-style: italic;
}

.checkout-item-price {
  text-align: right;
  font-weight: bold;
  color: #FF4500;
  font-size: 16px; /* 從 17px 改為 16px */
  white-space: nowrap;
  align-self: center;
}

/* Price Rows */
.price-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0; /* 從 10px 改為 8px */
  font-size: 15px; /* 從 16px 改為 15px */
  color: #333;
}

.total-row {
  border-top: 3px solid #FFB347;
  margin-top: 10px; /* 從 12px 改為 10px */
  padding-top: 14px; /* 從 16px 改為 14px */
  font-size: 20px; /* 從 22px 改為 20px */
  font-weight: bold;
  color: #FF4500;
  background: linear-gradient(135deg, #FFF9F0, #FFE4E1);
  padding: 14px; /* 從 16px 改為 14px */
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(255, 69, 0, 0.2);
}

/* Payment Methods */
.payment-methods {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px; /* 從 14px 改為 12px */
}

.payment-option {
  cursor: pointer;
}

.payment-option input[type="radio"] {
  display: none;
}

.payment-card {
  border: 3px solid #FFD4A3;
  border-radius: 16px;
  padding: 16px; /* 從 20px 改為 16px */
  text-align: center;
  background: white;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px; /* 從 10px 改為 8px */
  height: 100%;
  box-shadow: 0 3px 10px rgba(255, 212, 163, 0.2);
}

.payment-card:hover {
  border-color: #FFB347;
  box-shadow: 0 5px 15px rgba(255, 179, 71, 0.3);
  transform: translateY(-2px);
}

.payment-card i {
  font-size: 30px; /* 從 32px 改為 30px */
  color: #FFB347;
  transition: all 0.3s ease;
}

.payment-card span {
  font-size: 14px; /* 從 15px 改為 14px */
  font-weight: bold;
  color: #8B4513;
}

.payment-option input[type="radio"]:checked + .payment-card {
  border-color: #FF9AA2;
  background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
  box-shadow: 0 6px 20px rgba(255, 154, 162, 0.5);
  transform: scale(1.05);
}

.payment-option input[type="radio"]:checked + .payment-card i {
  color: #FF9AA2;
  transform: scale(1.15);
}

/* ==================== Checkout Modal - 完全不遮住進度條 ==================== */

#checkoutModal {
  border: 3px solid #FFB347;
  background: #FFF7FB;
  border-radius: 24px;
  padding: 0;
  display: none;
  position: fixed;
  top: 45%; /* 從 50% 改為 45%，往上移 */
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90%;
  max-width: 650px;
  max-height: 65vh; /* 從 75vh 改為 65vh */
  overflow: hidden;
  z-index: 1100;
  box-shadow: 0 20px 60px rgba(255, 179, 71, 0.5);
  animation: checkoutModalFadeIn 0.35s ease-out;
}

@keyframes checkoutModalFadeIn {
  from {
    opacity: 0;
    transform: translate(-50%, -48%) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}

/* Checkout Header */
.checkout-header {
  background: linear-gradient(135deg, #FF9AA2, #FFB347);
  padding: 16px 20px; /* 從 18px 改為 16px */
  border-radius: 21px 21px 0 0;
  color: white;
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 4px 12px rgba(255, 154, 162, 0.3);
}

.checkout-header h3 {
  margin: 0;
  font-size: 24px; /* 從 26px 改為 24px */
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Checkout Content */
.checkout-content {
  padding: 18px; /* 從 20px 改為 18px */
  max-height: calc(65vh - 145px); /* 調整計算高度 */
  overflow-y: auto;
  overflow-x: hidden;
}

/* 自訂滾動條 */
.checkout-content::-webkit-scrollbar {
  width: 6px; /* 從 8px 改為 6px */
}

.checkout-content::-webkit-scrollbar-track {
  background: #FFE4E1;
  border-radius: 10px;
}

.checkout-content::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #FF9AA2, #FFB347);
  border-radius: 10px;
}

.checkout-content::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #FF7B84, #FFA500);
}

/* Checkout Section */
.checkout-section {
  margin-bottom: 16px; /* 從 20px 改為 16px */
  padding-bottom: 14px; /* 從 16px 改為 14px */
  border-bottom: 2px dashed #FFD4EA;
  animation: slideIn 0.4s ease-out backwards;
  overflow: visible;
}

.checkout-section:nth-child(1) { animation-delay: 0.1s; }
.checkout-section:nth-child(2) { animation-delay: 0.2s; }
.checkout-section:nth-child(3) { animation-delay: 0.3s; }

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.checkout-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
}

.checkout-section h4 {
  color: #FF4500;
  margin: 0 0 12px 0; /* 從 14px 改為 12px */
  font-size: 16px; /* 從 17px 改為 16px */
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
}

/* Payment Section 特別處理 */
.checkout-section:has(.payment-methods) {
  padding-bottom: 16px;
  min-height: 140px; /* 從 150px 改為 140px */
}

/* Checkout Items List */
#checkoutItemsList {
  max-height: 180px; /* 從 220px 改為 180px */
  overflow-y: auto;
  padding-right: 4px;
}

#checkoutItemsList::-webkit-scrollbar {
  width: 5px; /* 從 6px 改為 5px */
}

#checkoutItemsList::-webkit-scrollbar-track {
  background: #FFF0F5;
  border-radius: 10px;
}

#checkoutItemsList::-webkit-scrollbar-thumb {
  background: #FFB347;
  border-radius: 10px;
}

/* Checkout Item */
.checkout-item {
  background: linear-gradient(135deg, #FFFAF0, #FFE4B5);
  padding: 10px 12px; /* 從 12px 14px 改為 10px 12px */
  border-radius: 12px; /* 從 14px 改為 12px */
  margin-bottom: 8px; /* 從 10px 改為 8px */
  border: 2px solid #FFD4A3;
  display: flex;
  justify-content: space-between;
  gap: 10px;
  box-shadow: 0 3px 8px rgba(255, 212, 163, 0.3);
  transition: all 0.3s ease;
}

.checkout-item:hover {
  box-shadow: 0 5px 12px rgba(255, 212, 163, 0.5);
  transform: translateX(2px);
}

.checkout-item:last-child {
  margin-bottom: 0;
}

.checkout-item-main {
  flex: 1;
  min-width: 0;
}

.checkout-item-name {
  font-weight: bold;
  color: #8B4513;
  font-size: 14px; /* 從 15px 改為 14px */
  margin-bottom: 4px;
  line-height: 1.3;
}

.checkout-item-details {
  font-size: 12px; /* 從 13px 改為 12px */
  color: #666;
  margin-bottom: 3px;
  line-height: 1.4;
}

.item-options {
  font-size: 11px; /* 從 12px 改為 11px */
  color: #888;
  margin-top: 2px;
  padding: 2px 5px; /* 從 3px 6px 改為 2px 5px */
  background: rgba(255, 255, 255, 0.5);
  border-radius: 5px;
  display: inline-block;
}

.item-note {
  font-size: 11px; /* 從 12px 改為 11px */
  color: #888;
  margin-top: 2px;
  padding: 2px 5px; /* 從 3px 6px 改為 2px 5px */
  background: rgba(255, 228, 181, 0.4);
  border-radius: 5px;
  font-style: italic;
}

.checkout-item-price {
  text-align: right;
  font-weight: bold;
  color: #FF4500;
  font-size: 15px; /* 從 16px 改為 15px */
  white-space: nowrap;
  align-self: center;
}

/* Price Rows */
.price-row {
  display: flex;
  justify-content: space-between;
  padding: 7px 0; /* 從 8px 改為 7px */
  font-size: 14px; /* 從 15px 改為 14px */
  color: #333;
}

.total-row {
  border-top: 3px solid #FFB347;
  margin-top: 8px; /* 從 10px 改為 8px */
  padding-top: 12px; /* 從 14px 改為 12px */
  font-size: 19px; /* 從 20px 改為 19px */
  font-weight: bold;
  color: #FF4500;
  background: linear-gradient(135deg, #FFF9F0, #FFE4E1);
  padding: 12px; /* 從 14px 改為 12px */
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(255, 69, 0, 0.2);
}

/* Payment Methods */
.payment-methods {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px; /* 從 12px 改為 10px */
  margin-top: 6px;
}

.payment-option {
  cursor: pointer;
  width: 100%;
}

.payment-option input[type="radio"] {
  display: none;
}

.payment-card {
  border: 3px solid #FFD4A3;
  border-radius: 14px; /* 從 16px 改為 14px */
  padding: 14px; /* 從 16px 改為 14px */
  text-align: center;
  background: white;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 7px; /* 從 8px 改為 7px */
  min-height: 90px; /* 從 100px 改為 90px */
  height: 100%;
  box-shadow: 0 3px 10px rgba(255, 212, 163, 0.2);
}

.payment-card:hover {
  border-color: #FFB347;
  box-shadow: 0 5px 15px rgba(255, 179, 71, 0.3);
  transform: translateY(-2px);
}

.payment-card i {
  font-size: 28px; /* 從 30px 改為 28px */
  color: #FFB347;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.payment-card span {
  font-size: 13px; /* 從 14px 改為 13px */
  font-weight: bold;
  color: #8B4513;
  white-space: nowrap;
}

.payment-option input[type="radio"]:checked + .payment-card {
  border-color: #FF9AA2;
  background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
  box-shadow: 0 6px 20px rgba(255, 154, 162, 0.5);
  transform: scale(1.05);
}

.payment-option input[type="radio"]:checked + .payment-card i {
  color: #FF9AA2;
  transform: scale(1.15);
}

/* Checkout Buttons */
/* ==================== 調整間距避免按鈕遮住內容 ==================== */

/* Total Row - 增加下方間距 */
.total-row {
  border-top: 3px solid #FFB347;
  margin-top: 8px;
  margin-bottom: 20px; /* 從無 → 20px */
  padding-top: 12px;
  font-size: 19px;
  font-weight: bold;
  color: #FF4500;
  background: linear-gradient(135deg, #FFF9F0, #FFE4E1);
  padding: 12px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(255, 69, 0, 0.2);
}

/* Payment Details Section 增加底部空間 */
.checkout-section:nth-child(2) {
  padding-bottom: 24px; /* 增加底部空間 */
}

/* Checkout Buttons */
.checkout-buttons {
  display: flex;
  flex-direction: row;
  gap: 12px;
  padding: 14px 18px;
  margin-top: 12px; /* 增加頂部間距 */
  background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
  border-radius: 0 0 21px 21px;
  position: sticky;
  bottom: 0;
  z-index: 10;
  box-shadow: 0 -4px 12px rgba(255, 154, 162, 0.2);
}

.checkout-buttons button {
  flex: 1;
  padding: 11px 16px;
  font-size: 14px;
  border-radius: 999px;
  transition: all 0.25s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  font-weight: 600;
  white-space: nowrap;
}

.checkout-buttons button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.checkout-buttons button:active {
  transform: translateY(0);
}

/* ==================== 響應式斷點（完整更新） ==================== */

/* 大螢幕優化 (>1200px) */
@media (min-width: 1200px) {
  #checkoutModal {
    max-width: 700px;
    max-height: 68vh;
    top: 45%;
  }
  
  .checkout-header h3 {
    font-size: 26px;
  }
  
  .checkout-content {
    padding: 20px;
    max-height: calc(68vh - 145px);
  }
  
  #checkoutItemsList {
    max-height: 200px;
  }
}

/* 平板橫向 (992px - 1199px) */
@media (max-width: 1199px) {
  #checkoutModal {
    width: 88%;
    max-width: 620px;
    max-height: 65vh;
    top: 45%;
  }
}

/* 平板直向 (768px - 991px) */
@media (max-width: 991px) {
  #checkoutModal {
    width: 90%;
    max-width: 560px;
    max-height: 65vh;
    top: 44%;
  }
  
  .checkout-content {
    max-height: calc(65vh - 135px);
  }
  
  #checkoutItemsList {
    max-height: 160px;
  }
}

/* 大手機 / 小平板 (576px - 767px) */
@media (max-width: 767px) {
  #checkoutModal {
    width: 92%;
    max-width: 500px;
    border-radius: 20px;
    max-height: 62vh;
    top: 43%;
  }
  
  .checkout-content {
    padding: 16px;
    max-height: calc(62vh - 130px);
  }
  
  #checkoutItemsList {
    max-height: 150px;
  }
  
  .payment-card {
    min-height: 80px;
    padding: 12px;
  }
}

/* 手機 (小於 576px) */
@media (max-width: 575px) {
  #checkoutModal {
    width: 94%;
    max-width: none;
    border-radius: 18px;
    max-height: 60vh;
    top: 42%;
  }
  
  .checkout-header {
    padding: 12px 14px;
  }
  
  .checkout-header h3 {
    font-size: 20px;
  }
  
  .checkout-content {
    padding: 14px;
    max-height: calc(60vh - 125px);
    padding-bottom: 200px;
  }
  
  #checkoutItemsList {
    max-height: 140px;
  }
  
  .checkout-section {
    margin-bottom: 14px;
    padding-bottom: 12px;
  }
  
  .checkout-item {
    flex-direction: column;
    padding: 10px;
  }
  
  /* 手機版：付款方式改為單欄 */
  .payment-methods {
    grid-template-columns: 1fr;
    gap: 10px;
  }
  
  .payment-card {
    padding: 12px;
    flex-direction: row;
    min-height: 55px;
    text-align: left;
    justify-content: flex-start;
    gap: 10px;
  }
  
  .payment-card i {
    font-size: 26px;
  }
  
  .payment-card span {
    font-size: 13px;
    flex: 1;
  }
  
  /* Checkout Buttons - 左右並排 */
.checkout-buttons {
  display: flex;
  flex-direction: row; /* 確保是橫向排列 */
  gap: 12px;
  padding: 14px 18px;
  background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
  border-radius: 0 0 21px 21px;
  position: sticky;
  bottom: 0;
  z-index: 10;
  box-shadow: 0 -4px 12px rgba(255, 154, 162, 0.2);
   margin-top: 16px; /* ✅ 新增：增加上方間距 */
}

.checkout-buttons button {
  flex: 1; /* 兩個按鈕平均分配寬度 */
  padding: 11px 16px;
  font-size: 14px;
  border-radius: 999px;
  transition: all 0.25s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  font-weight: 600;
  white-space: nowrap; /* 防止文字換行 */
}

.checkout-buttons button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.checkout-buttons button:active {
  transform: translateY(0);
}

/* ==================== 響應式調整（保持左右並排） ==================== */

/* 大螢幕優化 (>1200px) */
@media (min-width: 1200px) {
  .checkout-buttons {
    gap: 14px;
    padding: 16px 20px;
  }
  
  .checkout-buttons button {
    padding: 13px 20px;
    font-size: 15px;
  }
}

/* 平板橫向 (992px - 1199px) */
@media (max-width: 1199px) {
  .checkout-buttons {
    gap: 12px;
    padding: 14px 18px;
  }
}

/* 平板直向 (768px - 991px) */
@media (max-width: 991px) {
  .checkout-buttons {
    gap: 12px;
    padding: 14px 18px;
  }
  
  .checkout-buttons button {
    padding: 11px 16px;
    font-size: 14px;
  }
}

/* 大手機 / 小平板 (576px - 767px) */
@media (max-width: 767px) {
  .checkout-buttons {
    gap: 10px;
    padding: 12px 16px;
  }
  
  .checkout-buttons button {
    padding: 10px 14px;
    font-size: 14px;
  }
}

/* 手機 (小於 576px) - 依然保持左右並排 */
@media (max-width: 575px) {
  .checkout-buttons {
    flex-direction: row; /* 強制橫向 */
    gap: 10px;
    padding: 12px 14px;
  }
  
  .checkout-buttons button {
    flex: 1;
    padding: 10px 12px;
    font-size: 13px;
  }
}

/* 超小手機 (小於 400px) - 依然保持左右並排 */
@media (max-width: 399px) {
  .checkout-buttons {
    flex-direction: row; /* 強制橫向 */
    gap: 8px;
    padding: 12px 14px;
  }
  
  .checkout-buttons button {
    flex: 1;
    padding: 9px 10px;
    font-size: 12px;
  }
}

/* 超小手機且按鈕文字過長時的處理 */
@media (max-width: 360px) {
  .checkout-buttons button {
    font-size: 11px;
    padding: 8px 8px;
  }
}

/* 橫屏手機優化 - 保持左右並排 */
@media (max-height: 600px) and (orientation: landscape) {
  .checkout-buttons {
    flex-direction: row;
    gap: 10px;
    padding: 10px 16px;
  }
  
  .checkout-buttons button {
    padding: 8px 12px;
    font-size: 13px;
  }
}
  
  .checkout-content {
    max-height: calc(85vh - 115px);
  }
  
  #checkoutItemsList {
    max-height: 130px;
  }
  
  .checkout-header {
    padding: 10px 16px;
  }
  
  .checkout-buttons {
    padding: 10px 16px;
  }
}

/* 極小高度優化 */
@media (max-height: 500px) {
  #checkoutModal {
    max-height: 90vh;
  }
  
  .checkout-content {
    max-height: calc(90vh - 110px);
  }
  
  #checkoutItemsList {
    max-height: 100px;
  }
}

    /* Cart */
    #cartList div.cart-item {
      border:2px dashed #FFB347;
      border-radius:12px;
      padding:8px;
      margin-bottom:8px;
      background:#FFE4B5;
      display:flex;
      align-items:center;
    }
    #cartList img {
      border-radius:12px;
      border:2px solid #FFB347;
      width:60px;
      height:60px;
      object-fit:cover;
      margin-right:10px;
    }
    .cart-wrapper {
      position: relative;
      display: inline-block;
    }

    #cartCountBadge {
      position: absolute;
      top: -6px;
      right: -16px;
      background:#FF6B81;
      color:white;
      font-weight:bold;
      font-size:11px;
      padding:2px 5px;
      border-radius:50%;
      border:2px solid white;
    }

    #cartIcon,
    .w3-container .fa-search {
      cursor: pointer;
    }
    #cartIcon:hover, 
    #searchIcon:hover {
      transform: scale(1.3);
      transition: transform 0.3s ease;
    }
    #cartList .cart-item-info {
      flex: 1;
      margin-left: 10px;
      color: #222;
      font-weight: 500;
    }
    #cartList .cart-item-controls {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-left: auto;
    }
    #cartList .qty-btn {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 2px solid #FFB347;
      background: linear-gradient(135deg, #FFE4B5, #FFD700);
      color: #8B4513;
      font-size: 18px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      padding: 0;
    }
    #cartList .qty-btn:hover {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      transform: scale(1.1);
      box-shadow: 0 2px 8px rgba(255, 140, 0, 0.4);
    }
    #cartList .qty-btn:active {
      transform: scale(0.95);
    }
    #cartList .qty-display {
      min-width: 24px;
      text-align: center;
      font-weight: bold;
      color: #8B4513;
      font-size: 15px;
    }
    #cartList .remove-btn {
      align-self: center;
      margin-left: 8px;
      background: none;
      border: none;
      color: #ff4b5c;
      font-weight: bold;
      font-size: 14px;
      cursor: pointer;
      transition: transform 0.2s, color 0.2s;
      padding: 4px 8px;
    }
    #cartList .remove-btn:hover {
      color: #ff0000;
      transform: scale(1.1);
    }

    /* Greeting */
    /* Greeting 卡片：配合整體粉色系、圓角、陰影 */
#greeting {
  position: fixed;
  left: 50%;
  top: 80px;
  transform: translateX(-50%);
  padding: 32px 28px 26px;
  max-width: 480px;
  text-align: center;
  border-radius: 24px;
  border: 3px solid #FFB347;
  background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
  box-shadow: 0 14px 35px rgba(255, 154, 162, 0.5);
  animation: popgreeting 0.7s ease-out;
  z-index: 9000;
}

/* 大標：跟 Hero / Kirby 風格一樣可愛飽滿 */
#greeting1 {
  font-size: 32px;
  color: #FF4F7B;
  margin: 4px 0 6px;
  letter-spacing: 1px;
}

/* 小標：用較小字＋次要色 */
#welcome {
  font-size: 20px;
  color: #8B4513;
  margin: 0 0 18px;
}

/* Enter 按鈕只留間距，樣式用 .confirm-btn 或 .add-btn */
#enter {
  margin-top: 4px;
  margin-bottom: 4px;
  font-size: 18px;
}

    @keyframes popgreeting{
      from{opacity:0;}
      to{opacity:1;}
    }

    /* Card flip */
    .menu-card {
      position:relative;
      width:100%;
      height:230px;
      perspective:1000px;
      margin-bottom:10px;
    }
    .menu-card-inner {
      position:relative;
      width:100%;
      height:230px;
      transform-style:preserve-3d;
      transition:transform 0.6s;
    }
    .menu-card.flipped .menu-card-inner {
      transform:rotateY(180deg);
    }
    .card-front, .card-back {
      position:absolute;
      width:100%;
      height:100%;
      backface-visibility:hidden;
      border-radius:15px;
      overflow:hidden;
    }
    .card-front img {
      border-radius:15px;
      border:3px solid #FFD700;
      width:100%;
      height:230px;
      object-fit:cover;
    }
    .card-back {
      transform:rotateY(180deg);
      display:flex;
      justify-content:center;
      align-items:center;
      padding:10px;
      box-sizing:border-box;
      border:3px solid #FFD700;
      background:#ffbdde;
      cursor: pointer;
      text-align:center;
      color:#8B0000;
      font-size:15px;
    }

    /* Hover 覆蓋層：提示可以點 */
    .hover-card {
      position:absolute;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background:rgba(215, 174, 195, 0.9);
      display:flex;
      justify-content:center;
      align-items:center;
      opacity:0;
      transition:opacity 0.3s;
      border-radius:15px;
      color:white;
      font-size:20px;
      font-weight:bold;
      pointer-events: none;
    }
    .card-front:hover .hover-card {
      opacity:1;
    }

    /* 小小翻面提示貼紙 */
    .flip-hint {
      position: absolute;
      bottom: 8px;
      right: 8px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 8px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.9);
      color: #ff4f7b;
      font-size: 11px;
      font-weight: 600;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
      pointer-events: none;
      animation: flipHintPulse 1.4s ease-in-out infinite alternate;
    }

    .flip-hint i {
      font-size: 13px;
    }

    .menu-card:hover .flip-hint {
      background: #ffedf4;
      color: #ff2b6c;
    }

    @keyframes flipHintPulse {
      0% {
        transform: translateY(0);
        opacity: 0.7;
      }
      100% {
        transform: translateY(-2px);
        opacity: 1;
      }
    }

    @media (max-width: 600px) {
      .flip-hint {
        font-size: 10px;
        padding: 3px 6px;
      }
    }

    /* 翻面按鈕 */
    .flip-btn {
      margin-top: 6px;
      margin-bottom: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      border: none;
      background: linear-gradient(45deg, #FFB6C1, #FFD700);
      color: #8B0000;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .flip-btn i {
      font-size: 14px;
    }
    .flip-btn:hover {
      transform: translateY(-1px) scale(1.03);
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    /* Progress bar */
   .progress-container {
  position: fixed;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 95%;
  max-width: 900px;
  background: white;
  box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
  z-index: 500;
  border-radius: 12px 12px 0 0;
}

.progress-bar {
  display: flex;
  justify-content: space-around;
  align-items: center;
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  padding: 12px 16px;
}

.progress-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  position: relative;
  z-index: 2;
}

.rocket-icon {
  font-size: 40px;
  color: #ccc;
  transition: all 0.5s;
}

.rocket-icon.active {
  color: #FF6B81;
  transform: scale(1.3);
}

.rocket-icon.completed {
  color: #4CAF50;
}

.step-label {
  font-size: 14px;
  font-weight: bold;
  color: #666;
  margin-top: 4px;
}

.step-label.active {
  color: #FF6B81;
}

.step-label.completed {
  color: #4CAF50;
}

.progress-line {
  position: absolute;
  top: 26px;
  left: 10%;
  right: 10%;
  height: 4px;
  background: #ddd;
  z-index: 1;
}

.progress-line-fill {
  height: 100%;
  background: linear-gradient(90deg, #FF6B81, #4CAF50);
  width: 0%;
  transition: width 0.5s;
}

/* 平板尺寸 (768px - 1024px) */
@media (max-width: 1024px) {
  .progress-container {
    width: 90%;
    max-width: 800px;
  }
  
  .progress-bar {
    padding: 10px 12px;
  }
  
  .rocket-icon {
    font-size: 36px;
  }
  
  .rocket-icon.active {
    transform: scale(1.2);
  }
  
  .step-label {
    font-size: 13px;
  }
}

/* 小平板/大手機 (600px - 768px) */
@media (max-width: 768px) {
  .progress-container {
    width: 92%;
    max-width: 600px;
  }
  
  .progress-bar {
    padding: 10px 8px;
  }
  
  .rocket-icon {
    font-size: 32px;
  }
  
  .rocket-icon.active {
    transform: scale(1.15);
  }
  
  .step-label {
    font-size: 12px;
  }
  
  .progress-line {
    top: 22px;
  }
}

/* 手機尺寸 (小於 600px) */
@media (max-width: 600px) {
  .progress-container {
    width: 96%;
    border-radius: 8px 8px 0 0;
  }
  
  .progress-bar {
    padding: 8px 4px;
  }
  
  .rocket-icon {
    font-size: 28px;
  }
  
  .rocket-icon.active {
    transform: scale(1.1);
  }
  
  .step-label {
    font-size: 11px;
    margin-top: 2px;
  }
  
  .progress-line {
    top: 20px;
    left: 15%;
    right: 15%;
    height: 3px;
  }
}

/* 超小手機 (小於 400px) */
@media (max-width: 400px) {
  .progress-container {
    width: 98%;
  }
  
  .progress-bar {
    padding: 6px 2px;
  }
  
  .rocket-icon {
    font-size: 24px;
  }
  
  .rocket-icon.active {
    transform: scale(1.05);
  }
  
  .step-label {
    font-size: 10px;
  }
  
  .progress-line {
    top: 18px;
    height: 2px;
  }
}

/* 超寬螢幕 (大於 1440px) */
@media (min-width: 1440px) {
  .progress-container {
    max-width: 1000px;
  }
  
  .progress-bar {
    padding: 14px 20px;
  }
  
  .rocket-icon {
    font-size: 44px;
  }
  
  .step-label {
    font-size: 15px;
  }
}
    .step-label {
      font-size:14px;
      font-weight:bold;
      color:#666;
    }
    .step-label.active {
      color:#FF6B81;
    }
    .step-label.completed {
      color:#4CAF50;
    }
    .progress-line {
      position:absolute;
      top:26px;
      left:10%;
      right:10%;
      height:4px;
      background:#ddd;
      z-index:1;
    }
    .progress-line-fill {
      height:100%;
      background:linear-gradient(90deg,#FF6B81,#4CAF50);
      width:0%;
      transition:width 0.5s;
    }

    /* Back to Top */
    #backToTop {
      position: fixed;
      bottom: 60px;
      right: 20px;
      z-index: 99;
      background: linear-gradient(135deg, #FF9AA2, #FFB347);
      color: white;
      border: 3px solid #FFD700;
      border-radius: 50%;
      width: 55px;
      height: 55px;
      font-size: 24px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 107, 129, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;

    }
    #backToTop.show {
      opacity: 1;
      visibility: visible;
    }
    #backToTop:hover {
      transform: translateY(-5px) scale(1.1);
      box-shadow: 0 6px 20px rgba(255, 107, 129, 0.6);
      background: linear-gradient(135deg, #FFB347, #FF9AA2);
    }
    #backToTop:active {
      transform: translateY(0) scale(0.95);
    }
    @media (max-width: 600px) {
      #backToTop {
        bottom: 90px;
        right: 15px;
        width: 50px;
        height: 50px;
        font-size: 20px;
      }
    }
    /* 搜尋欄 RWD */
@media (max-width: 480px) {
   header .w3-right {  
    position: relative;
  }
  /* 搜尋包裝器 */
  div[style*="position: absolute"][style*="right: 60px"] {
    position: fixed !important;
    top: 70px !important;
    left: 50% !important;
    right: auto !important;
    transform: translateX(-50%) !important;
    width: 90% !important;
    max-width: 340px !important;
    flex-wrap: wrap !important;
    padding: 10px !important;
  }

  #keywordInput {
    width: 100% !important;
    max-width: 100% !important;
    margin-bottom: 8px !important;
  }

  #searchBtn {
  flex: 0 0 auto !important; 
  width: auto !important;
  padding: 5px 30px !important; 
}


  #closeSearch {
    flex-shrink: 0 !important;
  }

  #searchResultMsg {
    text-align: center !important;
    margin: 8px 0 0 0 !important;
  }
}

    /* Hero Title */
    #heroTitle {
      white-space: nowrap !important;
      word-break: keep-all;
      max-width: 100%;
      font-size: clamp(20px, 4vw, 40px);
    }
    @media (max-width: 1024px) {
      #heroTitle {
        font-size: clamp(20px, 4vw, 40px);
      }
    }

    /* 🎮 Game intro styles (原本的保留) */
    #gameIntro {
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 10% 0%, #ffe4f8 0, transparent 55%),
                  radial-gradient(circle at 90% 100%, #ffe5c4 0, transparent 55%),
                  radial-gradient(circle at 50% 100%, #c9f1ff 0, transparent 45%),
                  #ffbad1;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      z-index: 10000;
      font-family: 'Baloo 2', cursive;
    }

    /* 觸控控制按鈕：預設在大螢幕隱藏 */
    .gi-touch-controls {
      display: none;
      margin-top: 8px;
      justify-content: center;
      gap: 14px;
    }

    .gi-touch-btn {
      border-radius: 999px;
      padding: 10px 22px;
      font-size: 20px;
      border: none;
      cursor: pointer;
      background: linear-gradient(135deg,#ff7ba5,#ffb347);
      color: #fff;
      box-shadow: 0 6px 14px rgba(255,118,159,0.8);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .gi-touch-btn:active {
      transform: scale(0.95);
      box-shadow: 0 3px 8px rgba(255,118,159,0.5);
    }

    /* 📱 小螢幕時顯示觸控按鈕 */
    @media (max-width: 768px) {
      .gi-touch-controls {
        display: flex;
      }
    }

    /* 手機版 START 按鈕（只在小螢幕出現） */
    .gi-start-mobile {
      display: none;
      margin-top: 8px;
      align-self: center;
    }
    @media (max-width: 768px) {
      .gi-start-mobile {
        display: inline-flex;
      }
    }

    .gi-bg {
      position: absolute;
      inset: -60px;
      background:
        radial-gradient(circle at 15% 25%, #ffffff 0, transparent 60%),
        radial-gradient(circle at 85% 70%, #fff7e5 0, transparent 60%),
        linear-gradient(135deg, rgba(255,255,255,0.5), transparent 40%);
      opacity: 0;
      animation: giBgIn 0.8s ease-out forwards;
    }
    .gi-deco {
      position: absolute;
      border-radius: 999px;
      opacity: 0.4;
      mix-blend-mode: screen;
    }
    .gi-deco-1 {
      width: 280px;
      height: 280px;
      background: radial-gradient(circle, #ff9aa2, transparent 60%);
      top: -40px;
      left: -60px;
    }
    .gi-deco-2 {
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, #ffdf6b, transparent 60%);
      bottom: -40px;
      right: -40px;
    }
    .gi-deco-3 {
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, #a6e3ff, transparent 60%);
      bottom: 20%;
      left: 60%;
    }
    .gi-frame {
      position: relative;
      width: min(960px, 94%);
      max-height: 520px;
      padding: 22px 22px 20px;
      border-radius: 28px;
      background: linear-gradient(135deg, #fff9ff, #ffeef6);
      box-shadow: 0 20px 50px rgba(255, 106, 149, 0.55);
      border: 3px solid rgba(255, 255, 255, 0.9);
      overflow: hidden;
    }
    .gi-topbar {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:12px;
      color:#ff4f7b;
    }
    .gi-chip {
      padding:6px 14px;
      border-radius:999px;
      background:linear-gradient(135deg,#ffb9d9,#ffe6f4);
      color:#7b2640;
      font-size:14px;
      box-shadow:0 5px 12px rgba(255,143,186,0.7);
      border:2px solid #ffe5f0;
    }
    .gi-top-status{
      display:flex;
      align-items:baseline;
      gap:6px;
    }
    .gi-top-label{
      font-size:13px;
      opacity:0.7;
    }
    .gi-top-value{
      font-size:15px;
    }
    .gi-layout{
      display:flex;
      gap:18px;
    }
    .gi-playwrapper{
      flex:1.2;
      display:flex;
      flex-direction:column;
      gap:10px;
    }
    #giPlayfield{
      position:relative;
      width:100%;
      height:260px;
      border-radius:22px;
      overflow:hidden;
      background:linear-gradient(180deg,#ffeefb,#ffe0f0 40%,#ffd3e8 65%,#ffe9c7 75%,#ffc76f 100%);
      box-shadow: inset 0 0 0 2px rgba(255,255,255,0.9),
                  0 10px 20px rgba(255, 148, 177, 0.6);
    }
    .gi-parallax{
      position:absolute;
      inset:0;
      opacity:0.5;
      pointer-events:none;
    }
    .gi-parallax-back{
      background-image:
        radial-gradient(circle at 15% 20%, rgba(255,255,255,0.9) 0, transparent 60%),
        radial-gradient(circle at 80% 30%, rgba(255,255,255,0.8) 0, transparent 55%),
        radial-gradient(circle at 50% 0%, rgba(255,255,255,0.8) 0, transparent 55%);
      animation: giParaBack 18s linear infinite;
    }
    .gi-parallax-mid{
      background-image:
        radial-gradient(circle, rgba(255,255,255,0.6) 2px, transparent 2px);
      background-size: 26px 26px;
      opacity:0.4;
      animation: giParaMid 10s linear infinite;
    }
    .gi-ground{
      position:absolute;
      left:-30px;
      right:-30px;
      bottom:16px;
      height:38px;
      border-radius:40px;
      background:linear-gradient(180deg,#ffe4c0,#ffc46c);
      box-shadow:0 10px 20px rgba(255,173,91,0.7);
    }
    .gi-ground::before{
      content:"";
      position:absolute;
      inset:8px 18px;
      border-radius:999px;
      background-image:linear-gradient(90deg,rgba(255,255,255,0.85) 16px,transparent 16px,transparent 30px);
      background-size:32px 100%;
      opacity:0.85;
      animation: giTrackMove 2s linear infinite;
    }
    .gi-logo-ghost{
      position:absolute;
      top:14px;
      left:50%;
      transform:translateX(-50%);
      font-size:26px;
      color:rgba(255,255,255,0.8);
      text-shadow:0 0 10px rgba(255,104,167,0.7);
      letter-spacing:2px;
      pointer-events:none;
    }
    #giPlayer{
      position:absolute;
      bottom:36px;
      left:50%;
      transform:translateX(-50%);
      width:60px;
      height:60px;
      pointer-events:none;
    }
    .gi-player-glow{
      position:absolute;
      inset:6px;
      border-radius:18px;
      background:radial-gradient(circle,#fff7d6 0,#ffc76f 50%,transparent 70%);
      opacity:0.8;
      filter:blur(5px);
    }
    .gi-player-body{
      position:absolute;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:32px;
      background:#fff9f3;
      border-radius:18px;
      border:3px solid #ffb36c;
      box-shadow:0 6px 14px rgba(255,159,92,0.9);
    }
    .gi-hud{
      display:flex;
      align-items:center;
      gap:10px;
      margin-top:8px;
    }
    .gi-hud-item{
      background:#fff9ff;
      border-radius:999px;
      padding:6px 10px;
      border:2px solid #ffd2eb;
      box-shadow:0 3px 8px rgba(255,184,211,0.7);
      display:flex;
      flex-direction:column;
    }
    .gi-hud-label{
      font-size:11px;
      opacity:0.7;
    }
    .gi-hud-value{
      font-size:14px;
      color:#ff4f7b;
    }
    .gi-hud-bar{
      flex:1;
      height:12px;
      border-radius:999px;
      background:rgba(255,255,255,0.85);
      box-shadow:0 4px 10px rgba(255,153,177,0.7);
      overflow:hidden;
    }
    #giProgressFill{
      height:100%;
      width:0%;
      border-radius:999px;
      background:linear-gradient(90deg,#ff9aa2,#ffb347,#a6e3ff);
      transition:width 0.25s ease-out;
    }
    .gi-sidebar{
      flex:0.9;
      padding:10px 6px 0 4px;
      color:#7b2640;
    }
    .gi-title{
      margin:0 0 8px;
    }
    .gi-title-top{
      font-size:16px;
      opacity:0.8;
    }
    .gi-title-main{
      font-size:26px;
      display:block;
      color:#ff4f7b;
      text-shadow:0 0 12px rgba(255,255,255,0.9);
    }
    .gi-desc{
      font-size:14px;
      line-height:1.4;
      margin-bottom:8px;
    }
    .gi-key{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:2px 6px;
      margin:0 1px;
      border-radius:5px;
      background:#fff;
      border:1px solid #ffcadd;
      font-size:12px;
      box-shadow:0 2px 4px rgba(255,164,196,0.7);
    }
    .gi-list{
      padding-left:18px;
      margin:0 0 14px;
      font-size:13px;
      line-height:1.5;
    }
    .gi-buttons{
      display:flex;
      gap:8px;
      flex-wrap:wrap;
    }
    .gi-btn{
      border-radius:999px;
      padding:7px 14px;
      font-size:13px;
      border:none;
      cursor:pointer;
      transition:transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }
    .gi-btn-primary{
      background:linear-gradient(135deg,#ff7ba5,#ffb347);
      color:#fff;
      box-shadow:0 6px 14px rgba(255,118,159,0.8);
    }
    .gi-btn-primary:hover{
      transform:translateY(-1px) scale(1.03);
      box-shadow:0 8px 18px rgba(255,118,159,0.9);
    }
    .gi-btn-ghost{
      background:rgba(255,255,255,0.9);
      color:#ff4f7b;
      border:2px solid #ffd4ea;
      box-shadow:0 4px 10px rgba(255,176,205,0.7);
    }
    .gi-btn-ghost:hover{
      transform:translateY(-1px);
    }
    .gi-food{
      position:absolute;
      width:40px;
      height:40px;
      border-radius:14px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:24px;
      background:#fffdf8;
      border:3px solid #ffd973;
      box-shadow:0 6px 12px rgba(255,217,115,0.9);
    }
    .gi-food-pop{
      animation: giFoodPop 0.2s ease forwards;
    }
    #giClearOverlay,
    #giFailOverlay{
      position:absolute;
      inset:0;
      display:flex;
      justify-content:center;
      align-items:center;
      pointer-events:none;
      opacity:0;
      transition:opacity 0.35s ease;
    }
    #giClearOverlay.gi-show,
    #giFailOverlay.gi-show{
      opacity:1;
      pointer-events:auto;
    }
    .gi-clear-card{
      min-width:260px;
      padding:16px 20px;
      border-radius:20px;
      background:rgba(255,252,255,0.98);
      border:2px solid #ffe0f1;
      box-shadow:0 10px 26px rgba(255,125,169,0.7);
      text-align:center;
    }
    .gi-clear-chip{
      display:inline-block;
      padding:4px 12px;
      border-radius:999px;
      background:linear-gradient(135deg,#ffb347,#ff9aa2);
      color:#fff;
      font-size:13px;
      margin-bottom:6px;
    }
    .gi-fail-chip{
      background:linear-gradient(135deg,#ff7b7b,#ffb347);
    }
    .gi-clear-logo{
      font-size:26px;
      color:#ff4f7b;
      margin-bottom:4px;
    }
    .gi-clear-text{
      font-size:14px;
      color:#7b2640;
    }
    #cartList .edit-note-btn {
  background: none;
  border: none;
  color: #ff9a00;
  font-weight: bold;
  font-size: 14px;
  cursor: pointer;
  padding: 4px 8px;
}
#cartList .edit-note-btn:hover {
  color: #ff6b00;
  transform: scale(1.05);
}

    #gameIntro.gi-fadeout{
      animation: giIntroFade 0.7s ease forwards;
    }
    @keyframes giBgIn{
      from{opacity:0; transform:scale(1.05);}
      to{opacity:1; transform:scale(1);}
    }
    @keyframes giParaBack{
      0%{transform:translateX(0);}
      100%{transform:translateX(-30px);}
    }
    @keyframes giParaMid{
      0%{transform:translateX(0);}
      100%{transform:translateX(-60px);}
    }
    @keyframes giTrackMove{
      0%{background-position-x:0;}
      100%{background-position-x:-32px;}
    }
    @keyframes giFoodPop{
      0%{transform:scale(1); opacity:1;}
      100%{transform:scale(0.2); opacity:0;}
    }
    @keyframes giIntroFade{
      0%{opacity:1;}
      100%{opacity:0; visibility:hidden;}
    }

    @media (max-width: 820px){
      .gi-layout{
        flex-direction:column;
      }
      .gi-sidebar{
        padding-top:2px;
      }
    }
    @media (max-width: 600px){
      .gi-frame{
        padding:16px 14px;
      }
      #giPlayfield{
        height:230px;
      }
      .gi-logo-ghost{
        font-size:22px;
      }
    }

    /* 📱 小螢幕時：整個 gameIntro 可以捲動，避免 START 被吃掉 */
    @media (max-width: 768px) {
      #gameIntro {
        align-items: flex-start;
        padding-top: 20px;
        overflow-y: auto;
      }
      .gi-frame {
        max-height: none;
      }
    }

    /* 📱 最小螢幕尺寸（手機）時的對話框 RWD 調整 */
    @media (max-width: 480px) {
      #noteModal,
      #cartModal,
      #checkoutModal {
        width: 92%;
        padding: 16px;
        max-width: 480px;
      }

      #noteModal h3,
      #cartModal h3,
      .checkout-header h3 {
        font-size: 18px;
      }

      #noteModal,
      #noteModal label,
      #noteModal textarea,
      #cartModal,
      #cartModal .cart-item-info,
      #checkoutModal,
      .checkout-section h4,
      .checkout-item-name,
      .checkout-item-details,
      .item-options,
      .item-note,
      .price-row,
      .total-row {
        font-size: 13px;
      }

      button.add-btn,
      button.cancel-btn,
      button.confirm-btn {
        font-size: 13px;
        padding: 8px 10px;
      }

      .cart-actions {
        justify-content: space-between;
      }
      .cart-actions button {
        flex: 1 1 0;
        min-width: auto;
      }

      .checkout-buttons {
        gap: 8px;
        padding: 12px 14px;
      }
      .checkout-buttons button {
        font-size: 13px;
        padding: 10px 8px;
      }

      #cartList div.cart-item {
        padding: 6px;
      }
      #cartList img {
        width: 48px;
        height: 48px;
        margin-right: 8px;
      }
      #cartList .qty-btn {
        width: 24px;
        height: 24px;
        font-size: 16px;
      }
      #cartList .qty-display {
        font-size: 13px;
      }
    }


    #greetingOverlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 8999;
  display: block;
}
#send-bt{
  padding:10px ;
  width:100%;
  border-radius: 5px;
}

  </style>
</head>

<body class="w3-content" style="max-width:1200px">

  <!-- 🎮 INTERACTIVE GAME INTRO -->
  <div id="gameIntro">
    <div class="gi-bg"></div>
    <div class="gi-deco gi-deco-1"></div>
    <div class="gi-deco gi-deco-2"></div>
    <div class="gi-deco gi-deco-3"></div>

    <div class="gi-frame">
      <div class="gi-topbar">
        <div class="gi-chip">Kirby Café Quest</div>
        <div class="gi-top-status">
          <span class="gi-top-label">LEVEL</span>
          <span class="gi-top-value">1 · WELCOME</span>
        </div>
      </div>

      <div class="gi-layout">
        <!-- 遊戲區 -->
        <div class="gi-playwrapper">
          <div id="giPlayfield">
            <div class="gi-parallax gi-parallax-back"></div>
            <div class="gi-parallax gi-parallax-mid"></div>
            <div class="gi-ground"></div>
            <div class="gi-logo-ghost">Restaurant</div>
            <div id="giPlayer">
              <div class="gi-player-glow"></div>
              <div class="gi-player-body">🍔</div>
            </div>
          </div>

          <!-- 分數＋時間 -->
          <div class="gi-hud">
            <div class="gi-hud-item">
              <span class="gi-hud-label">SCORE</span>
              <span id="giScoreText" class="gi-hud-value">0 / 8</span>
            </div>
            <div class="gi-hud-item">
              <span class="gi-hud-label">TIME</span>
              <span id="giTimeText" class="gi-hud-value">20s</span>
            </div>
            <div class="gi-hud-bar">
              <div id="giProgressFill"></div>
            </div>
          </div>
        </div>

        <!-- 📱 小螢幕用觸控控制 -->
        <div class="gi-touch-controls">
          <button id="giLeftBtn" class="gi-touch-btn">◀</button>
          <button id="giRightBtn" class="gi-touch-btn">▶</button>
        </div>

        <!-- 📱 小螢幕專用 START 按鈕 -->
        <button id="giStartBtnMobile" class="gi-btn gi-btn-primary gi-start-mobile">
          START
        </button>

        <!-- 說明區 -->
        <div class="gi-sidebar">
          <h2 class="gi-title">
            <span class="gi-title-top">Welcome to</span>
            <span class="gi-title-main">Our Restaurant!</span>
          </h2>
          <p class="gi-desc">
            Use <span class="gi-key">◀</span><span class="gi-key">▶</span> or
            <span class="gi-key">A</span><span class="gi-key">D</span> to move the burger.
            Catch the falling foods to fill the gauge!
          </p>
          <ul class="gi-list">
            <li>🍕 Catch cute foods to power up the logo</li>
            <li>🌈 More colors, more sparkles, more fun</li>
            <li>✨ When gauge is full, the logo jumps to the center</li>
          </ul>
          <div class="gi-buttons">
            <button id="giStartBtn" class="gi-btn gi-btn-primary">START GAME</button>
            <button id="giSkipBtn" class="gi-btn gi-btn-ghost">SKIP INTRO</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 過關 -->
    <div id="giClearOverlay">
      <div class="gi-clear-card">
        <div class="gi-clear-chip">LEVEL 1 CLEAR</div>
        <div class="gi-clear-logo">Restaurant</div>
        <p class="gi-clear-text">Loading your lovely menu...</p>
      </div>
    </div>

    <!-- 失敗 -->
    <div id="giFailOverlay">
      <div class="gi-clear-card">
        <div class="gi-clear-chip gi-fail-chip">TIME UP</div>
        <p class="gi-clear-text">Oops! Try catching more foods!</p>
        <button id="giRetryBtn" class="gi-btn gi-btn-primary">PLAY AGAIN</button>
        <button id="giFailSkipBtn" class="gi-btn gi-btn-ghost">SKIP INTRO</button>
      </div>
    </div>
  </div>
  <!-- 🎮 END GAME INTRO -->

  <!-- Greeting -->
   <!-- Greeting 遮罩層 -->
<div id="greetingOverlay"></div>
  <div id="greeting">
    <h1 id="greeting1">Good Morning!</h1>
    <h2 id="welcome">Welcome to our restaurant!</h2>
    <button id="enter" class="confirm-btn">Enter</button>
  </div>

  <!-- Sidebar -->
  <nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top"
       style="z-index:3;width:250px" id="mySidebar">
    <div class="w3-container w3-display-container w3-padding-16">
      <i onclick="w3_close()"
         class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
      <h3 class="w3-wide"><b>Kirby Café</b></h3>
    </div>

    <!-- Login Box -->
    <div class="w3-container w3-padding-small"
       style="background:#fff;border-radius:10px;border:2px solid #ccc;margin:10px;">

      <?php if (empty($_SESSION["logged_in"])): ?>

  <!-- Login Form（未登入才顯示） -->
  <form id="loginForm" method="post">
    <label>Account:</label>
    <input class="w3-input w3-border" type="text" name="acc" id="accInput">

    <label>Password:</label>
    <input class="w3-input w3-border" type="password" name="pwd" id="pwdInput">

    <button class="w3-button w3-pink w3-margin-top" type="submit" name="login">
      Login
    </button>
  </form>

  <p id="loginMsg" style="font-weight:bold;margin-top:10px;color:red;">
  <?php echo $login_message ?? ""; ?>
</p>


<?php else: ?>

  <!-- 已登入畫面 -->
  <p style="font-weight:bold;color:green;">
    Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?> 👋
  </p>

  <form method="post">
    <button class="w3-button w3-gray w3-margin-top" type="submit" name="logout">
      Logout
    </button>
  </form>

<?php endif; ?>

    </div>

    <!-- Login Box end -->

   <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
  <a onclick="myAccFunc()" href="javascript:void(0)"
     class="w3-button w3-block w3-white w3-left-align" id="myBtn">
    Menu <i class="fa fa-caret-down"></i>
  </a>
  <div id="demoAcc" class="w3-bar-block w3-hide w3-padding-large w3-medium">
    <a href="#maincourse" class="w3-bar-item w3-button">🍖Main course</a>
    <a href="#dessert" class="w3-bar-item w3-button">🍰Dessert</a>
    <a href="#beverage" class="w3-bar-item w3-button">🥤Beverage</a>
  </div>
  
  <a href="#footer" class="w3-button w3-block w3-white w3-left-align" style="margin-top:16px;">Contact</a>
</div>
  </nav>

  <!-- Top bar (mobile) -->
  <header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
    <div class="w3-bar-item w3-padding-24 w3-wide">Kirby Café</div>
    <a href="javascript:void(0)"
       class="w3-bar-item w3-button w3-padding-24 w3-right"
       onclick="w3_open()"><i class="fa fa-bars"></i></a>
  </header>

  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer"
       title="close side menu" id="myOverlay"></div>

  <!-- Main content -->
  <div class="w3-main" style="margin-left:250px">
    <div class="w3-hide-large" style="margin-top:83px"></div>

    <!-- Header -->
    <header class="w3-container w3-xlarge">
      <p class="w3-left">Foods</p>
      <p class="w3-right">
        <span class="cart-wrapper">
          <i class="fa fa-shopping-cart" id="cartIcon"></i>
          <span id="cartCountBadge">0</span>
        </span>
        <i class="fa fa-search" id="searchIcon"></i>
      </p>
    </header>

    <!-- Hero -->
    <div class="w3-display-container w3-container">
      <img src="./22.jpg" alt="Foods"
       style="width:100%;height:400px;object-fit:cover;border-radius:20px;">
    </div>

    <div class="w3-container w3-text-grey">
      <p>20 items</p>
    </div>

    <!-- Main course -->
    <section class="w3-row-padding" id="maincourse">
      <!-- 1 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./1.jpg" alt="" class="zoomable" data-img="./1.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                This pasta is a little spicy and very yummy! The chicken dances with tasty sauce. 🌶️💃
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Mexican spicy and Tangy Chicken Pasta<br><b>$330</b></p>
          <button class="add-btn"
                  data-name="Mexican spicy and Tangy Chicken Pasta"
                  data-price="330"
                  data-img="./1.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 2 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./5.jpg" alt="" class="zoomable" data-img="./5.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Smoky rice with soft pork — it smells so good! 🔥🍚
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Charcoal-Grilled Pork Tongue Rice<br><b>$185</b></p>
          <button class="add-btn"
                  data-name="Charcoal-Grilled Pork Tongue Rice"
                  data-price="185"
                  data-img="./5.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 3 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./8.jpg" alt="" class="zoomable" data-img="./8.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Eggs and fish on toast! So soft and creamy. 🍳🐠
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Amazing Eggs Benedict-Seared Fish<br><b>$480</b></p>
          <button class="add-btn"
                  data-name="Amazing Eggs Benedict-Seared Fish"
                  data-price="480"
                  data-img="./8.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 4 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./9.jpg" alt="" class="zoomable" data-img="./9.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Crunchy nuts and sweet chicken — a happy bite every time! 🥢
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Kung Pao Chicken With Cashews<br><b>$258</b></p>
          <button class="add-btn"
                  data-name="Kung Pao Chicken With Cashews"
                  data-price="258"
                  data-img="./9.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 5 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./7.jpg" alt="" class="zoomable" data-img="./7.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                So much cheese! It’s melty, stretchy, and super fun! 🧀😋
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Cheese Monopoly Pizza<br><br><b>$230</b></p>
          <button class="add-btn"
                  data-name="Cheese Monopoly Pizza"
                  data-price="230"
                  data-img="./7.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 6 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./6.jpg" alt="" class="zoomable" data-img="./6.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Soft chicken and yummy rice — warm and cozy! 🍚💛
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Hainanese Chicken Rice<br><br><b>$110</b></p>
          <button class="add-btn"
                  data-name="Hainanese Chicken Rice"
                  data-price="110"
                  data-img="./6.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 7 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./4.jpg" alt="" class="zoomable" data-img="./4.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Noodles, shrimp, and peanuts — what a tasty mix! 🍤🍜
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Padyhai Gung Yang<br><br><b>$280</b></p>
          <button class="add-btn"
                  data-name="Padyhai Gung Yang"
                  data-price="280"
                  data-img="./4.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 8 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./2.jpg" alt="" class="zoomable" data-img="./2.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                A little sour, a little spicy — and full of fun! 🍋🔥
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Thai Spicy and Sour Noodles<br><b>$200</b></p>
          <button class="add-btn"
                  data-name="Thai Spicy and Sour Noodles"
                  data-price="200"
                  data-img="./2.jpg">Add to Cart</button>
        </div>
      </div>
    </section>

    <!-- Dessert -->
    <section class="w3-row-padding" id="dessert">
      <!-- 9 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./10.jpg" alt="" class="zoomable" data-img="./10.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Sweet purple taro in creamy milk — soft and happy! 💜🥛
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Taro Sago With Coconut Milk<br><b>$100</b></p>
          <button class="add-btn"
                  data-name="Taro Sago With Coconut Milk"
                  data-price="100"
                  data-img="./10.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 10 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./11.jpg" alt="" class="zoomable" data-img="./11.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Crunch on top, soft inside — like magic sugar! ✨
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Cream Brulee<br><br><b>$110</b></p>
          <button class="add-btn"
                  data-name="Cream Brulee"
                  data-price="110"
                  data-img="./11.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 11 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./12.jpg" alt="" class="zoomable" data-img="./12.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                A little burnt, a lot yummy! 🧀🔥
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Basque Burnt Cheesecake<br><br><b>$180</b></p>
          <button class="add-btn"
                  data-name="Basque Burnt Cheesecake"
                  data-price="180"
                  data-img="./12.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 12 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./15.jpg" alt="" class="zoomable" data-img="./15.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Fluffy cake with tea and strawberries — so pretty! ☁️
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Earl Grey Strawberry Chiffon Cake<br><b>$180</b></p>
          <button class="add-btn"
                  data-name="Earl Grey Strawberry Chiffon Cake"
                  data-price="180"
                  data-img="./15.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 13 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./17.jpg" alt="" class="zoomable" data-img="./17.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Soft cake roll with sweet cream — yum yum! 🍰💛
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Salted Cream Roll Cake<br><br><b>$180</b></p>
          <button class="add-btn"
                  data-name="Salted Cream Roll Cake"
                  data-price="180"
                  data-img="./17.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 14 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./20.jpg" alt="" class="zoomable" data-img="./20.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Many thin cakes stacked tall — like a yummy tower! 🎂💚
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Pistachio Mille Crepe Cake<br><br><b>$250</b></p>
          <button class="add-btn"
                  data-name="Pistachio Mille Crepe Cake"
                  data-price="250"
                  data-img="./20.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 15 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./19.jpg" alt="" class="zoomable" data-img="./19.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Pink drink with mochi bits — tastes like spring! 🌸🥤
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Sakura Mochi Frappe<br><br><b>$100</b></p>
          <button class="add-btn"
                  data-name="Sakura Mochi Frappe"
                  data-price="100"
                  data-img="./19.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 16 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./21.jpg" alt="" class="zoomable" data-img="./21.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Crunchy top, buttery heart — no pineapple, just happy! 🧈😄
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Hong Kong Style Butter Pineapple Bun<br><b>$130</b></p>
          <button class="add-btn"
                  data-name="Hong Kong Style Butter Pineapple Bun"
                  data-price="130"
                  data-img="./21.jpg">Add to Cart</button>
        </div>
      </div>
    </section>

    <!-- Beverage -->
    <section class="w3-row-padding" id="beverage">
      <!-- 17 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./14.jpg" alt="" class="zoomable" data-img="./14.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Sweet orange milk tea — sip, smile, and happy! 🧡
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Cha Yen<br><br><b>$95</b></p>
          <button class="add-btn"
                  data-name="Cha Yen"
                  data-price="95"
                  data-img="./14.jpg"
                  data-has-options="true">Add to Cart</button>
        </div>
      </div>

      <!-- 18 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./16.jpg" alt="" class="zoomable" data-img="./16.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Warm milk and brown sugar — cozy and sweet! 🍯
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Brown Sugar Latte<br><br><b>$150</b></p>
          <button class="add-btn"
                  data-name="Brown Sugar Latte"
                  data-price="150"
                  data-img="./16.jpg"
                  data-has-options="true">Add to Cart</button>
        </div>
      </div>

      <!-- 19 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./13.jpg" alt="" class="zoomable" data-img="./13.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Peach and berry smoothie — like a rainbow drink! 🌈🍓
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Peach Berry Cream Frappe<br><br><b>$150</b></p>
          <button class="add-btn"
                  data-name="Peach Berry Cream Frappe"
                  data-price="150"
                  data-img="./13.jpg">Add to Cart</button>
        </div>
      </div>

      <!-- 20 -->
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="menu-card">
            <div class="menu-card-inner">
              <div class="card-front">
                <img src="./18.jpg" alt="" class="zoomable" data-img="./18.jpg">
                <div class="hover-card"><span>Click Me!</span></div>
              </div>
              <div class="card-back">
                Bubbly and tangy — tickly and fun! ✨🍯🍋
              </div>
            </div>
          </div>
          <button class="flip-btn">
            <i class="fa fa-refresh"></i> Flip card
          </button>
          <p>Honey Lime Sparkling Cooler<br><b>$130</b></p>
          <button class="add-btn"
                  data-name="Honey Lime Sparkling Cooler"
                  data-price="130"
                  data-img="./18.jpg"
                  data-has-options="true">Add to Cart</button>
        </div>
      </div>
    </section>

    <!-- Subscribe -->
    <div class="subscribe-section">
      <h2>Join our fun newsletter!</h2>
      <p>Get special offers and colorful surprises:</p>
      <input type="text" placeholder="Enter your e-mail">
    </div>

    <!-- Footer -->
    <footer class="w3-padding-64 w3-small" id="footer">
      <div class="w3-row-padding">
        <div class="w3-col s4">
          <h4>Contact</h4>
          <p>Questions? Go ahead.</p>
          <form action="#" target="_blank">
            <p><input class="w3-input w3-border" type="text" placeholder="Name" name="Field1"></p>
            <p><input class="w3-input w3-border" type="text" placeholder="Email" name="Field2"></p>
            <p><input class="w3-input w3-border" type="text" placeholder="Subject" name="Field3"></p>
            <p><input class="w3-input w3-border" type="text" placeholder="Message" name="Field4"></p>
            <button type="submit" class="w3-button  w3-pink" id="send-bt">Send</button>
          </form>
        </div>

        <div class="w3-col s4">
          <h4>About</h4>
          <p><a href="#">About us</a></p>
          <p><a href="#">We're hiring</a></p>
          <p><a href="#">Support</a></p>
          <p><a href="#">Find store</a></p>
          <p><a href="#">Shipment</a></p>
          <p><a href="#">Payment</a></p>
          <p><a href="#">Gift card</a></p>
          <p><a href="#">Return</a></p>
          <p><a href="#">Help</a></p>
        </div>

        <div class="w3-col s4">
          <h4>Store</h4>
          <p><i class="fa fa-fw fa-map-marker"></i> Company Name</p>
          <p><i class="fa fa-fw fa-phone"></i> 0044123123</p>
          <p><i class="fa fa-fw fa-envelope"></i> ex@mail.com</p>

          <h4>We accept</h4>
          <p><i class="fa fa-fw fa-cc-amex"></i> Amex</p>
          <p><i class="fa fa-fw fa-credit-card"></i> Credit Card</p>

          <br>

          <i class="fa fa-facebook-official w3-hover-opacity w3-large"></i>
          <i class="fa fa-instagram w3-hover-opacity w3-large"></i>
          <i class="fa fa-snapchat w3-hover-opacity w3-large"></i>
          <i class="fa fa-pinterest-p w3-hover-opacity w3-large"></i>
          <i class="fa fa-linkedin w3-hover-opacity w3-large"></i>
        </div>
      </div>
    </footer>

    <div class="w3-black w3-center w3-padding-24">
      Powered by <a href="https://www.w3schools.com/w3css/default.asp" class="w3-hover-opacity">w3.css</a>
    </div>
  </div> <!-- end .w3-main -->

  <!-- Back to Top Button -->
  <button id="backToTop" title="Go to top">
    <i class="fa fa-arrow-up"></i>
  </button>

  <!-- Progress Bar -->
  <div class="progress-container">
    <div class="progress-bar">
      <div class="progress-line">
        <div class="progress-line-fill" id="progressFill"></div>
      </div>
      <div class="progress-step">
        <div class="rocket-icon active" id="step1">🚀</div>
        <div class="step-label active">order</div>
      </div>
      <div class="progress-step">
        <div class="rocket-icon" id="step2">🚀</div>
        <div class="step-label">confirm</div>
      </div>
      <div class="progress-step">
        <div class="rocket-icon" id="step3">🚀</div>
        <div class="step-label">checkout</div>
      </div>
      <div class="progress-step">
        <div class="rocket-icon" id="step4">🚀</div>
        <div class="step-label">finish</div>
      </div>
    </div>
  </div>

  <!-- Note Modal -->
  <div id="noteModal">
    <h3 style="margin-top:0;">Add Note</h3>
    <div class="option-group" style="margin-bottom:10px;">
      <label>Ice Level:</label><br>
      <input type="radio" name="ice1" value="Normal" checked> Regular
      <input type="radio" name="ice1" value="Less"> Less
      <input type="radio" name="ice1" value="No Ice"> No Ice
    </div>
    <div class="option-group" style="margin-bottom:10px;">
      <label>Sugar Level:</label><br>
      <input type="radio" name="sugar1" value="Normal" checked> Regular
      <input type="radio" name="sugar1" value="Half"> Half
      <input type="radio" name="sugar1" value="Less"> Less
      <input type="radio" name="sugar1" value="No Sugar"> No Sugar
    </div>
    <textarea id="noteText" placeholder="Enter note..."></textarea><br><br>
    <button class="add-btn" id="confirmAdd">Add</button>
    <button class="cancel-btn" id="cancelAdd">Cancel</button>
  </div>

  <!-- Cart Modal -->
  <div id="cartModal">
    <h3>Your Cart</h3>
    <div id="cartList"></div>
    <div class="cart-actions">
      <button class="cancel-btn" id="closeCart">Close</button>
      <button class="confirm-btn" id="confirmCart">Confirm</button>
    </div>
  </div>
  

  <!-- Checkout Modal -->
  <div id="checkoutModal">
    <div class="checkout-header">
      <h3>💳 Checkout</h3>
    </div>
    <div class="checkout-content">
      <div class="checkout-section">
        <h4>📋 Order Summary</h4>
        <div id="checkoutItemsList"></div>
      </div>
      <div class="checkout-section">
        <h4>💰 Payment Details</h4>
        <div class="price-row total-row">
          <span>Total:</span>
          <span id="totalAmount">$0</span>
        </div>
      </div>
      <div class="checkout-section">
        <h4>💳 Payment Method</h4>
        <div class="payment-methods">
          <label class="payment-option">
            <input type="radio" name="payment" value="credit" checked>
            <div class="payment-card">
              <i class="fa fa-credit-card"></i>
              <span>Credit Card</span>
            </div>
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="cash">
            <div class="payment-card">
              <i class="fa fa-money"></i>
              <span>Cash</span>
            </div>
          </label>
        </div>
      </div>
    </div>
    <div class="checkout-buttons">
      <button class="cancel-btn" id="backToCart">← Back</button>
      <button class="confirm-btn" id="payBtn">Confirm Payment</button>
    </div>
  </div>

  <!-- Lightbox：放大食物照片 -->
  <div id="lightbox" class="lightbox" style="display:none;">
    <span class="close">&times;</span>
    <img id="lightbox-img" src="" alt="">
  </div>

  <script>
    // Sidebar & menu
    function myAccFunc() {
      var x = document.getElementById("demoAcc");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
      } else {
        x.className = x.className.replace(" w3-show", "");
      }
    }
    function w3_open() {
      document.getElementById("mySidebar").style.display = "block";
      document.getElementById("myOverlay").style.display = "block";
    }
    function w3_close() {
      document.getElementById("mySidebar").style.display = "none";
      document.getElementById("myOverlay").style.display = "none";
    }


    
    // Greeting
    function enterGreeting() {
      var greet1 = document.getElementById("greeting1");
      var greet2 = document.getElementById("welcome");
      var greet  = document.getElementById("greeting");
      var now = new Date().getHours();

      if (now >= 5 && now < 12) {
        greet1.textContent = "Good Morning!";
        greet2.textContent = "Welcome to our restaurant!";
        greet.style.background="#f0e680";
      } else if (now >= 12 && now < 17) {
        greet1.textContent = "Good Afternoon!";
        greet2.textContent = "Welcome to our restaurant!";
      } else if (now >= 17 && now < 22) {
        greet1.textContent = "Good Evening!";
        greet2.textContent = "Welcome to our restaurant!";
        greet.style.background="#ffe4b5";
      } else {
        greet1.textContent = "Time to sleep!";
        greet2.textContent = "See you tomorrow!";
        greet.style.background="#21416e";
      }
    }
    function enter() {
      document.getElementById("greeting").style.display="none";
      document.getElementById("greetingOverlay").style.display="none";  // 新增這行
    }

    // Progress bar
    let currentStep = 1;
    function updateProgressBar(step) {
      currentStep = step;
      const steps = [
        { icon: document.getElementById("step1"), label: document.querySelector(".progress-step:nth-child(2) .step-label") },
        { icon: document.getElementById("step2"), label: document.querySelector(".progress-step:nth-child(3) .step-label") },
        { icon: document.getElementById("step3"), label: document.querySelector(".progress-step:nth-child(4) .step-label") },
        { icon: document.getElementById("step4"), label: document.querySelector(".progress-step:nth-child(5) .step-label") },
      ];
      const progressFill = document.getElementById("progressFill");
      progressFill.style.width = ((step - 1) / 3 * 100) + "%";

      steps.forEach((s, i) => {
        s.icon.classList.remove("active", "completed");
        s.label.classList.remove("active", "completed");
        if (i + 1 < step) {
          s.icon.classList.add("completed");
          s.label.classList.add("completed");
        } else if (i + 1 === step) {
          s.icon.classList.add("active");
          s.label.classList.add("active");
        }
      });
    }

    // Cart & checkout
    let cart = [];
    let selectedItem = null;
    let editingIndex = null;

    const addButtons = document.querySelectorAll(".w3-row-padding .add-btn");
    const noteModal = document.getElementById("noteModal");
    const cartModal = document.getElementById("cartModal");
    const checkoutModal = document.getElementById("checkoutModal");
    const cartList = document.getElementById("cartList");
    const cartCountBadge = document.getElementById("cartCountBadge");

    const confirmAddBtn = document.getElementById("confirmAdd");
    const cancelAddBtn = document.getElementById("cancelAdd");
    const cartIcon = document.getElementById("cartIcon");
    const closeCartBtn = document.getElementById("closeCart");
    const confirmCartBtn = document.getElementById("confirmCart");
    const backToCartBtn = document.getElementById("backToCart");
    const payBtn = document.getElementById("payBtn");

    // Add to cart: open note modal
addButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    selectedItem = {
      name: btn.dataset.name,
      price: btn.dataset.price,
      img: btn.dataset.img,
      hasOptions: btn.dataset.hasOptions === "true"
    };

    //✅ 一定是新增，不是編輯
    editingIndex = null;

    const groups = noteModal.querySelectorAll('.option-group');
    const iceGroup = groups[0];
    const sugarGroup = groups[1];

    if (selectedItem.hasOptions) {
      iceGroup.style.display = "block";
      sugarGroup.style.display = "block";

      // 預設選回 Regular
      noteModal.querySelectorAll('input[name="ice1"]').forEach(r => r.checked = (r.value === "Normal"));
      noteModal.querySelectorAll('input[name="sugar1"]').forEach(r => r.checked = (r.value === "Normal"));

    } else {
      iceGroup.style.display = "none";
      sugarGroup.style.display = "none";
    }
    document.getElementById("noteText").value = "";
    noteModal.style.display = "block";
  });
});

    // Confirm add item
    confirmAddBtn.addEventListener("click", () => {
      const note = document.getElementById("noteText").value;
      let ice = "", sugar = "";
      if (selectedItem && selectedItem.hasOptions) {
        const iceInput = noteModal.querySelector('input[name="ice1"]:checked');
        const sugarInput = noteModal.querySelector('input[name="sugar1"]:checked');
        if (iceInput) ice = iceInput.value;
        if (sugarInput) sugar = sugarInput.value;
      }

      if (selectedItem) {
        if (editingIndex !== null) {
          cart[editingIndex] = {
            ...selectedItem,
            note,
            ice,
            sugar,
            quantity: cart[editingIndex].quantity
          };
          editingIndex = null;
          if (cartModal.style.display === "block") {
          renderCart();
        }
        } else {
          cart.push({ ...selectedItem, note, ice, sugar, quantity: 1 });
        }
        cartCountBadge.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
      }
      document.getElementById("noteText").value = "";
      noteModal.style.display = "none";
      if (editingIndex === null) {
        updateProgressBar(1);
      }
    });

    // Cancel add
    cancelAddBtn.addEventListener("click", () => {
      document.getElementById("noteText").value = "";
      noteModal.style.display = "none";
    });

    // Render cart
    function renderCart() {
      cartList.innerHTML = "";
      cart.forEach((item, index) => {
        const div = document.createElement("div");
        div.classList.add("cart-item");

        let optionsHTML = '';
        if (item.hasOptions && item.ice && item.sugar) {
          optionsHTML = `Ice: ${item.ice}<br>Sugar: ${item.sugar}<br>`;
        }
        let noteHTML = item.note ? `Note: ${item.note}` : '';

        div.innerHTML = `
  <img src="${item.img}">
  <div class="cart-item-info">
    <b>${item.name}</b><br>
    $${item.price} × ${item.quantity}<br>
    ${optionsHTML}
    ${noteHTML}
  </div>
  <div class="cart-item-controls">
    <button class="qty-btn minus-btn" data-index="${index}">−</button>
    <span class="qty-display">${item.quantity}</span>
    <button class="qty-btn plus-btn" data-index="${index}">+</button>
    <button class="edit-note-btn" data-index="${index}">edit</button>
    <button class="remove-btn" data-index="${index}">delete</button>
  </div>
`;

        cartList.appendChild(div);
      });
    }

    // Open cart
    cartIcon.addEventListener("click", () => {
      renderCart();
      cartModal.style.display = "block";
      updateProgressBar(2);
    });

    cartList.addEventListener("click", (e) => {
  const index = parseInt(e.target.dataset.index, 10);
  if (Number.isNaN(index)) return;

  if (e.target.classList.contains("remove-btn")) {
    // 刪除
    cart.splice(index, 1);

  } else if (e.target.classList.contains("plus-btn")) {
    // + 數量
    cart[index].quantity++;

  } else if (e.target.classList.contains("minus-btn")) {
    // - 數量
    if (cart[index].quantity > 1) {
      cart[index].quantity--;
    } else {
      cart.splice(index, 1);
    }

  } else if (e.target.classList.contains("edit-note-btn")) {
    // ✅ 編輯備註
    const item = cart[index];
    selectedItem = {
      name: item.name,
      price: item.price,
      img: item.img,
      hasOptions: item.hasOptions
    };
    editingIndex = index; // 告訴 confirmAdd：這是編輯不是新增

    const groups = noteModal.querySelectorAll('.option-group');
    const iceGroup = groups[0];
    const sugarGroup = groups[1];

    if (item.hasOptions) {
      iceGroup.style.display = "block";
      sugarGroup.style.display = "block";

      // 先全部取消勾選
      noteModal.querySelectorAll('input[name="ice1"]').forEach(r => r.checked = false);
      noteModal.querySelectorAll('input[name="sugar1"]').forEach(r => r.checked = false);

      // 再勾目前的選項（如果有）
      if (item.ice) {
        const iceRadio = noteModal.querySelector(`input[name="ice1"][value="${item.ice}"]`);
        if (iceRadio) iceRadio.checked = true;
      }
      if (item.sugar) {
        const sugarRadio = noteModal.querySelector(`input[name="sugar1"][value="${item.sugar}"]`);
        if (sugarRadio) sugarRadio.checked = true;
      }
    } else {
      iceGroup.style.display = "none";
      sugarGroup.style.display = "none";
    }

    // 帶入原本備註
    document.getElementById("noteText").value = item.note || "";

    // 打開 modal
    noteModal.style.display = "block";
    return; // 下面的 renderCart 就先不要執行
  }

  cartCountBadge.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  renderCart();
});


    // Close cart
    closeCartBtn.addEventListener("click", () => {
      cartModal.style.display = "none";
      updateProgressBar(1);
    });

    // Cart Confirm → go to checkout
    confirmCartBtn.addEventListener("click", () => {
      if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
      }
      renderCheckout();
      cartModal.style.display = "none";
      checkoutModal.style.display = "block";
      updateProgressBar(3);
    });

    // Render checkout
    function renderCheckout() {
      const checkoutItemsList = document.getElementById("checkoutItemsList");
      checkoutItemsList.innerHTML = "";
      let subtotal = 0;

      cart.forEach(item => {
        const itemTotal = parseInt(item.price) * item.quantity;
        subtotal += itemTotal;

        const div = document.createElement("div");
        div.classList.add("checkout-item");

        let optionsText = '';
        if (item.hasOptions && item.ice && item.sugar) {
          optionsText = `<div class="item-options">Ice: ${item.ice} | Sugar: ${item.sugar}</div>`;
        }
        let noteText = '';
        if (item.note) {
          noteText = `<div class="item-note">Note: ${item.note}</div>`;
        }

        div.innerHTML = `
          <div class="checkout-item-main">
            <div class="checkout-item-name">${item.name}</div>
            <div class="checkout-item-details">
              $${item.price} × ${item.quantity}
              ${optionsText}
              ${noteText}
            </div>
          </div>
          <div class="checkout-item-price">$${itemTotal}</div>
        `;
        checkoutItemsList.appendChild(div);
      });

      document.getElementById("totalAmount").textContent = `$${subtotal}`;
    }

    // Checkout Back
    backToCartBtn.addEventListener("click", () => {
      checkoutModal.style.display = "none";
      cartModal.style.display = "block";
      updateProgressBar(2);
    });

    // 在 test.php 中找到 payBtn.addEventListener 這段程式碼
// 替換成以下內容：

// Checkout Pay - 提交訂單到資料庫
payBtn.addEventListener("click", async () => {
  const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
  const methodText = paymentMethod === 'credit' ? 'Credit Card' : 'Cash';
  const total = document.getElementById("totalAmount").textContent;

  // 檢查購物車是否為空
  if (cart.length === 0) {
    alert("Your cart is empty！");
    return;
  }

  // 詢問客戶姓名
  const customerName = prompt("Please enter your full name：");
  
  if (!customerName || customerName.trim() === "") {
    alert("Please enter a valid full name！");
    return;
  }

  // 準備訂單資料
  const orderData = {
    name: customerName.trim(),
    items: cart.map(item => ({
      name: item.name,
      price: item.price,
      quantity: item.quantity,
      ice: item.ice || '',
      sugar: item.sugar || '',
      note: item.note || ''
    })),
    payment_method: methodText
  };

  // 顯示載入中
  payBtn.disabled = true;
  payBtn.textContent = "Processing...";

  try {
    // 發送 AJAX 請求到後端
    const response = await fetch('submit_order.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(orderData)
    });

    const result = await response.json();

    if (result.success) {
      updateProgressBar(4);
      
      setTimeout(() => {
        alert(
          `Payment Successful! 🎉\n\n` +
          `Order ID：${result.order_id}\n` +
          `Customer：${customerName}\n` +
          `Payment Method：${methodText}\n` +
          `Total：${total}\n\n` +
          `Thank you for visiting!！`
        );
        
        // 清空購物車
        cart = [];
        renderCart();
        cartCountBadge.textContent = 0;
        checkoutModal.style.display = "none";
        updateProgressBar(1);
        
        // 恢復按鈕狀態
        payBtn.disabled = false;
        payBtn.textContent = "Confirm Payment";
      }, 300);
      
    } else {
      alert(`Order submission failed:${result.message}`);
      payBtn.disabled = false;
      payBtn.textContent = "Confirm Payment";
    }
    
  } catch (error) {
    console.error('錯誤：', error);
    alert(`An error occurred：${error.message}\nPlease check your connection or contact support.`);
    payBtn.disabled = false;
    payBtn.textContent = "Confirm Payment";
  }
});

    // Flip cards：用按鈕控制翻面，點背面翻回來
    const menuCards = document.querySelectorAll('.menu-card');
    menuCards.forEach(card => {
      const cardBack = card.querySelector('.card-back');
      if (cardBack) {
        cardBack.addEventListener('click', () => {
          card.classList.remove('flipped');
        });
      }
    });

    // Flip 按鈕
    document.querySelectorAll('.flip-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const container = btn.closest('.w3-container');
        if (!container) return;
        const card = container.querySelector('.menu-card');
        if (!card) return;
        card.classList.toggle('flipped');
      });
    });

    // Back to top button
    const backToTopBtn = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        backToTopBtn.classList.add("show");
      } else {
        backToTopBtn.classList.remove("show");
      }
    });
    backToTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // Search Bar
    const header = document.querySelector(".w3-main header");
    const searchIcon = document.getElementById("searchIcon");
    

    const searchWrapper = document.createElement("div");
    searchWrapper.style.position = "absolute";
    searchWrapper.style.top = "20px";
    searchWrapper.style.right = "60px";
    searchWrapper.style.display = "none";
    searchWrapper.style.alignItems = "center";
    searchWrapper.style.background = "white";
    searchWrapper.style.border = "3px solid gold";
    searchWrapper.style.borderRadius = "12px";
    searchWrapper.style.padding = "8px";
    searchWrapper.style.boxShadow = "0 4px 10px rgba(0,0,0,0.2)";
    searchWrapper.style.zIndex = "999";

    searchWrapper.innerHTML = `
      <input type="text" id="keywordInput" placeholder="Search foods..."
        style="padding:6px 8px;font-size:15px;border-radius:8px;border:2px solid gold;width:180px;">
      <button id="searchBtn" style="background:linear-gradient(45deg,#FFD700,#FF9AA2);border:none;
        color:white;padding:6px 10px;border-radius:8px;margin-left:6px;cursor:pointer;">Go</button>
      <button id="closeSearch" style="background:none;border:none;color:gray;font-size:18px;
        margin-left:6px;cursor:pointer;">❌</button>
      <span id="searchResultMsg" style="font-size:14px;color:#FF69B4;margin-left:8px;"></span>
    `;
    header.appendChild(searchWrapper);
    // 在 window.onload 裡面或是在創建 searchWrapper 之後加入：
document.getElementById("searchBtn").addEventListener("click", searchKeyword);

// 也可以讓按 Enter 鍵時搜尋
document.getElementById("keywordInput").addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    searchKeyword();
  }
});


    searchIcon.addEventListener("click", () => {
      if (searchWrapper.style.display === "none") {
        searchWrapper.style.display = "flex";
        document.getElementById("keywordInput").focus();
      } else {
        searchWrapper.style.display = "none";
      }
    });

// ✅ 正確的做法（只移除 <mark> 標籤，保留其他）
document.getElementById("closeSearch").addEventListener("click", () => {
  searchWrapper.style.display = "none";
  document.getElementById("keywordInput").value = "";
  document.getElementById("searchResultMsg").textContent = "";

  // 只移除 <mark> 標籤，保留原本的 <br> 和 <b>
  document.querySelectorAll("section.w3-row-padding .w3-container > p:first-of-type").forEach(p => {
    // 用正則表達式移除 <mark> 標籤，但保留內容
    p.innerHTML = p.innerHTML.replace(/<mark[^>]*>(.*?)<\/mark>/gi, '$1');
  });
});

// 搜尋功能
function searchKeyword() {
  const keyword = document.getElementById("keywordInput").value.trim();
  const resultMsg = document.getElementById("searchResultMsg");
  resultMsg.textContent = "";
  const items = document.querySelectorAll("section.w3-row-padding .w3-container > p:first-of-type");
  
  // 先清除之前的高亮（只移除 mark 標籤）
  items.forEach(p => {
    p.innerHTML = p.innerHTML.replace(/<mark[^>]*>(.*?)<\/mark>/gi, '$1');
  });
  
  if (!keyword) return;
  
  // 跳脫特殊字元，避免正則錯誤
  const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const regex = new RegExp(`(${escapedKeyword})`, "gi");
  
  let matchCount = 0;
  let firstMatch = null;
  
  items.forEach(p => {
    const html = p.innerHTML;
    if (regex.test(html)) {
      matchCount++;
      p.innerHTML = html.replace(regex, `<mark style="background-color:yellow;">$1</mark>`);
      if (!firstMatch) firstMatch = p;
    }
  });
  
  if (matchCount > 0) {
    resultMsg.textContent = `Found ${matchCount} result${matchCount > 1 ? 's' : ''}`;
    if (firstMatch) {
      firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  } else {
    resultMsg.textContent = "No match found.";
  }
}

// 關閉搜尋
document.getElementById("closeSearch").addEventListener("click", () => {
  searchWrapper.style.display = "none";
  document.getElementById("keywordInput").value = "";
  document.getElementById("searchResultMsg").textContent = "";
  
  // 只移除 <mark> 標籤，保留其他
  document.querySelectorAll("section.w3-row-padding .w3-container > p:first-of-type").forEach(p => {
    p.innerHTML = p.innerHTML.replace(/<mark[^>]*>(.*?)<\/mark>/gi, '$1');
  });
});
    // 🎮 Game intro logic
    function initGameIntro() {
      const intro = document.getElementById("gameIntro");
      if (!intro) return;

      const playfield = document.getElementById("giPlayfield");
      const player = document.getElementById("giPlayer");
      const scoreText = document.getElementById("giScoreText");
      const timeText = document.getElementById("giTimeText");
      const progressFill = document.getElementById("giProgressFill");
      const clearOverlay = document.getElementById("giClearOverlay");
      const failOverlay = document.getElementById("giFailOverlay");

      const startBtn = document.getElementById("giStartBtn");
      const startBtnMobile = document.getElementById("giStartBtnMobile");
      const skipBtn = document.getElementById("giSkipBtn");
      const retryBtn = document.getElementById("giRetryBtn");
      const failSkipBtn = document.getElementById("giFailSkipBtn");

      let playerX = 50;
      let moveLeft = false;
      let moveRight = false;
      const leftBtn = document.getElementById("giLeftBtn");
      const rightBtn = document.getElementById("giRightBtn");

      const targetScore = 8;
      let score = 0;
      let timeLeft = 20;

      let foods = [];
      let spawnInterval = null;
      let gameLoopInterval = null;
      let timerInterval = null;
      let playing = false;

      const foodEmojis = ["🍕","🍓","🥤","🍰","🍟","🌮","🍩","🍜"];

      function setPlayerPosition() {
        player.style.left = playerX + "%";
      }

      function spawnFood() {
        const food = document.createElement("div");
        food.className = "gi-food";
        const emoji = foodEmojis[Math.floor(Math.random() * foodEmojis.length)];
        food.textContent = emoji;

        const startX = 10 + Math.random() * 80;
        food.style.left = startX + "%";
        food.style.top = "-10%";

        playfield.appendChild(food);
        foods.push({ el: food, y: -10, speed: 0.5 + Math.random() * 1.2 });
      }

      function rectsOverlap(r1, r2) {
        return !(
          r1.right < r2.left ||
          r1.left > r2.right ||
          r1.bottom < r2.top ||
          r1.top > r2.bottom
        );
      }

      function updateGame() {
        if (moveLeft) playerX -= 1.7;
        if (moveRight) playerX += 1.7;
        if (playerX < 5) playerX = 5;
        if (playerX > 95) playerX = 95;
        setPlayerPosition();

        const playerRect = player.getBoundingClientRect();

        foods.forEach((f, index) => {
          f.y += f.speed;
          f.el.style.top = f.y + "%";

          const foodRect = f.el.getBoundingClientRect();
          if (rectsOverlap(playerRect, foodRect)) {
            score++;
            if (score > targetScore) score = targetScore;
            scoreText.textContent = `${score} / ${targetScore}`;
            const percent = (score / targetScore) * 100;
            progressFill.style.width = percent + "%";

            f.el.classList.add("gi-food-pop");
            setTimeout(() => f.el.remove(), 200);
            foods.splice(index, 1);
          }

          if (f.y > 120) {
            f.el.remove();
            foods.splice(index, 1);
          }
        });

        if (score >= targetScore) {
          endGame(true);
        }
      }

      function updateTimer() {
        timeLeft--;
        if (timeLeft < 0) timeLeft = 0;
        timeText.textContent = timeLeft + "s";
        if (timeLeft <= 0) {
          endGame(false);
        }
      }

      function startGame() {
        if (playing) return;
        playing = true;
        score = 0;
        timeLeft = 20;
        scoreText.textContent = `0 / ${targetScore}`;
        timeText.textContent = timeLeft + "s";
        progressFill.style.width = "0%";

        foods.forEach(f => f.el.remove());
        foods = [];

        clearOverlay.classList.remove("gi-show");
        failOverlay.classList.remove("gi-show");

        spawnInterval = setInterval(spawnFood, 600);
        gameLoopInterval = setInterval(updateGame, 20);
        timerInterval = setInterval(updateTimer, 1000);
      }

      function stopAllTimers() {
        if (spawnInterval) clearInterval(spawnInterval);
        if (gameLoopInterval) clearInterval(gameLoopInterval);
        if (timerInterval) clearInterval(timerInterval);
        spawnInterval = gameLoopInterval = timerInterval = null;
      }

      function endGame(success) {
        if (!playing) return;
        playing = false;
        stopAllTimers();

        if (success) {
          clearOverlay.classList.add("gi-show");
          setTimeout(() => {
            intro.classList.add("gi-fadeout");
            setTimeout(() => {
              intro.remove();
              document.body.style.overflow = "auto";
            }, 800);
          }, 1300);
        } else {
          failOverlay.classList.add("gi-show");
        }
      }

      function skipIntro() {
        stopAllTimers();
        intro.classList.add("gi-fadeout");
        setTimeout(() => {
          intro.remove();
          document.body.style.overflow = "auto";
        }, 600);
      }

      // 📱 觸控 / 滑鼠控制（給小螢幕用）
      if (leftBtn && rightBtn) {
        const startLeft = (e) => {
          e.preventDefault();
          moveLeft = true;
          moveRight = false;
        };
        const endLeft = (e) => {
          e.preventDefault();
          moveLeft = false;
        };

        const startRight = (e) => {
          e.preventDefault();
          moveRight = true;
          moveLeft = false;
        };
        const endRight = (e) => {
          e.preventDefault();
          moveRight = false;
        };

        // 左邊按鈕
        leftBtn.addEventListener("touchstart", startLeft);
        leftBtn.addEventListener("touchend", endLeft);
        leftBtn.addEventListener("touchcancel", endLeft);
        leftBtn.addEventListener("mousedown", startLeft);
        leftBtn.addEventListener("mouseup", endLeft);
        leftBtn.addEventListener("mouseleave", endLeft);

        // 右邊按鈕
        rightBtn.addEventListener("touchstart", startRight);
        rightBtn.addEventListener("touchend", endRight);
        rightBtn.addEventListener("touchcancel", endRight);
        rightBtn.addEventListener("mousedown", startRight);
        rightBtn.addEventListener("mouseup", endRight);
        rightBtn.addEventListener("mouseleave", endRight);
      }

      document.addEventListener("keydown", e => {
        if (e.key === "ArrowLeft" || e.key === "a" || e.key === "A") {
          moveLeft = true;
        }
        if (e.key === "ArrowRight" || e.key === "d" || e.key === "D") {
          moveRight = true;
        }
      });

      document.addEventListener("keyup", e => {
        if (e.key === "ArrowLeft" || e.key === "a" || e.key === "A") {
          moveLeft = false;
        }
        if (e.key === "ArrowRight" || e.key === "d" || e.key === "D") {
          moveRight = false;
        }
      });

      // 桌機版 START
      if (startBtn) startBtn.addEventListener("click", startGame);
      // 手機版 START
      if (startBtnMobile) startBtnMobile.addEventListener("click", startGame);

      skipBtn.addEventListener("click", skipIntro);
      if (retryBtn) retryBtn.addEventListener("click", () => {
        failOverlay.classList.remove("gi-show");
        startGame();
      });
      if (failSkipBtn) failSkipBtn.addEventListener("click", skipIntro);

      document.body.style.overflow = "hidden";
      setPlayerPosition();
    }

    // Login Box (JS)
    function initLoginBox() {
  const loginForm = document.getElementById("loginForm");
  const accInput = document.getElementById("accInput");
  const pwdInput = document.getElementById("pwdInput");
  const msg = document.getElementById("loginMsg");

  if (!loginForm) return;

  loginForm.addEventListener("submit", function(e) {
    const acc = accInput.value.trim();
    const pwd = pwdInput.value.trim();

    // ❗只有沒填才擋
    if (acc === "" || pwd === "") {
      e.preventDefault();
      msg.style.color = "red";
      msg.textContent = "Please enter both account and password ❌";
    }
    // ✅ 有填 → 不擋 → 表單會送到 PHP
  });
}

    // Lightbox：照片放大邏輯
    function initLightbox() {
      const lightbox = document.getElementById('lightbox');
      const lightboxImg = document.getElementById('lightbox-img');
      const lightboxClose = document.querySelector('#lightbox .close');
      if (!lightbox || !lightboxImg || !lightboxClose) return;

      document.querySelectorAll('.zoomable').forEach(img => {
        img.addEventListener('click', function(e){
          e.stopPropagation();
          const src = this.getAttribute('data-img') || this.src;
          lightboxImg.src = src;
          lightbox.style.display = 'flex';
        });
      });

      lightboxClose.addEventListener('click', function(){
        lightbox.style.display = 'none';
      });

      lightbox.addEventListener('click', function(e){
        if(e.target === lightbox){
          lightbox.style.display = 'none';
        }
      });
    }

    window.onload = function() {
      document.getElementById("myBtn").click();
      document.getElementById("enter").onclick = enter;
      enterGreeting();
      initGameIntro();
      initLoginBox();
      initLightbox();
    };
  </script>
</body>
</html>