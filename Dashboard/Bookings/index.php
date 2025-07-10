<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle ADD Booking
if (isset($_POST['add_booking'])) {
    error_log("PHP: add_booking POST request received."); // Debugging log
    // Sanitize and validate inputs
    $customer_name_full = $_POST['new_customer_name'] ?? '';
    $name_parts = explode(' ', $customer_name_full, 2); // Split into at most 2 parts
    $first_name = $conn->real_escape_string($name_parts[0] ?? '');
    $last_name = $conn->real_escape_string($name_parts[1] ?? '');

    $room_type = $conn->real_escape_string($_POST['new_room_type'] ?? '');
    $hotel_branch = $conn->real_escape_string($_POST['new_hotel_branch'] ?? '');
    $check_in = $conn->real_escape_string($_POST['new_check_in_date'] ?? '');
    $check_out = $conn->real_escape_string($_POST['new_check_out_date'] ?? '');
    $payment_type = $conn->real_escape_string($_POST['new_payment_type'] ?? '');
    $payment_method_detail = $conn->real_escape_string($_POST['new_payment_method_detail'] ?? '');
    $account_number = $conn->real_escape_string($_POST['new_account_number'] ?? '');
    $account_name = $conn->real_escape_string($_POST['new_account_name'] ?? '');
    $amount = filter_var($_POST['new_amount'] ?? 0, FILTER_VALIDATE_FLOAT);
    $status = $conn->real_escape_string($_POST['new_status'] ?? '');
    $reservation_date = date('Y-m-d H:i:s'); // Current timestamp

    // Generate a simple transaction_id (you might want a more robust method)
    $transaction_id = 'TRN-' . uniqid(); 

    // Debugging: Log all received POST data
    error_log("PHP: Received POST data: " . print_r($_POST, true));
    error_log("PHP: Generated transaction_id: " . $transaction_id);

    if (!empty($first_name) && !empty($room_type) && !empty($hotel_branch) && !empty($check_in) && !empty($check_out) && !empty($payment_type) && $amount !== false && !empty($status)) {
        $stmt = $conn->prepare("INSERT INTO booking_db (transaction_id, first_name, last_name, room_type, hotel_branch, check_in, check_out, payment_type, payment_method_detail, account_number, account_name, amount, status, reservation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
           
            $stmt->bind_param("sssssssssssdss", 
                $transaction_id, 
                $first_name, 
                $last_name, 
                $room_type, 
                $hotel_branch, 
                $check_in, 
                $check_out, 
                $payment_type, 
                $payment_method_detail, 
                $account_number, 
                $account_name, // This was the missing 's' and where 'd' was misplaced
                $amount, 
                $status, 
                $reservation_date
            );
            if ($stmt->execute()) {
                $_SESSION['notification_message'] = "New booking added successfully!";
                $_SESSION['notification_type'] = "success";
                error_log("PHP: Booking added successfully.");
            } else {
                $_SESSION['notification_message'] = "Error adding booking: " . $stmt->error;
                $_SESSION['notification_type'] = "error";
                error_log("PHP: Error executing statement: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $_SESSION['notification_message'] = "Database prepare error: " . $conn->error;
            $_SESSION['notification_type'] = "error";
            error_log("PHP: Database prepare error: " . $conn->error);
        }
    } else {
        $_SESSION['notification_message'] = "Error: Missing or invalid required fields for adding booking. Check server logs for details.";
        $_SESSION['notification_type'] = "error";
        error_log("PHP: Validation failed for add booking. First Name: " . (empty($first_name) ? 'EMPTY' : $first_name) . ", Room Type: " . (empty($room_type) ? 'EMPTY' : $room_type) . ", Amount: " . ($amount === false ? 'INVALID' : $amount));
    }
    header('Location: index.php'); // Redirect to prevent re-submission
    exit();
}

// Handle EDIT Booking (unchanged, but included for completeness)
if (isset($_POST['edit_booking'])) {
    error_log("PHP: edit_booking POST request received."); // Debugging log
    $id = filter_var($_POST['modal_booking_id'] ?? 0, FILTER_VALIDATE_INT); 
    $customer_name_full = $_POST['modal_customer_name'] ?? '';
    $name_parts = explode(' ', $customer_name_full, 2); 
    $first_name = $conn->real_escape_string($name_parts[0] ?? '');
    $last_name = $conn->real_escape_string($name_parts[1] ?? '');
    $room_type = $conn->real_escape_string($_POST['modal_room_type'] ?? '');
    $hotel_branch = $conn->real_escape_string($_POST['modal_hotel_branch'] ?? '');
    $check_in = $conn->real_escape_string($_POST['modal_check_in_date'] ?? '');
    $check_out = $conn->real_escape_string($_POST['modal_check_out_date'] ?? '');
    $payment_type = $conn->real_escape_string($_POST['modal_payment_type'] ?? '');
    $payment_method_detail = $conn->real_escape_string($_POST['modal_payment_method_detail'] ?? '');
    $account_number = $conn->real_escape_string($_POST['modal_account_number'] ?? '');
    $account_name = $conn->real_escape_string($_POST['modal_account_name'] ?? '');
    $amount = filter_var($_POST['modal_amount'] ?? 0, FILTER_VALIDATE_FLOAT);
    $status = $conn->real_escape_string($_POST['modal_status'] ?? '');

    if ($id > 0 && !empty($first_name) && !empty($room_type) && !empty($hotel_branch) && !empty($check_in) && !empty($check_out) && !empty($payment_type) && $amount !== false && !empty($status)) {
        $stmt = $conn->prepare("UPDATE booking_db SET first_name = ?, last_name = ?, room_type = ?, hotel_branch = ?, check_in = ?, check_out = ?, payment_type = ?, payment_method_detail = ?, account_number = ?, account_name = ?, amount = ?, status = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sssssssssdssi", $first_name, $last_name, $room_type, $hotel_branch, $check_in, $check_out, $payment_type, $payment_method_detail, $account_number, $account_name, $amount, $status, $id); 
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $_SESSION['notification_message'] = "Booking updated successfully!";
                    $_SESSION['notification_type'] = "success";
                } else {
                    $_SESSION['notification_message'] = "No changes made to booking ID " . $id . ".";
                    $_SESSION['notification_type'] = "info";
                }
            } else {
                $_SESSION['notification_message'] = "Error updating booking: " . $stmt->error;
                $_SESSION['notification_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['notification_message'] = "Database prepare error: " . $conn->error;
            $_SESSION['notification_type'] = "error";
        }
    } else {
        $_SESSION['notification_message'] = "Error: Missing or invalid required fields for editing booking.";
        $_SESSION['notification_type'] = "error";
    }
    header('Location: index.php'); 
    exit();
}

// Handle DELETE Booking (unchanged, but included for completeness)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['db_id'])) { 
    error_log("PHP: delete_booking GET request received for ID: " . $_GET['db_id']); // Debugging log
    $id_to_delete = filter_var($_GET['db_id'], FILTER_VALIDATE_INT); 

    if ($id_to_delete > 0) { 
        $stmt = $conn->prepare("DELETE FROM booking_db WHERE id = ?"); 
        if ($stmt) {
            $stmt->bind_param("i", $id_to_delete); 
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $_SESSION['notification_message'] = "Booking deleted successfully!";
                    $_SESSION['notification_type'] = "success";
                } else {
                    $_SESSION['notification_message'] = "Booking ID " . $id_to_delete . " not found or already deleted.";
                    $_SESSION['notification_type'] = "info";
                }
            } else {
                $_SESSION['notification_message'] = "Error deleting booking: " . $stmt->error;
                $_SESSION['notification_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['notification_message'] = "Database prepare error: " . $conn->error;
            $_SESSION['notification_type'] = "error";
        }
    } else {
        $_SESSION['notification_message'] = "Error: Invalid Booking ID provided for deletion.";
        $_SESSION['notification_type'] = "error";
    }
    header('Location: index.php'); 
    exit();
}


