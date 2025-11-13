# Car Rental Management API

REST API built with Symfony to manage a car rental system. It allows customers to view available cars and create reservations, while managers can manage the entire catalog and reservations.

## Technologies Used

- **Symfony 6.4** - PHP Framework
- **API Platform 3.2** - REST API Framework
- **PostgreSQL 16** - Database
- **JWT Authentication** - Secure authentication
- **Docker & Docker Compose** - Containerization
- **PHPUnit** - Unit testing

## Installation

### Prerequisites
- Docker and Docker Compose installed on your machine

### Quick Start

1. Clone the project
2. Start the application with Docker:

```bash
docker compose up -d
```

The API will be accessible at `http://localhost:8000`

The database is automatically created with test data (cars and users).

## API Documentation (Swagger)

Interactive API documentation is available via Swagger UI:

**URL:** `http://localhost:8000/api/docs`

The Swagger interface allows you to:
- Browse all available endpoints
- View request/response schemas
- Test endpoints directly from your browser
- Authenticate with JWT tokens (click "Authorize" button and enter: `Bearer {your_token}`)

## User Accounts

The application contains pre-created test users:

### Manager (Administrator)
- **Email**: admin@carrental.com
- **Password**: admin123
- **Name**: Hassan Bennani
- **Role**: ROLE_MANAGER
- **Permissions**: Full access (CRUD on cars, view and modify all reservations)

### Customer 1
- **Email**: karim.alaoui@gmail.com
- **Password**: karim123
- **Name**: Karim Alaoui
- **Role**: ROLE_CUSTOMER
- **Permissions**: View cars, manage own reservations only

### Customer 2
- **Email**: fatima.idrissi@gmail.com
- **Password**: fatima123
- **Name**: Fatima Idrissi
- **Role**: ROLE_CUSTOMER
- **Permissions**: View cars, manage own reservations only

## JWT Authentication

All endpoints require JWT authentication. Here's how to get a token:

### Login


**Request:**
```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@carrental.com",
  "password": "admin123"
}
```

**Response:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

### Using the Token

For all subsequent requests, add the token in the header:
```
Authorization: Bearer {your_token}
```

## API Endpoints

### Endpoints Accessible to All (Manager and Customer)

#### 1. List All Cars

Display all available cars in the catalog.

**Accessible by:** Manager and Customer  
**Request:**
```bash
GET /api/cars
Authorization: Bearer {token}
```

**Response:**
```json
{
  "@context": "/api/contexts/Car",
  "@id": "/api/cars",
  "@type": "hydra:Collection",
  "hydra:totalItems": 10,
  "hydra:member": [
    {
      "@id": "/api/cars/1",
      "@type": "Car",
      "id": 1,
      "model": "Logan 2023",
      "brand": "Dacia",
      "inventory": 8,
      "dailyFee": "250.00",
      "seats": 5,
      "transmission": "MANUAL",
      "fuelType": "GASOLINE"
    }
  ]
}
```

#### 2. Get Car Details

Display detailed information for a specific car.

**Accessible by:** Manager and Customer  
**Request:**
```bash
GET /api/cars/1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "@context": "/api/contexts/Car",
  "@id": "/api/cars/1",
  "@type": "Car",
  "id": 1,
  "model": "Logan 2023",
  "brand": "Dacia",
  "inventory": 8,
  "dailyFee": "250.00",
  "seats": 5,
  "transmission": "MANUAL",
  "fuelType": "GASOLINE"
}
```

#### 3. Create a Reservation

Allows a customer to book a car for a given period.

**Accessible by:** Manager and Customer  
**Request:**
```bash
POST /api/reservations
Authorization: Bearer {token}
Content-Type: application/ld+json

{
  "startDate": "2025-12-01T10:00:00.000Z",
  "endDate": "2025-12-05T10:00:00.000Z",
  "carId": 1,
  "customerPhone": "0612345678",
  "driverLicenseNumber": "MA-ABC-123456"
}
```

**Response:**
```json
{
  "@context": "/api/contexts/Reservation",
  "@id": "/api/reservations/1",
  "@type": "Reservation",
  "id": 1,
  "startDate": "2025-12-01T10:00:00+00:00",
  "endDate": "2025-12-05T10:00:00+00:00",
  "car": {
    "id": 1,
    "brand": "Dacia",
    "model": "Logan 2023",
    "dailyFee": "250.00"
  },
  "customerName": "Karim Alaoui",
  "customerPhone": "0612345678",
  "customerEmail": "karim.alaoui@gmail.com",
  "driverLicenseNumber": "MA-ABC-123456",
  "totalDays": 4,
  "totalPrice": "1000.00",
  "status": "ACTIVE"
}
```

**Note:** The system automatically checks:
- Car availability for the requested period
- End date is after start date
- Dates are not in the past
- The authenticated user's information (name, email) is automatically filled from their profile

---

### Endpoints for Manager Only

#### 4. Add a Car

Add a new car to the catalog.

