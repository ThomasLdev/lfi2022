let productsImage = document.querySelectorAll('.woocommerce ul.products li.product a img');

productsImage.forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        item.parentNode.parentNode.children[1].click();
    });
});
