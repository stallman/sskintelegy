const popup = document.querySelector('.popup');
const popup_open = document.querySelectorAll('.open-popup');
const popup_close = document.querySelectorAll('.close-popup');
const popup_content = document.querySelector('.popup__content');

let activePopup = null;

popup_open.forEach((btn, index) => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        let popupId = btn.dataset.popup;
        activePopup = popupId;
        let currentPopup = document.getElementById(popupId);
        document.body.classList.add('popup-overflow');
        currentPopup.classList.add('active');
    });
});

popup_close.forEach((btn, index) => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        let popupId = btn.dataset.popup;
        activePopup = null;
        let currentPopup = document.getElementById(popupId);
        document.body.classList.remove('popup-overflow');
        currentPopup.classList.remove('active');
    })
});

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('popup')) {
        document.body.classList.remove('popup-overflow');
        document.getElementById(activePopup).classList.remove('active');
        activePopup = null;
    }
})