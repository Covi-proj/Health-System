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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!--table duplicate-->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <!--med duplicate -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!--Dashboard duplicate -->


    <!--new pmr-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!--pmr-->


    <style>
        /* Clean Professional UI */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #2a2185;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navigation Bar */
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
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 28px;
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
        }

        .navbar .user-name {
            font-weight: normal;
            color: black;
            margin: 0;
        }

        .navbar .user-image {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Sidebar */
        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
        }

        .sidebar {
            width: 205px;
            background-color: #8B0000;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            transition: width 0.3s;
        }

        .sidebar.collapsed {
            width: 80px;
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

        .logout {
            position: fixed;
            bottom: 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout:hover {
            background-color: black;
        }

        .logout i {
            margin-right: 10px;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 20px;
            position: relative;
            overflow-y: auto;
        }

        /* Tables */
        table {
            width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: black;
            font-weight: bold;
        }

        th {
            background-color: rgb(7, 6, 6);
            color: white;
            font-weight: bold;
            font-size: 12px;
            border-top: 10px;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: rgb(73, 82, 89);
            color: white;
        }

        /* Buttons */
        button,
        .action-btn {
            background-color: #3c2f9b;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover,
        .action-btn:hover {
            background-color: #5243b4;
        }

        /* Forms */
        input[type="text"],
        input[type="password"],
        select {
            padding: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            width: 200px;
            margin-bottom: 1.5rem;
            margin-left: 5px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #8B0000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(139, 0, 0, 0.1);
        }

        /* Profile Section */
        .profile-container {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 2rem auto;
        }

        .profile-img img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #8B0000;
            color: white !important;
            border-color: #8B0000;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }

            .navbar {
                padding: 1rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B0000;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a00;
        }

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
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 28px;
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
        }

        .navbar .user-name {
            font-weight: normal;
            color: black;
            margin: 0;
        }

        .navbar .user-image {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Sidebar Styles */
        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
        }

        .sidebar {
            width: 205px;
            background-color: #8B0000;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            transition: width 0.3s;
        }

        .sidebar.collapsed {
            width: 80px;
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
        }

        .sidebar .toggle-btn {
            display: none;
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
            position: fixed;
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
            /* Ensure the form elements don't get too small */
        }

        .form-row .form-group {
            flex: 1;
            /* Allow form elements to be evenly spaced */
            min-width: 220px;
            /* Ensure the form elements don't get too small */
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
            width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: black;
            font-weight: bold;


        }

        th {
            background-color: rgb(7, 6, 6);
            color: white;
            font-weight: bold;
            font-size: 12px;
            border-top: 10px;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: rgb(73, 82, 89);
            color: white;
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
            gap: 15px;
            /* Sets space between input groups */
            margin: 20px;
            /* Adds space outside the container */
            padding: 20px;
            /* Adds space inside the container */
            background-color: #e6e6e6;
            border-radius: 10px;
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

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        /*form layout*/
    </style>

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="navbar">
        <div class="left-content">
            <span class="logo">
                <img src="unnamed.png" alt="Health-e Logo" class="navbar-logo">Health-e <p id="date-time"
                    style="font-size: 16px; color: black; font-weight: light; margin-left: 20px;"></p>
            </span>

            <script>
                // Automatically display the first tab (London) when the page loads


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
        <div class="right-content">
            <div class="user-info">
                <p class="user-name"><?php echo $user_name; ?></p>
                <img src="avatar.jpg" alt="User Profile" class="user-image">


            </div>
        </div>
    </div>

    <!--I was just learning how to manuallly to push through github-->

    <div class="container">
        <div class="sidebar">

            <ul>
                <li onclick="showPage('dashboard')" class = "active">
                    <i class="fas fa-chart-bar"></i> Dashboard
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

                <li onclick="showPage('equip')">
                    <i class="fas fa-person-dress"></i></i>Maternity Equipment
                </li>

                <li onclick="showPage('profile')">
                    <i class="fas fa-user"></i> User Profile
                </li>
            </ul>
            <span id="logout" onclick="logout()" class="logout"> <i class="fas fa-sign-out-alt"></i> Sign Out</span>
        </div>

        <div class="content">
            <div class="page active" id="dashboard">

                <div class="container-fluid py-4">
                    <h1 class="mb-4" style = "color: black;">Dashboard</h1>

                        <div class="row">
                            <!-- Total Employees Card -->
                            <div class="col-md-4">
                                <div class="dashboard-card">
                                    <h1 style = "color: black;">Total Employees</h1>
                                    <?php
                                    $host = 'localhost';
                                    $db = 'e_system';
                                    $user = 'root';
                                    $pass = '';

                                    try {
                                        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM employees");
                                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo '<div class="stat-card">';
                                        echo '<h2 style = "color: black;"> Total Registered Employees: ' . $result['total'] . '</h2>';
                            
                                        echo '</div>';
                                    } catch (PDOException $e) {
                                        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="modal-body">
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

                                    // SQL query to calculate the total quantity of each Med_name in medicines
                                    $sqlMedicines = "SELECT Med_name, SUM(quantity) AS quantity FROM medicines GROUP BY Med_name";
                                    $stmtMedicines = $pdo->prepare($sqlMedicines);
                                    $stmtMedicines->execute();
                                    $medicinesData = $stmtMedicines->fetchAll(PDO::FETCH_ASSOC);

                                    // SQL query to calculate the total quantity of each medicine in tbl_medicine
                                    $sqlTblMedicine = "SELECT medicine, SUM(quantity) AS quantity FROM tbl_medicine GROUP BY medicine";
                                    $stmtTblMedicine = $pdo->prepare($sqlTblMedicine);
                                    $stmtTblMedicine->execute();
                                    $tblMedicineData = $stmtTblMedicine->fetchAll(PDO::FETCH_ASSOC);

                                    // Create a map for tbl_medicine quantities
                                    $tblMedicineMap = [];
                                    foreach ($tblMedicineData as $row) {
                                        $tblMedicineMap[$row['medicine']] = $row['quantity'];
                                    }

                                    // Display the data in a horizontal scrollable list with subtraction logic
                                    echo "<div class='horizontal-scroll' style='display: flex; gap: 10px; max-width: 100%; overflow-x: auto; padding: 10px;'>";
                                    foreach ($medicinesData as $item) {
                                        $medName = $item['Med_name'];
                                        $medQuantity = $item['quantity'];
                                        $tblQuantity = $tblMedicineMap[$medName] ?? 0; // Default to 0 if no match in tbl_medicine
                                
                                        // Subtract quantities
                                        $resultQuantity = $medQuantity - $tblQuantity;

                                        // Check if the result quantity is below 25, change background color to red
                                        $backgroundColor = $resultQuantity < 25 ? 'background-color: red;' : 'background-color: rgb(0, 0, 0);';
                                        $refillMessage = $resultQuantity < 25 ? '<p style="color: yellow;">Needs to Refill</p>' : '';

                                        // Display each item with dynamic background color
                                        echo "<div class='item' style='min-width: 200px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; $backgroundColor text-align: center; font-weight:bold; color: white;'>";
                                        echo htmlspecialchars($medName) . " — " . htmlspecialchars($resultQuantity);
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                } catch (PDOException $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                                ?>
                            </div>

                            <!-- Medicine Usage by Reason -->
                            <div class="col-md-8">
                                <div class="dashboard-card">
                                    <h1 style = "color: black;">Medicine Usage by Reason</h1>
                                    <canvas id="reasonChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Medicine Usage Details -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="dashboard-card">
                                    <h4>Medicine Usage Details</h4>
                                    <div class="table-responsive">
                                        <table id="medicineUsageTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Reason</th>
                                                    <th>Medicine</th>
                                                    <th>Count</th>
                                                    <th>Total Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                try {
                                                    $stmt = $pdo->query("
                                                 SELECT 
                                                reason,
                                                medicine,
                                                COUNT(*) as count,
                                                SUM(quantity) as total_quantity
                                                FROM tbl_medicine
                                                GROUP BY reason, medicine
                                                ORDER BY count DESC
                                                ");

                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        echo '<tr>';
                                                        echo '<td>' . htmlspecialchars($row['reason']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($row['medicine']) . '</td>';
                                                        echo '<td>' . $row['count'] . '</td>';
                                                        echo '<td>' . $row['total_quantity'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                } catch (PDOException $e) {
                                                    echo '<tr><td colspan="4" class="text-danger">Error: ' . $e->getMessage() . '</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>


                                        <script>
                                            $(document).ready(function () {
                                                $('#medicineUsageTable').DataTable({
                                                    paging: true,
                                                    searching: true,
                                                    ordering: true,
                                                    responsive: true,
                                                    lengthMenu: [5, 10, 25, 50],
                                                    language: {
                                                        emptyTable: "No data available",
                                                        zeroRecords: "No matching records found"
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <script>
                    // Prepare data for the chart
                    <?php
                    try {
                        $stmt = $pdo->query("
                SELECT reason, COUNT(*) as count
                FROM tbl_medicine
                GROUP BY reason
                ORDER BY count DESC
            ");
                        $reasons = [];
                        $counts = [];
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $reasons[] = $row['reason'];
                            $counts[] = $row['count'];
                        }
                    } catch (PDOException $e) {
                        $reasons = [];
                        $counts = [];
                    }
                    ?>

                    // Create the chart
                    const ctx = document.getElementById('reasonChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($reasons); ?>,
                            datasets: [{
                                label: 'Number of Cases',
                                data: <?php echo json_encode($counts); ?>,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>


            </div>

            <div class="page" id="equip">

                <h2 style="color: black;">Maternity Equipment</h2>
                <button id="addItemButton" onclick="window.location.href='add_item_form.php';" class="fas fa-plus"
                    style="background-color: green; color: white; font-weight: bold; margin-bottom: 10px;">
                    Add Item
                </button>

                <select id="itemTypeFilter" class="form-select" required onchange="filterTable()">
                    <option value="">--Select Item Type--</option>
                    <?php
                    try {
                        // Query to fetch distinct item types from tbl_item
                        $stmt = $pdo->prepare("SELECT DISTINCT item_type FROM tbl_item");
                        $stmt->execute();

                        // Fetch and populate the select options
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($row['item_type']) . '">' . htmlspecialchars($row['item_type']) . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">Error fetching item types</option>';
                    }
                    ?>
                </select>
                <select id="statusFilter" class="form-select" required onchange="filterTable_status()">
                    <option value="">--Select Status--</option>
                    <?php
                    try {
                        // Query to fetch distinct item types from tbl_item
                        $stmt = $pdo->prepare("SELECT DISTINCT status FROM tbl_item");
                        $stmt->execute();

                        // Fetch and populate the select options
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($row['status']) . '">' . htmlspecialchars($row['status']) . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">Error fetching item types</option>';
                    }
                    ?>
                </select>

                <script>
                    function filterTable() {
                        var itemTypeFilterValue = document.getElementById("itemTypeFilter").value.toLowerCase();
                        var statusFilterValue = document.getElementById("statusFilter").value.toLowerCase();
                        var table = $('#equipmentTable').DataTable();

                        // Clear previous search functions
                        $.fn.dataTable.ext.search = [];

                        // Add new search function
                        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                            var itemType = data[1].toLowerCase(); // Item Name column
                            var status = data[3].toLowerCase();  // Status column

                            if ((itemTypeFilterValue === "" || itemType === itemTypeFilterValue) &&
                                (statusFilterValue === "" || status === statusFilterValue)) {
                                return true;
                            }
                            return false;
                        });

                        table.draw();
                    }

                    function filterTable_status() {
                        var statusFilterValue = document.getElementById("statusFilter").value.toLowerCase();
                        var table = $('#equipmentTable').DataTable();


                        // Clear previous search functions
                        $.fn.dataTable.ext.search = [];

                        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                            var status = data[3].toLowerCase();

                            if ((statusFilterValue === "" || status === statusFilterValue)) {
                                return true;
                            }
                            return false;
                        });

                        table.draw();
                    }

                    $(document).ready(function () {
                        // Disable DataTables error alerts
                        $.fn.dataTable.ext.errMode = 'none';

                        // Initialize DataTable
                        var table = $('#equipmentTable').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            responsive: true,
                            info: true,
                            lengthMenu: [5, 10, 25, 50],
                            language: {
                                emptyTable: "No records available",
                                zeroRecords: "No matching records found"
                            }
                        });
                    });
                </script>

                <table id="equipmentTable" class="display table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Item No.</th>
                            <th>Item Name</th>
                            <th>Date Arrived</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Query to fetch data
                            $stmt = $pdo->prepare("SELECT * FROM tbl_item ORDER BY date_arrive ASC");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if (!empty($data)) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($item['item_no']) . '</td>';
                                    echo '<td class="item-type">' . htmlspecialchars($item['item_type']) . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date_arrive']) . '</td>';
                                    echo '<td>' . htmlspecialchars($item['status']) . '</td>';
                                    echo '<td class="text-center">';
                                    echo '<a href="edit_item.php?id=' . htmlspecialchars($item['id']) . '" class="link-dark fas fa-pen-to-square"></a>';
                                    echo '<a href="delete_item.php?id=' . htmlspecialchars($item['id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="5" class="text-center">Error fetching data: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <div style="margin-top: 20px;">

                    <h2 style="color: black;">Borrow Record</h2>

                    <select id="status_filter" class="form-select" required onchange="status()">
                        <option value="">--Select Status--</option>
                        <?php
                        try {
                            // Query to fetch distinct statuses from borrow_records
                            $stmt = $pdo->prepare("SELECT DISTINCT status FROM borrow_records");
                            $stmt->execute();

                            // Fetch and populate the select options
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . htmlspecialchars($row['status']) . '">' . htmlspecialchars($row['status']) . '</option>';
                            }
                        } catch (PDOException $e) {
                            echo '<option value="">Error fetching statuses</option>';
                        }
                        ?>
                    </select>
                    <script>
                        function status() {
                            var statusValue = document.getElementById("status_filter").value.toLowerCase();
                            var table = $('#borrowedItemsTable').DataTable();

                            // Clear previous search functions
                            $.fn.dataTable.ext.search = [];

                            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                                var status = data[7].toLowerCase();

                                if ((statusValue === "" || status === statusValue)) {
                                    return true;
                                }
                                return false;
                            });

                            table.draw();
                        }   
                    </script>
                    <table id="borrowedItemsTable" class="display table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Item No.</th>
                                <th>Item Name</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Corrected query
                                $stmt = $pdo->prepare("SELECT br.id, e.name AS name, e.emp_id, e.emp_no, br.item_no, br.item_name, br.borrow_date, br.return_date, br.status, br.quantity
                           FROM borrow_records br
                           JOIN employees e ON br.emp_id = e.emp_id");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $borrowedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if (!empty($borrowedData)) {
                                    foreach ($borrowedData as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['id']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['emp_no']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['item_no']) . '</td>'; // Fixed
                            
                                        echo '<td>' . htmlspecialchars($item['item_name']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['borrow_date']) . '</td>';
                                        echo '<td>' . (isset($item['return_date']) ? htmlspecialchars($item['return_date']) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['status']) . '</td>';
                                        echo '<td>' . (isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 'N/A') . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="7" class="text-center">Error fetching data: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <script>
                        $(document).ready(function () {
                            $.fn.dataTable.ext.errMode = 'none'; // Disable DataTables error alerts
                            $('#borrowedItemsTable').DataTable({
                                "language": {
                                    "emptyTable": "No records available",
                                    "zeroRecords": "No matching records found"
                                }
                            });
                        });
                    </script>

                </div>

            </div>


            <!--Forms-->
            <div id="form_section" class="page">
                <h1 style="color: black;">Clinical Forms</h1>

                <div class="input-container" id="formContainer">
                    <!-- Employee No. and Name Row 1-->

                    <input type="hidden" id="emp_id" name="emp_no" class="input-field">

                    <div class="input-group">
                        <label style="color: black;" for="emp_number">Employee No. :</label>
                        <input type="text" id="emp_number" name="emp_no" class="input-field">
                    </div>
                    <div class="input-group">
                        <label style="color: black;" for="full_name">Name :</label>
                        <input type="text" id="full_name" name="name" class="input-field" readonly>

                    </div>

                    <!-- Age and Birthday Row 2-->


                    <input type="hidden" id="age_input" name="age_input" class="input-field" readonly>

                    <input type="hidden" id="birthday_input" name="bday" class="input-field" readonly>
                    <input type="hidden" id="gender_input" name="gender" class="input-field" readonly>


                    <div class="input-group">
                        <label style="color: black;" for="section_input">Section/Department :</label>
                        <input type="text" id="section_input" name="division" class="input-field" readonly>
                    </div>

                    <!-- Company Row 4-->
                    <div class="input-group">
                        <label style="color: black;" for="company_input">Company :</label>
                        <input type="text" id="company_input" name="company" class="input-field" readonly>
                    </div>
                   
                    <div class="input-group">

                        <button class="fas fa-times" type="button" onclick="clearForm()" style="margin-left: 10px; margin-top:40px; background-color: #8B0000; padding: 10px; color: white; 
                                text-decoration: none; border-radius: 5px;">
                        </button>

                        <a href="export_all_tables.php" class="fas fa-file-excel" style="margin-left: 10px; margin-top:40px; background-color: #8B0000; padding: 10px; color: white; 
                                text-decoration: none; border-radius: 5px;">
                            Export to Excel
                        </a>
                       
                    </div>


                </div>
                <!--End Forms-->
                <script>
                    // Function to search for user data based on employee ID
                    function searchUser() {
                        const emp = document.getElementById('emp_number').value;

                        if (!emp) {
                            alert("Please enter Employee No.");
                            return;
                        }

                        const xhr = new XMLHttpRequest();
                        xhr.open('GET', 'fetch_scan.php?emp_no=' + encodeURIComponent(emp), true);

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                try {
                                    const userData = JSON.parse(xhr.responseText);

                                    if (userData.error) {
                                        alert(userData.error);
                                    } else {
                                        document.getElementById('emp_id').value = userData.emp_id || '';
                                        document.getElementById('full_name').value = userData.name || '';
                                        document.getElementById('age_input').value = userData.age || '';
                                        document.getElementById('birthday_input').value = userData.bday || '';
                                        document.getElementById('section_input').value = userData.division || '';
                                        document.getElementById('gender_input').value = userData.gender || '';
                                        document.getElementById('company_input').value = userData.company || '';
                                    }

                                    saveFormData(); // Save after autofill
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

                    // Save form data to localStorage
                    function saveFormData() {
                        const formData = {
                            emp_number: document.getElementById('emp_number').value,
                            emp_id: document.getElementById('emp_id').value,
                            full_name: document.getElementById('full_name').value,
                            age_input: document.getElementById('age_input').value,
                            birthday_input: document.getElementById('birthday_input').value,
                            section_input: document.getElementById('section_input').value,
                            gender_input: document.getElementById('gender_input').value,
                            company_input: document.getElementById('company_input').value
                        };
                        localStorage.setItem('clinical_form', JSON.stringify(formData));
                    }

                    // Load saved data from localStorage
                    function loadFormData() {
                        const saved = JSON.parse(localStorage.getItem('clinical_form'));
                        if (saved) {
                            document.getElementById('emp_number').value = saved.emp_number || '';
                            document.getElementById('emp_id').value = saved.emp_id || '';
                            document.getElementById('full_name').value = saved.full_name || '';
                            document.getElementById('age_input').value = saved.age_input || '';
                            document.getElementById('birthday_input').value = saved.birthday_input || '';
                            document.getElementById('section_input').value = saved.section_input || '';
                            document.getElementById('gender_input').value = saved.gender_input || '';
                            document.getElementById('company_input').value = saved.company_input || '';
                        }
                    }

                    // Clear form and localStorage
                    function clearForm() {
                        localStorage.removeItem('clinical_form');
                        document.querySelectorAll('#form_section input').forEach(input => {
                            input.value = '';
                        });
                    }

                    // Handle keypress (Enter key) on Employee No. field
                    document.getElementById('emp_number').addEventListener('keypress', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            searchUser();
                        }
                    });

                    // Real-time table filter based on Employee No.
                    document.getElementById('emp_number').addEventListener('input', function () {
                        const searchValue = this.value.trim();
                        const tables = document.querySelectorAll('table:not(#employeeTable):not(#medicineTable)');

                        tables.forEach(table => {
                            const tableRows = table.querySelectorAll('tbody tr');

                            tableRows.forEach(row => {
                                let empNoCell = null;

                                row.querySelectorAll('td').forEach(td => {
                                    if (!empNoCell && /\d+/.test(td.textContent.trim())) {
                                        empNoCell = td;
                                    }
                                });

                                if (empNoCell) {
                                    const empNoText = empNoCell.textContent.trim();
                                    if (searchValue === '' || empNoText.includes(searchValue)) {
                                        row.style.display = '';
                                    } else {
                                        row.style.display = 'none';
                                    }
                                }
                            });
                        });

                        saveFormData(); // Save form data as user types
                    });

                    // Save form before unload
                    window.addEventListener('beforeunload', saveFormData);

                    // Load form on page load
                    window.addEventListener('load', loadFormData);
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
                    <button class="tab-button" onclick="openTabContent(event, 'vehicular_accident_tab')">Vehicular Accident</button>
                </div>

                <!-- Tab Contents -->

                <!--fit to work-->
                <div id="fit_work_tab" class="tab-content active" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Fit To Work</h3>
                    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">

                    <button id="toggleForm"
                        onclick="window.location.href='eligibility_form.php?emp_id=' + document.getElementById('emp_id').value;"
                        class="fas fa-file" style="background-color: green; font-weight: bold; margin-bottom: 10px;">
                        New Record
                    </button>
                    <!--recycle
                    <a href="export_fit_record.php" class="fas fa-file-excel" style="margin-left: 10px; background-color: #8B0000; padding: 10px; color: white; 
                        text-decoration: none; border-radius: 5px;">
                        Export to Excel
                    </a>
                    -->
                    <div class="filter" style="float: right; display: inline-flex; align-items: center;">

                    </div>
                    <table id="new" class="display" style="width: 100%; margin-top: 20px;">
                        <thead>
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>From</th>
                                <th>To</th>
                                <th>No. days Absent</th>
                                <th>Reason</th>
                                <th>File</th>
                                <th>Remarks</th>
                                <th>Nurse on Duty</th>
                                <th style="width :90px;">Note</th>

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

                                // Query to fetch data
                                $stmt = $pdo->prepare("
                                    SELECT tbl_fittowork.*, employees.emp_no, employees.name
                                    FROM tbl_fittowork
                                    LEFT JOIN employees ON tbl_fittowork.emp_id = employees.emp_id
                                    ORDER BY tbl_fittowork.f_id DESC
                                    ");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['f_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . (!empty($item['time']) ? date("h:i A", strtotime($item['time'])) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['from_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['to_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nfa'] ?? 'N/A') . ' day/s </td>';
                                        echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                        // Handle file download
                                        echo '<td>';
                                        if (!empty($item['with_without_cert'])) {
                                            $filePath = htmlspecialchars($item['with_without_cert']);  // Sanitize the file path
                                            $fileName = basename($filePath);  // Extract just the file name
                                            echo '<a href="download.php?file=' . urlencode($filePath) . '">' . $fileName . '</a>'; // Display the file name as the link text
                                        } else {
                                            echo 'N/A';  // Display 'N/A' if the file path is empty
                                        }
                                        echo '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                        // Action buttons
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_fitwork.php?emp_id=' . $item['emp_id'] . '&f_id=' . $item['f_id'] . '" class="link-dark fas fa-pen-to-square"></a>';

                                        echo '<a href="delete_fit.php?f_id=' . htmlspecialchars($item['f_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    // Display "No records found"
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                // Handle database errors
                                echo '<tr><td colspan="9" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>

                        </tbody>
                    </table>

                    <script>
                        // Global configuration for all DataTables
                        $(document).ready(function () {
                            // Disable all DataTables error alerts globally
                            $.fn.dataTable.ext.errMode = 'none';

                            // Common configuration for all tables
                            const commonConfig = {
                                responsive: true,
                                paging: true,
                                searching: true,
                                ordering: true,
                                info: true,
                                processing: true,
                                destroy: true,
                                language: {
                                    emptyTable: "No records available",
                                    zeroRecords: "No matching records found",
                                    error: null
                                },
                                columnDefs: [{
                                    targets: '_all',
                                    defaultContent: "N/A"
                                }],
                                error: function (settings, helpPage, message) {
                                    console.log('An error has occurred. Please refresh the page.');
                                    return false;
                                }
                            };

                            // Initialize all tables with error handling
                            ['#new', '#table', '#special', '#table2', '#table3', '#example', '#medicineTable'].forEach(tableId => {
                                if ($(tableId).length) {  // Check if table exists
                                    const table = $(tableId).DataTable(commonConfig);

                                    // Add error event handler to each table
                                    table.on('error.dt', function (e, settings, techNote, message) {
                                        console.log('DataTables error: ' + message);
                                        return false;
                                    });
                                }
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
                <div id="medication_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Medicine</h3>
                    <button
                        onclick="window.location.href='new_medicine.php?emp_id=' + document.getElementById('emp_id').value;"
                        class="fas fa-plus" style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="medicine" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th style="width: 100px;">Name</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Medicine</th>
                                <th>Supply</th>
                                <th>Quantity</th> <!-- Fixed spelling of "Quantity" -->
                                <th>Nurse on Duty</th>
                                <th>Note</th>

                                <th>Action</th>
                            </tr> <!-- Added missing closing </tr> -->
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

                                // Query to fetch data
                                $stmt = $pdo->prepare("
                                SELECT tbl_medicine.*, employees.emp_no, employees.name
                                FROM tbl_medicine
                                LEFT JOIN employees ON tbl_medicine.emp_id = employees.emp_id
                                ORDER BY tbl_medicine.med_id DESC
                                ");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? $item['guest_name']) . '</td>';
                                        echo '<td>' . htmlspecialchars($item['med_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['medicine'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['supply'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['quantity'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';

                                        echo '<a href="edit_meds.php?emp_id=' . urlencode($item['emp_id']) .
                                            '&med_id=' . urlencode($item['med_id']) .
                                            '&guest_name=' . urlencode($item['guest_name']) .
                                            '" class="link-dark fas fa-pen-to-square"></a>';

                                        echo '<a href="delete_medicine.php?med_id=' . htmlspecialchars($item['med_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>

                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#medicine').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end medicine-->

                <!--vital signs-->
                <div id="vital_signs_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Vital Signs</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='vital_signs.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="vital" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Blood Pressure</th>
                                <th>Temperature</th>
                                <th>Sugar Reading</th>
                                <th>Pulse Rate</th>
                                <th>Respiratory</th>
                                <th>Oxygen Level</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr> <!-- Added missing closing </tr> -->
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

                                // Query to fetch data
                                $stmt = $pdo->prepare("
                                SELECT tbl_vitalsgn.*, employees.emp_no, employees.name
                                FROM tbl_vitalsgn
                                LEFT JOIN employees ON tbl_vitalsgn.emp_id = employees.emp_id
                                ORDER BY tbl_vitalsgn.vt_id DESC
                                ");
                                $stmt->execute();

                                // Fetch all rows
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['vt_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . (!empty($item['time']) ? date("h:i A", strtotime($item['time'])) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['bp'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['temp'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['sugar'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['pr'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['rr'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['ol'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_vt.php?emp_id=' . $item['emp_id'] . '&vt_id=' . $item['vt_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_vt.php?vt_id=' . htmlspecialchars($item['vt_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
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

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#vital').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end vital signs-->

                <!--consultation-->
                <div id="consultation_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Consultation</h3>
                    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">

                    <button id="toggleForm"
                        onclick="window.location.href='new_consultation.php?emp_id=' + document.getElementById('emp_id').value;"
                        class="fas fa-stethoscope"
                        style="background-color: green; font-weight: bold; margin-bottom: 10px;">
                        New Consultation
                    </button>

                    <div class="filter" style="float: right; display: inline-flex; align-items: center;">
                    </div>

                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Diagnosis</th>
                                <th>Physician</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Actions</th>
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

                                // Query to fetch data
                                $query = "
                                SELECT 
                                tbl_consultation.date, 
                                employees.emp_id, 
                                employees.emp_no, 
                                employees.name, 
                                tbl_consultation.diagnosis, 
                                tbl_consultation.physician, 
                                tbl_consultation.remarks, 
                                tbl_consultation.status, 
                                tbl_consultation.cons_id
                                FROM tbl_consultation
                                LEFT JOIN employees ON tbl_consultation.emp_id = employees.emp_id
                                ORDER BY tbl_consultation.cons_id DESC 
                                ";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute();

                                // Fetch all rows
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['cons_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';

                                        echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['physician'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['status'] ?? 'N/A') . '</td>';
                                        echo '<td>';
                                        echo '<a href="edit_cons.php?emp_id=' . urlencode($item['emp_id']) . '&cons_id=' . urlencode($item['cons_id']) . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_consult.php?cons_id=' . htmlspecialchars($item['cons_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="8" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>


                        </tbody>
                    </table>



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
                    <button class="fas fa-plus"
                        onclick="window.location.href='confine_form.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="confine" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Date of Visit</th>
                                <th>Time of Visit</th>
                                <th>Chief Complaint</th>
                                <th>Findings</th>
                                <th>Endorsed</th>
                                <th>Time of Released</th>
                                <th>Total Hours Confined</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr> <!-- Added missing closing </tr> -->
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

                                // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_confinement LEFT JOIN employees ON tbl_confinement.emp_id = employees.emp_id");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['con_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date_of_visit'] ?? 'N/A') . '</td>';
                                        echo '<td>' . (!empty($item['time_of_visit']) ? date("h:i A", strtotime($item['time_of_visit'])) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['chief_complaint'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['findings'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['endorsed'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['time_of_released'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['total_hrs'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['status'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_confine.php?emp_id=' . $item['emp_id'] . '&con_id=' . $item['con_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href=delete_confine.php?con_id=' . htmlspecialchars($item['con_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>

                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#confine').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end confinement-->

                <!--sent home-->
                <div id="sent_home_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Sent Home</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='sent_home.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="sent_home" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Assessment</th>
                                <th>Diagnosis</th>
                                <th>Remarks</th>
                                <th>Sore Throat</th>
                                <th>Body Pain</th>
                                <th>Headache</th>
                                <th>Fever</th>
                                <th>Cough/Colds</th>
                                <th>LBM</th>
                                <th>Loss Taste/Smell</th>
                                <th>Action</th>
                            </tr> <!-- Added missing closing </tr> -->
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

                                // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_senthome LEFT JOIN employees ON tbl_senthome.emp_id = employees.emp_id ORDER BY employees.emp_id DESC");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['sh_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['assessment'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['sore_throat'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['body_pain'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['headache'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['fever'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['cough_colds'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['lbm'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['loss_ts'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_sh.php?emp_id=' . $item['emp_id'] . '&sh_id=' . $item['sh_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_sh.php?sh_id=' . htmlspecialchars($item['sh_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#sent_home').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end sent home-->

                <!--pregnant notification-->
                <div id="pregnancy_notification_tab" class="tab-content">
                    <h3 style="color: black;">Pregnancy Notification</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='pregnant_notification.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="pregnant" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>EDC</th>
                                <th>Date Submited</th>
                                <th>Remarks</th>
                                <th>OB Score</th>
                                <th>Start Leave</th>
                                <th>Leave End</th>
                                <th>Note</th>
                                <th>Back to Work</th>
                                <th>Apron Date Released</th>
                                <th>Date Arpon Returned</th>
                                <th>Chair Released</th>
                                <th>Date of Chair Returned</th>
                                <th>Action</th>
                            </tr> <!-- Added closing </tr> -->
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

                                // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_pregnant_notif LEFT JOIN employees ON tbl_pregnant_notif.emp_id = employees.emp_id ORDER BY tbl_pregnant_notif.date_sub DESC");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['pn_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['edc'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date_sub'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['ob_score'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['start_leave'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['l_end'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['btw'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['adr'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['dar'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['cdr'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['dcr'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_preg.php?emp_id=' . $item['emp_id'] . '&pn_id=' . $item['pn_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_preg.php?pn_id=' . htmlspecialchars($item['pn_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#pregnant').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end pregnant notification-->

                <!--special case-->
                <div id="special_case_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color:black;">Special Case</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='special_case.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;"> New Record</button>
                    <table id="special_case" class="table table-striped table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th style="width: 100px;">Name</th>
                                <th>Case No.</th>
                                <th>Date</th>
                                <th>Diagnosis</th>
                                <th>Retain Am Shift</th>
                                <th style="width: 600px;">No Exposure, To Chem, XRF, Soldering</th>
                                <th>Max HRS OT</th>
                                <th>No Sunday Shift</th>
                                <th style="width: 600px;">Always Secure FTW/ Med Cert Form</th>
                                <th>FF Up to Secure FTW/MED Cert Form</th>
                                <th>Provide Chair</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Controlled/Uncontrolled</th>
                                <th>Action</th>
                            </tr> <!-- Added closing </tr> -->
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

                                // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_specialcase LEFT JOIN employees ON tbl_specialcase.emp_id = employees.emp_id ORDER BY tbl_specialcase.date DESC");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars(str_pad($item['sc_id'] ?? 'N/A', 6, '0', STR_PAD_LEFT)) . '</td>';

                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['retain'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['no_exp'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['max_ot'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nss'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['asfmcf'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['ffcpm'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['provide_chair'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['status_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['cu'] ?? 'N/A') . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_sc.php?emp_id=' . $item['emp_id'] . '&sc_id=' . $item['sc_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_sc.php?sc_id=' . htmlspecialchars($item['sc_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#special_case').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!--end special case-->

                <!--incident report tab-->
                <div id="incident_report_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                    <h3 style="color: black;">Incident Accident Report</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='incident_report.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;">New Record</button>
                    <table id="incident_report" class="table table-striped table-bordered" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Date of Incident</th>
                                <th>Time of Incident</th>
                                <th>Place of incident</th>
                                <th>Nature of incident</th>
                                <th>Part of the body Affected</th>
                                <th>Remarks</th>
                                <th>Immediate Action</th>
                                <th>Treatment</th>
                                <th>Status</th>
                                <th>Days Lost</th>
                                <th>Date of Absence</th>
                                <th>File</th>
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

                                // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_incident_report LEFT JOIN employees ON tbl_incident_report.emp_id = employees.emp_id ORDER BY tbl_incident_report.ir_id DESC");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['ir_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . (!empty($item['time_i']) ? date("h:i A", strtotime($item['time_i'])) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['place_i'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nature_i'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['part_b_a'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['i_action'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['treatment'] ?? 'N/A') . '</td>';
                                        echo '<td >' . htmlspecialchars($item['status_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['d_lost'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['d_absence'] ?? 'N/A') . '</td>';

                                        // Handle file download
                                        echo '<td>';
                                        if (!empty($item['file'])) {
                                            $filePath = htmlspecialchars($item['file']);  // Sanitize the file path
                                            $fileName = basename($filePath);  // Extract just the file name
                            
                                            // Display a link with the file name that opens in a new tab
                                            echo '<a href="' . $filePath . '" target="_blank">View ' . $fileName . '</a>';
                                        } else {
                                            echo 'N/A';  // Display 'N/A' if the file is empty
                                        }
                                        echo '</td>';

                                        // Action buttons
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_incident.php?emp_id=' . $item['emp_id'] . '&ir_id=' . $item['ir_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_incident.php?ir_id=' . htmlspecialchars($item['ir_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#incident_report').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>
                <!-- end incident report tab-->

                <!-- Vehicular Accident -->
                <div id="vehicular_accident_tab" class="tab-content" style="max-height: 400px; overflow-y: auto;">
                <h3 style="color: black;">Incident Accident Report</h3>
                    <button class="fas fa-plus"
                        onclick="window.location.href='vehicular_accident.php?emp_id=' + document.getElementById('emp_id').value;"
                        style="background-color: green; margin-bottom: 10px;">New Record</button>
                    <table id="vehicular_accident" class="table table-striped table-bordered" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="med">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Filing Date</th>
                                <th>Date of Accident</th>
                                <th>Time of Incident</th>
                                <th>Reason</th>
                                <th>Type of Vehicle</th>
                                <th>Remarks</th>
                                <th>Nurse on Duty</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Days Lost</th>
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

                                // Query to fetch data from the "tbl_vehicle_accident" table and join it with the "employees" table
                                $stmt = $pdo->prepare("SELECT * FROM tbl_vehicle_accident LEFT JOIN employees ON tbl_vehicle_accident.emp_id = employees.emp_id ORDER BY tbl_vehicle_accident.va_id DESC");
                                $stmt->execute();

                                // Fetch all rows as an associative array
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Check if data exists
                                if ($data) {
                                    foreach ($data as $item) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['va_id'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['f_date'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['date_incident'] ?? 'N/A') . '</td>';
                                        echo '<td>' . (!empty($item['time_incident']) ? date("h:i A", strtotime($item['time_incident'])) : 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['v_type'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['from_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['to_'] ?? 'N/A') . '</td>';
                                        echo '<td>' . htmlspecialchars($item['no_days'] ?? 'N/A') . '</td>';
                                        // Action buttons
                                        echo '<td class="text-center">';
                                        echo '<a href="edit_vehicular.php?emp_id=' . $item['emp_id'] . '&va_id=' . $item['va_id'] . '" class="link-dark fas fa-pen-to-square"></a>';
                                        echo '<a href="delete_vehicular.php?va_id=' . htmlspecialchars($item['va_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                    <!-- Initialize DataTables -->
                    <script>
                        $(document).ready(function () {
                            $('#vehicular_accident').DataTable({  // Corrected table ID
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        });
                    </script>
                </div>

                <!--  End Vehicular Accident -->

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
                        <select id="emp" class="form-select" required>
                            <option value="">--Select Company--</option>
                            <?php
                            // Database connection settings
                            $host = 'localhost';
                            $db = 'e_system';
                            $user = 'root';
                            $pass = '';

                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sqloption = "SELECT DISTINCT company FROM employees";

                            try {
                                $stmt = $pdo->prepare($sqloption);
                                $stmt->execute();
                            } catch (PDOException $e) {
                                echo 'Error: ' . $e->getMessage();
                            }

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_GET['company']) && $_GET['company'] === $row['company']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['company']) . '" ' . $selected . '>' . htmlspecialchars($row['company']) . '</option>';
                            }
                            ?>
                        </select>

                        <select id="dept" class="form-select" required>
                            <option value="">--Select Department--</option>
                            <?php
                            // Database connection settings
                            $host = 'localhost';
                            $db = 'e_system';
                            $user = 'root';
                            $pass = '';

                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sqloption = "SELECT DISTINCT division FROM employees";

                            try {
                                $stmt = $pdo->prepare($sqloption);
                                $stmt->execute();
                            } catch (PDOException $e) {
                                echo 'Error: ' . $e->getMessage();
                            }

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_GET['division']) && $_GET['division'] === $row['division']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['division']) . '" ' . $selected . '>' . htmlspecialchars($row['division']) . '</option>';
                            }
                            ?>
                        </select>


                        <table id="employeeTable" class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
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
                                            echo '<tr class="employee-row" data-company="' . htmlspecialchars($item['company']) . '" data-division="' . htmlspecialchars($item['division']) . '">';

                                            echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['division'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($item['company'] ?? 'N/A') . '</td>';
                                            echo '<td class="text-center">';
                                            echo '<a href="view_record.php?emp_id=' . htmlspecialchars($item['emp_id']) . '" 
                                                    style="display: inline-flex; align-items: center; gap: 5px; background-color:rgb(167, 0, 17); color: white; 
                                                    padding: 8px 12px; border-radius: 5px; font-size: 12px; text-decoration: none; transition: 0.3s ease-in-out;"
                                                    onmouseover="this.style.backgroundColor=\'#c82333\'; this.style.transform=\'scale(1.05)\'"
                                                    onmouseout="this.style.backgroundColor=\'#dc3545\'; this.style.transform=\'scale(1)\'"
                                                    class="btn btn-sm btn-danger btn-action">
                                                    <i class="fas fa-eye" style="font-size: 16px;"></i> View Records
                                                    </a>';
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


                document.addEventListener("DOMContentLoaded", function () {
                    const emp = document.getElementById("emp");
                    const dept = document.getElementById("dept"); // Define dept properly
                    const rows = document.querySelectorAll(".employee-row");

                    // Company filtering
                    emp.addEventListener("change", function () {
                        const selectedCompany = this.value.toLowerCase();

                        rows.forEach(row => {
                            const company = row.getAttribute("data-company").toLowerCase();
                            if (selectedCompany === "" || company === selectedCompany) {
                                row.style.display = "";
                            } else {
                                row.style.display = "none";
                            }
                        });
                    });

                    // Department filtering
                    dept.addEventListener("change", function () {
                        const selectedDept = this.value.toLowerCase();

                        rows.forEach(row => {
                            const division = row.getAttribute("data-division").toLowerCase(); // Now data-division exists
                            if (selectedDept === "" || division === selectedDept) {
                                row.style.display = "";
                            } else {
                                row.style.display = "none";
                            }
                        });
                    });
                });



            </script>

            <!--Medicine-->
            <div id="payment" class="page">
                <h2 style="color:black; font-size: 30px;">Medicine Inventory</h2>

                <!-- New Medicine Button -->
                <button id="toggleForm" onclick="window.location.href='new_med.php';" class="fas fa-pills"
                    style="background-color: green; font-weight: bold; margin-bottom: 15px;"> New Medicine</button>

                <a href="export_med_inventory.php" class="fas fa-file-excel" style="margin-left: 10px; background-color: #8B0000; padding: 10px; color: white; 
                  text-decoration: none; border-radius: 5px;">
                    Export to Excel
                </a>

                <!--Count Medicines-->
                <div class="modal-body">
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

                        // SQL query to calculate the total quantity of each Med_name in medicines
                        $sqlMedicines = "SELECT Med_name, SUM(quantity) AS quantity FROM medicines GROUP BY Med_name";
                        $stmtMedicines = $pdo->prepare($sqlMedicines);
                        $stmtMedicines->execute();
                        $medicinesData = $stmtMedicines->fetchAll(PDO::FETCH_ASSOC);

                        // SQL query to calculate the total quantity of each medicine in tbl_medicine
                        $sqlTblMedicine = "SELECT medicine, SUM(quantity) AS quantity FROM tbl_medicine GROUP BY medicine";
                        $stmtTblMedicine = $pdo->prepare($sqlTblMedicine);
                        $stmtTblMedicine->execute();
                        $tblMedicineData = $stmtTblMedicine->fetchAll(PDO::FETCH_ASSOC);

                        // Create a map for tbl_medicine quantities
                        $tblMedicineMap = [];
                        foreach ($tblMedicineData as $row) {
                            $tblMedicineMap[$row['medicine']] = $row['quantity'];
                        }

                        // Display the data in a horizontal scrollable list with subtraction logic
                        echo "<div class='horizontal-scroll' style='display: flex; gap: 10px; max-width: 100%; overflow-x: auto; padding: 10px;'>";
                        foreach ($medicinesData as $item) {
                            $medName = $item['Med_name'];
                            $medQuantity = $item['quantity'];
                            $tblQuantity = $tblMedicineMap[$medName] ?? 0; // Default to 0 if no match in tbl_medicine
                    
                            // Subtract quantities
                            $resultQuantity = $medQuantity - $tblQuantity;

                            // Check if the result quantity is below 25, change background color to red
                            $backgroundColor = $resultQuantity < 25 ? 'background-color: red;' : 'background-color: rgb(0, 0, 0);';
                            $refillMessage = $resultQuantity < 25 ? '<p style="color: yellow;">Needs to Refill</p>' : '';

                            // Display each item with dynamic background color
                            echo "<div class='item' style='min-width: 200px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; $backgroundColor text-align: center; font-weight:bold; color: white;'>";
                            echo htmlspecialchars($medName) . " — " . htmlspecialchars($resultQuantity);
                            echo "</div>";
                        }
                        echo "</div>";
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </div>

                <!--Count Medicines-->
                <div class="filter" style="float: right; display: inline-flex; align-items: center;">
                    <div class="form-row">

                        <div class="form-gorup">
                            <select class="date3" id="date3" name="date3" onchange="filterDateByMonth()"
                                style="margin-left: 0;">
                                <option value="all">--Set by Month--</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select id="companyFilter" class="form-select" required onchange="filterData()">
                                <option value="">--Select Medicine--</option>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "e_system");
                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                // Populate dropdown options
                                $sqloption = "SELECT DISTINCT Med_name FROM medicines";
                                $result = mysqli_query($conn, $sqloption);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = (isset($_GET['Med_name']) && $_GET['Med_name'] === $row['Med_name']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['Med_name']) . '" ' . $selected . '>' . htmlspecialchars($row['Med_name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No Medicines Found</option>';
                                }
                                ?>
                            </select>

                            <script>
                                function filterData() {
                                    const filterValue = document.getElementById('companyFilter').value;
                                    window.location.href = `clinic_admin.php?Med_name=${encodeURIComponent(filterValue)}#payment`;
                                }
                            </script>


                        </div>
                    </div>
                </div>
                <?php
                // Initialize variables
                $medFilter = isset($_GET['Med_name']) ? $_GET['Med_name'] : '';
                $totalCount = 0;

                try {
                    // Query to fetch filtered or all medicines
                    $query = $medFilter ? "SELECT * FROM medicines WHERE Med_name = :medFilter" : "SELECT * FROM medicines";
                    $stmt = $pdo->prepare($query);

                    if ($medFilter) {
                        $stmt->bindParam(':medFilter', $medFilter, PDO::PARAM_STR);
                    }

                    $stmt->execute();
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Count total rows
                    $totalCount = $stmt->rowCount();
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                ?>

                <!-- Medicine Table -->
                <table id="medicineTable" class="display">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Expiration Date</th>
                            <th>Quantity</th>
                            <th>Date Received</th>
                            <th>Receiver</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($data) {
                            foreach ($data as $item) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($item['Med_name' ?? 'N/A']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['supply' ?? 'N/A']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['quantity' ?? 'N/A']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['date_receive' ?? 'N/A']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['receiver' ?? 'N/A']) . '</td>';
                                echo '<td class="text-center">';
                                echo '<a href="edit_med.php?med_id=' . htmlspecialchars($item['med_id']) . '" class="link-dark"><i class="fas fa-edit"></i></a>';
                                echo '<a href="delete_med.php?med_id=' . htmlspecialchars($item['med_id']) . '" class="link-dark" style="margin-left: 10px;"><i class="fas fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No medicines found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <script>
                $(document).ready(function () {
                    // Initialize DataTable with custom filtering
                    var table = $('#medicineTable').DataTable({
                        initComplete: function () {
                            // Custom filtering function for dates
                            $.fn.dataTable.ext.search.push(
                                function (settings, data, dataIndex) {
                                    var selectedMonth = $('.date3').val().toLowerCase();
                                    if (selectedMonth === 'all') return true;

                                    // Get date from the date column (index 3)
                                    var dateStr = data[3];
                                    if (!dateStr) return false;

                                    try {
                                        // Parse the date string to get the month name
                                        var date = new Date(dateStr);
                                        var monthName = date.toLocaleString('en-US', { month: 'long' }).toLowerCase();
                                        return monthName === selectedMonth;
                                    } catch (e) {
                                        return false;
                                    }
                                }
                            );

                            // Apply the date filter
                            $('.date3').on('change', function () {
                                table.draw();
                            });

                            // Apply the medicine filter
                            // Apply the date filter
                            $('.date3').on('change', function () {
                                var month = $(this).val().toLowerCase();
                                if (month === 'all') {
                                    table.column(3).search('').draw();
                                } else {
                                    table.column(3).search(month).draw();
                                }
                            });

                            // Apply the medicine filter
                            $('#companyFilter').on('change', function () {
                                var medicine = $(this).val();
                                table.column(0).search(medicine).draw();
                            });
                        }
                    });
                });

                function filterData() {
                    // Prevent default page reload behavior
                    const filterValue = document.getElementById('companyFilter').value;
                    var table = $('#medicineTable').DataTable();
                    table.column(0).search(filterValue).draw();
                    return false;
                }

                function filterdate3() {
                    let selectedMonth = document.getElementById("date3").value.toLowerCase();
                    let table = document.getElementById("medicineTable");
                    let rows = table.getElementsByTagName("tr");

                    for (let i = 1; i < rows.length; i++) { // Skip the header row
                        let dateCell = rows[i].getElementsByTagName("td")[3]; // Date Received column

                        if (dateCell) {
                            let dateText = dateCell.textContent.trim(); // Example: "2025-03-21"
                            let monthIndex = new Date(dateText).getMonth(); // Get month (0-11)

                            let monthNames = ["january", "february", "march", "april", "may", "june",
                                "july", "august", "september", "october", "november", "december"];

                            if (selectedMonth === "all" || monthNames[monthIndex] === selectedMonth) {
                                rows[i].style.display = ""; // Show row
                            } else {
                                rows[i].style.display = "none"; // Hide row
                            }
                        }
                    }
                }



                // Auto-hide success message after 5 seconds
                setTimeout(function () {
                    const successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                }, 5000);


            </script>

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
            } else if (hash === '#form_section') {
                showPage('form_section'); // Show the fit section

                if (hash === '#fit_work_tab') {
                    showPage('fit_work_tab');

                } else if (hash === '#medication_tab') {
                    showPage('medication_tab');

                } else if (hash === '#vital_signs_tab') {
                    showPage('vital_signs_tab');

                } else if (hash === '#consultation_tab') {
                    showPage('consultation_tab');

                } else if (hash === '#confinement_tab') {
                    showPage('confinement_tab');

                } else if (hash === '#sent_home_tab') {
                    showPage('sent_home_tab');

                } else if (hash === '#special_case_tab') {
                    showPage('special_case_tab');

                } else if (hash === '#incident_report_tab') {
                    showPage('incident_report_tab');
                }

            } else if (hash === '#preg') {
                showPage('preg');// Show the pregnant notification

            } else if (hash === '#equip') {
                showPage('equip');

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
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function () {
            let sidebar = document.querySelector(".sidebar");
            let closeBtn = document.querySelector("#btn");

            closeBtn.addEventListener("click", () => {
                sidebar.classList.toggle("open");
                menuBtnChange();
            });

            function menuBtnChange() {
                if (sidebar.classList.contains("open")) {
                    closeBtn.classList.replace("fa-bars", "fa-times");
                } else {
                    closeBtn.classList.replace("fa-times", "fa-bars");
                }
            }

            // Show tooltips only when sidebar is closed
            const tooltips = document.querySelectorAll('.tooltip');
            sidebar.addEventListener('mouseover', () => {
                if (!sidebar.classList.contains('open')) {
                    tooltips.forEach(tooltip => {
                        tooltip.style.display = 'block';
                    });
                }
            });

            sidebar.addEventListener('mouseout', () => {
                tooltips.forEach(tooltip => {
                    tooltip.style.display = 'none';
                });
            });
        });
    </script>
    <script>
        function filterDateByMonth() {
            var selectedMonth = document.getElementById("date3").value;
            var table = $('#medicineTable').DataTable();

            // Clear previous search functions
            $.fn.dataTable.ext.search = [];

            // Add new search function for month filtering
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                if (selectedMonth === "all") {
                    return true; // Show all rows if "all" is selected
                }

                var dateStr = data[3];

                var dateParts = dateStr.split('-');
                if (dateParts.length >= 2) {
                    var month = dateParts[1];
                    return month === selectedMonth;
                }

                return false;
            });

            table.draw();
        }
    </script>
    <script>
        function searchRecords() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const searchFilter = document.getElementById('searchFilter').value;
            const table = document.querySelector('.table-responsive table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                let text = '';
                let match = false;

                if (searchFilter === 'all') {
                    text = row.textContent.toLowerCase();
                    match = text.includes(searchInput);
                } else {
                    const cells = row.getElementsByTagName('td');
                    const columnIndex = {
                        'emp_no': 0,
                        'name': 1,
                        'diagnosis': 4,
                        'company': 3
                    }[searchFilter];

                    if (cells[columnIndex]) {
                        text = cells[columnIndex].textContent.toLowerCase();
                        match = text.includes(searchInput);
                    }
                }

                row.style.display = match ? '' : 'none';
            }
        }

        // Add event listener for real-time search
        document.getElementById('searchInput').addEventListener('keyup', searchRecords);
        document.getElementById('searchFilter').addEventListener('change', searchRecords);
    </script>
</body>

</html>