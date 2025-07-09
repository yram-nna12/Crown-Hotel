<?php
// This is a placeholder for your database connection.
// In a real application, you would include your database connection file here.
// For now, we'll simulate data.

// Include your database connection file once it's ready:
// include_once 'path/to/your/database_connection.php';

// Simulated booking data. In a real scenario, this would come from your database.
// Each array represents a row from your 'bookings' table.
// IMPORTANT: Expanded data to include all fields needed for the edit modal.
$bookings = [
    [
        'id' => 1,
        'customer_name' => 'Mary Ann B. Camacho',
        'status' => 'paid',
        'room_type' => 'Suite Room',
        'hotel_branch' => 'Crown Hotel at Legaspi',
        'check_in' => '2025-07-17', // Added for modal
        'check_out' => '2025-07-20', // Added for modal
        'payment_type' => 'E-Wallet/E-Money Payment',
        'payment_method_detail' => 'GCASH', // Added for modal
        'account_number' => '0956212135168', // Added for modal
        'account_name' => 'Mary Ann Camacho', // Added for modal
        'amount' => 1200.00 // Added for modal
    ],
    [
        'id' => 2,
        'customer_name' => 'John Doe',
        'status' => 'cancelled',
        'room_type' => 'Deluxe Room',
        'hotel_branch' => 'Crown Hotel at Cebu',
        'check_in' => '2025-08-01',
        'check_out' => '2025-08-05',
        'payment_type' => 'Credit Card',
        'payment_method_detail' => 'Visa',
        'account_number' => '**** **** **** 1234',
        'account_name' => 'John Doe',
        'amount' => 2500.00
    ],
    [
        'id' => 3,
        'customer_name' => 'Jane Smith',
        'status' => 'pending',
        'room_type' => 'Standard Room',
        'hotel_branch' => 'Crown Hotel at Davao',
        'check_in' => '2025-09-10',
        'check_out' => '2025-09-12',
        'payment_type' => 'Bank Transfer',
        'payment_method_detail' => 'BDO',
        'account_number' => '***********9876',
        'account_name' => 'Jane Smith',
        'amount' => 800.00
    ],
    [
        'id' => 4,
        'customer_name' => 'Robert Johnson',
        'status' => 'paid',
        'room_type' => 'Family Room',
        'hotel_branch' => 'Crown Hotel at Boracay',
        'check_in' => '2025-10-20',
        'check_out' => '2025-10-25',
        'payment_type' => 'Cash',
        'payment_method_detail' => 'N/A',
        'account_number' => 'N/A',
        'account_name' => 'Robert Johnson',
        'amount' => 3000.00
    ]
];

