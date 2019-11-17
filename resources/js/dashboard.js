feather = require('feather-icons');

feather.replace();

//Notifications

function sendNotification(notification_text) {
    if (!("Notification" in window)) {
      console.log("This browser does not support system notifications");
    }
  
    else if (Notification.permission === "granted") {
      var notification = new Notification(notification_text);
    }
  
    else if (Notification.permission !== 'denied') {
      Notification.requestPermission(function (permission) {
        if (permission === "granted") {
          var notification = new Notification(notification_text);
        }
      });
    }
}


// Call Notifications

function callNotifications() {
    $.getJSON('/data/missed_calls.json', function (data) {
        let count = object.data.length;
        if (count > 0) {
            sendNotification('Existem ' + count + ' Chamadas Perdidas');
        }
    });

    $.getJSON('/data/callbacks.json', function (data) {
        let count = object.data.length;
        if (count > 0) {
            sendNotification('Existem ' + count + ' Chamadas Por Devolver');
        }
    });
}

callNotifications();
setInterval(callNotifications, 60 * 60 * 1000);