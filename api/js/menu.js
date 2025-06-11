const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".sidebar");
const menucloset=document.querySelector(".close-menuu")
// console.log(menucloset);
hamburger.addEventListener("click", () => nav.classList.toggle("active") ,console.log("logged out") );
hamburger.addEventListener("click", () =>console.log("logged out") );
menucloset.addEventListener("click", () => nav.classList.remove("active"))