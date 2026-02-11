-- Run this in Supabase SQL Editor to DELETE the default admin user
-- This allows you to register with your own credentials

DELETE FROM users WHERE email = 'admin@example.com';

-- After running this, you can register at /register with your own details
