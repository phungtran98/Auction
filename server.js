
const express = require('express')
const app = express()
const port = 3000
const http = require('http')
const server = http.createServer(app)
const {Server} = require("socket.io")

const io = new Server(server,{
    cors: {origin: "*"}
})

  function renderCountdown(dateStart, dateEnd){

    console.log(dateStart, dateEnd); 
    // Logs 
    // Sat Dec 19 2015 11:42:04 GMT-0600 (CST) 
    // Mon Jan 18 2016 11:42:04 GMT-0600 (CST)

    let currentDate = dateStart.getTime();
    let targetDate = dateEnd.getTime(); // set the countdown date
    let days, hours, minutes, seconds; // variables for time units
    // let countdown = document.getElementById("tiles"); // get tag element
    let count = 0;
    var getCountdown = function (c){
        // find the amount of "seconds" between now and target
        let secondsLeft = ((targetDate - currentDate) / 1000) - c;
        console.log(secondsLeft);
        days = pad( Math.floor( secondsLeft / 86400 ) );
        secondsLeft %= 86400;
        hours = pad( Math.floor( secondsLeft / 3600 ) );
        secondsLeft %= 3600;
        minutes = pad( Math.floor( secondsLeft / 60 ) );
        seconds = pad( Math.floor( secondsLeft % 60 ) );
        // format countdown string + set tag value
        console.log(days + ", " + hours + ", " + minutes + ", " + seconds); 
        
    }
    function pad(n) {
        return (n < 10 ? '0' : '') + n;
    }   
    getCountdown();
    setInterval(function () { getCountdown(count++ ); }, 900);
}

renderCountdown(new Date(),new Date('Tue Sep 21 2021 01:45:53') )

io.on("connection",(socket)=>{
    console.log("connected width socket.io!");
    socket.on("sendChatToServer",(messages)=>{
        console.log(messages);
        io.emit('sendChatToClient', messages);
    })

    socket.on("disconeted",(socket)=>{
        console.log("Disconected width socket.io");
    })
})
server.listen(port, () => console.log('Server node is start !'))