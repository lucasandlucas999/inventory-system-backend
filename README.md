# Faculty Inventory Management System (Backend)

## Description
This project is an **Inventory Management System** designed specifically for our faculty, developed as a collaborative group project. It serves as the robust backend infrastructure to efficiently manage, track, and organize stock and inventory.

## Technologies Used
- **Framework:** [Laravel](https://laravel.com/)
- **Language:** PHP

## Planned Features & Roadmap
- [x] Basic project setup & routing (e.g., API health checks)
- [x] **JWT Authentication:** Secure API endpoint access using JSON Web Tokens.
- [ ] **OAuth2 Integration:** Advanced authorization flows for third-party access or single sign-on.
- [ ] **Real-time Broadcasting:** Implementing **Laravel Reverb** for live stock updates, alerts, and real-time notifications.
- [ ] **Role-Based Access Control (RBAC):** Distinct access levels for administrators, inventory managers, and general staff.
- [ ] **Automated Alerts:** Low stock warnings and expiration date notifications.
- [ ] **Audit Log & History:** Comprehensive tracking of who added, removed, or updated items.
- [ ] **Reporting & Analytics:** Exportable reports (PDF/Excel) for stock movements and inventory valuation.

## Prerequisites
- PHP >= 8.2
- Composer
- A supported Database (MySQL / PostgreSQL / SQLite)
- Node.js & NPM (for compiling any frontend assets or broadcasting dependencies)

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/lucasandlucas999/inventory-system-backend
   cd backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   Copy the example environment file and configure your local settings:
   ```bash
   cp .env.example .env
   ```
   *Make sure to update your database credentials in the `.env` file.*

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the Development Server**
   ```bash
   php artisan serve
   ```
   *The API will be available at `http://localhost:8000`.*

## Available API Routes
Currently implemented testing/health routes:
- `GET /api/health` - API Health check

## Collaboration & Contributing
As this is a group project, please follow our standard Git workflow:
1. Create a descriptive feature branch (`git checkout -b feature/jwt-auth`)
2. Commit your changes with clear messages (`git commit -m 'Implement initial JWT scaffolding'`)
3. Push to your branch (`git push origin feature/jwt-auth`)
4. Open a Pull Request for code review.

---
*Desarrollado por grupo 1, sistema de stock management.*
