const accordionItems = document.querySelectorAll('.accordion-item');

accordionItems.forEach(item => {
  const header = item.querySelector('.accordion-header');
  const content = item.querySelector('.accordion-content');
  const arrow = item.querySelector('.faq-accordion__header_open');

  header.addEventListener('click', () => {
    content.classList.toggle('active');
    header.classList.toggle('active');
    arrow.classList.toggle('active');
  });
});