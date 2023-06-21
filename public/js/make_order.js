let openShopping = document.querySelector('.shopping');
let closeShopping = document.querySelector('.closeShopping');
let list = document.querySelector('.list');
let listCard = document.querySelector('.listCard');
let body = document.querySelector('body');
let total = document.querySelector('.total');
let quantity = document.querySelector('.quantity');

openShopping.addEventListener('click', ()=> {
    body.classList.add('active');
})
closeShopping.addEventListener('click', ()=> {
    body.classList.remove('active');
})

function submitForm(itemID) {
    document.getElementById("updateForm" + itemID).submit();
}

function decrement(itemID) {
    var input = document.querySelector("#updateForm" + itemID + " input[name=updateQty]");
    if (input.value > 1) {
        input.value = parseInt(input.value) - 1;
    }
    submitForm(itemID);
}

function increment(itemID) {
    var input = document.querySelector("#updateForm" + itemID + " input[name=updateQty]");
    input.value = parseInt(input.value) + 1;
    submitForm(itemID);
}