// public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyB56s9ttNHzWd7dYuVJoCEe3t6FCsrd9NY",
    projectId: "amelys-klinik",
    messagingSenderId: "269503372470",
    appId: "1:269503372470:web:dabe88f549b2da2ae61a17"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Handler notifikasi saat di background
messaging.onBackgroundMessage(function(payload) {
    console.log('Notif masuk saat background:', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/dist/img/logoamelys.png' // Sesuaikan path logo lo
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});