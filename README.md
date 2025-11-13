# Enroll Key Block

A simple Moodle block that allows users to enter an enrollment key and automatically enroll in the corresponding course. The plugin checks the submitted key against the database and enrolls the user into the matching course if the key is valid.

## Features
- Displays a single input field for users to enter an enrollment key  
- Validates the key against what's entered for any courses's self-enrollment instance
- Automatically enrolls the authenticated user in the associated course  
- Shows success or error messages

## Compatibility
Tested on:
-  Moodle 4.4, PHP 8.2

## Installation
1. Download repository.  
2. Place the folder `enrollkey` into Moodle `/blocks/` directory
3. Visit **Site administration > Notifications** to complete the installation.

## Usage
1. Add the **Enroll Key** block to any page where you want users to enter enrollment keys (e.g., Dashboard or My Home).  
2. Users type the enrollment key into the block.  
3. If the key matches a course, the user is enrolled and redirected and shown a confirmation message.

## Configuration
- No special configuration required unless your implementation uses a custom enrollment-key table.  
- You can change the language strings for the confirmation/error messages

## Versioning
- This plugin follows Moodle’s standard version.php structure.

## License
This plugin is licensed under the **GNU GPL v3 or later**, consistent with Moodle core and all official Moodle plugins.
