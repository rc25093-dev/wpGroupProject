document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("feedbackForm");

    if(!form) return;

    form.addEventListener("submit", function(e){

        const eventSelect =
        document.getElementById("event_id");

        const comments =
        document.getElementById("comments");

        const rating =
        document.querySelector(
        'input[name="rating"]:checked'
        );

        const error =
        document.getElementById("errorMessage");

        error.innerHTML = "";

        if(eventSelect.value === ""){
            e.preventDefault();
            error.innerHTML =
            "Please select an event.";
            return;
        }

        if(!rating){
            e.preventDefault();
            error.innerHTML =
            "Please select a rating.";
            return;
        }

        if(comments.value.trim() === ""){
            e.preventDefault();
            error.innerHTML =
            "Please enter comments.";
            return;
        }

        if(comments.value.trim().length < 10){
            e.preventDefault();
            error.innerHTML =
            "Comments must be at least 10 characters.";
            return;
        }

        if(comments.value.trim().length > 300){
            e.preventDefault();
            error.innerHTML =
            "Comments cannot exceed 300 characters.";
            return;
        }

    });

});