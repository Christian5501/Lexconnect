# LexConnect

**LexConnect** is a web-based legal appointment management system. It connects clients with lawyers through a simple three-role workflow: the client requests an appointment, a secretary reviews and forwards it, and the lawyer confirms the consultation date and time.

## Features

- Client self-registration and secure login (hashed passwords)
- Role-based dashboards for Client, Secretary and Lawyer
- Appointment request form with a short description of the client's legal problem
- Secretary forwards requests to a chosen lawyer
- Lawyer confirms the appointment, sets the consultation date and time, and adds notes
- Real-time status tracking: Pending, Forwarded, Confirmed, Done
- Public pages: Home, About Us and Contact
- 3D courtroom background built with Three.js

## Technologies Used

- PHP
- MySQL
- HTML and CSS
- JavaScript
- Three.js

## User Roles

| Role | What they can do |
|------|------------------|
| Client | Register, log in, submit appointment requests, track status |
| Secretary | View all requests and forward them to a lawyer |
| Lawyer | View assigned cases, confirm appointments, mark them as done |

## Appointment Workflow

Client submits request (Pending) -> Secretary forwards to lawyer (Forwarded) -> Lawyer sets date and time (Confirmed) -> Consultation completed (Done)

## Database

Database name: `lexconnect_db`

| Table | Purpose |
|-------|---------|
| `users` | Stores clients, secretaries and lawyers (separated by the `role` column) |
| `appointments` | Stores each request, its status, assigned lawyer and consultation time |

## How to Run

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this project folder into `C:\xampp\htdocs\`.
3. Open `http://localhost/phpmyadmin`, go to the **SQL** tab, and run the contents of `database.sql`.
4. Check the database settings in `db.php` (default user is `root` with an empty password).
5. Open `http://localhost/LexConnect3d/` in your browser. Use your own folder name if it is different.

## Demo Accounts

Password for all accounts: `password`

| Role | Email |
|------|-------|
| Client | client@lexconnect.rw |
| Secretary | secretary@lexconnect.rw |
| Lawyer | lawyer@lexconnect.rw |

## Project Structure

```
LexConnect3d/
├── index.php                 Home page
├── about.php                 About Us page
├── contact.php               Contact page
├── register.php              Client registration
├── login.php                 Login for all roles
├── logout.php                Ends the session
├── client_dashboard.php      Client dashboard
├── secretary_dashboard.php   Secretary dashboard
├── lawyer_dashboard.php      Lawyer dashboard
├── appointment.php           Handles create, forward, confirm and done actions
├── threescene.php            Three.js 3D courtroom scene
├── db.php                    MySQL connection
├── database.sql              Database setup script
└── style.css                 Stylesheet
```

## Author

Developed by **Christian MUHIRE** as a student project, Kigali, Rwanda.