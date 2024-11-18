// Function to handle invoice creation
function createInvoice(userId) {
    if (!userId) {
        console.error('User ID is not defined.');
        return;
    }

    // Create an AJAX request to check the user's plan status
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../phpHandlers/check_plan.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Prepare the data to send
    const params = 'user_id=' + encodeURIComponent(userId);
    xhr.send(params);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response.status);

                if (response.status === 'redirect') {
                    alert("Buy a Plan to continue creating invoice!");
                    window.location.href = '../pricing/pricing.php';
                } else if (response.status === 'success') {
                    saveInvoice(userId);
                    createInvoices(userId);
                   
                    
                      
                }
            } else {
                console.error('Error checking plan status:', xhr.responseText);
            }
        }
    };
}

function saveInvoice(userId) {
    const element = document.querySelector('.frame-9');
    const inputs = element.querySelectorAll('input, textarea');

    // Temporarily hide borders and placeholders
    inputs.forEach(input => {
        input.style.border = 'none';
        input.style.boxShadow = 'none';
        input.setAttribute('data-placeholder', input.placeholder); // Store original placeholder
        input.placeholder = ''; // Remove placeholder text
    });

    // Elements to hide for the PDF
    const elementsToHide = element.querySelectorAll('.frame-53, .frame-3f, .cross_div');
    elementsToHide.forEach(el => el.classList.add('hidden-for-pdf'));



    const elementsToTransparent = element.querySelectorAll('.frame-3a'); // Define your class in HTML for transparent items
    elementsToTransparent.forEach(el => el.classList.add('pdf-transparent'));

    // Add classes to hide placeholders and borders
    element.classList.add('no-borders', 'no-placeholders');
    element.classList.add('bold-for-pdf');

        const elementsToBold = element.querySelectorAll('.who-is-this-from,.frame-4f'); // Define your class in HTML for bold text
        elementsToBold.forEach(el => el.classList.add('pdf-bold'));

    html2pdf().from(element).set({
        margin: 0, // Remove margins
        filename: 'invoice.pdf',
        image: { type: 'jpeg', quality: 1.0 }, // Highest quality
        html2canvas: {
            scale: 6, // Higher scale for better resolution
            letterRendering: true,
            useCORS: true // Enable cross-origin resource sharing
        },
        jsPDF: { 
            unit: 'mm', 
            format: [210, 297], // A4 size in mm
            orientation: 'portrait',
            precision: 16
        }
    }).save().finally(() => {
        // Restore original styles and placeholders after saving
        inputs.forEach(input => {
            input.style.border = '';
            input.style.boxShadow = '';
            input.placeholder = input.getAttribute('data-placeholder'); // Restore original placeholder text
        });

        // Remove temporary classes after saving
        element.classList.remove('no-borders', 'no-placeholders', 'bold-for-pdf');
        
        elementsToBold.forEach(el => el.classList.remove('bold-pdf'));
        elementsToTransparent.forEach(el => el.classList.remove('pdf-transparent'));

        elementsToHide.forEach(el => el.classList.remove('hidden-for-pdf'));
        upgradeCredits(userId);
        
    });
}

function upgradeCredits(userId) {
    if (!userId) {
        console.error('User ID is not defined.');
        return;
    }

    // Create an AJAX request to update credits
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../phpHandlers/update_credits.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Prepare the data to send
    const params = 'user_id=' + encodeURIComponent(userId);
    xhr.send(params);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    console.log("Credits decremented successfully.");
                    window.location.href = '../created/invo_created.php';
                    
                } else {
                    console.error('Error updating credits:', response.message);
                }
            } else {
                console.error('Error updating credits:', xhr.responseText);
            }
        }
    };
}

function createInvoices(userID) {
    // Collect invoice data
    const invoiceData = {
      user_id: userID,
      invoice_from: document.getElementById('inputField1').value,
      invoice_to: document.getElementById('inputField2').value,
      ship_to: document.querySelector('textarea[name="ship_to"]').value,
      invoice_number: document.querySelector('input[name="invoice_number"]').value,
      inv_date: document.querySelector('input[name="inv_date"]').value,
      inv_due_date: document.querySelector('input[name="inv_due_date"]').value,
      payment_terms: document.querySelector('input[name="payment_terms"]').value,
      inv_contact: document.querySelector('input[name="inv_contact"]').value,
      currency: document.querySelector('select[name="currency"]').value,
      subtotal: document.getElementById('subtotal').textContent,
      total_amt: document.getElementById('total').textContent,
      amt_paid: document.getElementById('amountPaid').value,
      balance_due: document.getElementById('balanceDue').textContent,
      inv_notes: document.querySelector('textarea[name="inv_notes"]').value,
      inv_terms: document.querySelector('input[name="inv_terms"]').value,
    };
  
    // Send data via AJAX request
    $.ajax({
      url: '../phpHandlers/insert_invoice.php', // PHP script URL for processing the request
      type: 'POST',
      data: invoiceData,
      success: function(response) {
        console.log(response);
       
      },
      error: function(xhr, status, error) {
        console.error(error);
        alert('Failed to create invoice.');
      }
    });
  }
  