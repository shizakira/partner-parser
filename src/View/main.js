const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page = urlParams.get("page");
const links = document.querySelectorAll(".pagination");

links[page - 1].classList.add("active");
