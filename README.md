<!DOCTYPE html>
<html>
<head>
<title>Kids Food Website</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6,.w3-wide {
    font-family: 'Baloo 2', cursive;
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
body {
    background: #FFF0F5; 
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

.w3-row-padding .w3-container {
    text-align: center;
    margin-bottom: 24px;
    background-color: #FFEAF2;
    border-radius: 18px;
    padding: 16px 10px 18px;
}

.w3-row-padding .w3-container p {
    color: #FF4500;
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 10px;
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
button.add-btn:hover {
    background: linear-gradient(45deg, #FFB347, #FF9AA2);
}
button.cancel-btn {
    background: linear-gradient(45deg, #FF6B6B, #a8d5f2);
    color:white;
}
button.cancel-btn:hover {
    background: linear-gradient(45deg, #FFB347, #FF6B6B);
}
#noteModal {
    border:3px solid #bbe8f9;
    background: #fdfdfd;
    padding:30px;
    border-radius:20px;
    display:none;
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    z-index:10;
}
#noteModal textarea {
    border-radius:12px;
    border:2px solid #FFB347;
    padding:40px;
    width:100%;
}
#cartModal {
    border:3px solid #FFB347;
    background: #FFF0F5;
    border-radius:20px;
    padding:15px;
    display:none;
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    width:90%;
    max-width:500px;
    z-index:10;
}
#cartList div {
    border:2px dashed #FFB347;
    border-radius:12px;
    padding:8px;
    margin-bottom:8px;
    background: #FFE4B5;
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
#cartCountBadge {
    background: #FF6B81;
    color:white;
    font-weight:bold;
    font-size:13px;
    padding:2px 6px;
    border-radius:50%;
    vertical-align:top;
}
</style>
</head>
<body class="w3-content" style="max-width:1200px">


<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top"
     style="z-index:3;width:250px" id="mySidebar">
  <div class="w3-container w3-display-container w3-padding-16">
    <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
    <h3 class="w3-wide"><b>LOGO</b></h3>
  </div>
  <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
    <a href="#" class="w3-bar-item w3-button">Shirts</a>
    <a href="#" class="w3-bar-item w3-button">Dresses</a>
    <a onclick="myAccFunc()" href="javascript:void(0)"
       class="w3-button w3-block w3-white w3-left-align" id="myBtn">
      Jeans <i class="fa fa-caret-down"></i>
    </a>
    <div id="demoAcc" class="w3-bar-block w3-hide w3-padding-large w3-medium">
      <a href="#" class="w3-bar-item w3-button w3-light-grey">
        <i class="fa fa-caret-right w3-margin-right"></i>Skinny
      </a>
      <a href="#" class="w3-bar-item w3-button">Relaxed</a>
      <a href="#" class="w3-bar-item w3-button">Bootcut</a>
      <a href="#" class="w3-bar-item w3-button">Straight</a>
    </div>
    <a href="#" class="w3-bar-item w3-button">Jackets</a>
    <a href="#" class="w3-bar-item w3-button">Gymwear</a>
    <a href="#" class="w3-bar-item w3-button">Blazers</a>
    <a href="#" class="w3-bar-item w3-button">Shoes</a>
  </div>
  <a href="#footer" class="w3-bar-item w3-button w3-padding">Contact</a>
</nav>

<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
  <div class="w3-bar-item w3-padding-24 w3-wide">LOGO</div>
  <a href="javascript:void(0)"
     class="w3-bar-item w3-button w3-padding-24 w3-right"
     onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>

<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer"
     title="close side menu" id="myOverlay"></div>


<div class="w3-main" style="margin-left:250px">
  <div class="w3-hide-large" style="margin-top:83px"></div>
  

  <header class="w3-container w3-xlarge">
    <p class="w3-left">Foods</p>
    <p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right" id="cartIcon"></i>
      <span id="cartCountBadge">0</span>
      <i class="fa fa-search"></i>
    </p>
  </header>

  <div class="w3-display-container w3-container">
    <img src="./3.jpg" alt="Foods"
         style="width:100%;height:600px;object-fit:cover;border-radius:20px;">
    <div class="w3-display-topleft w3-text-white"
         style="padding:24px 48px;">
      <h2 class="w3-hide-small"
          style="white-space:nowrap;font-size:clamp(24px,6vw,55px);margin:0;">
        Sauteed Short Rib With Spicy Cream
      </h2>
    </div>
  </div>

  <div class="w3-container w3-text-grey" id="jeans">
    <p>20 items</p>
  </div>

  <!-- 20 items, 4 per row -->
  <div class="w3-row-padding">

    <!-- 1 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./1.jpg" alt="">
        <p>Mexican spicy and Tangy Chicken Pasta<br><b>$330</b></p>
        <button class="add-btn" data-name="Mexican spicy and Tangy Chicken Pasta" data-price="330" data-img="./1.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 2 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./5.jpg" alt="">
        <p>Charcoal-Grilled Pork Tongue Rice<br><b>$185</b></p>
        <button class="add-btn" data-name="Charcoal-Grilled Pork Tongue Rice" data-price="185" data-img="./5.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 3 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./8.jpg" alt="">
        <p>Amazing Eggs Benedict-Seared Fish<br><b>$480</b></p>
        <button class="add-btn" data-name="Amazing Eggs Benedict-Seared Fish" data-price="480" data-img="./8.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 4 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./9.jpg" alt="">
        <p>Kung Pao Chicken With Cashews<br><b>$258</b></p>
        <button class="add-btn" data-name="Kung Pao Chicken With Cashews" data-price="258" data-img="./9.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 5 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./7.jpg" alt="">
        <p>Cheese Monopoly Pizza<br><b>$230</b></p>
        <button class="add-btn" data-name="Cheese Monopoly Pizza" data-price="230" data-img="./7.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 6 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./6.jpg" alt="">
        <p>Hainanese Chicken Rice<br><b>$110</b></p>
        <button class="add-btn" data-name="Hainanese Chicken Rice" data-price="110" data-img="./6.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 7 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./4.jpg" alt="">
        <p>Padyhai Gung Yang<br><b>$280</b></p>
        <button class="add-btn" data-name="Padyhai Gung Yang" data-price="280" data-img="./4.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 8 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./2.jpg" alt="">
        <p>Thai Spicy and Sour Noodles<br><b>$200</b></p>
        <button class="add-btn" data-name="Thai Spicy and Sour Noodles" data-price="200" data-img="./2.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 9 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./10.jpg" alt="">
        <p>Taro Sago With Coconut Milk<br><b>$100</b></p>
        <button class="add-btn" data-name="Taro Sago With Coconut Milk" data-price="100" data-img="./10.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 10 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./11.jpg" alt="">
        <p>Cream Brulee<br><b>$110</b></p>
        <button class="add-btn" data-name="Cream Brulee" data-price="110" data-img="./11.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 11 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./12.jpg" alt="">
        <p>Basque Burnt Cheesecake<br><b>$180</b></p>
        <button class="add-btn" data-name="Basque Burnt Cheesecake" data-price="180" data-img="./12.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 12 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./15.jpg" alt="">
        <p>Earl Grey Strawberry Chiffon Cake<br><b>$180</b></p>
        <button class="add-btn" data-name="Earl Grey Strawberry Chiffon Cake" data-price="180" data-img="./15.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 13 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./17.jpg" alt="">
        <p>Salted Cream Roll Cake<br><b>$180</b></p>
        <button class="add-btn" data-name="Salted Cream Roll Cake" data-price="180" data-img="./17.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 14 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./20.jpg" alt="">
        <p>Pistachio Mille Crepe Cake<br><b>$250</b></p>
        <button class="add-btn" data-name="Pistachio Mille Crepe Cake" data-price="250" data-img="./20.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 15 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./19.jpg" alt="">
        <p>Sakura Mochi Frappe<br><b>$100</b></p>
        <button class="add-btn" data-name="Sakura Mochi Frappe" data-price="100" data-img="./19.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 16 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./21.jpg" alt="">
        <p>Hong Kong Style Butter Pineapple Bun<br><b>$130</b></p>
        <button class="add-btn" data-name="Hong Kong Style Butter Pineapple Bun" data-price="130" data-img="./21.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 17 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./14.jpg" alt="">
        <p>Cha Yen<br><b>$95</b></p>
        <button class="add-btn" data-name="Cha Yen" data-price="95" data-img="./14.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 18 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./16.jpg" alt="">
        <p>Brown Sugar Latte<br><b>$150</b></p>
        <button class="add-btn" data-name="Brown Sugar Latte" data-price="150" data-img="./16.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 19 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./13.jpg" alt="">
        <p>Peach Berry Cream Frappe<br><b>$150</b></p>
        <button class="add-btn" data-name="Peach Berry Cream Frappe" data-price="150" data-img="./13.jpg">Add to Cart</button>
      </div>
    </div>

    <!-- 20 -->
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <img src="./18.jpg" alt="">
        <p>Honey Lime Sparkling Cooler<br><b>$130</b></p>
        <button class="add-btn" data-name="Honey Lime Sparkling Cooler" data-price="130" data-img="./18.jpg">Add to Cart</button>
      </div>
    </div>

  </div>

  <!-- Subscribe -->
  <div class="subscribe-section">
    <h2>Join our fun newsletter!</h2>
    <p>Get special offers and colorful surprises:</p>
    <input type="text" placeholder="Enter your e-mail">
  </div>

  <!-- Footer -->
  <footer class="w3-padding-64 w3-small w3-center" id="footer">
    <div class="w3-row-padding">
      <div class="w3-col s4">
        <h4>Contact</h4>
        <p>Questions? Go ahead.</p>
      </div>
      <div class="w3-col s4">
        <h4>About</h4>
        <p><a href="#">About us</a></p>
        <p><a href="#">Support</a></p>
      </div>
      <div class="w3-col s4 w3-justify">
        <h4>Store</h4>
        <p><i class="fa fa-fw fa-map-marker"></i> Company Name</p>
        <p><i class="fa fa-fw fa-phone"></i> 0044123123</p>
        <p><i class="fa fa-fw fa-envelope"></i> ex@mail.com</p>
      </div>
    </div>
  </footer>

  <div class="w3-black w3-center w3-padding-24">
    Powered by <a href="https://www.w3schools.com/w3css/default.asp" class="w3-hover-opacity">w3.css</a>
  </div>
</div>

<!-- Note Modal -->
<div id="noteModal">
  <textarea id="noteText" placeholder="Enter note..."></textarea><br><br>
  <button class="add-btn" id="confirmAdd">Add</button>
  <button class="cancel-btn" id="cancelAdd">Cancel</button>
</div>

<!-- Cart Modal -->
<div id="cartModal">
  <h3>Your Cart</h3>
  <div id="cartList"></div>
  <button class="cancel-btn" id="closeCart">Close</button>
</div>

<script>
function myAccFunc() {
  var x = document.getElementById("demoAcc");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}
document.getElementById("myBtn").click();

function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

let cart = [];
let selectedItem = null;
const addButtons = document.querySelectorAll(".add-btn");
const noteModal = document.getElementById("noteModal");
const cartModal = document.getElementById("cartModal");
const cartList = document.getElementById("cartList");
const cartCountBadge = document.getElementById("cartCountBadge");

addButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    selectedItem = {
      name: btn.dataset.name,
      price: btn.dataset.price,
      img: btn.dataset.img
    };
    noteModal.style.display = "block";
  });
});

document.getElementById("confirmAdd").addEventListener("click", () => {
  const note = document.getElementById("noteText").value;
  cart.push({ ...selectedItem, note });
  document.getElementById("noteText").value = "";
  noteModal.style.display = "none";
  cartCountBadge.textContent = cart.length;
});

document.getElementById("cancelAdd").addEventListener("click", () => {
  document.getElementById("noteText").value = "";
  noteModal.style.display = "none";
});

document.getElementById("cartIcon").addEventListener("click", () => {
  cartList.innerHTML = "";
  cart.forEach(item => {
    const div = document.createElement("div");
    div.innerHTML =
      `<img src="${item.img}">
       <div><b>${item.name}</b><br>$${item.price}<br>Note: ${item.note || ''}</div>`;
    cartList.appendChild(div);
  });
  cartModal.style.display = "block";
});

document.getElementById("closeCart").addEventListener("click", () => {
  cartModal.style.display = "none";
});
</script>

</body>
</html>
