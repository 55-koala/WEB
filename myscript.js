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
function enterGreeting(){
    var greet1=document.getElementById("greeting1");
    var greet=document.getElementById("greeting");
    var greet2=document.getElementById("welcome");
    var now = new Date().getHours();
    if(now>=5 && now<12){
        greet1.innerHTML="Good Morning!";
    }else if(now>=12 && now<17){
        greet1.innerHTML="Good Afternoon!";
        greet.style.backgorund="#60e68f";
    }else if(now>=17 && now<22){
        greet1.innerHTML="Good Evening!";
        greet.style.backgorund="#ff941b";
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


