<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="table-styles.css">
</head>
<body>
    <div class="container">
        <h1>User Registration System</h1>
        
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('register', this)">Register</button>
            <button class="tab-btn" onclick="showTab('users', this)">View Users</button>
        </div>

        <!-- Registration Form -->
        <div id="register" class="tab-content active">
            <form id="registrationForm" enctype="multipart/form-data">
                <input type="hidden" id="userId" name="userId">
                
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Phone:</label>
                    <input type="tel" name="phone">
                </div>

                <div class="form-group">
                    <label>Birth Date:</label>
                    <input type="date" name="birth_date">
                </div>

                <div class="form-group">
                    <label>Birth Time:</label>
                    <input type="time" name="birth_time">
                </div>

                <div class="form-group">
                    <label>Birth Month:</label>
                    <input type="month" name="birth_month">
                </div>

                <div class="form-group">
                    <label>Birth Week:</label>
                    <input type="week" name="birth_week">
                </div>

                <div class="form-group">
                    <label>Birth Date & Time:</label>
                    <input type="datetime-local" name="birth_datetime">
                </div>

                <div class="form-group">
                    <label>Website URL:</label>
                    <input type="url" name="website_url">
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <input type="radio" name="gender" value="male" id="male" required>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" value="female" id="female" required>
                    <label for="female">Female</label>
                </div>

                <div class="form-group">
                    <label>Favorite Color:</label>
                    <input type="color" name="profile_color" value="#000000">
                </div>

                <div class="form-group">
                    <label>Salary Range:</label>
                    <input type="range" name="salary_range" min="20000" max="200000" value="50000" oninput="updateSalary(this.value)">
                    <span id="salaryValue">50000</span>
                </div>

                <div class="form-group">
                    <label>Bio:</label>
                    <input type="search" name="bio" placeholder="Search or enter bio...">
                </div>

                <div class="form-group">
                    <label>Profile Image:</label>
                    <input type="file" name="profile_image" accept="*">
                </div>

                <div class="form-group">
                    <input type="checkbox" name="newsletter" id="newsletter">
                    <label for="newsletter">Subscribe to newsletter</label>
                </div>

                <div class="form-buttons">
                    <input type="submit" value="Register">
                    <input type="reset" value="Reset Form">
                    <input type="button" value="Cancel" onclick="cancelEdit()">
                </div>
            </form>
        </div>

        <!-- Users List -->
        <div id="users" class="tab-content">
            <div id="usersList"></div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>