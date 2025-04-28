# 1\. Schedule.php

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-1 | Choose All option for each filtering choice | 1\. Navigate to the appointments page 2\. Set each option to All and click ‘Apply Filter’ | 1\. Every confirmed appointment is shown in the table | Passed |
| TC-2 | Filter for past, present, and future | 1\. Navigate to the appointments page 2\. Set the show appointments filter to past 3\. Set the show appointments filter to present 4\. Set the show appointments filter to future | 1\. All past appointments are shown and press apply filters 2\. All present appointments are shown and press apply filters 3\. All future appointments are shown and press apply filters | Passed |
| TC-3 | Filter for barbers | 1\. Navigate to the appointments page 2\. Select a barber in barber filter and click apply filter | 1\. If the barber has appointments, it shows in the table 2\. If the barber has no appointment, show ‘No appointments schedule’ | Passed |
| TC-4 | Filter for clients | 1\. Navigate to the appointments page 2\. Unselect All and then select three random clients and click apply filter | 1\. If the barber has appointments for any of the selected clients, show in the table 2\. If the barber has no appointment for all of the selected clients, show ‘No appointments schedule’ | Passed |
| TC-5 | Filter for time | 1\. Navigate to the appointments page 2\. Unselect All and then select five random times and click apply filter | 1\. If the barber has appointments at any of the selected times, show in the table 2\. If the barber has no appointment for all of the selected times, show ‘No appointments schedule’ | Passed |
| TC-6 | Reset Button | 1\. Navigate to the appointments page 2\. Add a bunch of filtering options and click apply filters 3\. Click the reset button | 1\. Resets the filters to All except for the barber filter, which is set to the current logged in barber | Passed |

# 