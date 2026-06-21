// =====================================
// EVENT MODAL
// =====================================

const modal = document.getElementById("eventModal");
const closeBtn = document.querySelector(".close");
const detailButtons = document.querySelectorAll(".details-btn");

let countdown;

// Open Modal
detailButtons.forEach(button=>{

    button.addEventListener("click",function(){

        document.getElementById("modalImage").src =
        this.dataset.image;

        document.getElementById("modalTitle").innerHTML =
        this.dataset.name;

        document.getElementById("modalCategory").innerHTML =
        this.dataset.category;

        document.getElementById("modalDate").innerHTML =
        this.dataset.date;

        document.getElementById("modalTime").innerHTML =
        this.dataset.time;

        document.getElementById("modalVenue").innerHTML =
        this.dataset.venue;

        document.getElementById("modalDescription").innerHTML =
        this.dataset.description;

        document.getElementById("modalPrice").innerHTML =
        this.dataset.price;

        document.getElementById("modalEarly").innerHTML =
        this.dataset.early;

        document.getElementById("modalDiscount").innerHTML =
        this.dataset.discount;

        document.getElementById("modalCapacity").innerHTML =
        this.dataset.capacity;

        document.getElementById("modalBooked").innerHTML =
        this.dataset.booked;

        document.getElementById("modalRemaining").innerHTML =
        this.dataset.remaining;

        document.getElementById("bookButton").href =
        "booking.php?event_id="+this.dataset.id;

        animateProgress(this.dataset.progress);

        startCountdown(this.dataset.date,this.dataset.time);

        modal.style.display="flex";

    });

});

// Close Button

closeBtn.onclick=function(){

    modal.style.display="none";

    clearInterval(countdown);

}

// Outside Click

window.onclick=function(e){

    if(e.target==modal){

        modal.style.display="none";

        clearInterval(countdown);

    }

}

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