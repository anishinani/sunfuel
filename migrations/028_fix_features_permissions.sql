-- Migration 028: Fix features table for RBAC
-- Description: Aligns features schema with AccessController and seeds navigation permissions

USE sunfuel;

ALTER TABLE features
    ADD COLUMN IF NOT EXISTS name VARCHAR(100) NULL AFTER id,
    ADD COLUMN IF NOT EXISTS permission VARCHAR(100) NULL AFTER name,
    ADD COLUMN IF NOT EXISTS action VARCHAR(255) NULL AFTER permission,
    ADD COLUMN IF NOT EXISTS module_id INT NULL AFTER action;

UPDATE features SET name = featureName WHERE name IS NULL;

DELETE FROM role_permissions;
DELETE FROM role_modules;
DELETE FROM features;

INSERT INTO features (name, permission, action, module_id, featureName, description) VALUES
('View Users', 'view-users', '/sunfuel/views/users/index.php', 2, 'view-users', 'View system users'),
('Create Users', 'create-users', '/sunfuel/views/users/create.php', 2, 'create-users', 'Create system users'),
('Delete Users', 'delete-users', '/sunfuel/views/users/delete.php', 2, 'delete-users', 'Delete system users'),
('View Roles', 'view-roles', '/sunfuel/views/roles/index.php', 3, 'view-roles', 'View roles'),
('Create Roles', 'create-roles', '/sunfuel/views/roles/create.php', 3, 'create-roles', 'Create roles'),
('Edit Roles', 'edit-roles', '/sunfuel/views/roles/edit.php', 3, 'edit-roles', 'Edit roles'),
('View Territories', 'view-territories', '/sunfuel/views/territories/index.php', 4, 'view-territories', 'View territories'),
('Create Territories', 'create-territories', '/sunfuel/views/territories/create.php', 4, 'create-territories', 'Create territories'),
('Edit Territories', 'edit-territories', '/sunfuel/views/territories/edit.php', 4, 'edit-territories', 'Edit territories'),
('Delete Territories', 'delete-territories', '/sunfuel/views/territories/delete.php', 4, 'delete-territories', 'Delete territories'),
('View Fuel Stations', 'view-fuelstations', '/sunfuel/views/fuelstation/index.php', 5, 'view-fuelstations', 'View fuel stations'),
('Create Fuel Stations', 'create-fuelstations', '/sunfuel/views/fuelstation/create.php', 5, 'create-fuelstations', 'Create fuel stations'),
('Edit Fuel Station', 'edit-fuelstation', '/sunfuel/views/fuelstation/update.php', 5, 'edit-fuelstation', 'Edit fuel stations'),
('Delete Fuel Stations', 'delete-fuelstations', '/sunfuel/views/fuelstation/delete.php', 5, 'delete-fuelstations', 'Delete fuel stations'),
('View Fuel Agents', 'view-fuelagents', '/sunfuel/views/fuelagent/index.php', 5, 'view-fuelagents', 'View fuel agents'),
('Create Fuel Agents', 'create-fuelagent', '/sunfuel/views/fuelagent/create.php', 5, 'create-fuelagent', 'Create fuel agents'),
('Edit Fuel Agents', 'edit-fuelagent', '/sunfuel/views/fuelagent/edit.php', 5, 'edit-fuelagent', 'Edit fuel agents'),
('View Stages', 'view-stages', '/sunfuel/views/stage/index.php', 6, 'view-stages', 'View stages'),
('Create Stages', 'create-stage', '/sunfuel/views/stage/create.php', 6, 'create-stage', 'Create stages'),
('View Boda Riders', 'view-bodausers', '/sunfuel/views/bodauser/index.php', 7, 'view-bodausers', 'View boda riders'),
('Create Boda Riders', 'create-bodausers', '/sunfuel/views/bodauser/create.php', 7, 'create-bodausers', 'Create boda riders'),
('Edit Boda Riders', 'edit-bodauser', '/sunfuel/views/bodauser/edit.php', 7, 'edit-bodauser', 'Edit boda riders'),
('View Loans', 'view-loans', '/sunfuel/views/loans/index.php', 8, 'view-loans', 'View loans'),
('View Payments', 'view-payments', '/sunfuel/views/payments/index.php', 9, 'view-payments', 'View payments'),
('View Deposits', 'view-deposits', '/sunfuel/views/deposits/index.php', 10, 'view-deposits', 'View deposits'),
('Create Deposits', 'create-deposits', '/sunfuel/views/deposits/create.php', 10, 'create-deposits', 'Create deposits'),
('View Deposit Receipts', 'view-deposit-receipts', '/sunfuel/views/deposits/showReceipt.php', 10, 'view-deposit-receipts', 'View deposit receipts'),
('View Packages', 'view-packages', '/sunfuel/views/packages/index.php', 11, 'view-packages', 'View packages'),
('Create Packages', 'create-package', '/sunfuel/views/packages/create.php', 11, 'create-package', 'Create packages');

INSERT INTO role_modules (role_id, module_id)
SELECT 1, id FROM modules;

INSERT INTO role_permissions (role_id, feature_id, permission, status)
SELECT 1, id, permission, 1 FROM features;
