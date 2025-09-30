# Ultimate Project Manager API Documentation

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

---

## Authentication & Onboarding

### Register User
```http
POST /auth/register-user
```
Body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login User
```http
POST /auth/login-user
```
**Body:**
```json
{
  "email": "john@example.com",
  "password": "password123",
  "device_name": "web"
}
```

Profile completion accepts additional fields:
```http
POST /auth/complete-profile
```
Body:
```json
{
  "name": "John Doe",
  "position": "Project Manager",
  "account_type": "company", // or "individual"
  "phone": "+263 77 123 4567",
  "avatar_url": "https://..."
}
```
Behavior:
- If `account_type` is `company`, proceed to company creation step.
- If `account_type` is `individual`, navigate user to dashboard directly.

### Create Company
```http
POST /companies
```
**Body:**
```json
{
  "name": "Acme Corp",
  "slug": "acme-corp",
  "phone": "+1234567890",
  "country": "US",
  "timezone": "America/New_York",
  "currency": "USD"
}
```

### Select Plan
```http
POST /companies/{companyId}/select-plan
```
**Body:**
```json
{
  "plan_code": "pro"
}
```

---

## User Profile Management

### Get Profile
```http
GET /profile
```

Updated profile response (companies removed):
```http
GET /profile
```
Response:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com",
      "position": "Project Manager",
      "account_type": "company",
      "phone": "+263..."
    },
    "plan": {
      "id": 2,
      "status": "active",
      "plan": { "code": "pro", "name": "Pro" }
    }
  }
}
```

### Update Profile
```http
PUT /profile
```
**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com"
}
```

### Change Password
```http
POST /profile/change-password
```
**Body:**
```json
{
  "current_password": "oldpassword",
  "new_password": "newpassword",
  "new_password_confirmation": "newpassword"
}
```

---

## Company Management

### Get Company Profile
```http
GET /companies/{companyId}/profile
```

### Update Company Profile
```http
PUT /companies/{companyId}/profile
```
**Body:**
```json
{
  "name": "Acme Corp",
  "phone": "+1234567890",
  "country": "US",
  "timezone": "America/New_York",
  "currency": "USD"
}
```

---

## Company User Management

### List Company Users
```http
GET /companies/{companyId}/users
```

### Invite User to Company
```http
POST /companies/{companyId}/users
```
**Body:**
```json
{
  "email": "jane@example.com",
  "name": "Jane Smith",
  "role": "project_manager"
}
```

### Get Company User
```http
GET /companies/{companyId}/users/{userId}
```

### Update Company User Role
```http
PUT /companies/{companyId}/users/{userId}
```
**Body:**
```json
{
  "role": "admin"
}
```

### Remove User from Company
```http
DELETE /companies/{companyId}/users/{userId}
```

---

## Dashboard Statistics

### Company Statistics
```http
GET /companies/{companyId}/stats
```

### Project Statistics
```http
GET /companies/{companyId}/projects/{projectId}/stats
```

---

## Project Management

### List Projects
```http
GET /companies/{companyId}/projects
```

### Create Project
```http
POST /companies/{companyId}/projects
```
**Body:**
```json
{
  "name": "Website Redesign",
  "description": "Redesign company website",
  "start_date": "2024-01-01",
  "end_date": "2024-06-30",
  "status": "active"
}
```

### Get Project
```http
GET /companies/{companyId}/projects/{id}
```

### Update Project
```http
PUT /companies/{companyId}/projects/{id}
```
**Body:**
```json
{
  "name": "Website Redesign v2",
  "description": "Updated project description",
  "status": "completed"
}
```

### Delete Project
```http
DELETE /companies/{companyId}/projects/{id}
```

---

## Task List Management

### List Task Lists
```http
GET /companies/{companyId}/projects/{projectId}/task-lists
```

### Create Task List
```http
POST /companies/{companyId}/projects/{projectId}/task-lists
```
**Body:**
```json
{
  "name": "Design Phase",
  "description": "Tasks for design phase"
}
```

### Get Task List
```http
GET /companies/{companyId}/projects/{projectId}/task-lists/{id}
```

### Update Task List
```http
PUT /companies/{companyId}/projects/{projectId}/task-lists/{id}
```
**Body:**
```json
{
  "name": "Design Phase Updated",
  "description": "Updated description"
}
```