// Fetch booking data from the database (always done after potential modifications)
$bookings = [];
$sql = "SELECT
            id,              -- Fetch the actual integer ID
            transaction_id,  -- Keep this if you need to display it
            first_name,
            last_name,
            reservation_date,
            check_in,
            check_out,
            room_type,
            hotel_branch,
            payment_type,
            payment_method_detail,
            account_number,
            account_name,
            amount,
            status
        FROM booking_db ORDER BY reservation_date DESC"; // Order by most recent
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = [
                'id' => htmlspecialchars($row['id']), // Use the actual integer ID here
                'transaction_id' => htmlspecialchars($row['transaction_id']), // Keep this if needed for display
                'first_name' => htmlspecialchars($row['first_name']),
                'last_name' => htmlspecialchars($row['last_name']),
                'customer_name' => htmlspecialchars($row['first_name'] . ' ' . $row['last_name']),
                'status' => htmlspecialchars($row['status']),
                'room_type' => htmlspecialchars($row['room_type']),
                'hotel_branch' => htmlspecialchars($row['hotel_branch']),
                'check_in' => htmlspecialchars($row['check_in']),
                'check_out' => htmlspecialchars($row['check_out']),
                'payment_type' => htmlspecialchars($row['payment_type']),
                'payment_method_detail' => htmlspecialchars($row['payment_method_detail']),
                'account_number' => htmlspecialchars($row['account_number']),
                'account_name' => htmlspecialchars($row['account_name']),
                'amount' => htmlspecialchars(number_format((float)$row['amount'], 2, '.', '')),
                'raw_amount' => (float)$row['amount'] // Keep raw float for modal population
            ];
        }
    }
} else {
    error_log("PHP: Error fetching bookings: " . $conn->error); // Debugging log
    // Do not echo directly in production, use session messages or a proper error display
}

