initSlider();
function initSlider() {
    const slidesContainer = document.querySelector('#sliderSection .slides');
    const slides = document.querySelectorAll('#sliderSection .slide');
    const dots = document.querySelectorAll('#sliderSection .dot');
    if (!slidesContainer || slides.length < 1) return;

    let current = 0;
    let interval = null;

    function goTo(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        current = index;
        slidesContainer.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
    }

    function next() { goTo(current + 1); }

    function start() {
        stop();
        interval = setInterval(next, 3000);
    }
    function stop() {
        if (interval) { clearInterval(interval); interval = null; }
    }

    if (dots.length) {
        dots.forEach((dot, i) => {
            dot.addEventListener('click', function () {
                stop();
                goTo(i);
                start();
            });
        });
    }


    goTo(0);
    start();

    const wrapper = document.querySelector('#sliderSection .slider-wrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', stop);
        wrapper.addEventListener('mouseleave', start);
    }
}

const btn = document.getElementById("toggleCategoriesBtn");
const menu = document.getElementById("categorySection");

btn.addEventListener("click", () => {
    menu.classList.toggle("show");
});
