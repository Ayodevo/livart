importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');
firebase.initializeApp({apiKey: "AIzaSyDpm13r8WWowot_exhiqrGOzkLWigBYJ5s",authDomain: "livart-maze.firebaseapp.com",projectId: "livart-maze",storageBucket: "livart-maze.appspot.com", messagingSenderId: "735549878193", appId: "1:735549878193:web:f5d80decd6b0d16a379790"});
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });
