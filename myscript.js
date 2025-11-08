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
addButtons.forEach(btn=>{
    btn.addEventListener("click",()=>{
        selectedItem = {
            name: btn.dataset.name,
            price: btn.dataset.price,
            img: btn.dataset.img
        };
        noteModal.style.display="block";
    });
});
document.getElementById("confirmAdd").addEventListener("click",()=>{
    const note=document.getElementById("noteText").value;
    cart.push({...selectedItem,note});
    document.getElementById("noteText").value="";
    noteModal.style.display="none";
    cartCountBadge.textContent=cart.length;
});
document.getElementById("cancelAdd").addEventListener("click",()=>{
    document.getElementById("noteText").value="";
    noteModal.style.display="none";
});
document.getElementById("cartIcon").addEventListener("click",()=>{
    cartList.innerHTML="";
    cart.forEach(item=>{
        const div=document.createElement("div");
        div.innerHTML=`<img src="${item.img}"><div><b>${item.name}</b><br>$${item.price}<br>Note: ${item.note || ''}</div>`;
        cartList.appendChild(div);
    });
    cartModal.style.display="block";
});
document.getElementById("closeCart").addEventListener("click",()=>{cartModal.style.display="none";});
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
window.onload=function(){
    var enterButton=document.getElementById("enter");
    enterButton.onclick=enter;
    enterGreeting();
}





