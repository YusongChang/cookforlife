var topBar = document.getElementById('top-bar'),
    press = 1;

document.getElementById('menu-btn').addEventListener('click',showMenu);
document.getElementById('collapse-btn').addEventListener('click',hiddenMenu);

function showMenu(){
  topBar.style.display = 'block';
  if(press++ % 2 == 0) topBar.style.display = "none";
  else topBar.style.display = "block";
  if(press > 2) press = 1;
}

function hiddenMenu(){
  topBar.style.display = "none";
  press = 1;
}