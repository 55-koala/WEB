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
document.getElementById("myBtn").click();

// Greeting
function enterGreeting() {
  var greet1 = document.getElementById("greeting1");
  var greet2 = document.getElementById("welcome");
  var greet=document.getElementById("greeting");
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

function enter(){
  document.getElementById("greeting").style.display="none";
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

// (*** 火箭動態移動邏輯 ***) 
const backToTopBtn = document.getElementById("backToTop");
const orderSection = document.querySelector(".progress-container"); // 滾動目標
let scrollPositionBeforeOrder = 0; // 儲存滾動位置

// Add to cart: open note modal (*** 已修改：使用動態JS ***)
addButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    
    // 1. 儲存位置並滾動
    scrollPositionBeforeOrder = window.scrollY;
    orderSection.scrollIntoView({ behavior: 'smooth' });

    // 2. 動態計算火箭新位置 (新邏輯)
    const orderSectionHeight = orderSection.offsetHeight; 
    // 設定火箭的新位置 = 進度條高度 + 20px 間距
    backToTopBtn.style.bottom = `${orderSectionHeight + 20}px`;

    // (原本的邏輯)
    selectedItem = {
      name: btn.dataset.name,
      price: btn.dataset.price,
      img: btn.dataset.img,
      hasOptions: btn.dataset.hasOptions === "true"
    };
    const iceGroup = noteModal.querySelector('.option-group:nth-child(1)');
    const sugarGroup = noteModal.querySelector('.option-group:nth-child(2)');
    if (selectedItem.hasOptions) {
      iceGroup.style.display = "block";
      sugarGroup.style.display = "block";
    } else {
      iceGroup.style.display = "none";
      sugarGroup.style.display = "none";
    }
    noteModal.style.display = "block";
  });
});

// Confirm add item (*** 已修改：恢復火箭位置 ***)
confirmAddBtn.addEventListener("click", () => {
  const note = document.getElementById("noteText").value;
  let ice = "", sugar = "";
  if (selectedItem && selectedItem.hasOptions) {
    ice = noteModal.querySelector('input[name="ice1"]:checked').value;
    sugar = noteModal.querySelector('input[name="sugar1"]:checked').value;
  }
  if (selectedItem) {
    cart.push({ ...selectedItem, note, ice, sugar });
    cartCountBadge.textContent = cart.length;
  }
  document.getElementById("noteText").value = "";
  noteModal.style.display = "none";
  updateProgressBar(1);

  // (新邏輯) 恢復火箭原始位置
  backToTopBtn.style.bottom = '80px'; // 恢復到 CSS 檔案中的原始設定
});

// Cancel add (*** 已修改：恢復火箭位置 ***)
cancelAddBtn.addEventListener("click", () => {
  // 1. 滾動回使用者原本的位置
  if (scrollPositionBeforeOrder >= 0) { 
    window.scrollTo({
      top: scrollPositionBeforeOrder,
      behavior: 'smooth'
    });
  }

  // 2. (新邏輯) 恢復火箭原始位置
  backToTopBtn.style.bottom = '80px'; // 恢復到 CSS 檔案中的原始設定

  // (原本的邏輯)
  document.getElementById("noteText").value = "";
  noteModal.style.display = "none";
});