// Calculate the total number of bookings
$totalBookedCount = count($bookings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="./assets/images/LOGO.png" alt="Crown Hotel Logo" class="logo-img">
            </div>
            <nav class="menu">
                <a href="../index.php">Dashboard</a>
                <a href="../Rooms/index.php">Rooms</a>
                <a href="../Users/index.php">Users</a>
                <a href="#" class="active">Bookings</a>
            </nav>
            <div class="profile">
                <img src="./assets/images/admin-profile.png" alt="Admin Icon" />
            </div>
        </aside>

        <main class="main-content">
            <div class="bookings-header-container">
                <h2>Total Booked <span class="total-booked-count"><?php echo str_pad($totalBookedCount, 2, '0', STR_PAD_LEFT); ?></span></h2>
                <span id="addBookingButton" class="add-booking-icon">+</span>
            </div>

            <div class="bookings-list">
                <?php if (empty($bookings)): ?>
                    <p>No bookings found.</p>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card" data-id="<?php echo htmlspecialchars($booking['id']); ?>">
                            <div class="card-row header-row">
                                <div class="booking-info-left">
                                    <span class="customer-name" data-field="customer_name"><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                                    <span class="status-tag <?php echo htmlspecialchars($booking['status']); ?>" data-field="status">
                                        <?php echo htmlspecialchars(strtoupper($booking['status'])); ?>
                                    </span>
                                </div>
                                <div class="booking-icons">
                                    <span class="edit-icon" data-id="<?php echo $booking['id']; ?>">&#9998;</span>
                                    <span class="delete-icon" data-id="<?php echo $booking['id']; ?>">&times;</span>
                                </div>
                            </div>
                            <div class="card-row details-row">
                                <p class="room-type" data-field="room_type"><?php echo htmlspecialchars($booking['room_type']); ?></p>
                                <p class="hotel-branch" data-field="hotel_branch"><?php echo htmlspecialchars($booking['hotel_branch']); ?></p>
                                <p class="payment-type" data-field="payment_type"><?php echo htmlspecialchars($booking['payment_type']); ?></p>
                                <span style="display:none;" data-field="check_in"><?php echo htmlspecialchars($booking['check_in']); ?></span>
                                <span style="display:none;" data-field="check_out"><?php echo htmlspecialchars($booking['check_out']); ?></span>
                                <span style="display:none;" data-field="payment_method_detail"><?php echo htmlspecialchars($booking['payment_method_detail']); ?></span>
                                <span style="display:none;" data-field="account_number"><?php echo htmlspecialchars($booking['account_number']); ?></span>
                                <span style="display:none;" data-field="account_name"><?php echo htmlspecialchars($booking['account_name']); ?></span>
                                <span style="display:none;" data-field="amount"><?php echo htmlspecialchars($booking['amount']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

        <div id="editBookingModal" class="modal">
            <div class="modal-content">
                <span class="close-button edit-modal-close">&times;</span> <h2>Edit Booking <span class="edit-icon">&#9998;</span></h2>

                <p id="modalErrorMessage" class="error-message" style="display:none;"></p>

                <form id="editBookingForm" method="POST">
                    <input type="hidden" id="modal_booking_id" name="booking_id">

                    <div class="form-group">
                        <label for="modal_customer_name">Customer Name</label>
                        <input type="text" id="modal_customer_name" name="customer_name" required>
                    </div>

                    <div class="form-group">
                        <label for="modal_room_type">Room Type</label>
                        <select id="modal_room_type" name="room_type" required>
                            <option value="Suite Room">Suite Room</option>
                            <option value="Deluxe Room">Deluxe Room</option>
                            <option value="Standard Room">Standard Room</option>
                            <option value="Family Room">Family Room</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modal_hotel_branch">Hotel Branch</label>
                        <select id="modal_hotel_branch" name="hotel_branch" required>
                            <option value="Crown Hotel at Legaspi">Crown Hotel at Legaspi</option>
                            <option value="Crown Hotel at Cebu">Crown Hotel at Cebu</option>
                            <option value="Crown Hotel at Davao">Crown Hotel at Davao</option>
                            <option value="Crown Hotel at Boracay">Crown Hotel at Boracay</option>
                        </select>
                    </div>

                    <div class="check-in-out-group">
                        <div class="form-group">
                            <label for="modal_check_in_date">Check In Date</label>
                            <input type="date" id="modal_check_in_date" name="check_in_date" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_check_out_date">Check Out Date</label>
                            <input type="date" id="modal_check_out_date" name="check_out_date" required>
                        </div>
                    </div>

                    <div class="payment-details-group">
                        <h3>Payment Details</h3>
                        <div class="form-group">
                            <label for="modal_payment_type">Payment Type</label>
                            <select id="modal_payment_type" name="payment_type" required>
                                <option value="E-Wallet/E-Money Payment">E-Wallet/E-Money Payment</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="modal_payment_method_detail">Payment Method Detail (e.g., GCASH, Visa, BDO)</label>
                            <input type="text" id="modal_payment_method_detail" name="payment_method_detail">
                        </div>

                        <div class="form-group">
                            <label for="modal_account_number">Account Number</label>
                            <input type="text" id="modal_account_number" name="account_number">
                        </div>

                        <div class="form-group">
                            <label for="modal_account_name">Account Name</label>
                            <input type="text" id="modal_account_name" name="account_name">
                        </div>
                    </div>

                    <div class="amount-status-group">
                        <div class="total-amount-display">
                            <span class="amount-display">₱ <input type="number" step="0.01" id="modal_amount" name="amount" required style="width: 120px; text-align: right; background-color: transparent; border: none; color: inherit; font-size: inherit; font-weight: inherit;"></span>
                        </div>
                        <div class="form-group">
                            <label for="modal_status">Status</label>
                            <select id="modal_status" name="status" class="status-select" required>
                                <option value="paid">PAID</option>
                                <option value="cancelled">CANCELLED</option>
                                <option value="pending">PENDING</option>
                            </select>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="cancel-button edit-modal-close">Cancel</button>
                        <button type="submit" class="save-button">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="addBookingModal" class="modal">
            <div class="modal-content">
                <span class="close-button add-modal-close">&times;</span> <h2>Add New Booking <span>+</span></h2> <p id="addModalErrorMessage" class="error-message" style="display:none;"></p>

                <form id="addBookingForm" method="POST">
                    <div class="form-group">
                        <label for="new_customer_name">Customer Name</label>
                        <input type="text" id="new_customer_name" name="customer_name" required>
                    </div>

                    <div class="form-group">
                        <label for="new_room_type">Room Type</label>
                        <select id="new_room_type" name="room_type" required>
                            <option value="Suite Room">Suite Room</option>
                            <option value="Deluxe Room">Deluxe Room</option>
                            <option value="Standard Room">Standard Room</option>
                            <option value="Family Room">Family Room</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="new_hotel_branch">Hotel Branch</label>
                        <select id="new_hotel_branch" name="hotel_branch" required>
                            <option value="Crown Hotel at Legaspi">Crown Hotel at Legaspi</option>
                            <option value="Crown Hotel at Cebu">Crown Hotel at Cebu</option>
                            <option value="Crown Hotel at Davao">Crown Hotel at Davao</option>
                            <option value="Crown Hotel at Boracay">Crown Hotel at Boracay</option>
                        </select>
                    </div>

                    <div class="check-in-out-group">
                        <div class="form-group">
                            <label for="new_check_in_date">Check In Date</label>
                            <input type="date" id="new_check_in_date" name="check_in_date" required>
                        </div>
                        <div class="form-group">
                            <label for="new_check_out_date">Check Out Date</label>
                            <input type="date" id="new_check_out_date" name="check_out_date" required>
                        </div>
                    </div>

                    <div class="payment-details-group">
                        <h3>Payment Details</h3>
                        <div class="form-group">
                            <label for="new_payment_type">Payment Type</label>
                            <select id="new_payment_type" name="payment_type" required>
                                <option value="E-Wallet/E-Money Payment">E-Wallet/E-Money Payment</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="new_payment_method_detail">Payment Method Detail (e.g., GCASH, Visa, BDO)</label>
                            <input type="text" id="new_payment_method_detail" name="payment_method_detail">
                        </div>

                        <div class="form-group">
                            <label for="new_account_number">Account Number</label>
                            <input type="text" id="new_account_number" name="account_number">
                        </div>

                        <div class="form-group">
                            <label for="new_account_name">Account Name</label>
                            <input type="text" id="new_account_name" name="account_name">
                        </div>
                    </div>

                    <div class="amount-status-group">
                        <div class="total-amount-display">
                            <span class="amount-display">₱ <input type="number" step="0.01" id="new_amount" name="amount" required style="width: 120px; text-align: right; background-color: transparent; border: none; color: inherit; font-size: inherit; font-weight: inherit;"></span>
                        </div>
                        <div class="form-group">
                            <label for="new_status">Status</label>
                            <select id="new_status" name="status" class="status-select" required>
                                <option value="paid">PAID</option>
                                <option value="cancelled">CANCELLED</option>
                                <option value="pending">PENDING</option>
                            </select>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="cancel-button add-modal-close">Cancel</button>
                        <button type="submit" class="save-button">Add Booking</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="notification" class="notification"></div>
    </div>

    <script>
        // PHP to JavaScript: Pass the initial bookings data to JS
        // This makes the data available for client-side manipulation
        const allBookingsData = <?php echo json_encode($bookings); ?>;
        const bookingsById = {};
        allBookingsData.forEach(booking => {
            bookingsById[booking.id] = booking;
        });

        // Get elements for Edit Modal
        const editBookingModal = document.getElementById("editBookingModal");
        const editModalCloseButtons = document.querySelectorAll(".edit-modal-close"); // Specific class for edit modal close
        const editBookingForm = document.getElementById("editBookingForm");
        const editModalErrorMessage = document.getElementById("modalErrorMessage"); // Renamed for clarity

        // Get form fields in the Edit Modal
        const modalBookingId = document.getElementById("modal_booking_id");
        const modalCustomerName = document.getElementById("modal_customer_name");
        const modalRoomType = document.getElementById("modal_room_type");
        const modalHotelBranch = document.getElementById("modal_hotel_branch");
        const modalCheckInDate = document.getElementById("modal_check_in_date");
        const modalCheckOutDate = document.getElementById("modal_check_out_date");
        const modalPaymentType = document.getElementById("modal_payment_type");
        const modalPaymentMethodDetail = document.getElementById("modal_payment_method_detail");
        const modalAccountNumber = document.getElementById("modal_account_number");
        const modalAccountName = document.getElementById("modal_account_name");
        const modalAmount = document.getElementById("modal_amount");
        const modalStatus = document.getElementById("modal_status");

        // Get elements for Add Booking Modal (NEW)
        const addBookingModal = document.getElementById("addBookingModal");
        const addModalCloseButtons = document.querySelectorAll(".add-modal-close"); // Specific class for add modal close
        const addBookingForm = document.getElementById("addBookingForm");
        const addModalErrorMessage = document.getElementById("addModalErrorMessage");

        // Get form fields in the Add Booking Modal (NEW)
        const newCustomerName = document.getElementById("new_customer_name");
        const newRoomType = document.getElementById("new_room_type");
        const newHotelBranch = document.getElementById("new_hotel_branch");
        const newCheckInDate = document.getElementById("new_check_in_date");
        const newCheckOutDate = document.getElementById("new_check_out_date");
        const newPaymentType = document.getElementById("new_payment_type");
        const newPaymentMethodDetail = document.getElementById("new_payment_method_detail");
        const newAccountNumber = document.getElementById("new_account_number");
        const newAccountName = document.getElementById("new_account_name");
        const newAmount = document.getElementById("new_amount");
        const newStatus = document.getElementById("new_status");

        // General elements
        const addBookingButton = document.getElementById("addBookingButton"); // The main '+' icon
        const bookingsListContainer = document.querySelector(".bookings-list");
        const totalBookedCountSpan = document.querySelector(".total-booked-count");
        const notificationElement = document.getElementById("notification");

        // Function to show notification
        function showNotification(message, isError = false) {
            notificationElement.textContent = message;
            notificationElement.className = 'notification'; // Reset classes
            if (isError) {
                notificationElement.classList.add('error');
            }
            notificationElement.style.display = 'block';
            notificationElement.style.opacity = '1';

            setTimeout(() => {
                notificationElement.style.opacity = '0';
                setTimeout(() => {
                    notificationElement.style.display = 'none';
                }, 500); // Wait for fade out to complete before hiding
            }, 3000); // Hide after 3 seconds
        }

        // Function to open the Edit modal and populate data
        function openEditModal(bookingId) {
            editModalErrorMessage.style.display = 'none'; // Hide any previous error messages

            const booking = bookingsById[bookingId];

            if (booking) {
                modalBookingId.value = booking.id;
                modalCustomerName.value = booking.customer_name;
                modalRoomType.value = booking.room_type;
                modalHotelBranch.value = booking.hotel_branch;
                modalCheckInDate.value = booking.check_in || '';
                modalCheckOutDate.value = booking.check_out || '';
                modalPaymentType.value = booking.payment_type;
                modalPaymentMethodDetail.value = booking.payment_method_detail || '';
                modalAccountNumber.value = booking.account_number || '';
                modalAccountName.value = booking.account_name || '';
                modalAmount.value = parseFloat(booking.amount || 0).toFixed(2);
                modalStatus.value = booking.status;

                editBookingModal.style.display = "block"; // Show the modal
            } else {
                console.error("Booking not found for ID:", bookingId);
                showNotification("Error: Booking not found.", true);
            }
        }

        // Function to close the Edit modal
        function closeEditModal() {
            editBookingModal.style.display = "none";
            editBookingForm.reset(); // Reset form fields
        }

        // Function to open the Add Booking modal (NEW)
        function openAddModal() {
            addModalErrorMessage.style.display = 'none'; // Hide any previous error messages
            addBookingForm.reset(); // Clear all fields for a new entry
            addBookingModal.style.display = "block"; // Show the modal
            newStatus.value = 'pending'; // Default status for new bookings
        }

        // Function to close the Add Booking modal (NEW)
        function closeAddModal() {
            addBookingModal.style.display = "none";
            addBookingForm.reset(); // Reset form fields
        }

        // Event listener for opening Add Booking modal
        addBookingButton.addEventListener("click", openAddModal);

        // Event listeners for closing modals
        editModalCloseButtons.forEach(button => {
            button.addEventListener("click", closeEditModal);
        });
        addModalCloseButtons.forEach(button => { // NEW
            button.addEventListener("click", closeAddModal);
        });

        // Close modal if user clicks outside of it
        window.addEventListener("click", function(event) {
            if (event.target == editBookingModal) {
                closeEditModal();
            } else if (event.target == addBookingModal) { // NEW
                closeAddModal();
            }
        });

        // Event Delegation for Edit and Delete icons (REFACTORED)
        bookingsListContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains('edit-icon')) {
                const bookingId = event.target.dataset.id;
                openEditModal(bookingId);
            } else if (event.target.classList.contains('delete-icon')) {
                const bookingId = event.target.dataset.id;
                if (confirm('Are you sure you want to delete this booking?')) {
                    // --- SIMULATED CLIENT-SIDE DELETE (for demonstration without backend) ---
                    if (bookingsById[bookingId]) {
                        delete bookingsById[bookingId]; // Remove from JS object
                        event.target.closest('.booking-card').remove(); // Remove from UI
                        
                        // Update total count
                        const currentCount = parseInt(totalBookedCountSpan.textContent);
                        totalBookedCountSpan.textContent = String(currentCount - 1).padStart(2, '0');

                        showNotification("Booking deleted successfully!");
                    } else {
                        showNotification("Error: Booking not found for deletion.", true);
                    }
                    // --- END SIMULATED CLIENT-SIDE DELETE ---

                    // --- REAL AJAX Delete (UNCOMMENT AND IMPLEMENT WHEN DATABASE IS READY) ---
                    /*
                    fetch(`delete_booking.php?id=${bookingId}`, {
                        method: 'GET' // Or POST if you prefer
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            event.target.closest('.booking-card').remove();
                            // Update total count
                            const currentCount = parseInt(totalBookedCountSpan.textContent);
                            totalBookedCountSpan.textContent = String(currentCount - 1).padStart(2, '0');
                            showNotification("Booking deleted successfully!");
                        } else {
                            showNotification(data.message || "Failed to delete booking.", true);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification("An error occurred during deletion.", true);
                    });
                    */
                }
            }
        });


        // Handle Edit Form submission via AJAX (simulated for now)
        editBookingForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(editBookingForm);
            const updatedBooking = {};
            for (let [key, value] of formData.entries()) {
                updatedBooking[key] = value;
            }

            const bookingId = parseInt(updatedBooking.booking_id);

            // --- SIMULATED CLIENT-SIDE UPDATE (for demonstration without backend) ---
            if (bookingsById[bookingId]) {
                Object.assign(bookingsById[bookingId], updatedBooking);
                updateBookingCardInUI(bookingId, bookingsById[bookingId]);
                closeEditModal();
                showNotification("Booking updated successfully!");
            } else {
                editModalErrorMessage.textContent = "Error: Booking not found for update.";
                editModalErrorMessage.style.display = 'block';
            }
            // --- END SIMULATED CLIENT-SIDE UPDATE ---

            // --- REAL AJAX Update (UNCOMMENT AND IMPLEMENT WHEN DATABASE IS READY) ---
            /*
            fetch('update_booking_ajax.php', { // You'd create this PHP file
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateBookingCardInUI(bookingId, data.updatedBookingData || updatedBooking);
                    closeEditModal();
                    showNotification("Booking updated successfully!");
                } else {
                    editModalErrorMessage.textContent = data.message || "Failed to update booking.";
                    editModalErrorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                editModalErrorMessage.textContent = "An error occurred during update.";
                editModalErrorMessage.style.display = 'block';
            });
            */
        });

        // Handle Add New Booking Form submission (NEW)
        addBookingForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(addBookingForm);
            const newBooking = {};
            for (let [key, value] of formData.entries()) {
                newBooking[key] = value;
            }

            // Generate a new unique ID for the simulated booking
            // Get the maximum existing ID and add 1, or start at 1 if no bookings
            const newId = (Object.keys(bookingsById).length > 0) ? Math.max(...Object.keys(bookingsById).map(Number)) + 1 : 1;
            newBooking.id = newId;

            // --- SIMULATED CLIENT-SIDE ADD (for demonstration without backend) ---
            bookingsById[newId] = newBooking; // Add to our JS object

            // Create and append the new booking card to the UI
            const newCardHTML = createBookingCardHTML(newBooking);
            bookingsListContainer.insertAdjacentHTML('beforeend', newCardHTML);

            // Update total count
            const currentCount = parseInt(totalBookedCountSpan.textContent);
            totalBookedCountSpan.textContent = String(currentCount + 1).padStart(2, '0');

            closeAddModal();
            showNotification("New booking added successfully!");
            // --- END SIMULATED CLIENT-SIDE ADD ---

            // --- REAL AJAX Add (UNCOMMENT AND IMPLEMENT WHEN DATABASE IS READY) ---
            /*
            fetch('create_booking_ajax.php', { // You'd create this PHP file
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Assuming server returns the full new booking data including its actual ID
                    const createdBooking = data.newBookingData;
                    bookingsById[createdBooking.id] = createdBooking;
                    const newCardHTML = createBookingCardHTML(createdBooking);
                    bookingsListContainer.insertAdjacentHTML('beforeend', newCardHTML);

                    // Update total count
                    const currentCount = parseInt(totalBookedCountSpan.textContent);
                    totalBookedCountSpan.textContent = String(currentCount + 1).padStart(2, '0');

                    closeAddModal();
                    showNotification("New booking added successfully!");
                } else {
                    addModalErrorMessage.textContent = data.message || "Failed to add new booking.";
                    addModalErrorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addModalErrorMessage.textContent = "An error occurred during booking creation.";
                addModalErrorMessage.style.display = 'block';
            });
            */
        });

        // Function to update the booking card elements on the main page
        function updateBookingCardInUI(id, data) {
            const cardElement = document.querySelector(`.booking-card[data-id="${id}"]`);
            if (cardElement) {
                cardElement.querySelector('[data-field="customer_name"]').textContent = data.customer_name;
                
                const statusTag = cardElement.querySelector('[data-field="status"]');
                statusTag.textContent = data.status.toUpperCase();
                statusTag.className = `status-tag ${data.status}`; // Update class for color

                cardElement.querySelector('[data-field="room_type"]').textContent = data.room_type;
                cardElement.querySelector('[data-field="hotel_branch"]').textContent = data.hotel_branch;
                cardElement.querySelector('[data-field="payment_type"]').textContent = data.payment_type;

                // Update hidden fields in the card for consistency, although not visible
                cardElement.querySelector('[data-field="check_in"]').textContent = data.check_in || '';
                cardElement.querySelector('[data-field="check_out"]').textContent = data.check_out || '';
                cardElement.querySelector('[data-field="payment_method_detail"]').textContent = data.payment_method_detail || '';
                cardElement.querySelector('[data-field="account_number"]').textContent = data.account_number || '';
                cardElement.querySelector('[data-field="account_name"]').textContent = data.account_name || '';
                cardElement.querySelector('[data-field="amount"]').textContent = parseFloat(data.amount || 0).toFixed(2);
            }
        }

        // Function to create HTML string for a new booking card (NEW)
        function createBookingCardHTML(booking) {
            // Ensure data fields are properly escaped for HTML insertion
            const customerName = escapeHtml(booking.customer_name);
            const status = escapeHtml(booking.status);
            const roomType = escapeHtml(booking.room_type);
            const hotelBranch = escapeHtml(booking.hotel_branch);
            const paymentType = escapeHtml(booking.payment_type);
            const checkIn = escapeHtml(booking.check_in || '');
            const checkOut = escapeHtml(booking.check_out || '');
            const paymentMethodDetail = escapeHtml(booking.payment_method_detail || '');
            const accountNumber = escapeHtml(booking.account_number || '');
            const accountName = escapeHtml(booking.account_name || '');
            const amount = parseFloat(booking.amount || 0).toFixed(2);

            return `
                <div class="booking-card" data-id="${booking.id}">
                    <div class="card-row header-row">
                        <div class="booking-info-left">
                            <span class="customer-name" data-field="customer_name">${customerName}</span>
                            <span class="status-tag ${status}" data-field="status">
                                ${status.toUpperCase()}
                            </span>
                        </div>
                        <div class="booking-icons">
                            <span class="edit-icon" data-id="${booking.id}">&#9998;</span>
                            <span class="delete-icon" data-id="${booking.id}">&times;</span>
                        </div>
                    </div>
                    <div class="card-row details-row">
                        <p class="room-type" data-field="room_type">${roomType}</p>
                        <p class="hotel-branch" data-field="hotel_branch">${hotelBranch}</p>
                        <p class="payment-type" data-field="payment_type">${paymentType}</p>
                        <span style="display:none;" data-field="check_in">${checkIn}</span>
                        <span style="display:none;" data-field="check_out">${checkOut}</span>
                        <span style="display:none;" data-field="payment_method_detail">${paymentMethodDetail}</span>
                        <span style="display:none;" data-field="account_number">${accountNumber}</span>
                        <span style="display:none;" data-field="account_name">${accountName}</span>
                        <span style="display:none;" data-field="amount">${amount}</span>
                    </div>
                </div>
            `;
        }

        // Helper function to escape HTML for dynamically created content
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            // Ensure text is treated as a string before replacing
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>