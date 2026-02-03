# Iseki Saran - Employee Suggestion System

## Overview

**Iseki Saran** is an internal management system designed to facilitate the collection, review, and tracking of employee suggestions for operational improvements. The platform provides a structured workflow from suggestion submission by members to multi-stage review and finalization by leadership.

The system emphasizes transparency, auditability, and efficient reporting of continuous improvement initiatives within the organization.

## Key Features

### 1. Member Operations (Suggestion Submission)
*   **Simple Submission**: Easy-to-use interface for employees to submit new suggestions.
*   **Real-time Collaboration**: Ability to update suggestion fields and details during the drafting phase.
*   **Suggestion Tracking**: Personal dashboard for members to view the status of their submitted suggestions.

### 2. Leader Operations (Review & Management)
*   **Unified Dashboard**: Comprehensive overview for leaders to monitor the pulse of employee feedback.
*   **Sophisticated Filtering**: Monitor suggestions based on submission status:
    *   **Pending Submission**: Track suggestions that are still in progress.
    *   **Pending Signature**: Identify suggestions awaiting final sign-off.
*   **Field Management**: Fine-grained control over suggestion details during the review process.
*   **Finalization Workflow**: Secure process to finalize and archive approved suggestions.

### 3. Reporting & Exports
*   **Bulk Exports**: Capability to export all suggestions to Excel (via PHPSpreadsheet) for deep analysis.
*   **PDF Generation**: Generate high-quality PDF documentation for individual or bulk suggestions (via mPDF).
*   **Audit Lists**: Specialized exports for tracking "Not Submit" or "Not Sign" lists to ensure process compliance.

### 4. Administration
*   **User & Member Management**: Dedicated CRUD interfaces for managing system access and employee profiles.
*   **Secure Access**: Role-based access control leveraging custom `LeaderMiddleware` and `MemberMiddleware`.

## Technology Stack

### Backend
*   **Framework**: [Laravel 12.x](https://laravel.com)
*   **Language**: PHP ^8.2+
*   **Database**: SQLite (Local) / MySQL Compatible (Production)
*   **Key Libraries**:
    *   `mpdf/mpdf`: Used for robust PDF generation and report styling.
    *   `phpoffice/phpspreadsheet`: used for complex Excel exports.
    *   `yajra/laravel-datatables-oracle`: High-performance data grids for the admin interface.

### Frontend
*   **Build Tool**: [Vite](https://vitejs.dev)
*   **Styling**: [Tailwind CSS v4.0](https://tailwindcss.com)
*   **HTTP Client**: Axios

## Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone <repository-url>
    cd iseki_saran
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Configure your database and application settings in `.env`.

4.  **Database Initialization**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Build Frontend**
    ```bash
    npm run build
    ```

6.  **Run Development Server**
    ```bash
    php artisan serve
    ```
    Access the system at `http://localhost:8000`.

## License

This project is proprietary.
