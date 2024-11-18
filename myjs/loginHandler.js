
$(document).ready(function() {
    // Move the variable declaration to a higher scope
    var validationMessagesContainer = $("#validationMessages");
  
   
    function displayValidationMessage(message) {
        // Append a new paragraph with the validation message
        validationMessagesContainer.append(message);
    }


    $("#loginButton").click(function(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Store the current values of the form fields.
        var email = $("#email").val();
        var password = $("#password").val();

        // Clear previous validation messages
        validationMessagesContainer.empty();

        // Simple client-side validation
        if (email === '' || password === '') {
            displayValidationMessage("Please fill in all fields");
            return;
        }

        // Validate email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            displayValidationMessage("Invalid email format");
            return;
        }

        //calling php file for  login check //

        $.ajax({
            type: "POST",
            url: "../phpHandlers/loginHandler.php",
            data: { 
                action: "login",
                email: email,   
                password: password 
            },
            success: function(response) {
                // Check if login was successful
                if (response === "successful") {
                   
                    window.location.href = "../home/createinvoice.php";
                } else {
                    // Display the PHP response as a validation message
                    displayValidationMessage(response);
                }
            }
        }); 
        
    });
    
});
