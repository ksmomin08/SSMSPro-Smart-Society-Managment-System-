<<<<<<< HEAD
# 🏢 Smart Society Management System

[![Laravel Version](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/alpine.js-%238BC0D0.svg?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

A modern, SaaS-ready, multi-tenant **Smart Society Management System** built with **Laravel 12**, **Tailwind CSS**, and **Alpine.js**. This application streamlines residential society operations by bringing admins, residents, and security staff onto a single, cohesive, interactive digital platform.

---

## 🚀 Key Features by Portal

The system is architected around **four distinct, highly secure user roles**, each with their own dedicated, responsive dashboard:

### 1. 👑 Super Admin Portal (SaaS Master)
* **SaaS Tenant Management:** Supervise and onboard multiple residential societies under a single, unified database schema.
* **Society Configuration:** Dynamically configure subscription details and administrative accounts for individual societies.
* **Activity & Security Auditing:** Track comprehensive global action logs to audit super-admin events and tenant modifications.

### 2. 🏢 Society Admin Dashboard
* **Dynamic Customization Engine:** Personalize the look and feel of the society portal via a dynamic CSS customizer.
* **Infrastructure Management:** Complete CRUD capabilities for managing Buildings, Apartments (Flats), and Residents.
* **Smart Visitor Management:** Track walk-in guests, view dynamic QR-coded security passes, and blacklist suspicious individuals.
* **Automated Maintenance Billing:** Generate recurring maintenance bills, track payment statuses, and dynamically export premium PDF receipts.
* **Facility Booking & Reservation:** View and manage resident requests to book common amenities (clubhouses, gyms, pools) with single-click approvals.
* **Assigned Parking Slot Allocation:** Allocate, release, or approve resident requests for dedicated parking spots.
* **Notice Board Broadcasting:** Instantly share society notices, updates, and safety alerts with residents.

### 3. 🏡 Resident Portal
* **Digital Maintenance Invoicing:** View and pay maintenance bills online via the integrated **Razorpay** payment gateway.
* **Real-time Gate Approvals:** Leverage live polling to approve or deny visitor entry requests instantly from the security gate.
* **Visitor Pre-Approvals:** Generate secure passcodes and dynamic QR codes for expected guests to expedite entry.
* **Interactive Facility Booking:** Seamlessly check availability and request reservations for shared society amenities.
* **Internal Complaint Ticketing:** Raise complaints, tag categories, upload descriptions, and track real-time resolution status.
* **Smart Parking:** Instantly view assigned parking slots or submit requests for vacant spots.

### 4. 🛡️ Security Guard Terminal
* **Walk-In Registrations:** Quick-onboard visitor credentials (name, phone, destination flat) at the gate.
* **Secure QR Pass Scanner:** Verify pre-approved visitor passes instantly using standard smartphone cameras or passcode matching.
* **Live Resident Ping:** Initiate real-time entry approval popups on the target resident's dashboard.
* **Gate Operations:** Conduct smooth check-in and check-out tracking for visitors.
* **Panic Alert Button:** Instantly trigger security alert notifications to the society administration in emergencies.

---

## 🛠️ Technology Stack & Integrations

* **Core Framework:** [Laravel 12.x](https://laravel.com) (incorporating strict routing, middleware validation, and Eloquent ORM relationships)
* **Frontend Tech:** [Tailwind CSS](https://tailwindcss.com) & [Alpine.js](https://alpinejs.dev) (for high-fidelity design aesthetics, custom CSS variable engines, and reactive UI states)
* **Build System:** [Vite 7.x](https://vitejs.dev) (for lightning-fast module bundling)
* **Payment Gateway:** [Razorpay PHP SDK](https://razorpay.com) (for secure, real-time maintenance payments)
* **PDF Utility:** [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) (for dynamic, high-performance invoice generation and download)
* **QR Engine:** [Simple Software QR Code](https://github.com/SimpleSoftwareIO/simple-qrcode) (for generating secure, dynamic visitor gate passes)

---

## ⚡ Quick Setup & Installation

To make deployment seamless, the project comes pre-configured with a custom composer setup script that takes care of the entire initialization sequence.

### Prerequisites
* **PHP** `^8.2` or higher
* **Composer** `^2.0`
* **Node.js** & **NPM**
* A local database server (MySQL, SQLite, or PostgreSQL)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
   cd "Smart Society Management System/Society"
   ```

2. **Run One-Click Setup**
   The application uses a custom composer setup pipeline that automatically installs dependencies, copies files, generates cryptographic keys, and runs database migrations.
   ```bash
   composer run setup
   ```

3. **Configure Environment Variables**
   Open the generated `.env` file and configure your database settings and Razorpay API keys:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smart_society
   DB_USERNAME=root
   DB_PASSWORD=
   
   RAZORPAY_KEY_ID=your_razorpay_key_id
   RAZORPAY_KEY_SECRET=your_razorpay_key_secret
   ```

4. **Launch Local Servers**
   To launch the local web server, database queues, and hot-reload Vite compilation concurrently, run:
   ```bash
   composer run dev
   ```
   *Your server will boot at `http://localhost:8000`.*

---

## 🗂️ Database Seeding

To quickly populate the database with mock societies, residents, guards, and administrative accounts, run:
```bash
php artisan db:seed
```

---

## 📜 License
Distributed under the MIT License. See `LICENSE` for more information.
=======
# SSMSPro-Smart-Society-Managment-System-
SaaS Multi-Tenant Society Management System built on Laravel 12 &amp; Tailwind CSS. Includes 4 distinct user portals with Razorpay payments, QR-coded visitor passes, live gate security polling, dynamic theme styling, and automated PDF invoice generation.
>>>>>>> b65a7f4474322bb83c91467cca87bbb713fcebc2
