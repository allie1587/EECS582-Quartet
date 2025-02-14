# EECS582-Quartet
Welcome to Quartet
Follow the following steps to run it on your WSL:
```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install npm
sudo apt-get install nodejs
sudo npm install express
```
Then, in the terminal, type:
```
node server.js
```
To open, navigate to: http://localhost:3000

ctrl+c in terminal to terminate

if install npm is blocked then: Get-ExecutionPolicy
this should be restricted
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
this should allow you to npm install and everything should work
