

  setupClickListener("myinvoiceBtn","../invoicepage/invoices.php");
  setupClickListener("homepageBtn","../home/createinvoice.php");
  setupClickListener("upgradeBtn","../pricing/pricing.php");
  setupClickListener("pricingBtn","./pricingbefore.php");
  setupClickListener("createBtn","../home/createinvoice.php");
  setupClickListener("newinvoiceBtn","../home/createinvoice.php");
  setupClickListener("navinvoiceBtn","../invoicepage/invoices.php");
  setupClickListener("letsCreate","../home/createinvoice.php");
  setupClickListener("mypfl_btn","../profile/profile.php");
  setupClickListener("invoiceTo","../invoicepage/invoices.php");


  function setupClickListener(elementId, targetUrl) {
    var element = document.getElementById(elementId);
    if (element) {
      element.addEventListener("click", function () {

        window.location.href = targetUrl;
      });
    }
  }
  document.addEventListener('DOMContentLoaded', function() {
    // Get the date input fields
    var dueDateInput = document.querySelector('input[name="inv_due_date"]');
    var dateInput = document.querySelector('input[name="inv_date"]');
    
    // Create a new Date object
    var today = new Date();
    
    // Format the date as YYYY-MM-DD
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    var day = String(today.getDate()).padStart(2, '0');
    var formattedDate = `${year}-${month}-${day}`;
    
    // Set the value of the date input fields
    dueDateInput.value = formattedDate;
    dateInput.value = formattedDate;
});



function toggleDropdown(invoiceId) {
    console.log(invoiceId); // Logging the invoice ID for debugging
    
    // Get all dropdown menus
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    
    // Close all dropdowns except the one being toggled
    allDropdowns.forEach(menu => {
        if (menu.id !== `dropdownMenu_${invoiceId}`) {
            menu.style.display = 'none';
        }
    });
    
    // Get the dropdown menu for the clicked button
    const dropdownMenu = document.getElementById(`dropdownMenu_${invoiceId}`);
    
    // Toggle the display of the clicked dropdown menu
    if (dropdownMenu.style.display === 'block') {
        dropdownMenu.style.display = 'none';
    } else {
        dropdownMenu.style.display = 'block';
    }
}





function toggleDropdown1() {
    const dropdownMenu1 = document.getElementById("dropdownMenu1");
    dropdownMenu1.classList.toggle("show");
    
  
    
  }



function showSignup() {
    document.getElementById('signupDiv').style.display = 'flex';
    document.getElementById('loginDiv').style.display = 'none';
    document.getElementById('completeDiv').style.display = 'none';
    document.getElementById('login_image').attr('src', '../logic_pic.jpg'); 
    //document.getElementById('loginform').reset();
    // document.getElementById('signupvald').innerText = '';
    

    
}

function showLogin(){
    
    window.location.href = "loginsignup.php";
    document.getElementById('signupDiv').style.display = 'none';
    document.getElementById('loginDiv').style.display = 'flex';
    document.getElementById('completeDiv').style.display = 'none';
    //document.getElementById('signupform').reset();
    // document.getElementById('loginvald').innerText = '';



}



 //animation for login and signup

 var scrollAnimElements = document.querySelectorAll("[data-animate-on-scroll]");
 var observer = new IntersectionObserver(
   (entries) => {
     for (const entry of entries) {
       if (entry.isIntersecting || entry.intersectionRatio > 0) {
         const targetElement = entry.target;
         targetElement.classList.add("animate");
         observer.unobserve(targetElement);
       }
     }
   },
   {
     threshold: 0.15,
   }
 );
 for (let i = 0; i < scrollAnimElements.length; i++) {
   observer.observe(scrollAnimElements[i]);
 }

 
 function showPremium() {
    window.location.href = "../purchase/purchase.php?pack=premium";
}

function showBasic() {
    window.location.href = "../purchase/purchase.php?pack=basic";
}

function showStandard() {
    window.location.href = "../purchase/purchase.php?pack=standard";
}


window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const pack = urlParams.get('pack');

    // Default to hiding all divs
    document.getElementById('premium').style.display = 'none';
    document.getElementById('standard').style.display = 'none';
    document.getElementById('basic').style.display = 'none';

    // Show the appropriate div based on the "pack" parameter
    if (pack === 'premium') {
        document.getElementById('premium').style.display = 'flex';
    } else if (pack === 'basic') {
        document.getElementById('basic').style.display = 'flex';
    } else if (pack === 'standard') {
        document.getElementById('standard').style.display = 'flex';
    }
};




function initiateKhaltiPayment(packageName, amount) {
    console.log("Initiating payment for: " + packageName + ", Amount: " + amount);

    fetch('../phpHandlers/verifypayment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            package: packageName,
            amount: amount * 100,  // Convert to paisa for Khalti
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Payment response:', data);  // Debugging line
        if (data.payment_url) {
            window.location.href = data.payment_url;
        } else {
            console.error('Failed to initiate payment', data);
        }
    })
    .catch(error => {
        console.error('Error initiating payment:', error);
    });
}


document.addEventListener('DOMContentLoaded', function() {
    // Get all "Pay with Khalti" buttons
    const khaltiButtons = document.querySelectorAll('.button-khalti');

    // Add click event listener to each button
    khaltiButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retrieve package details from data attributes
            const packageName = this.getAttribute('data-package');
            const amount = parseFloat(this.getAttribute('data-amount'));

            // Call the function to initiate Khalti payment
            initiateKhaltiPayment(packageName, amount);
        });
    });
});


function downloadInvoice(invoiceId) {

   // window.location.href = `download_invoice.php?id=${invoiceId}`;
}


function deleteInvoice(invoiceId) {

      var confirmation = confirm('Are you sure you want to delete this Invoice?');
      if (confirmation) {
          $.ajax({
              type: 'POST',
              url: '../phpHandlers/delete_invoice.php',
              data: { invoice_id: invoiceId },
              success: function(response) {

                  handleDeleteResponse(response);
              },
              error: function(error) {
                  // Handle the error (e.g., display an error message)
                  console.error(error);
              }
          }); 
      }
  }
  function handleDeleteResponse(response) {
    console.log(response);

    try {
        var data = JSON.parse(response);
        
        if (data.status === 'success') {
            location.reload();
            // Optionally, update the UI or reload the page
        } else {
            alert('Error in deleting Invoice: ' + data.message);
            // Handle the case where the book deletion failed
        }
    } catch (e) {
        console.error('Error parsing JSON response', e);
        alert('Unexpected error in deleting invoice');
    }
}

function markAsPaid(invoiceId) {
    $.ajax({
        url: '../phpHandlers/update_status.php',  // Backend PHP file to handle the update
        type: 'POST',
        data: { invoice_id: invoiceId },
        success: function(response) {

            console.log(response);
            window.location.reload();
        
  
        }
    });
}

//Esewa Integration

// Function to generate the signature