**Accessible by:** Manager only  
**Request:**
```bash
POST /api/cars
Authorization: Bearer {token}
Content-Type: application/ld+json

{
  "model": "Megane",
  "brand": "Renault",
  "inventory": 3,
  "dailyFee": "350.00",
  "seats": 5,
  "transmission": "AUTOMATIC",
  "fuelType": "HYBRID"
}
```

**Available fuel types:** `GASOLINE`, `DIESEL`, `ELECTRIC`, `HYBRID`  
**Available transmission types:** `MANUAL`, `AUTOMATIC`

**Response:**
```json
{
  "@context": "/api/contexts/Car",
  "@id": "/api/cars/11",
  "@type": "Car",
  "id": 11,
  "model": "Megane",
  "brand": "Renault",
  "inventory": 3,
  "dailyFee": "350.00",
  "seats": 5,
  "transmission": "AUTOMATIC",
  "fuelType": "HYBRID"
}
```

#### 5. Get User Reservations

Display all reservations for a specific user.

**Accessible by:** Manager only  
**Request:**
```bash
GET /api/users/2/reservations
Authorization: Bearer {token}
```

**Response:**
```json
{
  "@context": "/api/contexts/Reservation",
  "@id": "/api/users/2/reservations",
  "@type": "hydra:Collection",
  "hydra:totalItems": 2,
  "hydra:member": [
    {
      "@id": "/api/reservations/1",
      "@type": "Reservation",
      "id": 1,
      "startDate": "2024-12-01T00:00:00+00:00",
      "endDate": "2024-12-05T00:00:00+00:00",
      "car": {
        "id": 1,
        "brand": "Dacia",
        "model": "Logan 2023",
        "dailyFee": "250.00"
      },
      "customerName": "Karim Alaoui",
      "customerPhone": "0612345678",
      "totalDays": 4,
      "totalPrice": "1000.00",
      "status": "ACTIVE"
    }
  ]
}
```

**Note:** Only managers can view other users' reservations.

#### 6. Update a Reservation

Modify the status or return date of an existing reservation.

**Accessible by:** Manager only  
**Request:**
```bash
PUT /api/reservations/1
Authorization: Bearer {token}
Content-Type: application/ld+json

{
  "actualReturnDate": "2025-12-07T14:30:00.000Z",
  "status": "RETURNED"
}
```

**Available status values:** `ACTIVE`, `RETURNED`

**Response:**
```json
{
  "@context": "/api/contexts/Reservation",
  "@id": "/api/reservations/1",
  "@type": "Reservation",
  "id": 1,
  "startDate": "2025-12-01T10:00:00+00:00",
  "endDate": "2025-12-05T10:00:00+00:00",
  "actualReturnDate": "2025-12-07T14:30:00+00:00",
  "car": {
    "id": 1,
    "brand": "Dacia",
    "model": "Logan 2023",
    "dailyFee": "250.00"
  },
  "customerName": "Karim Alaoui",
  "customerPhone": "0612345678",
  "totalDays": 4,
  "totalPrice": "1000.00",
  "status": "RETURNED"
}
```

**Note:** 
- Only managers can update reservations
- You can update the status (ACTIVE/RETURNED) and actualReturnDate
- Customer information cannot be modified through this endpoint

#### 7. Delete a Reservation

Cancel a reservation.

**Accessible by:** Manager only  
**Request:**
```bash
DELETE /api/reservations/1
Authorization: Bearer {token}
```

**Response:**
```
204 No Content
```

## Business Rules

### Privacy
- Customers can only view their own reservations
- Managers have access to all reservations

### Car Availability
- The system automatically checks if a car is available before creating a reservation
- A car is considered available if the number of reservations for the requested period is less than the available quantity

### Date Validation
- Start date must be before end date
- Dates cannot be in the past
- Overlapping reservation periods are taken into account in availability calculation

## Tests

To run unit tests:

```bash
php bin/phpunit tests/Service/
```

Tests cover:
- User creation (RegistrationService)
- Car availability checking (ReservationService)

## Project Structure

```
src/
├── Entity/          # Doctrine entities (User, Car, Reservation)
├── Enum/            # Enumerations (UserRole, ReservationStatus, FuelType, TransmissionType)
├── Repository/      # Doctrine repositories
├── Service/         # Business services (RegistrationService, ReservationService)
├── State/           # API Platform processors and providers
├── Dto/             # Data Transfer Objects
└── DataFixtures/    # Test data
```

## Usage Examples with PowerShell

### Login as manager
```powershell
$response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method POST -Body (@{email="admin@carrental.com"; password="admin123"} | ConvertTo-Json) -ContentType "application/json"
$token = $response.token
```

### Create a reservation
```powershell
$headers = @{Authorization = "Bearer $token"}
$body = @{
  startDate = "2025-12-01T10:00:00.000Z"
  endDate = "2025-12-05T10:00:00.000Z"
  carId = 1
  customerPhone = "0612345678"
  driverLicenseNumber = "MA-ABC-123456"
} | ConvertTo-Json
Invoke-RestMethod -Uri "http://localhost:8000/api/reservations" -Method POST -Headers $headers -Body $body -ContentType "application/ld+json"
```

### List available cars
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/cars" -Method GET -Headers @{Authorization = "Bearer $token"}
```
