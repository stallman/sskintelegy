document.addEventListener('DOMContentLoaded', () => {
  let activeDropdown = null;

  function updateDropdownIcon() {
    const openDropdownImages = document.querySelectorAll('.open-dropdown__img');
    const dropdownMenus = document.querySelectorAll('.dropdown-content');

    openDropdownImages.forEach((img, index) => {
      if (dropdownMenus[index] && dropdownMenus[index].classList.contains('active')) {
        img.classList.add('active');
      } else {
        img.classList.remove('active');
      }
    });
  }

  function closeOtherDropdowns(currentDropdown) {
    const dropdownMenus = document.querySelectorAll('.dropdown-content');

    dropdownMenus.forEach((menu) => {
      if (menu !== currentDropdown) {
        menu.classList.remove('active');
      }
    });

    activeDropdown = null;
    updateDropdownIcon();
  }

  function initDropdown(dropdownToggle, dropdownMenu, dropdown) {
    dropdownToggle.addEventListener('click', (e) => {
      e.stopPropagation();

      if (dropdown.classList.contains('select')) {
        dropdownMenu.classList.toggle('active');
        activeDropdown = dropdownMenu;
        updateDropdownIcon();
      } else {
        if (dropdownMenu.classList.contains('active')) {
          dropdownMenu.classList.remove('active');
          activeDropdown = null;
          updateDropdownIcon();
        } else {
          closeOtherDropdowns(dropdownMenu);
          dropdownMenu.classList.add('active');
          activeDropdown = dropdownMenu;
          updateDropdownIcon();
        }
      }
    });

    const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
    const selectedOption = dropdown.querySelector('.selected-option');

    dropdownItems.forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const optionText = item.textContent.trim();
        
        if (activeDropdown.parentNode.classList.contains('select')) {
          activeDropdown.parentNode.children[0].children[0].textContent = optionText;
          dropdownMenu.classList.remove('active');
          activeDropdown = null;
          updateDropdownIcon();
        }
      });
    });
  }

  const dropdownToggles = document.querySelectorAll('.open-dropdown');
  const dropdownMenus = document.querySelectorAll('.dropdown-content');
  const dropdowns = document.querySelectorAll('.dropdown');

  dropdownToggles.forEach((dropdownToggle, index) => {
    initDropdown(dropdownToggle, dropdownMenus[index], dropdowns[index]);
  });

  document.body.addEventListener('click', () => {
    if (activeDropdown) {
      activeDropdown.classList.remove('active');
      activeDropdown = null;
      updateDropdownIcon();
    }
  });
});