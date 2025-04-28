See\_feedback.php (barber can view feedback from clients)

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-1 | All customer feedback is shown to the barber from database | Check database | All customer feedback matches database | Pass |
| TC-2 | Barber can respond to feedback | Respond to customer feedback from feedback.php | Customer receives an email | Pass |
| TC-3 | Customer actually receives the barber's response. | Check email | Email received | Pass |

Testimonies.php (barber can view reviews from clients)

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-4 | Add to testimonies | Click any add to testimonies | Testimony added to index.php | Pass |
| TC-5 | Remove from testimonies | Click any remove from testimonies | Testimony removed from index.php | Pass |

Feedback.php (client side client makes review or feedback)

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-6 | Submit review and submit a feedback | Submit a review and submit a feedback | Barber can see this review on testimonies.php | Pass |

Receive\_feedback.php (submits clients feedback to the database)

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-6 continued | Submit feedback | Submit a review  | This review is present in the database | Pass |

Submit\_review (sends client review to the database) 

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-6 continued | Submit review | submit a feedback | This feedback is present in the database | Pass |

Send\_mail.php (creates email out of barber response to the customers feedback)

| Test Case ID | Test Scenario | Steps | Expected Result | Status |
| :---- | :---- | :---- | :---- | :---- |
| TC-7 | Receive email | Go to see\_feedback.php and click on send response.Check the email of whoever sent the feedback initially | There is an email with that persons first name and feedback and then the barbers response | pass |

