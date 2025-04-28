//  makeinovice.php javascript
function toggleDueDate() {
    const status = document.getElementById('status').value;
    const dueDateField = document.querySelector('.due-date-field');
    const dueDateInput = document.getElementById('due_date');
    
    if (status === 'pending' || status === 'unpaid') {
        dueDateField.style.display = 'block';
        dueDateInput.required = true;
    } else {
        dueDateField.style.display = 'none';
        dueDateInput.required = false;
    }
}

// Initialize due date field visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDueDate();
});

function togglePaymentFields() {
    const paymentMethod = document.getElementById('payment_method').value;
    const bankFields = document.querySelector('.bank-fields');
    const paypalFields = document.querySelector('.paypal-fields');
    
    // Hide all payment fields first
    bankFields.style.display = 'none';
    paypalFields.style.display = 'none';
    
    // Remove required attribute from all fields
    document.getElementById('bank_name').required = false;
    document.getElementById('account_number').required = false;
    document.getElementById('paypal_email').required = false;
    
    // Show and make required the relevant fields
    if (paymentMethod === 'bank') {
        bankFields.style.display = 'block';
        document.getElementById('bank_name').required = true;
        document.getElementById('account_number').required = true;
    } else if (paymentMethod === 'paypal') {
        paypalFields.style.display = 'block';
        document.getElementById('paypal_email').required = true;
    }
}

function addItem() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row row align-items-end';
    newRow.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control" name="item_desc[]" placeholder="Item Description" required>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="item_qty[]" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="item_price[]" placeholder="Price" step="0.01" max="1000000" required oninput="validatePrice(this)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function removeItem(button) {
    if(document.querySelectorAll('.item-row').length > 1) {
        button.closest('.item-row').remove();
    }
}

// function validatePrice(input) {
//     if(input.value > 100000000000) {
//         input.setCustomValidity('Price cannot exceed 100,000,000,000');
//     } else {
//         input.setCustomValidity('');
//     }
// }

// function validatePrice(input) {
//     if(input.value > 100000000000) {
//         input.setCustomValidity('Price cannot exceed 100,000,000,000');
//     } else {
//         input.setCustomValidity('');
//     }
// }