window.addEventListener("scroll", () => {
    if(window.scrollY == 0)
        document.querySelector("header").classList.remove("isScrolled");
    else
        document.querySelector("header").classList.add("isScrolled");
});
const scrollEvent = new Event('scroll');
window.dispatchEvent(scrollEvent);