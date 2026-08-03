import {getCookie, setCookie} from "./card.js";

const modal=document.getElementById("checkoutModal");
const totalPrice=document.getElementById("totalPrice");
document.querySelector("#checkout")
    .addEventListener("click",()=>{

        modal.classList.add("active");

        console.log(totalPrice)
        document.getElementById("modalTotal").innerHTML= totalPrice.innerHTML

    });

document.getElementById("closeModal")
    .addEventListener("click",()=>{

        modal.classList.remove("active");

    });

document
    .getElementById("checkoutForm")
    .addEventListener("submit", function (e) {

        e.preventDefault();

        document
            .getElementById("confirmTotal")
            .innerHTML = totalPrice.innerHTML;

        document
            .getElementById("confirmModal")
            .classList.add("active");

    });


document
    .getElementById("cancelConfirm")
    .onclick = function () {

    document
        .getElementById("confirmModal")
        .classList.remove("active");

};


document
    .getElementById("acceptOrder")
    .onclick = function () {

    document
        .getElementById("confirmModal")
        .classList.remove("active");

    const cookie =getCookie("cart");
    var cart = JSON.parse(cookie);
    submitOrder(cart);

};

export function submitOrder(cart)
{
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    const customerName = document.querySelector('input[name="customer_name"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const address = document.querySelector('textarea[name="address"]').value;
    console.log(customerName, address, phone)

    fetch("/order", {

        method: "POST",

        headers: {

            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
            "Accept": "application/json"
        },

        body: JSON.stringify({

            customer_name: customerName,

            phone: phone,

            address: address,

            cart: cart

        })

    })
    .then(async response=>{

        const data=await response.json();

        if(!response.ok){

            let msg="";
            console.log(data.errors);

            Object.values(data.errors).forEach(item=>{

                msg+=item[0]+"\n";

            });

            showNotify(

                "error",

                "Thông tin chưa hợp lệ",
                msg
            );

            return;
        }

        showNotify(

            "success",

            "Đặt hàng thành công",

            "Cảm ơn bạn đã mua hàng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.",
            () => {
                // Xóa cookie giỏ hàng
                setCookie("cart", []);

                // Chuyển về trang chủ
                window.location.href = "/";
            }
        );

    })
    .catch(()=>{

        showNotify(

            "error",

            "Đặt hàng thất bại",

            "Không thể gửi đơn hàng. Vui lòng thử lại sau."
        );
    });
}


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

        if (typeof callback === "function") {
            console.log('call back')
            callback();
        }
    };
}

document
    .getElementById("notifyBtn")
    .onclick = () => {

    modalSuccess.classList.remove("show");

};

