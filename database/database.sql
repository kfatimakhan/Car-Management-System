### users
- id (BIGINT)
- name (STRING)
- email (STRING)
- password (STRING)
- role (STRING) [admin/customer]
- phone (STRING, nullable)
- address (TEXT, nullable)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### cars
- id (BIGINT)
- name (STRING)
- brand (STRING)
- model (STRING)
- year (INTEGER)
- car_type (STRING)
- daily_rent_price (DECIMAL)
- availability (BOOLEAN)
- image (STRING, nullable)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### rentals
- id (BIGINT)
- user_id (BIGINT, foreign key)
- car_id (BIGINT, foreign key)
- start_date (DATE)
- end_date (DATE)
- total_cost (DECIMAL)
- status (ENUM) [ongoing, completed, canceled]
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
