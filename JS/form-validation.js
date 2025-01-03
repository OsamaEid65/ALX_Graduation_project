function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var checkInDate = document.getElementById('departure-date').value;
    var checkOutDate = document.getElementById('return-date').value;
    var numberOfPeople = document.getElementById('Number_of_people').value;
    var contactNumber = document.getElementById('Contact_number').value;

    if (name == "" || email == "" || checkInDate == "" || checkOutDate == "" || numberOfPeople == "" || contactNumber == "") {
        alert("Please fill out all fields.");
        return false;
    }


    return true; 
}