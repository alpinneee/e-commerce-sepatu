# 🔄 ALUR WEB APLIKASI E-COMMERCE TOKO SEPATU

## 📋 OVERVIEW SISTEM
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     GUEST       │    │    CUSTOMER     │    │     ADMIN       │
│   (Pengunjung)  │    │   (Pembeli)     │    │   (Pengelola)   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🌐 ALUR UTAMA APLIKASI

### 1️⃣ **GUEST FLOW (Pengunjung)**
```
START → Homepage (/) 
  ↓
Browse Products (/products)
  ↓
View Product Detail (/products/{slug})
  ↓
┌─────────────────────────┐
│ Pilihan:                │
│ • Register/Login        │
│ • Add to Cart (Guest)   │
│ • Continue Browsing     │
└─────────────────────────┘
```

### 2️⃣ **CUSTOMER FLOW (Setelah Login)**
```
Login (/login) → Dashboard Redirect (/)
  ↓
┌─────────────────────────────────────────┐
│              MAIN MENU                  │
│ • Browse Products                       │
│ • Search & Filter                       │
│ • View Categories                       │
│ • Manage Cart                           │
│ • Wishlist                              │
│ • Profile                               │
└─────────────────────────────────────────┘
  ↓
SHOPPING PROCESS:
Browse → Add to Cart → Checkout → Payment → Order Complete
```

### 3️⃣ **ADMIN FLOW**
```
Admin Login → Admin Dashboard (/admin)
  ↓
┌─────────────────────────────────────────┐
│            ADMIN PANEL                  │
│ • Product Management                    │
│ • Category Management                   │
│ • Order Management                      │
│ • User Management                       │
│ • Reports & Analytics                   │
└─────────────────────────────────────────┘
```

## 🛒 DETAILED SHOPPING FLOW

### **A. Product Discovery**
```
Homepage (/)
  ↓
Products Page (/products)
  ├── Search (/products/search?q=...)
  ├── Category Filter (/products/category/{slug})
  └── Product Detail (/products/{slug})
      ├── View Images
      ├── Read Reviews
      ├── Add to Wishlist (/wishlist/add)
      └── Add to Cart (/cart/add)
```

### **B. Cart Management**
```
Cart Page (/cart)
  ├── Update Quantity (/cart/update)
  ├── Remove Item (/cart/remove)
  ├── Apply Coupon (/cart/coupon)
  ├── Clear Cart (/cart/clear)
  └── Proceed to Checkout (/checkout)
```

### **C. Checkout Process**
```
Checkout (/checkout) [AUTH REQUIRED]
  ↓
Fill Shipping Address
  ↓
Select Payment Method
  ├── Midtrans Gateway
  │   ├── Credit Card
  │   ├── Bank Transfer
  │   ├── E-Wallet
  │   └── Convenience Store
  └── Manual Transfer
      └── Upload Payment Proof
  ↓
Order Created → Payment Processing
  ↓
┌─────────────────────────────────┐
│         PAYMENT RESULT          │
│ SUCCESS → Order Confirmed       │
│ PENDING → Awaiting Payment      │
│ FAILED  → Back to Checkout      │
└─────────────────────────────────┘
```

### **D. Post-Purchase**
```
Order Confirmed
  ↓
Order Tracking (/profile/orders)
  ├── View Order Details
  ├── Track Status
  └── Download Invoice
  ↓
Order Delivered
  ↓
Leave Review (/products/{slug}/review)
```

## 👤 USER MANAGEMENT FLOW

### **Authentication**
```
┌─────────────────┐
│   GUEST USER    │
└─────────────────┘
  ↓
┌─────────────────────────────┐
│        AUTH OPTIONS         │
│ • Register (/register)      │
│ • Login (/login)           │
│ • Forgot Password          │
└─────────────────────────────┘
  ↓
┌─────────────────────────────┐
│      ROLE DETECTION         │
│ Customer → Customer Panel   │
│ Admin    → Admin Panel      │
└─────────────────────────────┘
```

### **Profile Management**
```
Profile (/profile)
  ├── Edit Profile (/profile/edit)
  ├── Change Password (/profile/change-password)
  ├── Order History (/profile/orders)
  ├── Address Management (/profile/addresses)
  └── Logout
```

## 🔧 ADMIN MANAGEMENT FLOW

