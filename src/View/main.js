const upBtn = document.querySelector(".up-btn");
upBtn.addEventListener("click", () => {
    window.scrollTo(0, 0);
});

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page = urlParams.get("page") ? urlParams.get("page") : 1;
const links = document.querySelectorAll(".pagination");

links[page - 1].classList.add("active");

const nextPageBtn = document.querySelector(".next-page-btn");
nextPageBtn.addEventListener("click", () => {
    window.location.href = `?page=${Number(page) + 1}`;
    console.log(window.location.href);
});

const prevPageBtn = document.querySelector(".prev-page-btn");
if (page > 1) {
    prevPageBtn.style.display = "block";
    prevPageBtn.addEventListener("click", () => {
        window.location.href = `?page=${Number(page) - 1}`;
        console.log(window.location.href);
    });
}
