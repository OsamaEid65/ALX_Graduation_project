// form-validation.js

function validateForm() {
    console.log("Validating form..."); 

    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var address = document.getElementById('address').value;
    var message = document.getElementById('message').value;

    console.log("Name:", name); 
    console.log("Email:", email); 
    console.log("Address:", address);
    console.log("Message:", message); 

    if (name === "" || email === "" || address === "" || message === "") {
        alert("Please fill out all fields.");
        return false;
    }


    return true; 
}
