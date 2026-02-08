document.addEventListener("DOMContentLoaded", function() {
    const regForm = document.getElementById('regForm');
    
    if(regForm) {
        regForm.onsubmit = function() {
            console.log("Registering user...");
            // Aap yahan extra client-side validation add kar sakte hain
            return true;
        };
    }
});