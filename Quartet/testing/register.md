****************************************
# Test Scenario 1: Creating an Account
Status: Passed

| # | Steps | Expected result |
| --- | --- | --- |
| 1 | Navigate to the quartet.infinityfreeapp.com website. | The front page (index.php) will appear with store information. |
| 2 | Click the login icon on the rightmost part of the menu located at the top of the page. | A page will appear showing the login form. |
| 3 | Input a valid username and password and click the login button. | The site will redirect to the barber-side dashboard page. |
| 4 | Select the button on the top left corner of the page. | A menu will appear. |
| 5 | Click the "Create New Account" button on the menu. | A register page will appear to fill the information of the new barber. |
| 6 | Fill the fields with a valid first name, last name, username, and password | The page should have filled fields
| 7 | Click on the 'Sign Up' button on the bottom of the page | Barber should be succesfully added to the database and should be able to log into their account. |

****************************************

****************************************
# Test Scenario 2:Add an User with missing field
Status: Passed for all fields

| # | Steps | Expected result |
| --- | --- | --- |
| 1 | Repeat steps 1-5 from Test Scenario 1. | See above. |
| 2 | Fill the fields with a valid first name, last name, username, and password. | The page should have filled fields
| 3 | Delete one of the fields | Field should appear emptry |
| 4 | Click on the 'Sign Up' button on the bottom of the page | Barber should be prompted with error message saying: “Please fill out this field”. |


****************************************

****************************************
# Test Scenario 3:Add an User with a long field
Status: Passed for all fields

| # | Steps | Expected result |
| --- | --- | --- |
| 1 | Repeat steps 1-5 from Test Scenario 1. | See above. |
| 2 | Fill the fields with a valid first name, last name, username, and password. | The page should have filled fields
| 3 | Write on one of the fields over 500 characters | Field should appear overfilled |
| 4 | Click on the 'Sign Up' button on the bottom of the page | Barber should be prompted with error message saying: “FieldName must not Exceed X amount of characters”. |

****************************************

****************************************
# Test Scenario 4: Add an username with mismatching passwords
Status: Passed

| # | Steps | Expected result |
| --- | --- | --- |
| 1 | Repeat steps 1-5 from Test Scenario 1. | See above. |
| 2 | Fill the fields with a valid first name, last name, username, and password. | The page should have filled fields | 
| 3 | Delete one of the password fields and write mismatching passwords | Passwords should be mismatched |
| 4 | Click on the 'Sign Up' button on the bottom of the page | Barber should be prompted with error message saying: “Passwords do not match”. |
****************************************

****************************************
# Test Scenario 5:Trying to add SQL Injection
Status: Passed for all fields

| # | Steps | Expected result |
| --- | --- | --- |
| 1 | Repeat steps 1-5 from Test Scenario 1. | See above. |
| 2 | Fill the fields with a valid first name, last name, username, and password. | The page should have filled fields |
| 3 | Replace one of the fields with an SQL Injection line | SQL Injection should appear |
| 4 | Click on the 'Sign Up' button on the bottom of the page | Barber should be succefully added to the database but the SQL query is NOT exectuted. |






2. Modifying User Information
Format:
Test Case ID
Test Scenario
Steps
Expected Result
Status


TC-1
Edit user details successfully
1. Select an existing user
2. Modify first name, last name, email, phone, social media
3. Upload a valid image for professional photo and portfolio
4. Click ‘Update Profile’
- User successfully modified and changes are visible in the barbers page
- Should reflect in the database


Passed
TC-2
Remove a field name
1. Select an existing User
2. Delete a field
3. Click ‘Update Profile’
Error message appears: “Please fill out X field”
Passed
TC-3
Rename a field with a long name
1. Select an existing field
2. Enter a string longer than maximum characters
3. Click ‘Update Profile’
Error message appears: “Maximum X characters allowed”
Passed
TC-4
Edit wrong file type
1. Select an Image file
2. Update a file that isn’t a picture
3. Click ‘Update Profile’
Error message appears: “Please enter a valid image file”
Passed
TC-5
Input a non-number for the phone number field


1. Select phone number field
2. Enter a non-number at any point
3. Click ‘Update Profile’
Error message appears: “Phone must be 10 digits”
Passed
TC-6
Input a long/short for the phone number field


1. Select phone number field
2. Enter less or more than 10 numbers
3. Click ‘Update Profile’
Error message appears:“Phone must be 10 digits”
Passed
TC-7
Input a regular string as a email
1. Select the email field
2. Enter a string without including the ‘@’ symbol 
3. Click ‘Update Profile’
Error message appears: “Please include a @ in the address, email given isn't a valid email address”
Passed


TC-8
Try SQL Injection
1. Select an field to modify
2. Enter a “;” followed by an SQL Query into the field
3. Click ‘Update Profile’
Profile will get updated correctly without running the SQL Query. 
Passed


3. User Login
Format:
Test Case ID
Test Scenario
Steps
Expected Result
Status


TC-1
Log in as a valid user
1. Enter a valid username and password

4. Click ‘Login’
- Barber successfully logged in


Passed
TC-2
Enter long input when logging into Account
1. Enter a long field when logging into an account
3. Enter the rest of the information
3. Click ‘Login’
Error message appears: “FieldName must not Exceed X amount of characters”
Passed
TC-3
Trying to add SQL Injection
1. Fill a field with an SQL query
2. Fill rest of fields normally
3. Click ‘Login’
- Barber doesn’t log in and the SQL Query doesn’t get executed
Passed




