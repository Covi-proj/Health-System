<?php

session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="favicon_scitech.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier | Science Technology Institute</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #2a2185;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2a2185;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }

        .sidebar h1 {
            color: white;
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: #3c2f9b;
        }

        .sidebar ul li.active {
            border-left: 5px solid #fff;
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

        input[type="text"],
        input[type="password"],
        input[type="date"] {
            width: 48%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #2a2185;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
            margin-right: 4%;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus {
            border-color: #3c2f9b;
        }

        input[type="text"]:last-child,
        input[type="password"]:last-child,
        input[type="date"]:last-child {
            margin-right: 0;
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
            background-color: blue;
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
    </style>
</head>
<body>

<div class="container">

    <div class="sidebar">
        <img src="favicon_scitech.png" alt="logo" width="30" height="30">
        <h1>Cashier</h1>
        <ul>
            <li onclick="showDashboard()">Dashboard</li>
            <li onclick="showBalances()">PMR</li>
            <li onclick="showPayment()">Inventory</li>
        </ul>
        <span id="logout" onclick="logout()" class="logout">Sign Out</span>
    </div>

    <div class="content">
        <div id="dashboard" class="page active">
            <h2>Welcome, Cashier</h2>
            <h1>Dashboard</h1>
            <div class="rounded-box">
                <button onclick="reloadTable()">Refresh</button>
                <a class="link" href="download_payments.php">Download Table</a>
                <select class="Grade" id="Grade" onchange="filterGrade()">
                    <option value="All">All Grades</option>
                    <option value="Grade 7">Grade 7</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                </select>
                <h1 class="student-count">Payments</h1>
            </div>
        </div>

        <div id="balances" class="page">
            <h2>Student Fees</h2>
            <button type="button" onclick="balance()">Refresh</button>
        </div>

        <div id="payment" class="page">
            <h2>Payment</h2>
            <button id="toggleForm">New Payment</button>
            <form id="paymentForm" class="hidden" method="POST">
             
            </form>
        </div>
    </div>
</div>

<script>
    function showDashboard() {
        setActivePage('dashboard');
    }

    function showBalances() {
        setActivePage('balances');
    }

    function showPayment() {
        setActivePage('payment');
    }

    function setActivePage(pageId) {
        const pages = document.querySelectorAll('.page');
        pages.forEach(page => page.classList.remove('active'));
        document.getElementById(pageId).classList.add('active');
    }

    document.getElementById('toggleForm').addEventListener('click', function () {
        const form = document.getElementById('paymentForm');
        form.classList.toggle('hidden');
    });
</script>

</body>
</html>
