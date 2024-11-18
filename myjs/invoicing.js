$(document).ready(function() {
    let divCount = 1;
    let shippingCount = 1;
    let taxCount = 1;

    // Function to add a new div for line items
    $("#addLineItem").click(function() {
        $("#billcontainers").append(
            `<div class="frame-35" id="divbill${divCount}">
                <div class="frame-36">
                    <input type="text" class="description-text" placeholder="Description of item/service...">
                </div>
                <div class="frame-37">
                    <input class="text-info-38" name="quantity" type="number" value="1" min="1">
                </div>
                <div class="frame-39">
                    <div class="frame-3a"><span class="currency">Rs</span></div>
                    <div class="frame-3c"><input class="text-info-3d" name="price" type="number" value="0" min="0"></div>
                </div>
                <div class="frame-3e"> <span class="total-amt">Rs</span>
                    <span class="total-amount">0.00</span>
                </div>
                <button class="cross_div" data-id="${divCount}"><img class="crossimg" src="assets/images/cross.svg"></button>   
            </div>`
        );
        divCount++;
        updateCalculations(); // Update calculations when a new item is added
    });

    // Event delegation to delete a specific line item div
    $("#billcontainers").on("click", ".cross_div", function() {
        const divId = $(this).data("id");
        $(`#divbill${divId}`).remove();
        updateCalculations(); // Update calculations when an item is removed
    });

    // Event delegation to update total amount when quantity or price changes
    $("#billcontainers").on("input", ".text-info-38, .text-info-3d", function() {
        const parentDiv = $(this).closest('.frame-35');
        const quantity = parseFloat(parentDiv.find('.text-info-38').val()) || 0;
        const price = parseFloat(parentDiv.find('.text-info-3d').val()) || 0;

        const totalAmount = quantity * price;
        parentDiv.find('.total-amount').text(`${totalAmount.toFixed(2)}`);
        updateCalculations(); // Update calculations whenever an item's total amount changes
    });

    // Function to initialize total amounts for existing line items
    function initializeExistingDivs() {
        $(".frame-35").each(function() {
            const quantity = parseFloat($(this).find('.text-info-38').val()) || 0;
            const price = parseFloat($(this).find('.text-info-3d').val()) || 0;
            const totalAmount = quantity * price;
            $(this).find('.total-amount').text(`${totalAmount.toFixed(2)}`);
        });
        updateCalculations(); // Initialize calculations for existing line items
    }

    function updateCalculations() {
        let subtotal = 0;
    
        // Calculate subtotal from all line items
        $(".frame-35").each(function() {
            const totalAmount = parseFloat($(this).find('.total-amount').text().replace('Rs. ', '')) || 0;
            subtotal += totalAmount;
        });
        $('#subtotal').text(`${subtotal.toFixed(2)}`);
    
        // Calculate discount
         
        const discountAmount = parseFloat($('#discount').val()) || 0;
    
        // Calculate tax
        let taxAmount = 0;
        $(".tax-entry").each(function() {
            const taxValue = parseFloat($(this).find('.date-52').val()) || 0;
            taxAmount += taxValue;
        });
        const taxPercent = parseFloat($('#tax').val()) || 0;
        const taxTotal = (taxPercent / 100) * (subtotal - discountAmount) + taxAmount;
    
        // Calculate shipping
        let shippingAmount = 0;
        $(".shipping-entry").each(function() {
            const shippingValue = parseFloat($(this).find('.date-52').val()) || 0;
            shippingAmount += shippingValue;
        });
    
        // Calculate total amount
        const total = subtotal - discountAmount + taxTotal + shippingAmount;
        $('#total').text(`${total.toFixed(2)}`);
    
        // Calculate balance due
        const amountPaid = parseFloat($('#amountPaid').val()) || 0;
        const balanceDue = total - amountPaid;
        $('#balanceDue').text(`${balanceDue.toFixed(2)}`);
    }
    
    // Event handlers for discount, tax, shipping, and amount paid
    $('#discount, #tax, #shipping, #amountPaid').on('input', updateCalculations);
    
    // Use event delegation to handle changes in dynamically added shipping and tax entries
    $("#shipping_tax").on('input', '.date-52', updateCalculations);
    



    // Event delegation to add new shipping entries
    $("#shippingBtn").click(function() {
        $("#shipping_tax").append(
            `<div class="shipping-entry" id="divshipping${shippingCount}">
                <input class="frame-4f" type="text" value="Shipping">
                <div class="frame-50">
                    <input type="number" name="dynamic_charge" class="date-52" value="0">
                </div>
                <button data-id="${shippingCount}" class="cross_div"><img class="crossimg" src="assets/images/cross.svg"></button>
            </div>`
        );
        shippingCount++;
        updateCalculations(); // Update calculations when a new shipping entry is added
    });

    // Event delegation to add new tax entries
    $("#taxBtn").click(function() {
        $("#shipping_tax").append(
            `<div class="tax-entry" id="divtax${taxCount}">
                <input type="text" value="Tax" class="frame-4f">
                <div class="frame-50">
                    <input type="number" name="dynamic_charge" class="date-52" value="0">
                </div>
                <button data-id="${taxCount}" class="cross_div"><img class="crossimg" src="assets/images/cross.svg"></button>
            </div>`
        );
        taxCount++;
        updateCalculations(); // Update calculations when a new tax entry is added
    });

    // Event delegation to delete specific shipping or tax entries
    $("#shipping_tax").on("click", ".cross_div", function() {
        $(this).closest('.shipping-entry, .tax-entry').remove();
        updateCalculations(); // Update calculations when an entry is removed
    });

    // Initialize existing divs and calculations on page load
    initializeExistingDivs();
});


