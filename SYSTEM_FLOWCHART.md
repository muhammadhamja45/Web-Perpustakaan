# 📊 System Flowcharts - Sistem Perpustakaan Digital SMK

[![Mermaid](https://img.shields.io/badge/Mermaid-Flowcharts-FF6B6B?style=for-the-badge&logo=mermaid&logoColor=white)](https://mermaid.js.org)
[![System Design](https://img.shields.io/badge/System-Design-4ECDC4?style=for-the-badge&logo=diagramsdotnet&logoColor=white)](https://diagrams.net)

> **Koleksi lengkap flowchart dan diagram alur untuk semua fitur sistem perpustakaan digital**

---

## 📋 **Table of Contents**

1. [System Overview](#-system-overview)
2. [User Authentication Flow](#-user-authentication-flow)
3. [Book Management Flow](#-book-management-flow)
4. [Loan Management Flow](#-loan-management-flow)
5. [Admin Management Flow](#-admin-management-flow)
6. [Email Notification Flow](#-email-notification-flow)
7. [Dashboard Analytics Flow](#-dashboard-analytics-flow)
8. [Error Handling Flow](#-error-handling-flow)
9. [Security Flow](#-security-flow)
10. [Database Transaction Flow](#-database-transaction-flow)

---

## 🌐 **System Overview**

### **High-Level System Architecture**

```mermaid
graph TB
    subgraph "User Interface Layer"
        A1[Web Browser]
        A2[Mobile Browser]
        A3[PWA Future]
    end

    subgraph "Application Layer"
        B1[Laravel Routes]
        B2[Middleware Stack]
        B3[Controllers]
        B4[Business Logic]
        B5[Services]
    end

    subgraph "Data Layer"
        C1[Eloquent ORM]
        C2[Database Models]
        C3[MySQL Database]
        C4[Redis Cache]
    end

    subgraph "External Services"
        D1[Gmail SMTP]
        D2[File Storage]
        D3[Backup Service]
    end

    A1 --> B1
    A2 --> B1
    A3 --> B1

    B1 --> B2
    B2 --> B3
    B3 --> B4
    B4 --> B5

    B3 --> C1
    C1 --> C2
    C2 --> C3
    B5 --> C4

    B5 --> D1
    B5 --> D2
    B5 --> D3

    style A1 fill:#e3f2fd
    style B3 fill:#fff3e0
    style C3 fill:#e8f5e8
    style D1 fill:#f3e5f5
```

---

## 🔐 **User Authentication Flow**

### **Complete Registration Process**

```mermaid
flowchart TD
    A[User akses halaman registrasi] --> B[Isi form registrasi]
    B --> C{Validasi client-side}
    C -->|Invalid| D[Tampilkan error inline]
    D --> B

    C -->|Valid| E[Submit form via AJAX]
    E --> F{Validasi server-side}
    F -->|Invalid| G[Return JSON error]
    G --> H[Tampilkan error di form]
    H --> B

    F -->|Valid| I[Generate 6-digit code]
    I --> J[Simpan ke two_factor_tokens]
    J --> K[Kirim email 2FA]
    K --> L{Email berhasil dikirim?}
    L -->|Gagal| M[Return error email]
    M --> H

    L -->|Berhasil| N[Return success JSON]
    N --> O[Tampilkan modal 2FA]
    O --> P[Timer countdown 10 menit]

    P --> Q[User input kode 2FA]
    Q --> R[Submit kode via AJAX]
    R --> S{Validasi kode}
    S -->|Invalid| T[Tampilkan error kode]
    T --> Q

    S -->|Valid| U[Mark token as used]
    U --> V[Buat record user]
    V --> W{Role user}
    W -->|Student| X[Set approved=true]
    W -->|Admin| Y[Set approved=false]

    X --> Z[Auto login]
    Y --> AA[Redirect pending approval]
    Z --> BB[Redirect ke dashboard]

    style O fill:#fff3e0
    style BB fill:#e8f5e8
    style AA fill:#ffebee
```

### **Login Process with 2FA**

```mermaid
flowchart TD
    A[User input email/password] --> B{Kredensial valid?}
    B -->|Invalid| C[Tampilkan error login]
    C --> A

    B -->|Valid| D{User approved?}
    D -->|Tidak| E[Redirect approval pending]

    D -->|Ya| F[Generate 2FA code]
    F --> G[Kirim email kode]
    G --> H[Tampilkan modal 2FA]

    H --> I[User input kode]
    I --> J{Kode valid?}
    J -->|Invalid| K[Tampilkan error]
    K --> I

    J -->|Valid| L[Create session]
    L --> M[Set remember token]
    M --> N[Redirect dashboard]

    style H fill:#fff3e0
    style N fill:#e8f5e8
    style E fill:#ffebee
```

---

## 📚 **Book Management Flow**

### **Book CRUD Operations**

```mermaid
flowchart TD
    subgraph "Book Creation"
        A1[Admin klik 'Tambah Buku'] --> A2[Form tambah buku]
        A2 --> A3{Validasi input}
        A3 -->|Invalid| A4[Tampilkan error]
        A4 --> A2
        A3 -->|Valid| A5[Simpan ke database]
        A5 --> A6[Set available_quantity = quantity]
        A6 --> A7[Redirect dengan success]
    end

    subgraph "Book Update"
        B1[Admin klik 'Edit Buku'] --> B2[Form edit buku]
        B2 --> B3[Load data existing]
        B3 --> B4{Ada perubahan stok?}
        B4 -->|Ya| B5[Hitung selisih stok]
        B5 --> B6[Update available_quantity]
        B4 -->|Tidak| B7[Update data buku]
        B6 --> B7
        B7 --> B8[Redirect dengan success]
    end

    subgraph "Book Deletion"
        C1[Admin klik 'Hapus Buku'] --> C2{Ada loan aktif?}
        C2 -->|Ya| C3[Tampilkan error 'Buku sedang dipinjam']
        C2 -->|Tidak| C4[Konfirmasi penghapusan]
        C4 --> C5{User konfirmasi?}
        C5 -->|Tidak| C6[Batal hapus]
        C5 -->|Ya| C7[Soft delete buku]
        C7 --> C8[Redirect dengan success]
    end

    style A7 fill:#e8f5e8
    style B8 fill:#e8f5e8
    style C3 fill:#ffebee
    style C8 fill:#e8f5e8
```

### **Book Search & Filter System**

```mermaid
flowchart LR
    A[User input search] --> B{Tipe pencarian}
    B -->|Title| C[Search di kolom title]
    B -->|Author| D[Search di kolom author]
    B -->|ISBN| E[Search di kolom ISBN]
    B -->|All| F[Search di semua kolom]

    C --> G[Query dengan LIKE %search%]
    D --> G
    E --> H[Query dengan exact match]
    F --> G

    G --> I{Hasil ditemukan?}
    H --> I
    I -->|Ya| J[Tampilkan hasil]
    I -->|Tidak| K[Tampilkan 'Tidak ditemukan']

    J --> L{User filter availability?}
    L -->|Ya| M[Filter available_quantity > 0]
    L -->|Tidak| N[Tampilkan semua hasil]
    M --> N

    style J fill:#e8f5e8
    style K fill:#fff3e0
```

---

## 📋 **Loan Management Flow**

### **Book Borrowing Process**

```mermaid
flowchart TD
    A[Student pilih buku] --> B[Klik 'Pinjam Buku']
    B --> C{User sudah login?}
    C -->|Tidak| D[Redirect ke login]
    D --> E[Login berhasil]
    E --> F[Kembali ke buku]

    C -->|Ya| F
    F --> G{Role = student?}
    G -->|Admin| H[Proses langsung - no limit]
    G -->|Student| I{Cek jumlah loan aktif}

    I --> J{Loan aktif < 5?}
    J -->|Tidak| K[Error: 'Maksimal 5 buku']
    K --> L[Tampilkan pesan error]

    J -->|Ya| M{Buku tersedia?}
    M -->|Tidak| N[Error: 'Stok habis']
    N --> L

    M -->|Ya| O[Start database transaction]
    O --> P[Buat record loan]
    P --> Q[Set due_date = now + 14 hari]
    Q --> R[Update available_quantity - 1]
    R --> S[Commit transaction]
    S --> T[Kirim email notifikasi]
    T --> U[Redirect dengan success]

    H --> M

    style U fill:#e8f5e8
    style L fill:#ffebee
    style O fill:#fff3e0
```

### **Book Return Process**

```mermaid
flowchart TD
    A[User klik 'Kembalikan Buku'] --> B{User authorized?}
    B -->|Tidak| C[Error 403 Forbidden]

    B -->|Ya| D{Buku sudah dikembalikan?}
    D -->|Ya| E[Error: 'Sudah dikembalikan']

    D -->|Tidak| F[Start database transaction]
    F --> G[Set returned_date = now()]
    G --> H[Update available_quantity + 1]
    H --> I[Commit transaction]
    I --> J{Telat dikembalikan?}
    J -->|Ya| K[Catat denda (future feature)]
    J -->|Tidak| L[Proses selesai]
    K --> L
    L --> M[Redirect dengan success]

    style M fill:#e8f5e8
    style C fill:#ffebee
    style E fill:#ffebee
```

### **Overdue Management System**

```mermaid
flowchart TD
    A[Daily Cron Job] --> B[Query loans dengan due_date < today]
    B --> C{Ada overdue loans?}
    C -->|Tidak| D[Log: 'No overdue loans']

    C -->|Ya| E[Loop setiap overdue loan]
    E --> F[Kirim email reminder]
    F --> G[Update status reminder]
    G --> H{Lebih dari 7 hari overdue?}
    H -->|Ya| I[Kirim email peringatan keras]
    H -->|Tidak| J[Next loan]
    I --> K[Flag untuk tindakan admin]
    K --> J
    J --> L{Masih ada loan?}
    L -->|Ya| E
    L -->|Tidak| M[Selesai]

    style M fill:#e8f5e8
    style I fill:#ffebee
```

---

## 👨‍💼 **Admin Management Flow**

### **Admin Registration & Approval**

```mermaid
flowchart TD
    A[Calon admin registrasi] --> B[Pilih role 'Admin']
    B --> C[Proses 2FA sama seperti student]
    C --> D[Account created dengan approved=false]
    D --> E[Redirect ke approval pending page]

    E --> F[Super admin login]
    F --> G[Akses halaman user approval]
    G --> H[Review calon admin]
    H --> I{Keputusan super admin}

    I -->|Approve| J[Set approved=true]
    J --> K[Set approved_by = super admin ID]
    K --> L[Set approved_at = now()]
    L --> M[Kirim email approval]
    M --> N[Admin bisa login]

    I -->|Reject| O[Delete user record]
    O --> P[Kirim email rejection]
    P --> Q[Admin tidak bisa akses]

    style N fill:#e8f5e8
    style Q fill:#ffebee
    style E fill:#fff3e0
```

### **Admin Permission Matrix**

```mermaid
flowchart LR
    A[Admin Login] --> B{User approved?}
    B -->|Tidak| C[Access Denied]

    B -->|Ya| D{Super Admin?}
    D -->|Ya| E[Full Access]
    D -->|Tidak| F[Regular Admin Access]

    E --> E1[User Management]
    E --> E2[System Settings]
    E --> E3[All Reports]
    E --> E4[Database Backup]

    F --> F1[Book Management]
    F --> F2[Loan Management]
    F --> F3[Basic Reports]
    F --> F4[Own Profile]

    style E fill:#e8f5e8
    style F fill:#fff3e0
    style C fill:#ffebee
```

---

## 📧 **Email Notification Flow**

### **Email System Architecture**

```mermaid
sequenceDiagram
    participant U as User
    participant C as Controller
    participant M as Mail Service
    participant Q as Queue
    participant S as SMTP
    participant G as Gmail

    U->>C: Trigger Email Event
    C->>M: Create Mailable Instance
    M->>Q: Queue Email Job
    Q->>Q: Process Queue
    Q->>S: Send Email
    S->>G: SMTP Connection
    G->>U: Email Delivered

    Note over M: BookLoanNotification
    Note over Q: Redis Queue
    Note over S: Gmail SMTP TLS
```

### **Email Template Selection**

```mermaid
flowchart TD
    A[Email Event Triggered] --> B{Tipe Event}

    B -->|Registration| C[2FA Code Template]
    B -->|Book Loan| D[Loan Notification Template]
    B -->|Book Return| E[Return Confirmation Template]
    B -->|Overdue| F[Overdue Reminder Template]
    B -->|Admin Approval| G[Approval Notification Template]

    C --> H[Load template data]
    D --> H
    E --> H
    F --> H
    G --> H

    H --> I[Compile Blade template]
    I --> J[Add CSS styling]
    J --> K[Queue for sending]
    K --> L[Send via SMTP]

    style L fill:#e8f5e8
```

---

## 📊 **Dashboard Analytics Flow**

### **Real-time Dashboard Data Flow**

```mermaid
flowchart TD
    A[Dashboard Request] --> B{User Role}

    B -->|Student| C[Student Dashboard]
    B -->|Admin| D[Admin Dashboard]

    C --> C1[Query user's active loans]
    C --> C2[Query user's loan history]
    C --> C3[Query available books]
    C --> C4[Calculate statistics]

    D --> D1[Query system statistics]
    D --> D2[Query monthly trends]
    D --> D3[Query popular books]
    D --> D4[Query recent activities]
    D --> D5[Query overdue reports]

    C1 --> E[Compile student data]
    C2 --> E
    C3 --> E
    C4 --> E

    D1 --> F[Compile admin data]
    D2 --> F
    D3 --> F
    D4 --> F
    D5 --> F

    E --> G[Render student dashboard]
    F --> H[Render admin dashboard]

    H --> I[Initialize Chart.js]
    I --> J[Load interactive charts]

    style G fill:#e8f5e8
    style J fill:#e8f5e8
```

### **Chart Data Generation**

```mermaid
flowchart LR
    A[Chart Request] --> B{Chart Type}

    B -->|Monthly Loans| C[Group loans by month]
    B -->|Popular Books| D[Count loans per book]
    B -->|User Activity| E[Count loans per user]
    B -->|Overdue Trend| F[Track overdue patterns]

    C --> G[Last 12 months data]
    D --> H[Top 10 most borrowed]
    E --> I[Most active students]
    F --> J[Overdue statistics]

    G --> K[Format for Chart.js]
    H --> K
    I --> K
    J --> K

    K --> L[Return JSON data]
    L --> M[Render charts]

    style M fill:#e8f5e8
```

---

## ⚠️ **Error Handling Flow**

### **Global Exception Handling**

```mermaid
flowchart TD
    A[Application Exception] --> B{Exception Type}

    B -->|ValidationException| C[422 Validation Error]
    B -->|AuthenticationException| D[401 Unauthorized]
    B -->|AuthorizationException| E[403 Forbidden]
    B -->|ModelNotFoundException| F[404 Not Found]
    B -->|QueryException| G[Database Error]
    B -->|MailException| H[Email Error]
    B -->|Other| I[500 Server Error]

    C --> J[Return validation errors]
    D --> K[Redirect to login]
    E --> L[Show 403 page]
    F --> M[Show 404 page]
    G --> N[Log error & show generic message]
    H --> O[Log email error & show notification]
    I --> P[Log error & show 500 page]

    N --> Q[Send admin notification]
    O --> Q
    P --> Q

    style Q fill:#ffebee
    style J fill:#fff3e0
```

### **User-Friendly Error Messages**

```mermaid
flowchart TD
    A[System Error] --> B{User Type}

    B -->|Student| C[Simple Error Message]
    B -->|Admin| D[Detailed Error Message]

    C --> E["Terjadi kesalahan. Silakan coba lagi."]
    D --> F["Error: Database connection failed"]

    E --> G[Show sweet alert]
    F --> H[Show error panel with details]

    G --> I[Log error for admin]
    H --> I

    I --> J[Email error report to developers]

    style E fill:#fff3e0
    style F fill:#ffebee
    style J fill:#e8f5e8
```

---

## 🛡️ **Security Flow**

### **Request Security Pipeline**

```mermaid
flowchart TD
    A[Incoming Request] --> B[CSRF Token Check]
    B --> C{CSRF Valid?}
    C -->|No| D[403 CSRF Error]

    C -->|Yes| E[Authentication Check]
    E --> F{User Authenticated?}
    F -->|No| G[Redirect to Login]

    F -->|Yes| H[Authorization Check]
    H --> I{User Authorized?}
    I -->|No| J[403 Forbidden]

    I -->|Yes| K[Input Validation]
    K --> L{Input Valid?}
    L -->|No| M[422 Validation Error]

    L -->|Yes| N[Rate Limiting Check]
    N --> O{Within Rate Limit?}
    O -->|No| P[429 Too Many Requests]

    O -->|Yes| Q[Process Request]
    Q --> R[Success Response]

    style Q fill:#e8f5e8
    style D fill:#ffebee
    style J fill:#ffebee
    style P fill:#ffebee
```

### **Data Sanitization Flow**

```mermaid
flowchart LR
    A[User Input] --> B[HTML Escape]
    B --> C[SQL Injection Prevention]
    C --> D[XSS Prevention]
    D --> E[Input Length Validation]
    E --> F[Type Validation]
    F --> G[Business Rule Validation]
    G --> H[Sanitized Data]

    H --> I[Database Storage]

    style H fill:#e8f5e8
    style I fill:#e8f5e8
```

---

## 🗄️ **Database Transaction Flow**

### **Book Borrowing Transaction**

```mermaid
sequenceDiagram
    participant C as Controller
    participant DB as Database
    participant B as Books Table
    participant L as Loans Table
    participant M as Mail Service

    C->>DB: BEGIN TRANSACTION
    C->>B: SELECT available_quantity FOR UPDATE
    B->>C: Return current stock

    alt Stock Available
        C->>L: INSERT loan record
        C->>B: UPDATE available_quantity - 1
        C->>DB: COMMIT
        C->>M: Queue email notification
    else Stock Not Available
        C->>DB: ROLLBACK
        C->>C: Return error response
    end
```

### **Database Integrity Checks**

```mermaid
flowchart TD
    A[Database Operation] --> B[Foreign Key Check]
    B --> C{FK Constraint Valid?}
    C -->|No| D[Constraint Violation Error]

    C -->|Yes| E[Business Rule Check]
    E --> F{Business Rules Valid?}
    F -->|No| G[Business Logic Error]

    F -->|Yes| H[Data Type Check]
    H --> I{Data Types Valid?}
    I -->|No| J[Data Type Error]

    I -->|Yes| K[Trigger Execution]
    K --> L[After Insert/Update Triggers]
    L --> M[Operation Complete]

    style M fill:#e8f5e8
    style D fill:#ffebee
    style G fill:#ffebee
    style J fill:#ffebee
```

---

## 📱 **Responsive Design Flow**

### **Device Detection & Layout**

```mermaid
flowchart TD
    A[Page Load] --> B[Detect Device Type]
    B --> C{Device Type}

    C -->|Mobile| D[Mobile Layout]
    C -->|Tablet| E[Tablet Layout]
    C -->|Desktop| F[Desktop Layout]

    D --> G[Collapse Sidebar]
    D --> H[Stack Cards Vertically]
    D --> I[Touch-Friendly Buttons]

    E --> J[Sidebar Toggle]
    E --> K[2-Column Layout]
    E --> L[Medium Button Size]

    F --> M[Full Sidebar]
    F --> N[3-Column Layout]
    F --> O[Hover Effects]

    G --> P[Apply Styles]
    H --> P
    I --> P
    J --> P
    K --> P
    L --> P
    M --> P
    N --> P
    O --> P

    style P fill:#e8f5e8
```

---

## 🔄 **System Maintenance Flow**

### **Automated Maintenance Tasks**

```mermaid
flowchart TD
    A[Daily Cron Job] --> B[Cleanup Expired 2FA Tokens]
    B --> C[Archive Old Sessions]
    C --> D[Generate Daily Reports]
    D --> E[Check Overdue Books]
    E --> F[Send Reminder Emails]
    F --> G[Update Statistics Cache]
    G --> H[Database Optimization]
    H --> I[Backup Critical Data]
    I --> J[Send Health Report to Admin]

    style J fill:#e8f5e8
```

### **Manual Maintenance Tasks**

```mermaid
flowchart LR
    A[Admin Panel] --> B{Maintenance Task}

    B -->|User Management| C[Approve/Reject Users]
    B -->|Data Cleanup| D[Archive Old Data]
    B -->|Reports| E[Generate Custom Reports]
    B -->|System Health| F[Check System Status]

    C --> G[Update User Status]
    D --> H[Move to Archive Tables]
    E --> I[Export to Excel/PDF]
    F --> J[Display Health Metrics]

    style G fill:#e8f5e8
    style H fill:#fff3e0
    style I fill:#e8f5e8
    style J fill:#e8f5e8
```

---

## 🎯 **Performance Optimization Flow**

### **Query Optimization Strategy**

```mermaid
flowchart TD
    A[Database Query] --> B[Check Query Cache]
    B --> C{Cache Hit?}
    C -->|Yes| D[Return Cached Result]

    C -->|No| E[Execute Query]
    E --> F[Eager Load Relationships]
    F --> G[Apply Indexes]
    G --> H[Optimize WHERE Clauses]
    H --> I[Limit Result Set]
    I --> J[Cache Result]
    J --> K[Return Data]

    style D fill:#e8f5e8
    style K fill:#e8f5e8
```

---

**🎉 Flowchart System Lengkap!**

> Dokumentasi ini mencakup semua alur sistem dalam bentuk flowchart yang mudah dipahami dan diimplementasikan. Setiap diagram menggambarkan proses bisnis dan teknis yang detail dari Sistem Perpustakaan Digital SMK.

---

**📞 Quick Reference**
- **Main Documentation**: [DOCUMENTATION.md](DOCUMENTATION.md)
- **Programming Schema**: [PROGRAMMING_SCHEMA.md](PROGRAMMING_SCHEMA.md)
- **Database Schema**: [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)
- **User Manual**: [USER_MANUAL.md](USER_MANUAL.md)

**🔧 Development Team**: Full Stack Laravel Developer
**📅 Last Updated**: September 2025
**🏷️ Version**: 1.0.0

---