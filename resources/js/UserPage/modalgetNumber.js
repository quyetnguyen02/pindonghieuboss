const modal = document.getElementById("quickModal");
const popup = document.querySelector(".popup-overlay");

document.addEventListener('DOMContentLoaded', function () {
    const modal1 = document.getElementById("quoteModal");
    if (modal1) {
        modal1.style.display = "none";
    }


    document.querySelector(".popup-close").onclick = () => {
        popup.style.display = "none";
    };

    popup.onclick = function(e){

        if(e.target === popup){
            popup.style.display = "none";
        }

    }



    document.getElementById("openQuote").onclick = () => {
        popup.style.display = "";
    };

    const closeQuote = document.getElementById("closeQuote");

    if (closeQuote) {
        closeQuote.onclick = () => {
            popup.style.display = "none";
        };
    }

    if (modal) {
        modal.onclick = function(e){

            if(e.target === modal){
                popup.style.display = "none"
            }

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