// Render cart
function renderCart() {
  cartList.innerHTML = "";
  cart.forEach((item, index) => {
    const div = document.createElement("div");
    div.classList.add("cart-item");
    div.innerHTML = `
      <img src="${item.img}">
      <div class="cart-item-info">
        <b>${item.name}</b><br>
        $${item.price}<br>
        Ice: ${item.ice || '-'}<br>
        Sugar: ${item.sugar || '-'}<br>
        Note: ${item.note || ''}
      </div>
      <button class="remove-btn" data-index="${index}">delete</button>
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

// Delete item in cart
cartList.addEventListener("click", (e) => {
  if (e.target.classList.contains("remove-btn")) {
    const index = parseInt(e.target.dataset.index, 10);
    cart.splice(index, 1);
    cartCountBadge.textContent = cart.length;
    renderCart();
  }
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
  cartModal.style.display = "none";
  checkoutModal.style.display = "block";
  updateProgressBar(3);
});

// Checkout Back
backToCartBtn.addEventListener("click", () => {
  checkoutModal.style.display = "none";
  cartModal.style.display = "block";
  updateProgressBar(2);
});

// Checkout Pay → finish
payBtn.addEventListener("click", () => {
  updateProgressBar(4);
  setTimeout(() => {
    alert("Your order has been placed. Thank you for dining with us!");
    cart = [];
    renderCart();
    cartCountBadge.textContent = 0;
    checkoutModal.style.display = "none";
    updateProgressBar(1);
  }, 300);
});

// Flip cards
const menuCards = document.querySelectorAll('.menu-card');
menuCards.forEach(card => {
  const cardFront = card.querySelector('.card-front');
  const cardBack = card.querySelector('.card-back');
  if (cardFront) {
    cardFront.addEventListener('click', () => {
      card.classList.toggle('flipped');
    });
  }
  if (cardBack) {
    cardBack.addEventListener('click', () => {
      card.classList.remove('flipped');
    });
  }
});

// Back to top button 
// (變數宣告已移到上面)
window.addEventListener("scroll", () => {
  if (window.pageYOffset > 300) {
    backToTopBtn.classList.add("show");
  } else {
    backToTopBtn.classList.remove("show");
  }
});

backToTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});


// === 0. (新) 儲存所有品項的「原始 HTML」 ===
// 這段程式碼會在頁面載入時，只執行一次
const searchItems = document.querySelectorAll("section.w3-row-padding .w3-container > p:first-of-type");
const originalItemHTML = new Map();
searchItems.forEach(p => {
    originalItemHTML.set(p, p.innerHTML);
});
// ==========================================


const header = document.querySelector(".w3-main header");
const searchIcon = document.getElementById("searchIcon");

// === 1. 在 header 內加入搜尋欄容器 ===
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

// === 2. 顯示 / 隱藏 搜尋欄 (*** 關閉按鈕已更新 ***) ===
searchIcon.addEventListener("click", () => {
  if (searchWrapper.style.display === "none") {
    searchWrapper.style.display = "flex";
    document.getElementById("keywordInput").focus();
  } else {
    searchWrapper.style.display = "none";
  }
});

document.getElementById("closeSearch").addEventListener("click", () => {
  searchWrapper.style.display = "none";
  document.getElementById("keywordInput").value = "";
  document.getElementById("searchResultMsg").textContent = "";

  // --- (已修正) 關閉時，恢復成原始 HTML ---
  searchItems.forEach(p => {
    p.innerHTML = originalItemHTML.get(p);
  });
});

// === 3. 搜尋邏輯 (*** 已修正：不會弄亂格式 ***) ===
function searchKeyword() {
  const keyword = document.getElementById("keywordInput").value.trim();
  const resultMsg = document.getElementById("searchResultMsg");
  resultMsg.textContent = "";

  // --- 1. (已修正) 清除標註：恢復成原始 HTML ---
  searchItems.forEach(p => {
    p.innerHTML = originalItemHTML.get(p);
  });

  // 如果關鍵字為空，就到此為止 (只做清除)
  if (!keyword) return;

  const regex = new RegExp(`(${keyword})`, "gi");
  
  let matchCount = 0;
  let firstMatch = null;

  // --- 2. (已修正) 搜尋與標記 ---
  searchItems.forEach(p => {
    const originalHTML = originalItemHTML.get(p); // 取得原始 HTML (包含 <b> 等)
    const originalText = p.textContent; // 取得純文字 (只用來比對)

    if (originalText.match(regex)) {
      matchCount++;
      
      // (重點) 在「原始 HTML」上進行替換，這樣 <b> 和 <br> 標籤就會被保留
      const newHTML = originalHTML.replace(regex, `<mark style="background-color:yellow;">$1</mark>`);
      p.innerHTML = newHTML;
      
      if (!firstMatch) firstMatch = p;
    }
  });

  // 3. 顯示結果
  if (matchCount > 0) {
    resultMsg.textContent = `Found ${matchCount} result${matchCount > 1 ? 's' : ''}`;
    firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
  } else {
    resultMsg.textContent = "No match found.";
  }
}

// === 4. 事件設定 (*** Esc 鍵已更新 ***) ===
document.getElementById("searchBtn").addEventListener("click", searchKeyword);

document.addEventListener("keydown", e => {
  if (e.key === "Enter" && searchWrapper.style.display === "flex") {
    searchKeyword();
  }
  if (e.key === "Escape" && searchWrapper.style.display === "flex") {
    searchWrapper.style.display = "none";
    document.getElementById("keywordInput").value = "";
    document.getElementById("searchResultMsg").textContent = "";
    
    // --- (已修正) 按 Esc 時，恢復成原始 HTML ---
     searchItems.forEach(p => {
        p.innerHTML = originalItemHTML.get(p);
    });
  }
});

// On load
window.onload = function() {
  document.getElementById("enter").onclick = enter;
  enterGreeting();
};