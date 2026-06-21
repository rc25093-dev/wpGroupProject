// =====================================
// EVENTEASE BOOKING SYSTEM
// booking.js
// =====================================

// Form Elements
const eventSelect = document.getElementById("eventSelect");
const quantity = document.getElementById("quantity");

const priceDisplay = document.getElementById("priceDisplay");
const total = document.getElementById("total");
const discount = document.getElementById("discount");
const service = document.getElementById("service");
const finalTotal = document.getElementById("finalTotal");
const seatAvailable = document.getElementById("seatAvailable");

const bookingForm = document.getElementById("bookingForm");

// Fixed Service Fee
const SERVICE_FEE = 2.00;

// ================================
// Main Calculation Function
// ================================

function calculateBooking(){

    if(eventSelect.selectedIndex <= 0){

        clearFields();
        return;

    }

    const option = eventSelect.options[eventSelect.selectedIndex];

    const price = parseFloat(option.dataset.price);

    const availableSeats = parseInt(option.dataset.seat);

    let qty = parseInt(quantity.value);

    if(isNaN(qty) || qty < 1){

        qty = 0;

    }

    // Display Ticket Price
    priceDisplay.value = "RM " + price.toFixed(2);

    // Remaining Seats
    seatAvailable.value = availableSeats + " Seats";

    // Total Price
    let totalPrice = price * qty;

    // Discount
    let discountAmount = 0;

    if(qty >= 3){

        discountAmount = totalPrice * 0.10;

    }

    // Final Payment
    let finalPayment = totalPrice - discountAmount + SERVICE_FEE;

    total.value = "RM " + totalPrice.toFixed(2);

    discount.value = "RM " + discountAmount.toFixed(2);

    service.value = "RM " + SERVICE_FEE.toFixed(2);

    finalTotal.value = "RM " + finalPayment.toFixed(2);

}

// ================================
// Clear Form
// ================================

function clearFields(){

    priceDisplay.value = "";

    total.value = "";

    discount.value = "";

    service.value = "";

    finalTotal.value = "";

    seatAvailable.value = "";

}

// ================================
// Live Update
// ================================

if(eventSelect){

    eventSelect.addEventListener("change",calculateBooking);

}

if(quantity){

    quantity.addEventListener("input",calculateBooking);

}

// ================================
// Quantity Validation
// ================================

if(quantity){

quantity.addEventListener("change",function(){

    if(eventSelect.selectedIndex<=0){

        return;

    }

    const available=parseInt(

    eventSelect.options[
    eventSelect.selectedIndex
    ].dataset.seat

    );

    let qty=parseInt(quantity.value);

    if(qty>available){

        alert(

        "Only "+available+
        " seat(s) available."

        );

        quantity.value=available;

    }

    if(qty<1){

        quantity.value=1;

    }

    calculateBooking();

});

}

// ================================
// Submit Validation
// ================================

if(bookingForm){

bookingForm.addEventListener("submit",function(e){

    if(eventSelect.value==""){

        alert("Please select an event.");

        e.preventDefault();

        return;

    }

    if(quantity.value==""){

        alert("Please enter quantity.");

        e.preventDefault();

        return;

    }

});

}

// ================================
// Highlight Final Payment
// ================================

if(finalTotal){

finalTotal.addEventListener("focus",function(){

    finalTotal.style.background="#14532d";

});

finalTotal.addEventListener("blur",function(){

    finalTotal.style.background="#20263f";

});

}

// ================================
// Animation
// ================================

window.addEventListener("load",()=>{

    const form=document.querySelector(".booking-container");

    if(form){

        form.style.opacity="0";

        form.style.transform="translateY(40px)";

        setTimeout(()=>{

            form.style.transition=".8s";

            form.style.opacity="1";

            form.style.transform="translateY(0)";

        },200);

    }

});

// ================================
// Auto Calculate on Page Load
// ================================

calculateBooking();