import {addToCart, countCart, checkProductInCart, setCookie} from "./card.js";

const closeBtn = document.querySelector(".close-modal");

const modal = document.getElementById("quickModal");

document.addEventListener('DOMContentLoaded', function () {
    countCart();
})
document.querySelectorAll(".quick-view-btn").forEach(btn => {

    btn.addEventListener("click", function () {

        document.getElementById("modalTitle").textContent =
            this.dataset.name;

        document.getElementById("modalImage").src =
            this.dataset.image;

        document.getElementById('skuModal').value = this.dataset.sku;
        document.getElementById('categoryId').value = this.dataset.cate;
        let oldPrice = document.getElementById("modalOldPrice");
        let newPrice = document.getElementById("modalNewPrice");

        const btn = document.getElementById("btnAddCart");
        console.log('soso:' )
        console.log(parseInt(this.dataset.original) !== 0)
        if (Number(this.dataset.original) !== 0) {

            btn.dataset.id = this.dataset.id;
            console.log(this.dataset.id)
            if (checkProductInCart(this.dataset.id)) {
                btn.disabled = true;
                btn.innerText = "Đã thêm vào giỏ";
            } else {
                btn.disabled = false;
                btn.innerText = "Thêm Vào Giỏ";
            }



            if (Number(this.dataset.sale) === 0) {
                newPrice.innerHTML =
                    Number(this.dataset.original).toLocaleString() + "đ";

            } else {

                oldPrice.innerHTML =
                    Number(this.dataset.original).toLocaleString() + "đ";

                newPrice.innerHTML =
                    Number(this.dataset.sale).toLocaleString() + "đ";
            }
            btn.style = '';
        } else {
            btn.style = 'display:none';
            newPrice.innerHTML = '';
            oldPrice.innerHTML = '';
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

var btnAddCart = document.getElementById("btnAddCart");
if (btnAddCart) {
    document.getElementById("btnAddCart").onclick = function () {
        var cateId = parseInt(document.getElementById("categoryId").value);
        console.log('cate:' + cateId)
        var product= {
            'id':document.getElementById('btnAddCart').dataset.id,
            'sku':document.getElementById('skuModal').value,
            'name':document.getElementById('modalTitle').innerHTML,
            'image':document.getElementById('modalImage').src,
            'qty': cateId === 1 ? 10 : 1,
            'price':Number(document.getElementById('modalNewPrice').innerHTML.replace(/[^\d]/g, "")),
        }

        addToCart(product)
    };
}