function collectLineItemsData() {
    let items = [];
    $("#billcontainers .frame-35").each(function() {
        let description = $(this).find(".description-text").val();
        let quantity = $(this).find("input[name='quantity']").val();
        let price = $(this).find("input[name='price']").val();
        let totalAmount = $(this).find(".total-amount").text().replace('Rs. ', '');
        
        items.push({
            description: description,
            quantity: quantity,
            price: price,
            totalAmount: totalAmount
        });
    });
    return items;
}

   
    function loadSavedValues() {
        const inputField1Value = localStorage.getItem('inputField1');
        const inputField2Value = localStorage.getItem('inputField2');
        const savedImageUrl = localStorage.getItem('savedImageUrl');

        if (inputField1Value !== null) {
            $('#inputField1').val(inputField1Value);
        }
        if (inputField2Value !== null) {
            $('#inputField2').val(inputField2Value);
        }
        if (savedImageUrl !== null) {
            $('#previewImage').attr('src', savedImageUrl).show();
            $('#logo-add').hide(); // Hide the "Add a Logo" area if image is present
        }
    }

    // Save input values and uploaded image to localStorage
    $('#saveDefault').click(function() {
        const inputField1Value = $('#inputField1').val();
        const inputField2Value = $('#inputField2').val();
        const imageUrl = $('#previewImage').attr('src');

        localStorage.setItem('inputField1', inputField1Value);
        localStorage.setItem('inputField2', inputField2Value);
        localStorage.setItem('savedImageUrl', imageUrl || ''); // Save the image URL or empty string

        alert('Field Set to Default Values');
    });

    // Load saved values on page load
    loadSavedValues();

    document.getElementById('clearBtn').addEventListener('click', function() {
        localStorage.clear();
        // Optionally, you might want to refresh the page or redirect the user
        window.location.reload(); // Refresh the page to reflect the changes
    });

    $('#uploadWrapper').off('click').on('click', function() {
        console.log('Upload wrapper clicked');
        $('#fileInput').click(); // Trigger file input
    });

    // Handle file selection
    $('#fileInput').off('change').on('change', function(event) {
        console.log('File input changed');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            // Set up the FileReader to read the image
            reader.onload = function(e) {
                const imageUrl = e.target.result;
                
                console.log('File loaded, image URL:', imageUrl);
                // Hide the upload button text and show the image
                $('#logo-add').hide(); // Hide the "Add a Logo" area
                $('#previewImage').attr('src', imageUrl).show(); // Show the image

                // Save the image URL to localStorage immediately after selection
                localStorage.setItem('savedImageUrl', imageUrl);
            };
            
            // Read the file as a data URL
            reader.readAsDataURL(file);
        }
    });



        


    // Function to change currency symbols
function updateCurrencySymbol() {
    const currencySelect = document.getElementById('currency');
    const selectedCurrency = currencySelect.options[currencySelect.selectedIndex].text;
    
    const currencySymbol = selectedCurrency.split(" ").pop();
    
    // Update all elements that display the currency symbol (for 'Rs')
    document.querySelectorAll('.currency, .rs-5b, .span-rs,.total-amt, .rs').forEach(element => {
        if (element.classList.contains('total-amount') || element.classList.contains('span-rs')) {
            element.innerHTML = element.innerHTML.replace(/^.*/, currencySymbol + " "); // Replace the current symbol with the selected one
        } else {
            element.innerHTML = currencySymbol; // Update static currency symbols
        }
    });
}

// Attach event listener to the currency dropdown
document.getElementById('currency').addEventListener('change', updateCurrencySymbol);

    
    
    
    
    
    


    






