let productsImage = document.querySelectorAll('.woocommerce ul.products li.product a img');

productsImage.forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        item.parentNode.parentNode.children[1].click();
    });
});

let addToCartButton = document.querySelector('.cart .single_add_to_cart_button.button.alt');

addToCartButton.innerHTML = "Faire un don";
