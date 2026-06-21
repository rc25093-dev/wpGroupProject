// =====================================
// EVENT MODAL
// =====================================

let countdown;
let modal;
let closeBtn;

function openEventModal(button) {

    if (!button || !modal) return;

    document.getElementById("modalImage").src = button.dataset.image;
    document.getElementById("modalTitle").innerHTML = button.dataset.name;
    document.getElementById("modalCategory").innerHTML = button.dataset.category;
    document.getElementById("modalDate").innerHTML = button.dataset.date;
    document.getElementById("modalTime").innerHTML = button.dataset.time;
    document.getElementById("modalVenue").innerHTML = button.dataset.venue;
    document.getElementById("modalDescription").innerHTML = button.dataset.description;
    document.getElementById("modalPrice").innerHTML = button.dataset.price;
    document.getElementById("modalEarly").innerHTML = button.dataset.early;
    document.getElementById("modalDiscount").innerHTML = button.dataset.discount;
    document.getElementById("modalCapacity").innerHTML = button.dataset.capacity;
    document.getElementById("modalBooked").innerHTML = button.dataset.booked;
    document.getElementById("modalRemaining").innerHTML = button.dataset.remaining;
    document.getElementById("bookButton").href = "booking.php?event_id=" + button.dataset.id;

    animateProgress(button.dataset.progress);
    startCountdown(button.dataset.date, button.dataset.time);

    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}

document.addEventListener("DOMContentLoaded", function () {

    modal = document.getElementById("eventModal");
    closeBtn = document.querySelector(".close");
    const detailButtons = document.querySelectorAll(".details-btn");

    if (modal) {
        modal.style.display = "none";
    }

    detailButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            openEventModal(this);
        });
    });

    if (closeBtn) {
        closeBtn.onclick = function () {
            if (modal) {
                modal.style.display = "none";
            }
            document.body.style.overflow = "";
            clearInterval(countdown);
        };
    }

    window.onclick = function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
            document.body.style.overflow = "";
            clearInterval(countdown);
        }
    };

});

// =====================================
// PROGRESS BAR
// =====================================

function animateProgress(percent){

    let bar=document.getElementById("progressFill");

    let text=document.getElementById("progressText");

    bar.style.width="0%";

    let current=0;

    let animation=setInterval(function(){

        if(current>=percent){

            clearInterval(animation);

        }

        else{

            current++;

            bar.style.width=current+"%";

            text.innerHTML=current+"% Booked";

        }

    },10);

}

// =====================================
// COUNTDOWN TIMER
// =====================================

function startCountdown(date,time){

    clearInterval(countdown);

    let eventDate=new Date(date+" "+time).getTime();

    countdown=setInterval(function(){

        let now=new Date().getTime();

        let distance=eventDate-now;

        if(distance<0){

            clearInterval(countdown);

            document.getElementById("days").innerHTML="00";

            document.getElementById("hours").innerHTML="00";

            document.getElementById("minutes").innerHTML="00";

            return;

        }

        let days=Math.floor(distance/(1000*60*60*24));

        let hours=Math.floor((distance%(1000*60*60*24))/(1000*60*60));

        let minutes=Math.floor((distance%(1000*60*60))/(1000*60));

        document.getElementById("days").innerHTML=days;

        document.getElementById("hours").innerHTML=hours;

        document.getElementById("minutes").innerHTML=minutes;

    },1000);

}

// =====================================
// CARD HOVER EFFECT
// =====================================

document.querySelectorAll(".event-card").forEach(card=>{

    card.addEventListener("mouseenter",function(){

        this.style.transform="translateY(-10px)";

    });

    card.addEventListener("mouseleave",function(){

        this.style.transform="translateY(0px)";

    });

});

// =====================================
// IMAGE ZOOM
// =====================================

document.querySelectorAll(".image-wrapper img").forEach(img=>{

    img.addEventListener("mouseenter",function(){

        this.style.transform="scale(1.08)";

    });

    img.addEventListener("mouseleave",function(){

        this.style.transform="scale(1)";

    });

});