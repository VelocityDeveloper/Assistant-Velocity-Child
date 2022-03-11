const nav       = document.querySelector("#sticky-row-header");
const NavH      = nav.offsetHeight;
const NavTop    = nav.offsetTop;

function fixnavbar(){
  if(window.scrollY > NavH){
    nav.classList.add("nav-scrolled");
  } else {
    nav.classList.remove("nav-scrolled");
  }
}
window.addEventListener("scroll", fixnavbar);
