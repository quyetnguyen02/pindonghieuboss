
document.addEventListener("DOMContentLoaded", function () {
    renderCart();
    countCart();
});

document.addEventListener("click", function (e) {

    const row = e.target.closest(".cart-item");

    if (!row) return;

    const id = row.dataset.id;

    // Tăng
    if (e.target.classList.contains("plus")) {

        increaseQty(id);

    }

    // Giảm
    if (e.target.classList.contains("minus")) {

        decreaseQty(id);

    }

    // Xóa
    if (e.target.classList.contains("delete")) {

        removeItem(id);

    }

});

document.addEventListener("change", function (e) {

    if (!e.target.matches(".qty input")) return;

    const row = e.target.closest(".cart-item");

    const id = row.dataset.id;

    let qty = Number(e.target.value);

    if (qty < 1)
        qty = 1;

    const cookie = getCookie('cart')
    var cart = JSON.parse(cookie)
    const item = cart.find(p => p.id == id);

    item.qty = qty;

    setCookie("cart", JSON.stringify(cart));

    renderCart();

    countCart();
});

function increaseQty(id) {

    const cookie = getCookie("cart");

    console.log(typeof cookie)
    console.log(cookie)
    if(cookie)
        var cart = JSON.parse(cookie);

    const item = cart.find(p => p.id == id);

    if (!item) return;

    item.qty++;

    setCookie("cart", JSON.stringify(cart));

    renderCart();

    countCart();
}

function decreaseQty(id) {
    const cookie = getCookie('cart')
    var cart = JSON.parse(cookie)
    const item = cart.find(p => p.id == id);

    if (!item) return;

    item.qty--;

    if (item.qty <= 0) {

        removeItem(id);

        return;
    }

    setCookie("cart", JSON.stringify(cart));

    renderCart();

    countCart();
}

function removeItem(id) {
    const cookie = getCookie('cart')
    var cart = JSON.parse(cookie)
    const index = cart.findIndex(p => p.id == id);

    if (index == -1) return;

    cart.splice(index, 1);

    setCookie("cart", JSON.stringify(cart));

    renderCart();

    countCart();
}


export function setCookie(name, value, days = 30) {
    let expires = "";

    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 86400000);
        expires = "; expires=" + date.toUTCString();
    }

    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}

export function getCookie(name) {

    const key = name + "=";

    const arr = document.cookie.split(";");

    for (let c of arr) {

        c = c.trim();

        if (c.indexOf(key) === 0) {

            return decodeURIComponent(c.substring(key.length));

        }
    }

    return null;
}

export function addToCart(product) {
    console.log('vào fun add to cảd')
    console.log(product)

    let cart = [];

    const cookie = getCookie("cart");

    console.log(typeof cookie)
    console.log(cookie)
    if(cookie)
        cart = JSON.parse(cookie);

    const item = cart.find(x => x.id == product.id);

    if(item){

        item.qty += product.qty;

    }else{

        cart.push({
            ...product,
            qty:product.qty
        });

    }

    console.log(cart)
    setCookie("cart", JSON.stringify(cart));

    // Disable nút
    const btn = document.getElementById("btnAddCart");
    btn.disabled = true;
    btn.innerText = "Đã thêm vào giỏ";

    console.log(getCookie('cart'))
    countCart()
}

export function checkProductInCart(productId) {

    const cookie = getCookie("cart");

    if (!cookie) return false;

    const cart = JSON.parse(cookie);

    return cart.some(item => item.id == productId);
}
 export function countCart() {
     var cookie = getCookie('cart');
     if(cookie) {
         var cart = JSON.parse(cookie);
         document.getElementById('cartCount').innerHTML = cart.length;
     } else {
         document.getElementById('cartCount').innerHTML = 0;
     }
 }




// renderCart();

function renderCart() {
    const cookie = getCookie("cart");
    console.log(cookie)
    var cart = [];
    if (cookie) {
        cart = JSON.parse(cookie);
    }
    const container = document.getElementById("cartItems");

    let html = "";
    let total = 0;
    console.log(container, container !== null)
    if (!cart.length && container !== null) {

        container.innerHTML = `
            <div class="cart-item empty">
                <div style="grid-column:1/8;text-align:center;padding:40px">
                    Giỏ hàng đang trống
                </div>
            </div>
        `;

        document.getElementById("subTotal").innerText =
            formatMoney(total) + "đ";

        document.getElementById("totalPrice").innerText =
            formatMoney(total) + "đ";

        return;
    }

    cart.forEach(item => {

        const money = item.qty * item.price;

        total += money;

        html += `
            <div class="cart-item" data-id="${item.id}">

                <div>
                    <img src="${item.image}" alt="${item.name}">
                </div>

                <div>${item.sku}</div>

                <div>${item.name.trim()}</div>

                <div>

                    <div class="qty">

                        <button class="minus">-</button>

                        <input
                            type="number"
                            value="${item.qty}"
                            readonly>

                        <button class="plus">+</button>

                    </div>

                </div>

                <div>${formatMoney(item.price)}đ</div>

                <div class="price">
                    ${formatMoney(money)}đ
                </div>

                <div>
                    <button class="delete">
                        🗑
                    </button>
                </div>

            </div>
        `;
    });

    if (!cart.length && container !== null) {
        container.innerHTML = html;

        document.getElementById("subTotal").innerText =
            formatMoney(total) + "đ";

        document.getElementById("totalPrice").innerText =
            formatMoney(total) + "đ";
    }
}

export function formatMoney(number) {
    return Number(number).toLocaleString("vi-VN");
}

