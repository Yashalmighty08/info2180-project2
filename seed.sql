-- OPTIONAL sample data so every member can test their own pages alone.
-- Run schema.sql first, then this file. Do NOT rely on these rows in your code.
-- Extra login for testing: member@project2.com / Member123 (role Member)
USE dolphin_crm;

INSERT IGNORE INTO users (id, firstname, lastname, password, email, role, created_at) VALUES
(2, 'Jan',   'Levinson', '$2y$10$XPbpJJ5G9nJ9p3XexcC6yOY13uxNTfNz9FZ9SvYRvIaIbLwSp6PZq', 'member@project2.com',      'Member', '2026-11-01 09:00:00'),
(3, 'Andy',  'Bernard',  '$2y$10$XPbpJJ5G9nJ9p3XexcC6yOY13uxNTfNz9FZ9SvYRvIaIbLwSp6PZq', 'andy.bernard@project2.com', 'Member', '2026-11-02 10:30:00'),
(4, 'David', 'Wallace',  '$2y$10$XPbpJJ5G9nJ9p3XexcC6yOY13uxNTfNz9FZ9SvYRvIaIbLwSp6PZq', 'david.wallace@project2.com', 'Admin',  '2026-11-03 14:15:00');

INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at) VALUES
('Mr',  'Michael', 'Scott',   'michael.scott@paper.co',   '876-999-9999', 'The Paper Company',   'Sales Lead', 2,    1, '2026-11-04 09:00:00', '2026-11-05 09:00:00'),
('Mr',  'Dwight',  'Schrute', 'dwight.schrute@paper.co',  '876-555-0101', 'The Paper Company',   'Support',    1,    1, '2026-11-04 09:10:00', '2026-11-04 09:10:00'),
('Ms',  'Pam',     'Beesley', 'pam.beesley@dunder.co',    '876-555-0102', 'Dunder Mifflin',      'Support',    NULL, 2, '2026-11-04 10:00:00', '2026-11-04 10:00:00'),
('Ms',  'Angela',  'Martin',  'angela.martin@vance.co',   '876-555-0103', 'Vance Refrigeration', 'Sales Lead', 3,    2, '2026-11-04 11:00:00', '2026-11-04 11:00:00'),
('Dr',  'Kelly',   'Kapoor',  'kelly.kapoor@vance.co',    '876-555-0104', 'Vance Refrigeration', 'Support',    2,    3, '2026-11-04 12:00:00', '2026-11-04 12:00:00'),
('Prof','Jim',     'Halpert', 'jim.halpert@dunder.co',    '876-555-0105', 'Dunder Mifflin',      'Sales Lead', 1,    3, '2026-11-04 13:00:00', '2026-11-04 13:00:00');

INSERT INTO notes (contact_id, comment, created_by, created_at) VALUES
(1, 'Called to discuss the renewal. Wants a quote by Friday.', 2, '2026-11-04 16:00:00'),
(1, 'Sent the quote. Waiting for a reply.',                   1, '2026-11-05 10:00:00'),
(2, 'Reported a login problem. Reset his password.',          1, '2026-11-04 15:30:00');
