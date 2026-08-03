import {countCart} from "./card.js";

const mainImage = document.getElementById('mainImage');

const thumbs = document.querySelectorAll('.thumb');

const thumbList = document.querySelector(".thumb-list");

const oldPrice = document.querySelector(".old-price");

const newPrice = document.querySelector(".new-price");

const quantityInput=document.getElementById("quantity");
const unitPrice = Number(window.product.sale_price === 0 ? window.product.original_price : window.product.sale_price );
const totalPrice=document.getElementById("totalPrice");

// quantityInput.value=10;

const products = {

    body: {

        oldPrice: window.product ? window.product.original_price : '0',

        newPrice: window.product ? window.product.sale_price : '0',

        images:window.product ? window.product.thumbs : ''

    },

    full: {

        oldPrice: "2.690.000đ",

        newPrice: "2.190.000đ",

        images: [

            "/image/p3.webp",
            "/image/p4.webp",

        ]

    }

};
console.log(window.tiers)
const tiers = window.tiers.map(item => ({
    from: Number(item.from_quantity),
    price: Number(item.price)
}));
function getCurrentTier(quantity){

    let current=tiers[0];

    tiers.forEach(t=>{

        if(quantity>=t.from){
            current=t;
        }

    });

    return current;
}

function renderTier() {

    const qty = parseInt(quantityInput.value);

    const current = getCurrentTier(qty);

    let html = '';

    tiers.forEach((tier, index) => {

        let label = '';

        if (index == tiers.length - 1) {

            label = `≥ ${tier.from} viên`;

        } else {

            label = `${tier.from} - ${tiers[index + 1].from - 1} viên`;

        }

        html += `
        <div class="price-tier-item ${current.from === tier.from ? 'active' : ''}">
            <div class="left">
                <span class="icon">👉</span>
                <span>${label}</span>
            </div>

            <div class="price">
                ${formatMoney(tier.price)}/cell
            </div>
        </div>
        `;

    });

    document.getElementById("priceTierList").innerHTML = html;

    // unitPrice.innerHTML = formatMoney(current.price);

    totalPrice.innerHTML = formatMoney(current.price * qty);

}

renderProduct("body");
function renderProduct(type){

    const product = products[type];
    if (product.oldPrice === '0') {
        return;
    }

    if (product.newPrice > 0) {
        oldPrice.innerHTML = product.oldPrice.toLocaleString('vi-VN') + 'đ';

        newPrice.innerHTML = product.newPrice.toLocaleString('vi-VN') + 'đ';
    } else {
        newPrice.innerHTML = product.oldPrice.toLocaleString('vi-VN') + 'đ';

    }

    mainImage.src = '/image/' + product.images[0];

    thumbList.innerHTML = "";

    product.images.forEach((img,index)=>{

        thumbList.innerHTML += `
            <div class="thumb ${index===0?'active':''}"
                 data-image="/image/${img}">
                <img src="/image/${img}">
            </div>
        `;

    });

    initThumbEvent();
}

function initThumbEvent(){

    const thumbs = document.querySelectorAll(".thumb");

    thumbs.forEach(item=>{

        item.onclick=function(){

            mainImage.src=this.dataset.image;

            thumbs.forEach(t=>t.classList.remove("active"));

            this.classList.add("active");

        }

    });

}

const variantBtns=document.querySelectorAll(".variant-btn");

variantBtns.forEach(btn=>{

    btn.onclick=function(){

        variantBtns.forEach(item=>item.classList.remove("active"));

        this.classList.add("active");

        renderProduct(this.dataset.type);

    }

});

// Khởi tạo
const plusBtn = document.getElementById("plus");
const minusBtn = document.getElementById("minus");
const category_id = document.getElementById("category_id").value;

function formatMoney(number) {
    return new Intl.NumberFormat("vi-VN").format(number) + "đ";
}
updateTotal();
function updateTotal() {
    console.log(quantityInput.value)
    let qty = parseInt(quantityInput.value);

    if (isNaN(qty) || qty < 1) {
        qty = 1;
        quantityInput.value = 1;
    }

    console.log(formatMoney(unitPrice * qty))
    totalPrice.innerText = formatMoney(unitPrice * qty);
}

// Tăng
plusBtn.addEventListener("click", () => {
    if (category_id === '1') {
        quantityInput.value = parseInt(quantityInput.value) + 10;
        updateTotal();
        renderTier();
    } else {
        quantityInput.value++;
    }

    console.log(quantityInput.value)

    updateTotal();
    renderTier();
});

// Giảm
minusBtn.addEventListener("click", () => {
    console.log('cate:' + quantityInput.value)

    if (category_id === '1') {
        if (quantityInput.value > 10) {
            quantityInput.value = parseInt(quantityInput.value) - 10;

        }
        updateTotal();
        renderTier();
    } else {
        if (quantityInput.value > 1) {
            quantityInput.value--;
            updateTotal();
            renderTier();
        }
    }
});

// // Người dùng nhập trực tiếp
// quantityInput.addEventListener("change", () => {
//     quantityInput.value = document.getElementById('quantity').value;
//     updateTotal()
//
//     renderTier();
// });

document.addEventListener('DOMContentLoaded', function () {
    countCart();
    renderTier();
})




