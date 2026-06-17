# 📚 Library Collaboration Room Booking System

A web-based library room reservation system built as a **Web Development final project**. Students can browse available collaboration rooms, check real-time availability, and book time slots. Admins manage room schedules and handle booking approvals.

## Features

### Student Portal
- Browse available collaboration rooms
- Check real-time room availability by date
- Book rooms by selecting available time slots
- View booking history and reservation status
- Cancel upcoming reservations

### Admin Dashboard
- Manage room listings and configurations
- Review and approve/reject booking requests
- Monitor room utilization
- Manage student accounts

## Tech Stack
- **Backend**: PHP (94.4%)
- **Database**: MySQL
- **Frontend**: HTML, CSS
- **Architecture**: Server-side rendered with PHP includes

## Project Structure
```
├── admin/                  # Admin portal pages
├── images/                 # Static assets
├── authenticate.php        # Authentication handler
├── check_availability.php  # Real-time availability check
├── login.php              # Login page
├── my_bookings.php        # Student booking history
├── student_booking.php    # Room booking interface
├── student_home.php       # Student dashboard
├── student_reservation.php # Reservation management
├── wbdv.sql               # Database schema
└── README.md
```

## Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/airo-coder/Library-Collaboration-Room-Booking-System.git
   cd Library-Collaboration-Room-Booking-System
   ```

2. Import the database:
   - Create a MySQL database
   - Import `wbdv.sql`

3. Configure the database connection in the PHP config files.

4. Run with XAMPP/Apache or PHP's built-in server:
   ```bash
   php -S localhost:8000
   ```

5. Open [http://localhost:8000/login.php](http://localhost:8000/login.php) in your browser.

## Screenshots

> 📸 *Screenshots coming soon.*
