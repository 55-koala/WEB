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
const addButtons = document.querySelectorAll(".w3-row-padding .add-btn");
const noteModal = document.getElementById("noteModal");
const cartModal = document.getElementById("cartModal");
const cartList = document.getElementById("cartList");
const cartCountBadge = document.getElementById("cartCountBadge");

addButtons.forEach(btn => {
  btn.addEventListener("click", () => {
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

document.getElementById("confirmAdd").addEventListener("click", () => {
  const note = document.getElementById("noteText").value;
  let ice = "", sugar = "";
  if (selectedItem.hasOptions) {
    ice = noteModal.querySelector('input[name="ice1"]:checked').value;
    sugar = noteModal.querySelector('input[name="sugar1"]:checked').value;
  }
  cart.push({ ...selectedItem, note,ice, sugar });
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
       <div><b>${item.name}</b><br>$${item.price}<br>Ice: ${item.ice || '-'}<br>
     Sugar: ${item.sugar || '-'}<br>Note: ${item.note || ''}</div>`;
    cartList.appendChild(div);
  });
  cartModal.style.display = "block";
});

document.getElementById("closeCart").addEventListener("click", () => {
  cartModal.style.display = "none";
});
function enterGreeting(){
    var greet1=document.getElementById("greeting1");
    var greet=document.getElementById("greeting");
    var greet2=document.getElementById("welcome");
    var now = new Date().getHours();
    if(now>=5 && now<12){
        greet1.innerHTML="Good Morning!";
    }else if(now>=12 && now<17){
        greet1.innerHTML="Good Afternoon!";
        greet.style.background="#60e68f";
    }else if(now>=17 && now<22){
        greet1.innerHTML="Good Evening!";
        greet.style.background="#ff941b";
    }else{
        greet1.innerHTML="Time to sleep!";
        greet2.innerHTML="See you tomorrow!";
        greet.style.background="#1a327b";
    }
}

function enter(){
    var close=document.getElementById("greeting");
    close.style.display="none";
}
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
window.onload=function(){
    var enterButton=document.getElementById("enter");
    enterButton.onclick=enter;
    enterGreeting();
}



// 當前步驟變數
let currentStep = 1;

// 更新進度條函數
function updateProgressBar(step) {
  currentStep = step;
  
  // 取得所有步驟元素
  const steps = [
    { 
      icon: document.getElementById("step1"), 
      label: document.querySelector(".progress-step:nth-child(2) .step-label") 
    },
    { 
      icon: document.getElementById("step2"), 
      label: document.querySelector(".progress-step:nth-child(3) .step-label") 
    },
    { 
      icon: document.getElementById("step3"), 
      label: document.querySelector(".progress-step:nth-child(4) .step-label") 
    },
    { 
      icon: document.getElementById("step4"), 
      label: document.querySelector(".progress-step:nth-child(5) .step-label") 
    }
  ];
  
  // 更新連接線填充寬度
  const progressFill = document.getElementById("progressFill");
  progressFill.style.width = ((step - 1) / 3 * 100) + "%";
  
  // 更新每個步驟的狀態
  steps.forEach((s, i) => {
    s.icon.classList.remove("active", "completed");
    s.label.classList.remove("active", "completed");
    
    if (i + 1 < step) {
      // 已完成的步驟
      s.icon.classList.add("completed");
      s.label.classList.add("completed");
    } else if (i + 1 === step) {
      // 當前步驟
      s.icon.classList.add("active");
      s.label.classList.add("active");
    }
  });
}






// 步驟 1: 添加商品到購物車後
document.getElementById("confirmAdd").addEventListener("click", () => {
  // ... 添加商品邏輯 ...
  updateProgressBar(1);  // 回到點餐步驟
});

// 步驟 2: 打開購物車時
document.getElementById("cartIcon").addEventListener("click", () => {
  // ... 顯示購物車 ...
  updateProgressBar(2);  // 進入確認餐點步驟
});

// 步驟 3: 點擊結帳按鈕時
document.getElementById("checkoutBtn").addEventListener("click", () => {
  updateProgressBar(3);  // 進入結帳步驟
  
  setTimeout(() => {
    if (confirm(`Confirm payment?`)) {
      updateProgressBar(4);  // 進入完成步驟
      // ... 完成付款邏輯 ...
    } else {
      updateProgressBar(2);  // 取消則回到確認餐點
    }
  }, 500);
});

// 關閉購物車時
document.getElementById("closeCart").addEventListener("click", () => {
  // ... 關閉購物車邏輯 ...
  updateProgressBar(1);  // 回到點餐步驟
});







