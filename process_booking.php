<?php
session_start(); // Start session to track user login status

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookings";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$check_in = $_POST['check-in'];
$check_out = $_POST['check-out'];
$guests = $_POST['guests'];

// Retrieve user ID from session (for registered users)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

// Prepare the SQL statement to check for overlapping bookings
$stmt_check = $conn->prepare("
    SELECT * FROM bookings
    WHERE (check_in <= ? AND check_out >= ?) OR
          (check_in <= ? AND check_out >= ?)
");
$stmt_check->bind_param("ssss", $check_out, $check_in, $check_in, $check_out);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "<h1>Booking Unsuccessful</h1>";
    echo "<p>One of the dates (" . htmlspecialchars($check_in) . " or " . htmlspecialchars($check_out) . ") is already reserved. Please choose another date.</p>";
    echo '<a href="calendar.html" class="button">Go Back</a>';
} else {
    // Prepare the SQL statement to insert the new booking
    $stmt_insert = $conn->prepare("INSERT INTO bookings (user_id, guest_name, guest_email, check_in, check_out, guests) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("issssi", $user_id, $_POST['guest_name'], $_POST['guest_email'], $check_in, $check_out, $guests);

    if ($stmt_insert->execute()) {
        echo '<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Booking Confirmation</title>
                  <style>
                      body {
                          font-family: Arial, sans-serif;
                          background-color: #f9f9f9;
                          color: #333;
                          text-align: center;
                          margin-top: 50px;
                      }
                      h1 {
                          color: #4CAF50;
                      }
                      #payment-options {
                          margin-top: 20px;
                          padding: 20px;
                          border: 1px solid #ccc;
                          background-color: #fff;
                          display: inline-block;
                      }
                      label {
                          font-size: 18px;
                          margin-right: 10px;
                      }
                      button {
                          margin-top: 20px;
                          padding: 10px 20px;
                          font-size: 16px;
                          background-color: #4CAF50;
                          color: #fff;
                          border: none;
                          cursor: pointer;
                          border-radius: 5px;
                      }
                      button:hover {
                          background-color: #45a049;
                      }
                  </style>
              </head>
              <body>';

        echo "<h1>Double check the details below</h1>";
        echo "<p>Check-in Date: " . htmlspecialchars($check_in) . "</p>";
        echo "<p>Check-out Date: " . htmlspecialchars($check_out) . "</p>";
        echo "<p>Guests: " . htmlspecialchars($guests) . "</p>";
        
        // Add a placeholder for pricing confirmation
        echo '<p id="pricing-confirmation"></p>';

        // Display payment options
        echo '<div id="payment-options">
                <h2>Select Payment Method</h2>
                <form id="payment-form">
                    <label>
                        <input type="radio" name="payment-method" value="gcash"> GCash
                    </label><br>
                    <label>
                        <input type="radio" name="payment-method" value="other"> Other
                    </label><br><br>
                    <button type="button" id="proceed-button">Proceed</button>
                </form>
              </div>';

        // JavaScript to display selected pricing and handle payment
        echo '<script>
                window.addEventListener("load", () => {
                    const selectedPricing = localStorage.getItem("selectedPricing");
                    if (selectedPricing) {
                        const pricingDisplay = {
                            "380": "6 hours for ₱380.00",
                            "750": "12 hours for ₱750.00",
                            "1100": "24 hours for ₱1,100.00"
                        };
                        document.getElementById("pricing-confirmation").innerText = `You Chose: ${pricingDisplay[selectedPricing]}`;
                        localStorage.removeItem("selectedPricing"); // Optional: clear the selection after displaying
                    } else {
                        document.getElementById("pricing-confirmation").innerText = "No pricing option selected.";
                    }
                });

                document.getElementById("proceed-button").addEventListener("click", function() {
                    var selectedMethod = document.querySelector(\'input[name="payment-method"]:checked\').value;
                    if (selectedMethod === "gcash") {
                        window.location.href = "gcashqr.html";
                    } else {
                        alert("Sorry, but we can process GCash payment only.");
                    }
                });
              </script>';

        echo '</body></html>';
    } else {
        echo "Error: " . $stmt_insert->error;
    }
}

// Close connection
$stmt_check->close();
if (isset($stmt_insert)) {
    $stmt_insert->close();
}
$conn->close();
?>
