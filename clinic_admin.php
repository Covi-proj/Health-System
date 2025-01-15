<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'e_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user's name, password, and username
    $stmt = $pdo->prepare("SELECT name, password, username FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Securely output the values
        $user_name = htmlspecialchars($user['name']); // User's full name
        $username = htmlspecialchars($user['username']); // Username
        $user_password = htmlspecialchars($user['password']); // User's password
    } else {
        $user_name = "Guest"; // Fallback if user not found
        $user_password = "";
        $username = ""; // Fallback for username
    }
} catch (PDOException $e) {
    $user_name = "Error retrieving name.";
    $user_password = ""; // Empty password on error
    $username = ""; // Empty username on error
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health-e | Clinic</title>
    <link rel="icon" href="icon.jfif" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!--table duplicate-->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <!--med duplicate -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!--Dashboard duplicate -->


    <!--new pmr-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!--pmr-->


    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #2a2185;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Top Navigation Bar */
        .navbar {
            background-color: white;
            color: black;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            /* Space between left and right content */
            align-items: center;
            /* Align items vertically */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 30px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .navbar .navbar-logo {
            width: 75px;
            height: auto;
            margin-right: 10px;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Adds space between text and image */
        }

        .navbar .user-name {
            font-weight: normal;
            color: black;
            margin: 0;
        }

        .navbar .user-image {
            width: 40px;
            /* Adjust size as needed */
            height: 40px;
            border-radius: 50%;
            /* Makes it a circular image */
            object-fit: cover;
        }

        /* Sidebar Styles */
        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
        }

        .sidebar {
            width: 250px;
            background-color: #8B0000;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            transition: width 0.3s;
        }

        .sidebar.collapsed {
            width: 80px;
            /* Width when collapsed */
        }

        .sidebar h1 {
            color: white;
            font-size: 22px;
            margin-bottom: 20px;
            margin-left: 20px;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .sidebar h1 i {
            margin-right: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
            cursor: pointer;
            font-size: 15px;
            font-weight: light;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.6s;
            display: flex;
            align-items: center;
        }

        .sidebar ul li i {
            margin-right: 10px;
            display: block;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: black;
        }

        .sidebar ul li.active {
            border-left: 5px solid #fff;
        }

        .sidebar.collapsed ul li i {
            display: none;
            /* Hide icons when collapsed */
        }

        .sidebar .toggle-btn {
            display: none;
            /* Initially hidden */
        }

        /* Button for toggling the sidebar */
        @media (max-width: 768px) {
            .sidebar.collapsed {
                width: 0;
            }

            .sidebar .toggle-btn {
                display: block;
                position: absolute;
                top: 20px;
                left: 20px;
                font-size: 30px;
                background: none;
                color: white;
                border: none;
                cursor: pointer;
            }

            .sidebar h1 {
                justify-content: center;
            }
        }

        /* Content Styles */
        .content {
            flex: 1;
            padding: 20px;
            position: relative;
            overflow-y: auto;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        /* Form Styles */
        form {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #2a2185;
            font-size: 1rem;
            width: 100%;
        }

        button {
            background-color: #3c2f9b;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5243b4;
        }

        .link {
            text-decoration: none;
            padding: 12px 20px;
            background-color: royalblue;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }

        .link:hover {
            background-color: #3c2f9b;
        }

        /* Header Styles */
        header {
            background-color: #808080;
            color: white;
            padding: 15px;
            text-align: center;
        }

        /* Notification and Table Styles */
        .rounded-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .student-count {
            font-size: 18px;
            color: #333;
            margin-top: 20px;
        }

        /* Responsive Layout */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                box-shadow: none;
                padding: 10px;
            }

            .content {
                padding: 10px;
            }

            .sidebar ul li {
                font-size: 16px;
                margin-bottom: 10px;
            }

            input[type="text"],
            input[type="password"],
            input[type="date"] {
                width: 100%;
                margin-right: 0;
            }

            button {
                width: 100%;
            }

            .link {
                width: 100%;
            }
        }

        .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
            transition: background-color 0.6s ease;
            font-weight: bold;
        }

        .logout:hover {
            background-color: black;
            padding: 10px;
            border-radius: 5px;
        }

        .ul {
            font-weight: light;
        }

        li i {
            margin-right: 8px;
            font-size: 20px;
            /* Adjust icon size as needed */
        }

        h1 i {
            margin-right: 10px;
            /* Space between the icon and the text */
            font-size: 24px;
            /* Adjust icon size to match the heading */
        }

        /*modal */
        .pop-up-modal {
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        .modal.fade.show .modal-dialog {
            transform: scale(1);
        }

        /*end*/

        /* Modal Structure */
        /* Modal container */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            /* Centers the modal */
            width: 80%;
            max-width: 600px;

            animation: fadeIn 0.2s ease-out;
            /* Faster fade-in animation */
        }

        /* Modal content (the actual window) */
        .modal-content {
            background-color: #ffff;
            margin: 0;
            /* Remove margin for full-screen effect */
            padding: 40px;
            border-radius: 15px;
            width: 80%;
            /* You can adjust this width as needed */
            max-width: 800px;
            /* Optional: Limits the width */
            max-height: 90%;
            /* Prevents overflow vertically */

        }

        /* Modal header (title and close button) */
        .modal-header {
            background-color: #b11226;
            color: white;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Close button */
        .modal-header .close {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }



        .modal-header .close:hover {
            color: #f5f5f5;
            /* Light color when hovering */
        }

        /* Body of the modal (form fields) */
        .modal-body {
            padding-top: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            /* Increased gap for more space between fields */
            margin-bottom: 1.rem;
        }

        label {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 1rem;
            /* Space between label and input */
        }

        input[type="text"],
        input[type="password"],
        select {
            padding: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            width: 200px;
            margin-bottom: 1.5rem;
            /* Increased bottom margin for more space between inputs */
            margin-left: 5px;
            /* Space to the left of the textbox */
        }

        /* Layout for when form fields are next to each other */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            /* Allow the form to wrap when necessary */
            gap: 20px;
            /* Increased gap between the textboxes */
            justify-content: space-between;
        }

        .form-row .form-group {
            flex: 1;
            /* Allow form elements to be evenly spaced */
            min-width: 220px;
            /* Ensure the form elements don’t get too small */
        }

        .form-row .form-group {
            flex: 1;
            /* Allow form elements to be evenly spaced */
            min-width: 220px;
            /* Ensure the form elements don’t get too small */
        }

        /* Buttons */
        button[type="button"],
        button[type="submit"] {
            padding: 0.75rem 1.25rem;
            font-weight: bold;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="button"] {
            background-color: #9ca3af;
        }

        button[type="button"]:hover {
            background-color: #6b7280;
        }

        button[type="submit"] {
            background-color: #b11226;
        }

        button[type="submit"]:hover {
            background-color: #a00e23;
        }

        /* Animation for fading in the modal */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* Animation for sliding in the modal content */
        @keyframes slideIn {
            0% {
                transform: translateY(-30px);
                /* Start from above */
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                /* End at normal position */
                opacity: 1;
            }
        }

        /* Animation for closing the modal (fade and slide out) */
        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .modal .close.fade-out {

            animation: fadeOut 1s ease forwards;
        }

        /* Modal disappearing (close animation) */
        .modal.close-animation .modal-content {
            animation: fadeOut 0.2s ease-out forwards, slideOut 0.3s ease-out forwards;
        }

        /* Animation for sliding out the modal content */
        @keyframes slideOut {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            100% {
                transform: translateY(30px);
                /* Slide down */
                opacity: 0;
            }
        }

        /*end add modal*/

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            font-size: 14px;

        }

        th {
            background-color: #B22222;
            color: white;
            font-weight: bold;
            font-size: 14px;
            border-top: 10px;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: #e2e6ea;
        }

        .action-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .action-icons a {
            color: #007bff;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .action-icons a:hover {
            color: #0056b3;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 20px 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .status {
            font-size: 30px;
            margin: 2px 2px 0 0;
            display: inline-block;
            vertical-align: middle;
            line-height: 10px;
        }

        .text-success {
            color: #10c469;
        }

        .text-info {
            color: #62c9e8;
        }

        .text-warning {
            color: #FFC107;
        }

        .text-danger {
            color: #ff5b5b;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px;
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        button.edit,
        button.delete {
            padding: 4px 8px;
            /* Reduces padding */
            font-size: 12px;
            /* Reduces font size */
            height: 28px;
            /* Sets a fixed height */
            line-height: 1;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            background-color: #f0f0f0;
            color: #333;

        }

        .editBtn {
            background-color: blue;
            color: white;
            margin-bottom: 5px;
            font-weight: bold;
            width: 100px;

        }

        .deleteBtn {
            background-color: red;
            color: white;
            margin-bottom: 5px;
            font-weight: bold;
            width: 100px;

        }

        button.edit:hover {
            background-color: #d0e9ff;
            color: #007bff;
        }

        button.delete:hover {
            background-color: #ffd1d1;
            color: #d9534f;

        }

        .searchbar-users {
            margin-top: 10px;
            /* Adds space above the search bar */
            padding: 8px;
            font-size: 16px;
            width: 100%;
            max-width: 300px;
            /* Adjust the width of the search bar */
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .success-alert {
            display: none;
            /* Hidden by default */
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            /* Green background */
            color: white;
            /* White text */
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            font-family: Arial, sans-serif;
            z-index: 1000;
            animation: fadeInOut 5s ease-in-out;
        }

        /* Optional fade-in and fade-out animation */
        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            90% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: translateY(-20px);
            }
        }


        .container-pmr {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        td {
            background-color: #f9f9f9;
        }

        .dataTables_paginate {
            margin-top: 20px;
            text-align: center;
        }

        .search-container {
            margin-bottom: 10px;
        }

        .pagination {
            display: flex;
            list-style-type: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        .calendar-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 350px;
            text-align: center;
        }

        .calendar-header {
            font-size: 1.5em;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            font-weight: bold;
            color: #555555;
            margin-bottom: 10px;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-dates div {
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 4px;
            background-color: #f9f9f9;
            color: #333333;
            font-size: 0.9em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .calendar-dates div:hover {
            background-color: #007bff;
            /* Highlight color */
            color: #ffffff;
        }

        .calendar-dates .today {
            background-color: #28a745;
            /* Highlight today */
            color: #ffffff;
        }

        /*med*/


        .ten {
            text-align: center;
            color: #333;
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            gap: 30px;
            background-color: white;
            padding: 30px;
            /* Add padding inside the container */
            border-radius: 10px;
            /* Optional: gives a rounded corner effect */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            /* Optional: subtle shadow */
        }

        .profile-img,
        .profile-details {
            margin: 0 15px;
            /* Add spacing between profile image and details */
        }

        /* Optional: Add spacing between text elements in profile-details */
        .profile-details p {
            margin: 10px 0;
        }

        .profile-img img {
            border-radius: 50%;
            border: 4px solid #f4f6f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-img img:hover {
            transform: scale(1.1);
        }

        .profile-details {
            flex: 1;
            font-size: 16px;
        }

        .profile-details h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-details p {
            margin: 8px 0;
            color: #555;
        }

        .profile-details strong {
            color: #007BFF;
        }

        .profile-actions {
            text-align: center;
        }

        .action-btn {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            margin: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .action-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-container {
                flex-direction: column;
                text-align: center;
            }

            .profile-img img {
                width: 120px;
                height: 120px;
            }

            .profile-details h2 {
                font-size: 24px;
            }

            .action-btn {
                width: 100%;
                padding: 14px 30px;
                font-size: 18px;
            }
        }

        .w3-bar {
            display: flex;
            justify-content: left;
            background-color: black;
            padding: 10px 0;
        }

        .w3-bar-item {
            padding: 10px 20px;
            color: white;
            border: none;
            outline: none;
            cursor: pointer;
            background-color: transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-left: 10px;
        }

        .w3-bar-item:hover {
            background-color: #555;
        }

        .tablink.w3-red {
            background-color: #4CAF50;
            /* Active tab color */
            color: white;
            font-weight: bold;
        }

        .city {
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .city h2 {
            margin: 0 0 10px;
            color: #333;
        }

        .city p {
            color: #555;
        }

        /*med message*/
        .success-alert {
            background-color: #4CAF50;
            /* Success Green */
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .success-alert span {
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        #fade {
            animation: fadeOut 2s forwards;
        }

        .unique-tab {
            overflow: hidden;
            background-color: rgb(0, 0, 0);
            margin-left: 10px;
        }

        /* Style the buttons inside the tab */
        .page {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form Layout */
        .form-page {
            background-color: lightgray;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: rgb(11, 4, 4);
        }

        /* Form Layout */
        .input-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            /* Automatically adjusts based on container size */
            gap: 15px;
            /* Sets space between input groups */
            margin-bottom: 10px;
        }

        .input-group {
            /* No need for width calculation here, as grid will handle layout */
            box-sizing: border-box;
        }

        .input-field {
            width: 100%;
            /* Ensures input fields take up full container width */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            /* Prevents padding and border from affecting width */
        }

        @media (max-width: 768px) {
            .input-container {
                grid-template-columns: 1fr;
                /* Stacks all input groups vertically on small screens */
            }
        }



        /* Tabs */
        .tab-navigation {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab-button {
            padding: 10px 20px;
            background-color: rgb(192, 189, 189);
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s;
            color: black;
            font-weight: bold;
        }

        .tab-button:hover {
            background-color: #ddd;
        }

        .tab-button.active {
            background-color: rgb(17, 93, 19);
            color: white;
            font-weight: bold;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .input-container {
                flex-direction: column;
            }

            .input-group {
                width: 100%;
            }

            .tab-navigation {
                flex-direction: column;
            }

        }

        /*form layout*/
    </style>

</head>

<body>

    <div class="navbar">
        <div class="left-content">
            <span class="logo">
                <img src="unnamed.png" alt="Health-e Logo" class="navbar-logo">Health-e
            </span>
        </div>
        <div class="right-content">
            <div class="user-info">
                <p class="user-name"><?php echo $user_name; ?></p>
                <img src="avatar.jpg" alt="User Profile" class="user-image">


            </div>
        </div>
    </div>



    <div class="container">
        <div class="sidebar">
            <h1><i class="fas fa-chart-bar"></i> Reports</h1>


            <ul>
                <li onclick="showPage('dashboard')">
                    <i class="fas fa-clipboard-list"></i> Dashboard
                </li>

                <li onclick="showPage('patient_mr')">
                    <i class="fas fa-file-medical"></i> Patients Medical Record
                </li>

                <li onclick="showPage('form_section')">
                    <i class="fas fa-pen"></i> Clinical Forms
                </li>

                <li onclick="showPage('payment')">
                    <i class="fas fa-pills"></i> Medicine Inventory
                </li>

                <li onclick="showPage('profile')">
                    <i class="fas fa-user"></i> User Profile
                </li>

            </ul>
            <span id="logout" onclick="logout()" class="logout"> <i class="fas fa-sign-out-alt"></i> Sign Out</span>

        </div>

        <div class="content">

            <!--Forms-->
            <div id="form_section" class="page">
                <h1 style="color: black;">Clinical Forms</h1>

                <div class="input-container">
                    <!-- Employee No. and Name -->
                    <div class="input-group">
                        <label style="color: black;" for="emp_number">Employee No. :</label>
                        <input type="text" id="emp_number" name="emp_no" class="input-field">
                    </div>
                    <div class="input-group">
                        <label style="color: black;" for="full_name">Name :</label>
                        <input type="text" id="full_name" name="name" class="input-field">
                    </div>

                    <!-- Age and Birthday -->
                    <div class="input-group">
                        <label style="color: black;" for="age_input">Age :</label>
                        <input type="text" id="age_input" name="age_input" class="input-field">
                    </div>
                    <div class="input-group">
                        <label style="color: black;" for="birthday_input">Birthday :</label>
                        <input type="text" id="birthday_input" name="bday" class="input-field">
                    </div>

                    <!-- Gender and Section/Dept. -->
                    <div class="input-group">
                        <label style="color: black;" for="gender_input">Gender :</label>
                        <input type="text" id="gender_input" name="gender" class="input-field">
                    </div>

                    <div class="input-group">
                        <label style="color: black;" for="section_input">Section/Department :</label>
                        <input type="text" id="section_input" name="division" class="input-field">
                    </div>

                    <!-- Company -->
                    <div class="input-group">
                        <label style="color: black;" for="company_input">Company :</label>
                        <input type="text" id="company_input" name="company" class="input-field">
                    </div>
                </div>

                <script>
                    // Function to search for user data based on employee ID
                    function searchUser() {
                        const emp = document.getElementById('emp_number').value;

                        // Ensure the emp field isn't empty before proceeding
                        if (!emp) {
                            alert("Please enter Employee No.");
                            return;
                        }

                        // Perform an asynchronous request to the server
                        const xhr = new XMLHttpRequest();
                        xhr.open('GET', 'fetch_scan.php?emp_no=' + encodeURIComponent(emp), true);

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                console.log('Response:', xhr.responseText); // Debug server response
                                try {
                                    const userData = JSON.parse(xhr.responseText);

                                    // Update fields with received data
                                    if (userData.error) {
                                        alert(userData.error); // Handle the case where no user is found
                                    } else {
                                        document.getElementById('full_name').value = userData.name || '';
                                        document.getElementById('age_input').value = userData.age || '';
                                        document.getElementById('birthday_input').value = userData.bday || '';
                                        document.getElementById('section_input').value = userData.division || '';
                                        document.getElementById('gender_input').value = userData.gender || '';
                                        document.getElementById('company_input').value = userData.company || '';
                                    }
                                } catch (e) {
                                    console.error('Error parsing JSON response:', e);
                                }
                            } else {
                                console.error('Error: HTTP status', xhr.status, xhr.statusText);
                            }
                        };

                        xhr.onerror = function () {
                            console.error('Network error');
                        };

                        xhr.send();
                    }

                    // Add keypress event to detect Enter key
                    document.getElementById('emp_number').addEventListener('keypress', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault(); // Prevent form submission
                            searchUser(); // Call the function to fetch and auto-fill data
                        }
                    });
                </script>



                <!-- Tabs -->
                <div class="tab-navigation">
                    <button class="tab-button" onclick="openTabContent(event, 'fit_work_tab')">Fit to Work</button>
                    <button class="tab-button" onclick="openTabContent(event, 'medication_tab')">Medicine</button>
                    <button class="tab-button" onclick="openTabContent(event, 'vital_signs_tab')">Vital Signs</button>
                    <button class="tab-button"
                        onclick="openTabContent(event, 'consultation_tab')">Consultations</button>
                    <button class="tab-button" onclick="openTabContent(event, 'confinement_tab')">Confinement</button>
                    <button class="tab-button" onclick="openTabContent(event, 'sent_home_tab')">Sent Home</button>
                    <button class="tab-button" onclick="openTabContent(event, 'pregnancy_notification_tab')">Pregnant
                        Notification</button>
                    <button class="tab-button" onclick="openTabContent(event, 'special_case_tab')">Special Case</button>
                    <button class="tab-button" onclick="openTabContent(event, 'incident_report_tab')">Incident Accident
                        Report</button>
                </div>

                <!-- Tab Contents -->

                <!--fit to work-->
                <div id="fit_work_tab" class="tab-content active">
                    <h3 style="color: black;">Fit To Work</h3>
                    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">
                    <div class="container-pmr">
                        <button id="toggleForm" onclick="window.location.href='eligibility_form.php';"
                            class="fas fa-file"
                            style="background-color: green; font-weight: bold; margin-bottom: 10px;">
                            New Record
                        </button>
                        <a href="export_fit_record.php" class="fas fa-file-excel" style="margin-left: 10px; background-color: #8B0000; padding: 10px; color: white; 
                  text-decoration: none; border-radius: 5px;">
                            Export to Excel
                        </a>

                        <div class="filter" style="float: right; display: inline-flex; align-items: center;">
                            <label for="date" style="margin-right: 0;">Display by Month:</label>
                            <select class="date2" id="date2" name="date2" onchange="filterdate2()"
                                style="margin-left: 0;">
                                <option value="all">All</option>
                                <option value="january">January</option>
                                <option value="february">February</option>
                                <option value="march">March</option>
                                <option value="april">April</option>
                                <option value="may">May</option>
                                <option value="june">June</option>
                                <option value="july">July</option>
                                <option value="august">August</option>
                                <option value="september">September</option>
                                <option value="october">October</option>
                                <option value="november">November</option>
                                <option value="december">December</option>
                            </select>
                        </div>
                        <table id="new" class="display" style="width: 100%; margin-top: 20px;">
                            <thead>
                                <tr class="med">
                                    <th style="width: 150px;">Date</th>
                                    <th>Time</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th style="width: 250px;">No. days Absent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Database connection settings
                                $host = 'localhost';
                                $db = 'e_system';
                                $user = 'root';
                                $pass = '';

                                try {
                                    // Create PDO instance
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // Query to fetch data from the "fit_to_work" table
                                    $stmt = $pdo->prepare("SELECT * FROM tbl_fittowork");
                                    $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Check if data exists
                                    if ($data) {
                                        foreach ($data as $item) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['time'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['from_'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['to_'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['nfa'] ?? 'N/A') . '</td>';
                                            echo '<td class="text-center">';
                                            echo '<a href="?f_id=' . htmlspecialchars($item['f_id']) . '" class="link-dark fas fa-pen-to-square"></a>';
                                            echo '<a href="?f_id=' . htmlspecialchars($item['f_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="11" class="text-center">No records found</td></tr>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="11" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        $(document).ready(function () {
                            $('#new').DataTable({
                                responsive: true, // Makes the table responsive
                                paging: true,    // Enables pagination
                                searching: true, // Enables search bar
                                ordering: true,  // Enables column sorting
                                info: true       // Shows table information
                            });
                        });


                        document.addEventListener("DOMContentLoaded", function () {
                            // Set the current month as the default selected value

                            const dateSelect = document.getElementById('date2');
                            dateSelect.value = all;
                            filterdate2();  // Apply the filter by current month on page load
                        });

                        function filterdate2() {
                            const selectedMonth = document.getElementById('date2').value;
                            const rows = document.querySelectorAll('#new tbody tr');

                            rows.forEach(row => {
                                const dateCell = row.cells[0].textContent.trim(); // Date cell is in the 4th column
                                const rowMonth = new Date(dateCell).toLocaleString('default', { month: 'long' }).toLowerCase();

                                if (selectedMonth === 'all' || rowMonth === selectedMonth) {
                                    row.style.display = '';  // Show the row
                                } else {
                                    row.style.display = 'none';  // Hide the row
                                }
                            });
                        }

                    </script>

                </div>
                <!--end fit to work-->

                <!--Medicine-->
                <div id="medication_tab" class="tab-content">
                    <h3 style="color: black;">Medicine</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="medicine" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">

                                <th>Date</th>
                                <th>Reason</th>
                                <th>Medicine</th>
                                <th>Supply</th>
                                <th>Quantiyt</th>
                                <th>Nurse on Duty</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!--end medicine-->

                <!--vital signs-->
                <div id="vital_signs_tab" class="tab-content">
                    <h3 style="color: black;">Vital Signs</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="vital" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Date</th>
                                <th>Time</th>
                                <th>Temperature</th>
                                <th>Pulse Rate</th>
                                <th>Respiratory</th>
                                <th>Oxygen Level</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!--end vital signs-->

                <!--consultation-->
                <div id="consultation_tab" class="tab-content">
                    <h3 style="color: black;">Consultation</h3>
                    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">

                    <div class="container-pmr">
                        <button id="toggleForm" onclick="window.location.href='new_consultation.php';"
                            class="fas fa-stethoscope"
                            style="background-color: green; font-weight: bold; margin-bottom: 10px;">
                            New Consultation
                        </button>
                        <a href="export_patient_record.php" class="fas fa-file-excel" style="margin-left: 10px; background-color: #8B0000; padding: 10px; color: white; 
                  text-decoration: none; border-radius: 5px;">
                            Export to Excel
                        </a>
                        <div class="filter" style="float: right; display: inline-flex; align-items: center;">
                            <label for="date" style="margin-right: 0;">Display by Month:</label>
                            <select class="date" id="date" name="date" onchange="filterdate()" style="margin-left: 0;">
                                <option value="all">All</option>
                                <option value="january">January</option>
                                <option value="february">February</option>
                                <option value="march">March</option>
                                <option value="april">April</option>
                                <option value="may">May</option>
                                <option value="june">June</option>
                                <option value="july">July</option>
                                <option value="august">August</option>
                                <option value="september">September</option>
                                <option value="october">October</option>
                                <option value="november">November</option>
                                <option value="december">December</option>
                            </select>
                        </div>

                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr class="med">

                                    <th>Date</th>
                                    <th>Diagnosis</th>
                                    <th>Physician</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div> <!-- Closing container-pmr -->

                    <!-- Include jQuery and DataTables JS -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            // Set the current month as the default selected value

                            const dateSelect = document.getElementById('date');
                            dateSelect.value = all;
                            filterdate();  // Apply the filter by current month on page load
                        });

                        function filterdate() {
                            const selectedMonth = document.getElementById('date').value;
                            const rows = document.querySelectorAll('#example tbody tr');

                            rows.forEach(row => {
                                const dateCell = row.cells[3].textContent.trim(); // Date cell is in the 4th column
                                const rowMonth = new Date(dateCell).toLocaleString('default', { month: 'long' }).toLowerCase();

                                if (selectedMonth === 'all' || rowMonth === selectedMonth) {
                                    row.style.display = '';  // Show the row
                                } else {
                                    row.style.display = 'none';  // Hide the row
                                }
                            });
                        }

                    </script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                </div>
                <!--end consultation-->

                <!--confinement-->
                <div id="confinement_tab" class="tab-content">
                    <h3 style="color: black;">Confinement</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="confine" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Date of Visit</th>
                                <th>Time of Visit</th>
                                <th>Chief Complaint</th>
                                <th>Time of Released</th>
                                <th>Total Hours Confined</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!--end confinement-->

                <!--sent home-->
                <div id="sent_home_tab" class="tab-content">
                    <h3 style="color: black;">Sent Home</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="sent_home" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Reason</th>
                                <th>Assesment</th>
                                <th>Diagnosis</th>
                                <th>Remarks</th>
                                <th>Sore Throat</th>
                                <th>Headache</th>
                                <th>Fever</th>
                                <th>Cough/Colds</th>
                                <th>LBM</th>
                                <th>Loss Taste/Smell</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!--end sent home-->

                <!--pregnant notification-->
                <div id="pregnancy_notification_tab" class="tab-content">
                    <h3 style="color: black;">Pregnancy Notification</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="pregnant" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>EDC</th>
                                <th>Date Submited</th>
                                <th>Remarks</th>
                                <th>Start Leave</th>
                                <th>Leave End</th>
                                <th>Note</th>
                                <th>Back to Work</th>
                                <th>Cough/Colds</th>
                                <th>Action</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!--end pregnant notification-->

                <!--special case-->
                <div id="special_case_tab" class="tab-content">
                    <h3 style="color:black;">Special Case</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="special_case" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Case No.</th>
                                <th>Date</th>
                                <th>Diagnosis</th>
                                <th>Retain Am Shift</th>
                                <th>No Exposure, To Chem, XRF, Soldering </th>
                                <th>Max HRS OT</th>
                                <th>No Sunday Shift Always Secure FTW/ Med Cert Form</th>
                                <th>Cough/Colds</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!--end special case-->

                <!--incident report tab-->
                <div id="incident_report_tab" class="tab-content">
                    <h3 style="color: black;">Incident Accident Report</h3>
                    <button class="fas fa-plus" style="background-color: green;"> New Record</button>
                    <table id="incident_report" class="table table-striped table-bordered" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Date of Incident</th>
                                <th>Time of Incident</th>
                                <th>Place of incident</th>
                                <th>Nature of incident</th>
                                <th>Part of the body Affected</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Days Lost</th>
                                <th>Date of Absence</th>
                                <th>Action</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- end incident report tab-->

                <!-- end tab contents-->
                <script>
                    function openTabContent(event, tabId) {
                        var i, tabContents, tabButtons;

                        // Hide all tab content
                        tabContents = document.getElementsByClassName("tab-content");
                        for (i = 0; i < tabContents.length; i++) {
                            tabContents[i].style.display = "none";
                        }

                        // Remove 'active' class from all tab buttons
                        tabButtons = document.getElementsByClassName("tab-button");
                        for (i = 0; i < tabButtons.length; i++) {
                            tabButtons[i].classList.remove("active");
                        }

                        // Display the clicked tab content
                        document.getElementById(tabId).style.display = "block";

                        // Add 'active' class to the clicked tab button
                        event.currentTarget.classList.add("active");
                    }
                </script>
            </div>



            <!--Forms-->

            <div id="profile" class="page">
                <h1 class="ten">User Profile</h1>

                <div class="profile-container">
                    <div class="profile-img">
                        <img src="avatar.jpg" alt="User Avatar" width="150" height="150">
                    </div>

                    <div class="profile-details">
                        <h2><?php echo $user_name; ?></h2>
                        <label>User Name</p>
                            <p><?php echo $username; ?></p>
                            <label>Password</label>
                            <input type="password" id="password" value="<?php echo htmlspecialchars($user_password); ?>"
                                readonly>
                            <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                    </div>
                </div>
                <script>
                    // Function to toggle password visibility
                    function togglePassword() {
                        var passwordField = document.getElementById('password');
                        var showPasswordCheckbox = document.getElementById('show-password');
                        if (showPasswordCheckbox.checked) {
                            passwordField.type = 'text'; // Show password
                        } else {
                            passwordField.type = 'password'; // Hide password
                        }
                    }
                </script>
                <div class="profile-actions">
                    <a href="edit_user_profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="action-btn"
                        style="font-weight: bold; text-decoration: none;">
                        Edit Profile
                    </a>


                </div>
            </div>

            <!--dashboard-->
            <div id="dashboard" class="page active">
                <a class="right-content">


                    <h1 id="fade" style="color: black;">Hello, <?php echo $user_name; ?></h1>

                    <script>
                        // Start fade after the DOM loads
                        window.onload = function () {
                            const element = document.getElementById('fade');
                            element.style.transition = 'opacity 5s';
                            element.style.opacity = '0';
                        };
                    </script>

                    <div class="rounded-box">
                        <h1 class="student-count" style="font-size: 35px;">Dashboard</h1>

                        <p id="date-time" style="font-size: 20px; color: black; font-weight: bold;"></p>



                        <div class="w3-container">
                            <div class="w3-bar w3-black">

                                <button class="w3-bar-item w3-button tablink w3-red"
                                    onclick="openCity(event,'London')">Patients</button>
                                <button class="w3-bar-item w3-button tablink"
                                    onclick="openCity(event,'special_case')">Special
                                    Case</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openCity(event,'Paris')">Fit to
                                    Work</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openCity(event,'Tokyo')">Medicine
                                    Inventory</button>

                            </div>

                            <div id="London" class="w3-container w3-border city"
                                style="display:block; width: 100%; max-width: 1500px;">
                                <h2>Patients</h2>

                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead class="thead-dark">
                                        <tr class="med">
                                            <th>Employee No.</th>
                                            <th>Patient Name</th>
                                            <th>Division</th>
                                            <th>Company</th>
                                            <th>Date</th>
                                            <th>BP</th>
                                            <th>Temperature</th>
                                            <th>HR</th>
                                            <th>RR</th>
                                            <th style="width: 100px;">O2 Sat</th>
                                            <th>Medicine</th>
                                            <th>Quantity</th>
                                            <th>Special Case</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PHP code to populate rows dynamically -->
                                        <?php
                                        $host = 'localhost';
                                        $db = 'e_system';
                                        $user = 'root';
                                        $pass = '';

                                        try {
                                            // Create PDO instance
                                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            // Get the current month and year
                                            $currentMonth = date('m'); // e.g., 12 for December
                                            $currentYear = date('Y'); // e.g., 2024
                                        
                                            // Query to fetch data from the "consultation" table for the current month and year
                                            $stmt = $pdo->prepare("SELECT * FROM consultation WHERE MONTH(c_date) = :currentMonth AND YEAR(c_date) = :currentYear");
                                            $stmt->bindParam(':currentMonth', $currentMonth, PDO::PARAM_INT);
                                            $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
                                            $stmt->execute();

                                            // Fetch all rows as an associative array
                                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Check if data exists
                                            if ($data) {
                                                foreach ($data as $item) {
                                                    echo '<tr>';
                                                    echo '<td style="width: 150px;">' . htmlspecialchars($item['emp'] ?? 'N/A') . '</td>';
                                                    echo '<td style="width: 150px;">' . htmlspecialchars($item['pnt_name'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['division'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['company'] ?? 'N/A') . '</td>';
                                                    echo '<td style="width: 100px;">' . htmlspecialchars($item['c_date'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['bp'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['temp'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['HR'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['RR'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['O2_sat'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['medicine'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['qty'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="12" class="text-center">No consultations found for the current month</td></tr>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<tr><td colspan="12" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Include DataTables Library -->
                                <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
                                    rel="stylesheet">
                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                                <!-- Initialize DataTables -->
                                <script>
                                    $(document).ready(function () {
                                        $('#table').DataTable({
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            responsive: true
                                        });
                                    });
                                </script>

                            </div>

                            <div id="special_case" class="w3-container w3-border city"
                                style="display:none; width: 100%; max-width: 1500px;">
                                <h2>Special Case</h2>

                                <table id="special" class="display"
                                    style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                    <thead>
                                        <tr class="med">
                                            <th style="text-align: left; background-color: #d0daf9; color: black;">Date
                                            </th>
                                            <th style="text-align: left; background-color: #d0daf9; color: black;">
                                                Employee No.</th>
                                            <th style="text-align: left;  background-color: #d0daf9; color: black;">
                                                Patient Name</th>
                                            <th style="text-align: left;  background-color: #d0daf9; color: black;">
                                                Special Case</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Database connection settings
                                        $host = 'localhost';
                                        $db = 'e_system';
                                        $user = 'root';
                                        $pass = '';

                                        try {
                                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            // Query to fetch data from the "fit_to_work" table
                                            $stmt = $pdo->prepare("SELECT emp, c_date, pnt_name, remarks FROM consultation");

                                            $stmt->execute();

                                            // Fetch rows as associative array
                                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($data) {
                                                foreach ($data as $item) {
                                                    echo '<tr>';
                                                    echo '<td style = "font-weight: bold; color: white; background-color:#4169e1;">' . htmlspecialchars($item['c_date'] ?? 'N/A') . '</td>';
                                                    echo '<td style="width: 80px; font-weight: bold;">' . htmlspecialchars($item['emp'] ?? 'N/A') . '</td>';
                                                    echo '<td style = "font-weight: bold; color: white; background-color:#4169e1;">' . htmlspecialchars($item['pnt_name'] ?? 'N/A') . '</td>';
                                                    echo '<td style = "font-weight: bold; color: white;  background-color:#4169e1;">' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center">No records found for the current month</td></tr>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<tr><td colspan="3" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Include DataTables -->
                                <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
                                    rel="stylesheet">
                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                                <script>
                                    $(document).ready(function () {
                                        // Destroy existing instance to prevent duplication
                                        if ($.fn.DataTable.isDataTable('#table2')) {
                                            $('#special').DataTable().destroy();
                                        }

                                        // Initialize DataTable
                                        $('#special').DataTable({
                                            destroy: true, // Ensure reinitialization is allowed
                                            responsive: true,
                                        });
                                    });
                                </script>

                            </div>

                            <div id="Paris" class="w3-container w3-border city"
                                style="display:none; width: 100%; max-width: 1500px;">
                                <h2>Fit To Work</h2>

                                <table id="table2" class="display"
                                    style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                    <thead>
                                        <tr class="med">
                                            <th style="text-align: left;">Date</th>
                                            <th style="text-align: left;">Start Date</th>
                                            <th style="text-align: left;">End Date</th>
                                            <th style="text-align: left;">Time</th>
                                            <th style="width: 200px; text-align: left;">Patient Name</th>
                                            <th style="text-align: left;">Diagnosis</th>
                                            <th style="text-align: left;">Eligibility</th>
                                            <th style="width: 250px; text-align: left;">Total days of absence</th>
                                            <th style="text-align: left;">Medicine</th>
                                            <th style="width: 150px; text-align: left;">Remarks</th>
                                            <th style="text-align: center;">Nurse on Duty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Database connection settings
                                        $host = 'localhost';
                                        $db = 'e_system';
                                        $user = 'root';
                                        $pass = '';

                                        try {
                                            // Create PDO instance
                                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            // Query to fetch data from the "fit_to_work" table
                                            $stmt = $pdo->prepare("SELECT * FROM fit_to_work");
                                            $stmt->execute();

                                            // Fetch all rows as an associative array
                                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Check if data exists
                                            if ($data) {
                                                foreach ($data as $item) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($item['f_date'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['s_date'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['e_date'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['time_in'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['patient_name'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['ftw'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['date_ofabs'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['Med_name'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="11" class="text-center">No records found</td></tr>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<tr><td colspan="11" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Include DataTables -->
                                <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
                                    rel="stylesheet">
                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                                <!-- Initialize DataTables -->
                                <script>
                                    $(document).ready(function () {
                                        $('#table2').DataTable({
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            responsive: true,
                                            language: {
                                                emptyTable: "No records available"
                                            }
                                        });
                                    });
                                </script>

                            </div>

                            <div id="Tokyo" class="w3-container w3-border city"
                                style="display:none; width: 100%; max-width: 1500px;">
                                <h2>Medicine Inventory</h2>

                                <table id="table3" class="display"
                                    style="width: 100%; margin-top: 20px; border-collapse: collapse; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left; width: 33%;">Medicine</th>
                                            <th style="text-align: left; width: 33%;">Quantity</th>
                                            <th style="text-align: left; width: 33%;">Date Received</th>
                                            <th style="text-align: left; width: 33%;">Receiver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Database connection settings
                                        $host = 'localhost';
                                        $db = 'e_system';
                                        $user = 'root';
                                        $pass = '';

                                        try {
                                            // Create PDO instance
                                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            // Get the current month and year
                                            $currentMonth = date('m');
                                            $currentYear = date('Y');

                                            // Query to fetch data from the "medicines" table for the current month and year
                                            $stmt = $pdo->prepare("SELECT * FROM medicines WHERE MONTH(date_receive) = :currentMonth AND YEAR(date_receive) = :currentYear");
                                            $stmt->bindParam(':currentMonth', $currentMonth, PDO::PARAM_INT);
                                            $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
                                            $stmt->execute();

                                            // Fetch all rows as an associative array
                                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($data) {
                                                foreach ($data as $item) {
                                                    echo '<tr>';
                                                    echo '<td style="text-align: left;">' . htmlspecialchars($item['Med_name'] ?? 'N/A') . '</td>';
                                                    echo '<td style="text-align: left;">' . htmlspecialchars($item['quantity'] ?? 'N/A') . '</td>';
                                                    echo '<td style="text-align: left;">' . htmlspecialchars($item['date_receive'] ?? 'N/A') . '</td>';
                                                    echo '<td style="text-align: left;">' . htmlspecialchars($item['receiver'] ?? 'N/A') . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center">No medicines received this month</td></tr>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<tr><td colspan="3" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Include DataTables -->
                                <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
                                    rel="stylesheet">
                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                                <!-- Initialize DataTables -->
                                <script>
                                    $(document).ready(function () {
                                        $('#table3').DataTable({
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            responsive: true,
                                            language: {
                                                emptyTable: "No medicines available for the current month."
                                            }
                                        });
                                    });
                                </script>


                            </div>

                        </div>

                        <script>
                            // Automatically display the first tab (London) when the page loads
                            window.onload = function () {
                                document.getElementById("London").style.display = "block";
                            }

                            function openCity(evt, cityName) {
                                var i, x, tablinks;
                                x = document.getElementsByClassName("city");
                                for (i = 0; i < x.length; i++) {
                                    x[i].style.display = "none";
                                }
                                tablinks = document.getElementsByClassName("tablink");
                                for (i = 0; i < tablinks.length; i++) {
                                    tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
                                }
                                document.getElementById(cityName).style.display = "block";
                                evt.currentTarget.className += " w3-red";
                            }

                            function updateDateTime() {
                                const now = new Date();
                                let hours = now.getHours(); // Get hours in 24-hour format
                                const minutes = String(now.getMinutes()).padStart(2, '0'); // Add leading zero
                                const seconds = String(now.getSeconds()).padStart(2, '0'); // Add leading zero
                                const date = now.toLocaleDateString(); // Get the current date in user's locale format

                                const amPm = hours >= 12 ? 'PM' : 'AM'; // Determine AM/PM
                                hours = hours % 12 || 12; // Convert to 12-hour format (0 becomes 12)

                                const timeString = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${amPm} ${date}`;
                                document.getElementById('date-time').textContent = timeString;
                            }

                            setInterval(updateDateTime, 1000); // Update every second
                            updateDateTime();
                            // Initial call to display time immediately
                            // Fetch data from the backend
                        </script>
                    </div>
            </div>
            <!--dashboard-->

            <!--PMR-->


            <div id="patient_mr" class="page">

                <h1 class="text-center" style="color: black;">Patients Medical Record</h1>

                <!-- Search Bar -->
                <div class="row mb-4">
                    <div class="col-md-6 offset-md-3">
                        <div class="input-group">
                        </div>
                    </div>
                </div>

                <!-- Medical Records Table -->
                <div class="table-responsive">
                    <div class="table-responsive">
                        <script
                            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                        <select name="date" id="date">
                            <option value="option1">Display by Date</option>
                            <option value="option2">Display by Month</option>
                            <option value="option3">Display by Year</option>
                        </select>

                        <table id="employeeTable" class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Birthdate</th>
                                    <th>Gender</th>
                                    <th>Department</th>
                                    <th>Company</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="recordsTable">
                                <!-- Dynamic rows will be added here -->
                                <?php
                                // Database connection settings
                                $host = 'localhost';
                                $db = 'e_system';
                                $user = 'root';
                                $pass = '';

                                try {
                                    // Create PDO instance
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // Query to fetch data from the "employees" table
                                    $stmt = $pdo->prepare("SELECT * FROM employees");
                                    $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Check if data exists
                                    if ($data) {
                                        foreach ($data as $item) {
                                            echo '<tr class="employee-row" data-company="' . htmlspecialchars($item['company']) . '">';
                                            echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['age'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['bday'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['gender'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['division'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['company'] ?? 'N/A') . '</td>';
                                            echo '<td class="text-center">';
                                            echo '<a href="view_record.php?emp_id=' . htmlspecialchars($item['emp_id']) . '" class="btn btn-sm btn-danger btn-action"><i class="fas fa-eye"></i> View Records</a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        // Adjusted colspan to match the number of columns in the table
                                        echo '<tr><td colspan="8" class="text-center">No employees found</td></tr>';
                                    }
                                } catch (PDOException $e) {
                                    // Adjusted colspan to match the number of columns in the table
                                    echo '<tr><td colspan="8" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('#employeeTable').DataTable();
                });
            </script>



            <!--Medicine-->
            <div id="payment" class="page">
                <h2 style="color:black; font-size: 30px;">Medicine Inventory</h2>



                <div class="container-pmr">

                    <!-- New Medicine Button -->
                    <button id="toggleForm" onclick="window.location.href='new_med.php';" class="fas fa-pills"
                        style="background-color: green; font-weight: bold; margin-bottom: 15px;"> New Medicine</button>
                    <a href="export_med_inventory.php" class="fas fa-file-excel" style="margin-left: 10px; background-color: #8B0000; padding: 10px; color: white; 
                  text-decoration: none; border-radius: 5px;">
                        Export to Excel
                    </a>

                    <div class="filter" style="float: right; display: inline-flex; align-items: center;">
                        <label for="date" style="margin-right: 0;">Display by Month:</label>
                        <select class="date3" id="date3" name="date3" onchange="filterdate3()" style="margin-left: 0;">
                            <option value="all">All</option>
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="november">November</option>
                            <option value="december">December</option>
                        </select>
                    </div>
                    <!-- Success Message -->
                    <?php if (isset($_GET['msg'])): ?>
                        <div class="success-alert">
                            <?php echo htmlspecialchars($_GET['msg']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Medicine Table -->
                    <table id="medicineTable" class="display">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Quantity</th>
                                <th>Date Received</th>
                                <th>Receiver</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Database connection settings
                            $host = 'localhost';
                            $db = 'e_system';
                            $user = 'root';
                            $pass = '';

                            try {
                                // Create PDO instance
                                $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                // Query to fetch data from the "medicines" table
                                $stmt = $pdo->prepare("SELECT * FROM medicines");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['Med_name']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date_receive']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['receiver']) . '</td>';
                                        echo '<td class="text-center">';
                                        // Edit and Delete links with dynamic med_id
                                        echo '<a href="edit_med.php?med_id=' . htmlspecialchars($item['med_id']) . '" class="link-dark"><i class="fas fa-edit"></i></a>';
                                        echo '<a href="delete_med.php?med_id=' . htmlspecialchars($item['med_id']) . '" class="link-dark" style="margin-left: 10px;"><i class="fas fa-trash"></i></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">No medicines found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="4" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
                <script>
                    $(document).ready(function () {
                        $('#medicineTable').DataTable({
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true
                        });
                    });


                    document.addEventListener("DOMContentLoaded", function () {
                        // Set the current month as the default selected value

                        const dateSelect = document.getElementById('date3');
                        dateSelect.value = all;
                        filterdate3();  // Apply the filter by current month on page load
                    });

                    function filterdate3() {
                        const selectedMonth = document.getElementById('date3').value;
                        const rows = document.querySelectorAll('#medicineTable tbody tr');

                        rows.forEach(row => {
                            const dateCell = row.cells[2].textContent.trim(); // Date cell is in the 4th column
                            const rowMonth = new Date(dateCell).toLocaleString('default', { month: 'long' }).toLowerCase();

                            if (selectedMonth === 'all' || rowMonth === selectedMonth) {
                                row.style.display = '';  // Show the row
                            } else {
                                row.style.display = 'none';  // Hide the row
                            }
                        });
                    }


                    // Auto-hide success message after 5 seconds
                    setTimeout(function () {
                        const successMessage = document.getElementById('successMessage');
                        if (successMessage) {
                            successMessage.style.display = 'none';
                        }
                    }, 5000);


                </script>
            </div>
            <!--medicine-->
        </div>


    </div>

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Page Navigation
        function showPage(pageId) {
            $('.page').removeClass('active');
            $('#' + pageId).addClass('active');
        }

        // Toggle Form Visibility

        function logout() {


            // Replace current history state with login page to prevent going back
            window.location.replace("logout.php");

            // Alternatively, to ensure the history state is cleared:
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function () {
                window.history.go(1); // Prevent going back to the previous page
            };
        }

        //cancel
        function showPage(pageId) {
            // Hide all other sections (assuming all sections have the "page" class)
            document.querySelectorAll('.page').forEach(page => {
                page.style.display = 'none';
            });
            // Show the selected section
            document.getElementById(pageId).style.display = 'block';
        }

        // Check URL hash on page load


        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('msg') || window.location.hash === '#payment') {
                showPage('payment');  // Show the payment section if `msg` is in the URL
            }
        };

        window.onload = function () {
            const hash = window.location.hash;

            if (hash === '#payment') {
                showPage('payment'); // Show the payment section
            } else if (hash === '#patient_pmr') {
                showPage('patient_mr');
            } else if (hash === '#balances') {
                showPage('balances'); // Show the consultation section
            } else if (hash === '#fit') {
                showPage('fit'); // Show the fit section
            } else if (hash === '#preg') {
                showPage('preg');// Show the pregnant notification
            }
            else if (hash === '#profile') {
                showPage('profile'); // Show the fit section
            }
        };


        $(document).ready(function () {
            // Handle form submission for adding medicine
            $('#addMedicineForm').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = $(this).serialize(); // Serialize the form data

                $.ajax({
                    type: "POST",
                    url: "new_med.php", // The page that processes the form
                    data: formData,
                    success: function (response) {
                        if (response == 'success') {
                            // Show success message
                            $('#success-message').show();

                            // Update the table content by reloading #payment section
                            $('#payment').load('clinic_admin.php#payment');

                            // Scroll to the payment section
                            $('html, body').animate({
                                scrollTop: $('#payment').offset().top // Smooth scroll to payment section
                            }, 1000);
                        } else {
                            alert('Failed to add medicine. Please try again.');
                        }
                    }
                });
            });
        });
        $(document).ready(function () {
            // Initialize DataTable with pagination
            $('#example').DataTable({
                paging: true,
                searching: true,
                lengthChange: true, // Allows the user to choose the number of rows per page
                pageLength: 5, // Default rows per page
                info: true, // Show table info (e.g., "Showing 1 to 5 of 50 entries")
                responsive: true, // Make the table responsive on smaller screens
                language: {
                    paginate: {
                        previous: 'Prev',
                        next: 'Next'
                    }
                }
            });
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</body>

</html>