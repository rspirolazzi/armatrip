// server/app.js
const express = require('express');
const morgan = require('morgan');
const path = require('path');

const app = express();

// Setup logger
app.use(morgan(':remote-addr - :remote-user [:date[clf]] ":method :url HTTP/:http-version" :status :res[content-length] :response-time ms'));

// Serve static assets
app.use(express.static(path.resolve(__dirname,  '../build')));

//production mode
if(process.env.NODE_ENV === 'production') {
  app.use(express.static(path.join(__dirname, '../build')));
  //
  app.get('*', (req, res) => {
    res.sendfile(path.join(__dirname = '../build/index.html'));
  })
}
//build mode
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname+'/../build/index.html'));
})

module.exports = app;