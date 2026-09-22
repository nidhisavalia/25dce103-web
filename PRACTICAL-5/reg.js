function showPassword() {
    let password = document.getElementById("password");

    if (password.type == "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}


document.getElementById("registerForm").addEventListener("submit", function(e) {

    e.preventDefault();

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let mobile = document.getElementById("mobile").value;
    let course = document.getElementById("course").value;

    let nameRegex = /^[A-Za-z ]+$/;
    let emailRegex = /^[A-Za-z0-9._%+-]+@gmail\.com$/;
    let passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
    let mobileRegex = /^[0-9]{10}$/;

    let valid = true;


    if (!nameRegex.test(name)) {
        document.getElementById("nameError").innerHTML =
            "Name must contain only characters";
        valid = false;
    } else {
        document.getElementById("nameError").innerHTML = "";
    }


    if (!emailRegex.test(email)) {
        document.getElementById("emailError").innerHTML =
            "Enter valid Gmail";
        valid = false;
    } else {
        document.getElementById("emailError").innerHTML = "";
    }


    if (!passwordRegex.test(password)) {
        document.getElementById("passwordError").innerHTML =
            "Password must contain 8 characters, number and special character";
        valid = false;
    } else {
        document.getElementById("passwordError").innerHTML = "";
    }


    if (!mobileRegex.test(mobile)) {
        document.getElementById("mobileError").innerHTML =
            "Mobile must contain 10 digits";
        valid = false;
    } else {
        document.getElementById("mobileError").innerHTML = "";
    }


    if (course.trim() == "") {
        document.getElementById("courseError").innerHTML =
            "Course is required";
        valid = false;
    } else {
        document.getElementById("courseError").innerHTML = "";
    }


    if (valid) {
        alert("Registration Successful!");
    }

});