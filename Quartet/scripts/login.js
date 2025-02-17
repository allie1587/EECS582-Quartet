/*
Authors: Alexandra, Jose, Brinley, Ben, Kyle
Date: 02/16/2025
Last modified: 02/16/2025
Purpose: Save users login information
*/

function bakeCookie(name, value, days =7){ //cookie that expires after 7 days
    let expires = ""; //empty expireation string
    //If a valid day currently always 7 days set the cookie to expire after the given time
    if (days){ 
        const date = new Date(); //create a Date object
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000); //Convert days to ms
        //create a string that the is compatible with cookies that holds the expireation date
        expires = "; expires=" + date.toUTCString();
    }
    //encode cookie with special characters
    //path=/; makes cookie available on every page of our site
    document.cookie = name + "=" + encodeURIComponent(value) + expires + ";path=/; Secure; SameSite=Strict";
}

// Function to grab a cookie by its name
function grabCookie(name) {
    let nameEQ = name + "="; //find equivalent cookie name
    let cookies = document.cookie.split(';'); //Splits all cookies into an array
    // Loop through the cookie array for every for every cookie
    for (let i = 0; i < cookies.length; i++) {
        let c = cookies[i].trim(); // Remove whitespace from each cookie
        if (c.indexOf(nameEQ) === 0) { // Check if the cookie starts with the specified name
            return decodeURIComponent(c.substring(nameEQ.length, c.length)); // Return the cookie value, decoding it first
        }
    }
    return null; // Return null if there are no cookies
}

// Function to delete a cookie
function deleteCookie(name) {
    document.cookie = name + "=; expires=Sun, 01 Jan 2025 00:00:00 UTC; path=/;"; //chose time in past to expire cookie
}

// Check if a username cookie exists on page load
document.addEventListener('DOMContentLoaded', function () { //runs when the page is loaded
    const savedUsername = grabCookie('savedUsername'); //get a saved username from cookies
    if (savedUsername) { //if there is a saved user name then auto fill the username box and keep the remember me box checked
        document.getElementById('username').value = savedUsername;
        document.getElementById('rememberMe').checked = true;
    }
});

//event listener when user tries to login
document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); //Prevents prevents page reload
    document.getElementById('error-message').textContent = ''; //remove error messages
    //Gets the values entered by the user for each field
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var rememberMe = document.getElementById('rememberMe').checked;
    //TEST//
    //If username is "admin" and password is "123", redirect to index.html
    if (username === "admin" && password === "123") {
        if (rememberMe) {
            bakeCookie('savedUsername', username, 7); // save cookie for 7 days
        } else {
            deleteCookie('savedUsername'); //if wrong login then remove the cookie
        }
        //Redirects to the homepage
        window.location.href = "index.html";
    } else {
        //Displays an error message if the credentials are incorrect
        document.getElementById('error-message').textContent = "Invalid username or password.";
    }
});