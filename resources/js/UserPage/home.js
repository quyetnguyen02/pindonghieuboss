import {addToCart, countCart, checkProductInCart, setCookie} from "./card.js";

const closeBtn = document.querySelector(".close-modal");

const modal = document.getElementById("quickModal");
const popup = document.querySelector(".popup-overlay");


document.addEventListener('DOMContentLoaded', function () {
    countCart();

    document.querySelector(".popup-close").onclick = () => {
        popup.style.display = "none";
    };

    popup.onclick = function(e){

        if(e.target === popup){
            popup.style.display = "none";
        }

    }

    // const modal = document.getElementById("quoteModal");


    document.getElementById("openQuote").onclick = () => {
        popup.style.display = "";
    };

    document.getElementById("closeQuote").onclick = () => {
        popup.style.display = "none"
    };

    modal.onclick = function(e){

        if(e.target === modal){

            popup.style.display = "none"
        }

    }
})
const form = document.getElementById("consultForm");

form.addEventListener("submit", async function(e){

    e.preventDefault();

    const formData = new FormData(form);

    const response = await fetch("/consultation",{

        method:"POST",

        headers:{
            "X-CSRF-TOKEN":document
                .querySelector('meta[name="csrf-token"]')
                .content,

            "Accept":"application/json"
        },

        body:formData

    });

    const data = await response.json();

    if(response.ok){

        form.reset();
        showNotify(

            "success",

            "Đặt hàng thành công",

            'Đăng ký tư vấn thành công!'

        );


        // document.querySelector(".popup-overlay").style.display="none";

    }else{
        let message="";

        Object.values(data.errors).forEach(item=>{

            message+=item[0]+"\n";

        });

        showNotify(

            "error",

            "Thông tin chưa hợp lệ",
            message
        );

    }

});

const modalSuccess = document.getElementById("notifyModal");
function showNotify(type, title, message, callback = null) {

    const icon = document.getElementById("notifyIcon");

    icon.className = "notify-icon";

    if (type === "success") {
        icon.classList.add("success");
        icon.innerHTML = "✓";
    } else {
        icon.classList.add("error");
        icon.innerHTML = "✕";
    }

    document.getElementById("notifyTitle").innerText = title;
    document.getElementById("notifyMessage").innerText = message;

    modalSuccess.classList.add("show");

    document.getElementById("notifyBtn").onclick = () => {

        modalSuccess.classList.remove("show");
        if (type === 'success') {
            popup.style.display = "none";
        }

        if (typeof callback === "function") {
            console.log('call back')
            callback();
        }
    };
}
document.querySelectorAll(".quick-view-btn").forEach(btn => {

    btn.addEventListener("click", function () {
        console.log(1)
        console.log(this.dataset)

        document.getElementById("modalTitle").textContent =
            this.dataset.name;

        document.getElementById("modalImage").src =
            this.dataset.image;

        document.getElementById('skuModal').value = this.dataset.sku;

        const btn = document.getElementById("btnAddCart");
        btn.dataset.id = this.dataset.id;
        console.log(this.dataset.id)
        if (checkProductInCart(this.dataset.id)) {
            btn.disabled = true;
            btn.innerText = "Đã thêm vào giỏ";
        } else {
            btn.disabled = false;
            btn.innerText = "Thêm Vào Giỏ";
        }

        let oldPrice = document.getElementById("modalOldPrice");
        let newPrice = document.getElementById("modalNewPrice");

        if (Number(this.dataset.sale) === 0) {
            newPrice.innerHTML =
                Number(this.dataset.original).toLocaleString() + "đ";

        } else {

            oldPrice.innerHTML =
                Number(this.dataset.original).toLocaleString() + "đ";

            newPrice.innerHTML =
                Number(this.dataset.sale).toLocaleString() + "đ";
        }

        modal.classList.add("show");

        if (modal && closeBtn) {
            closeBtn.onclick=function(){

                modal.classList.remove("show");

            }

            modal.onclick=function(e){

                if(e.target===modal){

                    modal.classList.remove("show");

                }

            }
        }
    });

});

document.getElementById("btnAddCart").onclick = function () {
    var product= {
        'id':document.getElementById('btnAddCart').dataset.id,
        'sku':document.getElementById('skuModal').value,
        'name':document.getElementById('modalTitle').innerHTML,
        'image':document.getElementById('modalImage').src,
        'qty': 1,
        'price':Number(document.getElementById('modalNewPrice').innerHTML.replace(/[^\d]/g, "")),
    }

    addToCart(product)
};