### **Product Management**
```
Admin Products (/admin/products)
  ├── Create Product (/admin/products/create)
  ├── Edit Product (/admin/products/{id}/edit)
  ├── Delete Product (/admin/products/{id})
  └── Manage Images
```

### **Order Management**
```
Admin Orders (/admin/orders)
  ├── View Order Details (/admin/orders/{id})
  ├── Update Status (/admin/orders/{id}/update-status)
  ├── Confirm Payment (/admin/orders/{id}/confirm-payment)
  ├── Generate Invoice (/admin/orders/{id}/invoice)
  └── Export Orders (/admin/orders/export)
```

### **Category Management**
```
Admin Categories (/admin/categories)
  ├── Create Category (/admin/categories/create)
  ├── Edit Category (/admin/categories/{id}/edit)
  └── Delete Category (/admin/categories/{id})
```

## 💳 PAYMENT FLOW

### **Midtrans Integration**
```
Checkout → Midtrans Gateway
  ↓
Payment Methods:
  ├── Credit/Debit Card
  ├── Bank Transfer (BCA, BNI, BRI, Mandiri)
  ├── E-Wallet (GoPay, OVO, DANA)
  └── Convenience Store (Alfamart, Indomaret)
  ↓
Payment Processing
  ↓
Webhook Notification (/midtrans/notification)
  ↓
Update Order Status
  ├── SUCCESS → Order Confirmed
  ├── PENDING → Awaiting Payment
  └── FAILED  → Payment Failed
```

### **Manual Transfer**
```
Select Manual Transfer
  ↓
Show Bank Account Details
  ↓
Customer Transfer Money
  ↓
Upload Payment Proof (/orders/{id}/upload-payment-proof)
  ↓
Admin Review (/admin/orders/{id}/confirm-payment)
  ↓
Payment Confirmed → Order Processing
```

## 📊 DATA FLOW

### **Database Entities**
```
Users ←→ Orders ←→ OrderItems ←→ Products
  ↓        ↓         ↓           ↓
Roles   Payments  Quantities  Categories
  ↓        ↓         ↓           ↓
Permissions Addresses Reviews  ProductImages
```

### **Session Management**
```
Cart (Session/Database)
Wishlist (Database - Auth Required)
User Preferences (Session)
Admin Filters (Session)
```

## 🔄 SYSTEM INTEGRATIONS

### **External Services**
```
┌─────────────────┐    ┌─────────────────┐
│    MIDTRANS     │    │   FILE STORAGE  │
│  Payment Gateway│    │  Laravel Storage│
└─────────────────┘    └─────────────────┘
         ↓                       ↓
┌─────────────────┐    ┌─────────────────┐
│   EMAIL SERVICE │    │   IMAGE UPLOAD  │
│  Laravel Mail   │    │   Intervention  │
└─────────────────┘    └─────────────────┘
```

### **Security Flow**
```
Request → Middleware Check
  ├── Guest Middleware (auth routes)
  ├── Auth Middleware (protected routes)
  ├── Admin Middleware (admin routes)
  └── CSRF Protection (forms)
```

## 🎯 KEY FEATURES FLOW

### **Search & Filter**
```
Search Query → Database Query
  ├── Product Name
  ├── Category Filter
  ├── Price Range
  ├── Brand Filter
  └── Sort Options
```

### **Wishlist System**
```
Add to Wishlist → Database Store
View Wishlist → Display Items
Move to Cart → Transfer Items
Remove from Wishlist → Delete Items
```

### **Review System**
```
Purchase Complete → Enable Review
Write Review → Store Rating & Comment
Display Reviews → Show on Product Page
Admin Moderate → Approve/Reject Reviews
```

## 📱 RESPONSIVE DESIGN FLOW

### **Device Detection**
```
User Access → Device Detection
  ├── Desktop → Full Layout
  ├── Tablet  → Adapted Layout
  └── Mobile  → Mobile Layout
      ├── Touch Navigation
      ├── Swipe Gestures
      └── Mobile Checkout
```

---

## 🎯 **SUMMARY ALUR UTAMA**

1. **Guest** → Browse → Register/Login
2. **Customer** → Shop → Cart → Checkout → Payment → Order
3. **Admin** → Manage Products → Process Orders → Generate Reports
4. **System** → Handle Payments → Send Notifications → Update Status

**Tech Stack Flow:**
Laravel Backend → Blade Templates → Tailwind CSS → JavaScript → Database → External APIs