
document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // 1. DARK / LIGHT MODE
    // =========================

    let themeButton = document.getElementById("themeButton");

    if (themeButton) {

        // Previous theme check
        let savedTheme = localStorage.getItem("theme");

        if (savedTheme === "dark") {
            document.body.classList.add("dark");
            themeButton.innerText = "☀️ Light Mode";
        }

        themeButton.addEventListener("click", function () {

            document.body.classList.toggle("dark");

            if (document.body.classList.contains("dark")) {
                localStorage.setItem("theme", "dark");
                themeButton.innerText = "☀️ Light Mode";
            } else {
                localStorage.setItem("theme", "light");
                themeButton.innerText = "🌙 Dark Mode";
            }

        });
    }


    // =========================
    // 2. HAMBURGER MENU
    // =========================

    let menuButton = document.getElementById("menuButton");
    let nav = document.querySelector("nav");

    if (menuButton && nav) {

        menuButton.addEventListener("click", function () {

            nav.classList.toggle("show");

        });

    }


    // =========================
    // 3. FAQ COLLAPSIBLE
    // =========================

    let questions = document.querySelectorAll(".faq-question");

    questions.forEach(function (question) {

        question.addEventListener("click", function () {

            let answer = question.nextElementSibling;

            answer.classList.toggle("show");

        });

    });


    // =========================
    // 4. MODAL POPUP
    // =========================

    let modal = document.getElementById("myModal");
    let modalButton = document.getElementById("modalButton");
    let closeButton = document.getElementById("closeModal");

    if (modal && modalButton) {

        modalButton.addEventListener("click", function () {

            modal.classList.add("show");

        });

    }

    if (modal && closeButton) {

        closeButton.addEventListener("click", function () {

            modal.classList.remove("show");

        });

    }


    // =========================
    // 5. NOTIFICATION
    // =========================

    let notification = document.getElementById("notification");
    let closeNotification = document.getElementById("closeNotification");

    if (closeNotification && notification) {

        closeNotification.addEventListener("click", function () {

            notification.style.display = "none";

        });

    }


    // =========================
    // 6. FORM SUBMIT
    // =========================

    let forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            event.preventDefault();

            let inputs = form.querySelectorAll("input, textarea, select");

            let empty = false;

            inputs.forEach(function (input) {

                if (
                    input.type !== "submit" &&
                    input.type !== "reset" &&
                    input.type !== "radio" &&
                    input.value.trim() === ""
                ) {
                    empty = true;
                }

            });

            if (empty) {
                alert("Please fill all details!");
            } else {
                alert("Submitted Successfully!");
                form.reset();
            }

        });

    });


    // =========================
    // 7. EVENT SLIDER
    // =========================

    let slides = document.querySelectorAll(".slide");
    let nextButton = document.getElementById("nextButton");
    let previousButton = document.getElementById("previousButton");

    let currentSlide = 0;

    function showSlide(index) {

        slides.forEach(function (slide) {
            slide.classList.remove("active");
        });

        if (slides.length > 0) {
            slides[index].classList.add("active");
        }

    }

    if (slides.length > 0) {

        showSlide(currentSlide);

        if (nextButton) {

            nextButton.addEventListener("click", function () {

                currentSlide++;

                if (currentSlide >= slides.length) {
                    currentSlide = 0;
                }

                showSlide(currentSlide);

            });

        }

        if (previousButton) {

            previousButton.addEventListener("click", function () {

                currentSlide--;

                if (currentSlide < 0) {
                    currentSlide = slides.length - 1;
                }

                showSlide(currentSlide);

            });

        }

    }

});
