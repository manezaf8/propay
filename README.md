# ProPay: Streamlined People Management
ProPay is a web-based system designed for efficient people management, offering a range of features for seamless user data handling. Developed with a mini OOP PHP framework-like system that utilizes composer to add external Libraries.
# Key Features
# 1. User-Centric Operations
-	*Create:* Capture new user data effortlessly.
-	*Read:* Easily view and retrieve stored information.
-	*Update:* Modify existing records as needed.
-	*Delete:* Remove outdated or unnecessary entries.
# 2. Email Notification
•	Automatic email notifications are triggered upon adding a new person to the system, welcoming them to ProPay.
•	Seamless integration with frameworks for event-driven email processing.
# 3. Event Handling
•	Integration with frameworks allows ProPay to fire events, triggering jobs for efficient email processing.
# 4. Error Handling and Validations
•	Robust error handling mechanisms provide informative messages for users and detailed logs for administrators.
•	Client-side validations using HTML, CSS, and JavaScript enhance the user experience and ensure data accuracy.
# 5. Technology Stack
-	PHP >= 8.2: Power and efficiency for robust backend operations.
-	MySQL >= 5.1: Secure and reliable database management.
-	Apache: Flexible compatibility for virtual environments.
-	HTML, CSS,and JavaScript: Intuitive and responsive frontend design.
-	Bootstrap and jQuery: Optional enhancements for a sleek and modern UI.
- There is also a docker config for the project you can Easily run `docker-compose build` and `docker-compose up` assume that you already have docker running in your system.

# Getting Started
- Follow these simple steps to get started with ProPay:
    - 	Clone the repository.
    - 	Configure your PHP environment (>= 8.2).
    -	Set up a MySQL database (>= 5.1) and configure * *    database credentials.
    - 	Choose between Apache or Nginx for hosting.
    - 	Run `composer install` and `composer update`
    - 	Setup your database with the db sql
    - 	Make sure that the routers in the router.php file matches your setup mine is like http://ekomi.local/vmp/task-dashboard/ hance `/vmp/task-dashboard/user-edit` instead of `/user-edit`
    - 	Adjust `.htacces` file redirect accordingly preferable to `/`
- Welcome to ProPay – where simplicity meets effectiveness in people management.
