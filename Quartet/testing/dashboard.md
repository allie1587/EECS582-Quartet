# Test Scenario 1: Access Control  
**Status:** Passed  

| # | Steps | Expected Result |  
| --- | --- | --- |  
| 1 | Access page without login | Redirect to login page |  
| 2 | Access with barber role | Page loads successfully |  
| 3 | Access with manage role | Page loads successfully |  

# Test Scenario 2: Appointment Display  
**Status:** Passed  

| # | Steps | Expected Result |  
| --- | --- | --- |  
| 1 | Load page with no appointments | "No appointments" message displays |  
| 2 | Load page with 1 appointment | Single appointment shows correctly |  
| 3 | Load page with multiple appointments | All appointments ordered by time |  
| 4 | Check time formatting | Times display as HH:MM (12-hour format) |  
| 5 | Verify client information | Name, phone, email display correctly |  

# Test Scenario 3: Checkout Functionality  
**Status:** Passed  

| # | Steps | Expected Result |  
| --- | --- | --- |  
| 1 | Click checkout button | Confirmation dialog appears |  
| 2 | Confirm checkout | Appointment removed from list |  
| 3 | Check database after checkout | Record moved to Checkout_History |  
| 4 | Attempt checkout with invalid ID | Error message displays |  
| 5 | Test concurrent checkout attempts | Only one succeeds (transaction safe) |  

# Test Scenario 4: Store Information Display  
**Status:** Passed  

| # | Steps | Expected Result |  
| --- | --- | --- |  
| 1 | Load page with store info | All store details display correctly |  
| 2 | Load page with no store info | "No store information" message |  
| 3 | Verify social media links | Properly formatted with target="_blank" |  
| 4 | Test with missing social media | Only available platforms show |  
| 5 | Verify address formatting | City, State ZIP format correct |  

# Test Scenario 5: Store Hours Display  
**Status:** Passed  

| # | Steps | Expected Result |  
| --- | --- | --- |  
| 1 | Load with complete hours | All days show correct hours |  
| 2 | Load with some days closed | "Closed" displays for closed days |  
| 3 | Verify time formatting | 12-hour format with AM/PM |  
| 4 | Test with missing hours data | "No store hours available" message |  
| 5 | Verify day order | Monday through Sunday sequence |  

