toastr = require('toastr');

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
    console.log(Notification.permission);
}


// Call Notifications

function callNotifications() {
    $.getJSON('/data/missed_calls.json', function (object) {
        let count = object.data.length;
        if (count > 0) {
            let notification_text = 'Existem ' + count + ' Chamadas Perdidas';
            sendNotification(notification_text);
        }
    });

    $.getJSON('/data/callbacks.json', function (object) {
        let count = object.data.length;
        if (count > 0) {
            let notification_text = 'Existem ' + count + ' Chamadas Por Devolver';
            sendNotification(notification_text);
        }
    });
}


$( document ).ready(function() {
    callNotifications();
    setInterval(callNotifications, 60 * 60 * 1000);
});


// Open Door

window.openDoor = function openDoor(url) {
  $.get(url, function(data, status) {
      toastr.success('Porta Aberta', 'Video Porteiro')
  })
}