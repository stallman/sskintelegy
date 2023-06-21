

const alert_container = document.querySelectorAll('.alert');
const alert_close = document.querySelector('.alert__close');

alert_container.forEach((item, index) => {
    item.addEventListener('click', (e) => {
        console.log(e.target);
        if (e.target.classList == "alert__close" || e.target.classList == "alert__btn") {
            item.remove();
        }
    });

})

// inactive item in invenotry

const invenotry_item = document.querySelectorAll('.catalog__item');

invenotry_item.forEach((item, index) => {
    if (item.classList.contains('inactive')) {
        item.querySelector('.catalog__item_checkbox input[type="checkbox"]').setAttribute('disabled', 'disabled')
    }
});

function resetItem() {
    invenotry_item.forEach((item, index) => {
        if (item.classList.contains('inactive')) {
            item.classList.remove('inactive');
            item.querySelector('.catalog__item_checkbox input[type="checkbox"]').removeAttribute('disabled')
        }
    })
}
