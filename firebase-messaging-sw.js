// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
    apiKey: "AIzaSyA8fXYZre4nOBnWvV6tYttjR_laBzFSJE4",
    authDomain: "nativebit-175ba.firebaseapp.com",
    projectId: "nativebit-175ba",
    storageBucket: "nativebit-175ba.appspot.com",
    messagingSenderId: "695494499148",
    appId: "1:695494499148:web:7751c78c2ccb7df37a0de9",
    measurementId: "G-5SET3S4X5K"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();


// If you would like to customize notifications that are received in the
// background (Web app is closed or not in browser focus) then you should
// implement this optional method.
// [START background_handler]
messaging.setBackgroundMessageHandler(function (payload) {
    console.log('[firebase-messaging-sw.js] Set background message ', payload);
	
    // Customize notification here
    payload = JSON.parse(payload.data.data);
    var notificationTitle = payload.title;
    var notificationOptions = {
        body : payload.message,
        icon : payload.image,
        click_action : payload.onclick,
        onclick : payload.onclick,
    };
    return self.registration.showNotification(notificationTitle,notificationOptions);  
});
// [END background_handler]

messaging.onBackgroundMessage((payload) => {
    //console.log('[firebase-messaging-sw.js] Received background message ', payload);
	
    // Customize notification here
    payload = JSON.parse(payload.data.data);
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    var notificationTitle = payload.title;
    var notificationOptions = {
        body : payload.message,
        icon : payload.image,
        click_action : payload.onclick,
        onclick : payload.onclick
    }; 
    return self.registration.showNotification(notificationTitle,notificationOptions);    
});

self.addEventListener('notificationclick', function(event) {
    var url = event.notification.onclick;
    event.notification.close();
  
    if(url != "user_visible_auto_notification"){
        // This looks to see if the current is already open and
        // focuses if it is
        event.waitUntil(clients.matchAll({
            type: "window"
        }).then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];
                if (client.url == url && 'focus' in client)
                    return client.focus();
            }

            if(clients.openWindow)
                return clients.openWindow(url);
        }));
    }else{
        return clients.openWindow('/');
    }    
});