### Delete Task List
```http
DELETE /companies/{companyId}/projects/{projectId}/task-lists/{id}
```

---

## Task Management

### List Tasks
```http
GET /companies/{companyId}/projects/{projectId}/tasks
```

### Create Task
```http
POST /companies/{companyId}/projects/{projectId}/tasks
```
**Body:**
```json
{
  "title": "Create wireframes",
  "description": "Design wireframes for homepage",
  "task_list_id": 1,
  "assignee_id": 2,
  "priority": "high",
  "due_date": "2024-02-15"
}
```

### Get Task
```http
GET /companies/{companyId}/projects/{projectId}/tasks/{id}
```

### Update Task
```http
PUT /companies/{companyId}/projects/{projectId}/tasks/{id}
```
**Body:**
```json
{
  "title": "Create wireframes updated",
  "status": "in_progress",
  "priority": "medium"
}
```

### Delete Task
```http
DELETE /companies/{companyId}/projects/{projectId}/tasks/{id}
```

### Move Task
```http
POST /companies/{companyId}/projects/{projectId}/tasks/{id}/move
```
**Body:**
```json
{
  "task_list_id": 2,
  "order_index": 3
}
```

---

## Budget Management

### List Budget Categories
```http
GET /companies/{companyId}/projects/{projectId}/budget/categories
```

### Create Budget Category
```http
POST /companies/{companyId}/projects/{projectId}/budget/categories
```
**Body:**
```json
{
  "name": "Design",
  "description": "Design related costs"
}
```

### Update Budget Category
```http
PUT /companies/{companyId}/projects/{projectId}/budget/categories/{id}
```
**Body:**
```json
{
  "name": "Design Updated",
  "description": "Updated description"
}
```

### Delete Budget Category
```http
DELETE /companies/{companyId}/projects/{projectId}/budget/categories/{id}
```

### List Budget Items
```http
GET /companies/{companyId}/projects/{projectId}/budget/items
```

### Create Budget Item
```http
POST /companies/{companyId}/projects/{projectId}/budget/items
```
**Body:**
```json
{
  "category_id": 1,
  "name": "UI Design",
  "description": "User interface design",
  "quantity": 1,
  "unit_price": 5000,
  "unit": "project"
}
```

### Update Budget Item
```http
PUT /companies/{companyId}/projects/{projectId}/budget/items/{id}
```
**Body:**
```json
{
  "name": "UI Design Updated",
  "unit_price": 6000
}
```

### Delete Budget Item
```http
DELETE /companies/{companyId}/projects/{projectId}/budget/items/{id}
```

---

## Expense Management

### List Expenses
```http
GET /companies/{companyId}/projects/{projectId}/expenses
```

### Create Expense
```http
POST /companies/{companyId}/projects/{projectId}/expenses
```
**Body:**
```json
{
  "description": "Design software license",
  "amount": 299,
  "date": "2024-01-15",
  "category": "software"
}
```

### Delete Expense
```http
DELETE /companies/{companyId}/projects/{projectId}/expenses/{id}
```

### Get Expense Receipt
```http
GET /companies/{companyId}/projects/{projectId}/expenses/{id}/receipt
```

---

## File Upload

### Upload Receipt
```http
POST /companies/{companyId}/projects/{projectId}/upload/receipt
```
**Body:** Multipart form data with file

### Get Signed Upload URL
```http
GET /companies/{companyId}/projects/{projectId}/upload/signed-url
```

---

## Admin Management

### List Plans
```http
GET /admin/plans
```

### Create Plan
```http
POST /admin/plans
```
**Body:**
```json
{
  "code": "enterprise",
  "name": "Enterprise Plan",
  "price_cents": 99900,
  "currency": "USD",
  "interval": "month",
  "max_projects": 100,
  "max_users": 50,
  "features": ["advanced_analytics", "priority_support"]
}
```

### Update Plan
```http
PUT /admin/plans/{id}
```

### Delete Plan
```http
DELETE /admin/plans/{id}
```

### List Companies (Admin)
```http
GET /admin/companies
```

### Get Company (Admin)
```http
GET /admin/companies/{id}
```

### Create Company (Admin)
```http
POST /admin/companies
```

### Update Company (Admin)
```http
PUT /admin/companies/{id}
```

