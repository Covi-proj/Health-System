
function togglePasswords(isChecked) {
    const hiddenPasswords = document.querySelectorAll('.password-hidden');
    const visiblePasswords = document.querySelectorAll('.password-visible');

    if (isChecked) {
        hiddenPasswords.forEach(span => span.style.display = "none");
        visiblePasswords.forEach(span => span.style.display = "inline");
    } else {
        hiddenPasswords.forEach(span => span.style.display = "inline");
        visiblePasswords.forEach(span => span.style.display = "none");
    }
}

function showPage(pageId) {
    $('.page').removeClass('active');
    $('#' + pageId).addClass('active');
}

// Toggle Form Visibility
$(document).ready(function () {
    $('#toggleForm').on('click', function () {
        $('#paymentForm').toggleClass('hidden');
    });

    // Load User Name
    $.ajax({
        url: 'getUserName.php',
        type: 'GET',
        success: function (response) {
            $('#username').text(response);
        },
        error: function () {
            alert('Error fetching the name.');
        }
    });
});

function logout() {
alert("Logging out...");

// Replace current history state with login page to prevent going back
window.location.replace("login.php");

// Alternatively, to ensure the history state is cleared:
window.history.pushState(null, null, window.location.href);
window.onpopstate = function() {
    window.history.go(1); // Prevent going back to the previous page
};
}

  // Get modal and button elements
var modal = document.getElementById('userFormModal');
var btn = document.getElementById('newUserButton');
var closeModal = document.getElementById('closeModal');

// Show modal when button is clicked
btn.onclick = function() {
modal.style.display = "block";
}

// Close modal when "X" is clicked
closeModal.onclick = function() {
modal.style.display = "none";
}

// Close modal when user clicks anywhere outside of modal
window.onclick = function(event) {
if (event.target == modal) {
modal.style.display = "none";
}
}

// Close modal when cancel button is clicked
document.querySelector('.cancelBtn').onclick = function() {
modal.style.display = "none";
}

// Example search function
const searchInput = document.getElementById('search');
const tableBody = document.getElementById('table-body');

searchInput.addEventListener('input', function () {
    const filter = searchInput.value.toLowerCase();
    const rows = tableBody.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let match = false;
        
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().includes(filter)) {
                match = true;
                break;
            }
        }
        
        if (match) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
});


//New user Ajax
$(document).ready(function() {
$("#newUserForm").submit(function(event) {
event.preventDefault(); // Prevent form submission

var formData = new FormData(this); // Get form data

$.ajax({
  url: 'new_user.php', // Your backend script
  type: 'POST',
  data: formData,
  dataType: 'json', // Expected response from server
  contentType: false,
  processData: false,
  success: function(response) {
    if (response.success) {
      // Trigger external modal update with the message
      updateModalMessage(response.message);
      $('#successModal').css('display', 'block'); // Show modal
      $('#newUserForm')[0].reset(); // Reset form after successful submission
    } else {
      alert(response.message); // Display error message if any
    }
  },
  error: function() {
    alert("An error occurred while adding the user.");
  }
});
});
});

// Function to update modal message
function updateModalMessage(message) {
document.getElementById('modalMessage').textContent = message;
}