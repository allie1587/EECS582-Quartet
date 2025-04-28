# 1\. Schedule.php

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-1 | Choose All option for each filtering choice | 1\. Navigate to the schedule page 2\. Set each option to All and click ‘Apply Filter’ | 1\. Every available appointment is shown as a total on the month view 2\. Every available appointment on the week view is shown | Passed |
| TC-2 | Filter for multiple times, multiple barbers, and one service | 1\. Navigate to the schedule page 2\. Set the service filter to any service of choice 3\. Set the barber filter to three random barber 4\. Set the time filter to 6 random times and click ‘Apply Filter’ | 1\. All appointments that meet the filter requirements are shown on the month view 2\. All appointments that meet the filter requirements are shown on the week view | Passed |
| TC-3 | Days and Appointments gray out if the current date and time is greater that it | 1\. Navigate to the schedule page 2\. Look at the current month, click on week view 3\. Press ‘Previous Month’ until you reach the previous year 4\. Press ‘Next Month’ until you reach the next year. Click on a day to get to week view | 1\. Current month should show all the days prior to day as grayed out, all days after today and today are able to be clicked on. Week view shows the same as above, but will also gray out any appointment buttons on the current day of which the appointment time is less than the current time. 2\. Previous year shows all days grayed out 3\. Next year shows all appointments in month and week view  | Passed |

# 2\. Confirm\_Appointment.php

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-1 | Inputting Proper Data | 1\. Navigate to the confirm appointment page 2\. Enter a valid first name with just letters 3\. Enter a valid last name with just letters 4\. Enter an email address that has an ‘@’ 5\. Enter a ten digit phone number with only numbers 6\. Try to change the Date, Time, or Barber section 7\. User can select any of the valid appointment options for services | 1\. User can only enter a first name with no special characters or numbers 2\. User can only enter a valid last name with no special characters or numbers 3\. User’s inputted email has an ‘@’ 4\. User can only enter ten digits as their phone number 5\. User can’t change the Date, Time, or Barber Sections 6\. User can only select the available services for that time 7\. The ‘Confirm Appointment’ button can only be clicked if all the requirements for valid input are met 8\. Everything is properly sent to the database | Passed |

# 