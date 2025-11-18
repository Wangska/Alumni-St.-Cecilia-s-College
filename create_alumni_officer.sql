-- Create Alumni Officer Account
-- Username: officer
-- Password: officer123
-- This script creates a test alumni officer account for the system

INSERT INTO users (name, username, password, type, auto_generated_pass, alumnus_id)
VALUES ('Alumni Officer', 'officer', MD5('officer123'), 2, '', NULL);

-- Display success message
SELECT 'Alumni Officer account created successfully!' as Message,
       'Username: officer' as Username,
       'Password: officer123' as Password,
       'Type: 2 (Alumni Officer)' as UserType;