### Delete Company (Admin)
```http
DELETE /admin/companies/{id}
```

---

## Invite Management

### Accept Invite (GET)
```http
GET /invites/accept?token={invite_token}
```

### Accept Invite (POST)
```http
POST /invites/accept
```
**Body:**
```json
{
  "token": "invite_token",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

---

## Response Formats

### Success Response
```json
{
  "success": true,
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error"]
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": {
    "data": [
      // Items array
    ],
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

---

## Status Codes

- `200` - Success
- `201` - Created
- `204` - No Content (for delete operations)
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## User Roles

- `admin` - Full access to company and projects
- `project_manager` - Can manage projects and tasks
- `site_supervisor` - Can view and update tasks
- `viewer` - Read-only access
- `client` - Limited access to assigned projects

---

## Task Statuses

- `pending` - Task is created but not started
- `in_progress` - Task is being worked on
- `completed` - Task is finished
- `cancelled` - Task is cancelled

---

## Project Statuses

- `active` - Project is currently running
- `completed` - Project is finished
- `on_hold` - Project is temporarily paused
- `cancelled` - Project is cancelled

---

## Inspections

### Summary
```http
GET /companies/{companyId}/projects/{projectId}/inspections/summary
```
Returns counts: `total`, `completed`, `pending`, `overdue`.

### List Inspections
```http
GET /companies/{companyId}/projects/{projectId}/inspections
```

### Create Inspection
```http
POST /companies/{companyId}/projects/{projectId}/inspections
```
Body fields: `title`, `description`, `status`, `scheduled_date`, `council_officer`, `contact_email`.

### Update Inspection
```http
PUT /companies/{companyId}/projects/{projectId}/inspections/{id}
```

### Delete Inspection
```http
DELETE /companies/{companyId}/projects/{projectId}/inspections/{id}
```

### Send Reminder
```http
POST /companies/{companyId}/projects/{projectId}/inspections/{id}/send-reminder
```

### Send Email to Council Representative
```http
POST /companies/{companyId}/projects/{projectId}/inspections/send-email
```
Body: `{ "email": "council@example.gov", "message": "..." }`

---

## Daily Logs

### List Daily Logs
```http
GET /companies/{companyId}/projects/{projectId}/daily-logs
```

### Create Daily Log
```http
POST /companies/{companyId}/projects/{projectId}/daily-logs
```
Body fields: `date`, `weather`, `summary`, `notes`, `manpower_count`, `materials_used[]`, `issues[]`, `photos[]`.

### Update Daily Log
```http
PUT /companies/{companyId}/projects/{projectId}/daily-logs/{id}
```

### Delete Daily Log
```http
DELETE /companies/{companyId}/projects/{projectId}/daily-logs/{id}
```

---

## Notifications

### List Notifications
```http
GET /notifications
```

### Mark All as Read
```http
POST /notifications/mark-all-read
```

---

## Project Chat

### List Messages
```http
GET /companies/{companyId}/projects/{projectId}/chat/messages
```

### Send Message
```http
POST /companies/{companyId}/projects/{projectId}/chat/messages
```
Body: `{ "message": "text", "attachment_url": "https://..." }`

---

## Project Media (Site Photos)

### List Photos
```http
GET /companies/{companyId}/projects/{projectId}/photos
```

### Add Photo (by URL)
```http
POST /companies/{companyId}/projects/{projectId}/photos
```
Body: `{ "url": "https://...", "caption": "...", "taken_at": "2025-01-10" }`

### Delete Photo
```http
DELETE /companies/{companyId}/projects/{projectId}/photos/{id}
```

---

## AI Insights

### Project Insights
```http
GET /companies/{companyId}/projects/{projectId}/insights
```
Returns potential savings and recommendations with confidence scores.

### Select User Plan
```http
POST /user/select-plan
```
Body:
```json
{ "plan_code": "pro" }
```
Response includes the activated plan.

Profile response includes user-level plan:
```http
GET /profile
```
Returns:
```json
{
  "success": true,
  "data": {
    "user": { "id": 1, "name": "...", "account_type": "company" },
    "plan": { "id": 2, "status": "active", "plan": { "code": "pro", "name": "Pro" } },
    "companies": [ { "id": 1, "name": "Modern Family Home", "pivot": { "role": "admin" } } ]
  }
}
```
