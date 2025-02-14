const express = require('express'); //package.json should contain and take care of this even with the modules in gitignore
const app = express();
const port = 3000;

// Serve static files from the "public" directory
app.use(express.static('Quartet')); //Put all our files into the Quartet folder

// Start server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
