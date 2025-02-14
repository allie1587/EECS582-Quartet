/*
Script to save data while the customer is on the webpage
Currently saves date they chose but will also need time
will saves any review and rating they started
anything else we need to save?

in the future when client clicks a date/time it saves it to local
then when they confirm the time deletes this and then sends confirmation via text/email
*/

const confirmButton = document.getElementById('saveButton'); //confirm button
const date = document.getElementById("date"); //selection of dates button

confirmButton.addEventListener('click', function() {//when confirm is clicked
    let choseDate = JSON.parse(localStorage.getItem('choseDate')) || {}; //choseDate is assigned if null or it is retreived
    choseDate.date = date.value //sets the vale of choseDate to whatever the user selected
    localStorage.setItem('choseDate', JSON.stringify(choseDate)); //updates the local storage
    console.log("date saved:"); //message for debugging 
  });
  
