feather = require('feather-icons');

feather.replace();

//Prevent Windows from Sleeping

//navigator.wakeLock is the main standby API property.
//request method requests the computer to not enter standby mode. Here "display" indicates that the monitor shouldn't enter standby mode.
navigator.wakeLock.request("display").then(
    function successFunction() {
        // success
    },
    function errorFunction() {
        // error
    }
);
//here system indicates CPU, GPU, radio, wifi etc.
navigator.wakeLock.request("system").then(
    function successFunction() {
        // success
    },
    function errorFunction() {
        // error
    }
);

//release() is used to release the lock.
navigator.wakeLock.release("display");