// Close the database connection
$conn->close();

// Calculate the total number of bookings
$totalBookedCount = count($bookings);

// Retrieve and clear notification message
$notification_message = $_SESSION['notification_message'] ?? '';
$notification_type = $_SESSION['notification_type'] ?? '';
unset($_SESSION['notification_message']);
unset($_SESSION['notification_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Bookings</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <span id="addBookingButton" class="add-booking-icon"><i class="fas fa-plus"></i></span>
            </div>

            <div class="bookings-list">
                <?php if (empty($bookings)): ?>
                    <p>No bookings found.</p>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card" data-id="<?php echo $booking['id']; ?>">
                            <div class="card-row header-row">
                                <div class="booking-info-left">
                                    <span class="customer-name" data-field="customer_name"><?php echo $booking['customer_name']; ?></span>
                                    <span class="status-tag <?php echo $booking['status']; ?>" data-field="status"><?php echo $booking['status']; ?></span>
                                </div>
                                <div class="booking-icons">
                                    <a href="#" class="edit-icon" data-id="<?php echo $booking['id']; ?>"><i class="fas fa-edit"></i></a>
                                    <a href="?action=delete&db_id=<?php echo $booking['id']; ?>" class="delete-icon" onclick="return confirm('Are you sure you want to delete this booking?');"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                            <div class="card-row details-row">
                                <p><strong>Transaction ID:</strong> <span data-field="transaction_id"><?php echo $booking['transaction_id']; ?></span></p>
                                <p><strong>Room Type:</strong> <span data-field="room_type"><?php echo $booking['room_type']; ?></span></p>
                                <p><strong>Branch:</strong> <span data-field="hotel_branch"><?php echo $booking['hotel_branch']; ?></span></p>
                                <p><strong>Check-in:</strong> <span data-field="check_in"><?php echo $booking['check_in']; ?></span></p>
                                <p><strong>Check-out:</strong> <span data-field="check_out"><?php echo $booking['check_out']; ?></span></p>
                                <p><strong>Payment Type:</strong> <span data-field="payment_type"><?php echo $booking['payment_type']; ?></span></p>
                                <p><strong>Amount:</strong> <span data-field="amount">₱<?php echo $booking['amount']; ?></span></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Edit Booking Modal -->
    <div id="editBookingModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeEditModal()">&times;</span>
            <h2>Edit Booking <i class="fas fa-edit edit-icon"></i></h2>
            <form id="editBookingForm" action="index.php" method="POST">
                <input type="hidden" name="edit_booking" value="1">
                <input type="hidden" id="modal_booking_id" name="modal_booking_id">
                <input type="hidden" id="modal_transaction_id" name="modal_transaction_id">

                <div class="form-group">
                    <label for="modal_customer_name">Customer Name:</label>
                    <input type="text" id="modal_customer_name" name="modal_customer_name" required>
                </div>
                <div class="form-group">
                    <label for="modal_room_type">Room Type:</label>
                    <input type="text" id="modal_room_type" name="modal_room_type" required>
                </div>
                <div class="form-group">
                    <label for="modal_hotel_branch">Hotel Branch:</label>
                    <input type="text" id="modal_hotel_branch" name="modal_hotel_branch" required>
                </div>
                <div class="form-group">
                    <label for="modal_check_in_date">Check-in Date:</label>
                    <input type="date" id="modal_check_in_date" name="modal_check_in_date" required>
                </div>
                <div class="form-group">
                    <label for="modal_check_out_date">Check-out Date:</label>
                    <input type="date" id="modal_check_out_date" name="modal_check_out_date" required>
                </div>
                <div class="form-group">
                    <label for="modal_payment_type">Payment Type:</label>
                    <input type="text" id="modal_payment_type" name="modal_payment_type" required>
                </div>
                <div class="form-group">
                    <label for="modal_payment_method_detail">Payment Method Detail:</label>
                    <input type="text" id="modal_payment_method_detail" name="modal_payment_method_detail">
                </div>
                <div class="form-group">
                    <label for="modal_account_number">Account Number:</label>
                    <input type="text" id="modal_account_number" name="modal_account_number">
                </div>
                <div class="form-group">
                    <label for="modal_account_name">Account Name:</label>
                    <input type="text" id="modal_account_name" name="modal_account_name">
                </div>
                <div class="form-group">
                    <label for="modal_amount">Amount:</label>
                    <input type="number" id="modal_amount" name="modal_amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="modal_status">Status:</label>
                    <select id="modal_status" name="modal_status" required>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="button-group">
                    <button type="submit" class="save-button">Save Changes</button>
                    <button type="button" class="cancel-button" onclick="closeEditModal()">Cancel</button>
                </div>
                <p id="editModalErrorMessage" class="error-message"></p>
            </form>
        </div>
    </div>

    <!-- Add New Booking Modal -->
    <div id="addBookingModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeAddModal()">&times;</span>
            <h2>Add New Booking <span class="add-booking-icon-title"><i class="fas fa-plus"></i></span></h2>
            <form id="addBookingForm" action="index.php" method="POST">
                <input type="hidden" name="add_booking" value="1"> <!-- Trigger for PHP -->

                <div class="form-group">
                    <label for="new_customer_name">Customer Name:</label>
                    <input type="text" id="new_customer_name" name="new_customer_name" required>
                </div>
                <div class="form-group">
                    <label for="new_room_type">Room Type:</label>
                    <input type="text" id="new_room_type" name="new_room_type" required>
                </div>
                <div class="form-group">
                    <label for="new_hotel_branch">Hotel Branch:</label>
                    <input type="text" id="new_hotel_branch" name="new_hotel_branch" required>
                </div>
                <div class="form-group">
                    <label for="new_check_in_date">Check-in Date:</label>
                    <input type="date" id="new_check_in_date" name="new_check_in_date" required>
                </div>
                <div class="form-group">
                    <label for="new_check_out_date">Check-out Date:</label>
                    <input type="date" id="new_check_out_date" name="new_check_out_date" required>
                </div>
                <div class="form-group">
                    <label for="new_payment_type">Payment Type:</label>
                    <input type="text" id="new_payment_type" name="new_payment_type" required>
                </div>
                <div class="form-group">
                    <label for="new_payment_method_detail">Payment Method Detail:</label>
                    <input type="text" id="new_payment_method_detail" name="new_payment_method_detail">
                </div>
                <div class="form-group">
                    <label for="new_account_number">Account Number:</label>
                    <input type="text" id="new_account_number" name="new_account_number">
                </div>
                <div class="form-group">
                    <label for="new_account_name">Account Name:</label>
                    <input type="text" id="new_account_name" name="new_account_name">
                </div>
                <div class="form-group">
                    <label for="new_amount">Amount:</label>
                    <input type="number" id="new_amount" name="new_amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="new_status">Status:</label>
                    <select id="new_status" name="new_status" required>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="button-group">
                    <button type="submit" class="save-button">Add Booking</button>
                    <button type="button" class="cancel-button" onclick="closeAddModal()">Cancel</button>
                </div>
                <p id="addModalErrorMessage" class="error-message"></p>
            </form>
        </div>
    </div>

    <!-- Notification Message -->
    <?php if (!empty($notification_message)): ?>
        <div class="notification <?php echo $notification_type; ?>">
            <?php echo $notification_message; ?>
        </div>
    <?php endif; ?>

    <script>
        console.log("JavaScript: Script loaded.");

        // Get the modals
        var editBookingModal = document.getElementById("editBookingModal");
        var addBookingModal = document.getElementById("addBookingModal");

        // Get the buttons that open the modals
        var addBookingButton = document.getElementById("addBookingButton");

        // Get the <span> elements that close the modals
        var closeButtons = document.getElementsByClassName("close-button");

        // Function to open the Add Booking Modal
        function openAddModal() {
            console.log("JavaScript: Opening Add Modal.");
            addBookingModal.style.display = "block";
            document.getElementById("addModalErrorMessage").style.display = "none"; // Hide error on open
            document.getElementById("addBookingForm").reset(); // Clear form on open
        }

        // Function to close the Add Booking Modal
        function closeAddModal() {
            console.log("JavaScript: Closing Add Modal.");
            addBookingModal.style.display = "none";
        }

        // Function to open the Edit Booking Modal and populate data
        function openEditModal(bookingData) {
            console.log("JavaScript: Opening Edit Modal with data:", bookingData);
            document.getElementById("modal_booking_id").value = bookingData.id;
            document.getElementById("modal_transaction_id").value = bookingData.transaction_id;
            document.getElementById("modal_customer_name").value = bookingData.customer_name;
            document.getElementById("modal_room_type").value = bookingData.room_type;
            document.getElementById("modal_hotel_branch").value = bookingData.hotel_branch;
            document.getElementById("modal_check_in_date").value = bookingData.check_in;
            document.getElementById("modal_check_out_date").value = bookingData.check_out;
            document.getElementById("modal_payment_type").value = bookingData.payment_type;
            document.getElementById("modal_payment_method_detail").value = bookingData.payment_method_detail;
            document.getElementById("modal_account_number").value = bookingData.account_number;
            document.getElementById("modal_account_name").value = bookingData.account_name;
            document.getElementById("modal_amount").value = bookingData.raw_amount; // Use raw_amount for input type number
            document.getElementById("modal_status").value = bookingData.status;

            editBookingModal.style.display = "block";
            document.getElementById("editModalErrorMessage").style.display = "none"; // Hide error on open
        }

        // Function to close the Edit Booking Modal
        function closeEditModal() {
            console.log("JavaScript: Closing Edit Modal.");
            editBookingModal.style.display = "none";
        }

        // When the user clicks the add booking button, open the modal
        addBookingButton.onclick = function() {
            console.log("JavaScript: Add Booking button clicked.");
            openAddModal();
        }

        // When the user clicks on <span> (x), close the modal
        for (var i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                console.log("JavaScript: Close button clicked.");
                closeEditModal();
                closeAddModal();
            }
        }

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener('click', function(event) {
            if (event.target == editBookingModal) {
                console.log("JavaScript: Clicked outside Edit Modal.");
                closeEditModal();
            }
            if (event.target == addBookingModal) {
                console.log("JavaScript: Clicked outside Add Modal.");
                closeAddModal();
            }
        });

        // Event listeners for edit buttons
        document.querySelectorAll('.edit-icon').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                console.log("JavaScript: Edit icon clicked.");
                const card = this.closest('.booking-card');
                const bookingId = card.dataset.id;
                
                // Get the full booking data from the PHP-encoded JSON
                const allBookings = <?php echo json_encode($bookings); ?>;
                const fullBookingData = allBookings.find(b => b.id == bookingId);

                if (fullBookingData) {
                    openEditModal(fullBookingData);
                } else {
                    console.error("JavaScript: Full booking data not found for ID:", bookingId);
                    // Fallback to partial data if full data isn't found (less ideal)
                    const partialBookingData = {
                        id: bookingId,
                        customer_name: card.querySelector('[data-field="customer_name"]').textContent,
                        transaction_id: card.querySelector('[data-field="transaction_id"]').textContent,
                        room_type: card.querySelector('[data-field="room_type"]').textContent,
                        hotel_branch: card.querySelector('[data-field="hotel_branch"]').textContent,
                        check_in: card.querySelector('[data-field="check_in"]').textContent,
                        check_out: card.querySelector('[data-field="check_out"]').textContent,
                        payment_type: card.querySelector('[data-field="payment_type"]').textContent,
                        amount: card.querySelector('[data-field="amount"]').textContent.replace('₱', ''),
                        raw_amount: parseFloat(card.querySelector('[data-field="amount"]').textContent.replace('₱', '')),
                        status: card.querySelector('[data-field="status"]').textContent.toLowerCase(),
                        payment_method_detail: '', // Placeholder if not in card
                        account_number: '', // Placeholder if not in card
                        account_name: '' // Placeholder if not in card
                    };
                    openEditModal(partialBookingData);
                }
            });
        });

        // Client-side validation for Add Booking Form
        document.getElementById("addBookingForm").addEventListener('submit', function(event) {
            console.log("JavaScript: Add Booking Form submission attempted.");
            const errorMessageElement = document.getElementById("addModalErrorMessage");
            const requiredFields = [
                "new_customer_name", "new_room_type", "new_hotel_branch",
                "new_check_in_date", "new_check_out_date", "new_payment_type",
                "new_amount", "new_status"
            ];
            
            let allFieldsFilled = true;
            let missingFields = [];
            for (const fieldId of requiredFields) {
                const input = document.getElementById(fieldId);
                if (input.value.trim() === '') {
                    allFieldsFilled = false;
                    missingFields.push(input.previousElementSibling ? input.previousElementSibling.textContent.replace(':', '') : fieldId);
                }
            }

            if (!allFieldsFilled) {
                event.preventDefault(); // Stop form submission
                errorMessageElement.textContent = "Please fill in all required fields: " + missingFields.join(', ') + ".";
                errorMessageElement.style.display = "block";
                console.warn("JavaScript: Add Form validation failed. Missing fields:", missingFields);
            } else {
                errorMessageElement.style.display = "none";
                console.log("JavaScript: Add Form validation passed. Submitting form.");
            }
        });

        // Client-side validation for Edit Booking Form
        document.getElementById("editBookingForm").addEventListener('submit', function(event) {
            console.log("JavaScript: Edit Booking Form submission attempted.");
            const errorMessageElement = document.getElementById("editModalErrorMessage");
            const requiredFields = [
                "modal_customer_name", "modal_room_type", "modal_hotel_branch",
                "modal_check_in_date", "modal_check_out_date", "modal_payment_type",
                "modal_amount", "modal_status"
            ];
            
            let allFieldsFilled = true;
            let missingFields = [];
            for (const fieldId of requiredFields) {
                const input = document.getElementById(fieldId);
                if (input.value.trim() === '') {
                    allFieldsFilled = false;
                    missingFields.push(input.previousElementSibling ? input.previousElementSibling.textContent.replace(':', '') : fieldId);
                }
            }

            if (!allFieldsFilled) {
                event.preventDefault(); // Stop form submission
                errorMessageElement.textContent = "Please fill in all required fields: " + missingFields.join(', ') + ".";
                errorMessageElement.style.display = "block";
                console.warn("JavaScript: Edit Form validation failed. Missing fields:", missingFields);
            } else {
                errorMessageElement.style.display = "none";
                console.log("JavaScript: Edit Form validation passed. Submitting form.");
            }
        });

        // Notification display logic
        window.onload = function() {
            console.log("JavaScript: Window loaded.");
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.display = 'block';
                console.log("JavaScript: Notification displayed.");
                setTimeout(() => {
                    notification.style.display = 'none';
                    console.log("JavaScript: Notification hidden.");
                }, 5000); // Hide after 5 seconds
            }
        };
    </script>
</body>
</html>
