document.addEventListener('DOMContentLoaded', function () {
    const burgerMenuContainer = document.querySelector('.burger-menu-container');
    const mobileNav = document.querySelector('.mobile-nav');
    const closeNav = document.querySelector('.close-nav');
    const mainNav = document.querySelector('.main-navigation');

    if (!burgerMenuContainer || !mobileNav) {
        console.warn('Burger eller mobile-nav saknas i DOM.');
        return;
    }

    // Öppna hamburgermenyn
    burgerMenuContainer.addEventListener('click', function () {
        const isActive = mobileNav.classList.toggle('active');
        mobileNav.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        burgerMenuContainer.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        if (mainNav) mainNav.style.display = isActive ? 'none' : 'flex';
    });


    // Stäng hamburgermenyn
    if (closeNav) {
        closeNav.addEventListener('click', function () {
            mobileNav.classList.remove('active');
            mobileNav.setAttribute('aria-hidden', 'true');
            burgerMenuContainer.setAttribute('aria-expanded', 'false');
            if (mainNav) mainNav.style.display = 'flex';
        });
    }

    // Stäng när en menyval klickas
    document.querySelectorAll('.mobile-nav ul li a').forEach(item => {
        item.addEventListener('click', function () {
            mobileNav.classList.remove('active');
            mobileNav.setAttribute('aria-hidden', 'true');
            burgerMenuContainer.setAttribute('aria-expanded', 'false');
            if (mainNav) mainNav.style.display = 'flex';
        });
    });

    console.log("main.js är laddad!");

});