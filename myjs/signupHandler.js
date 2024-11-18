$(document).ready(function () {
    // Move the variable declaration to a higher scope
    var validationMessagesContainer = $("#signupValidation");


    function displayMessage(message) {

        validationMessagesContainer.append(message.trim());
    }

    $("#signupButton").click(function (event) {
        // Prevent the default form submission
        event.preventDefault();

        var email = $("#signup_email").val();
        var password = $("#signup_password").val();


        validationMessagesContainer.empty();

        // Simple client-side validation
        if (email === '' || password === '') {
            displayMessage("Please fill in all fields", "error");
            return;
        }

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            displayMessage("Invalid email format", "error");
            return;
        }

        const allowedDomains = ["gmail.com", "yahoo.com", "outlook.com"];
        const domain = email.split('@')[1];

        if (!allowedDomains.includes(domain)) {
            displayMessage("Email domain not allowed", "error");
            return false;
        }


        // Password validation
        if (password.length < 8) {
            displayMessage("Password must be at least 8 characters long", "error");
            return;
        }

        showComplete();


    });
});


function showComplete(){
    
    document.getElementById('completeDiv').style.display = 'flex';
    document.getElementById('signupDiv').style.display = 'none';
    //document.getElementById('signupDiv').reset();
    // document.getElementById('loginvald').innerText = '';



}

$("#completeBtn").click(function (event) {
    // Prevent the default form submission
    event.preventDefault();

    var fullname = $("#user_name").val();
    var orgname = $("#organization_name").val();
    var email = $("#signup_email").val();
    var password = $("#signup_password").val();

    if (fullname === '') {
        alert("Enter your name", "error");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../phpHandlers/signupHandler.php",
        data: {
            action: "signup",
            fullName: fullname,
            email: email,
            password: password,
            orgname: orgname,
        },
        success: function (response) {
            // Store the response in a variable
            var message = response;
            // Check if sign up was successful
            if (response.includes("successful")) {
                // Alert and navigate to the login page

                alert("Sign up sucess, you can login now");
                window.location.href = "./loginsignup.php";
            }else{
                showSignup(message);
            }
        }
    });

});

function showSignup(message) {
    document.getElementById('signupDiv').style.display = 'flex';
    document.getElementById('loginDiv').style.display = 'none';
    document.getElementById('completeDiv').style.display = 'none';
    $("#signupValidation").append(message.trim());

    
}


