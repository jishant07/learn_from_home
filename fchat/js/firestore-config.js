// Initialize Firebase
var config = {
	 apiKey: "AIzaSyBp-acW7Z5B9TC0yTNuciIRsTAhhBug4AI",
  authDomain: "webchat-c2993.firebaseapp.com",
  databaseURL: "https://webchat-c2993.firebaseio.com",
  projectId: "webchat-c2993",
  storageBucket: "webchat-c2993.appspot.com",
  messagingSenderId: "122903932513",
  appId: "1:122903932513:web:4b7b63ae5d1ed38bac8420",
  measurementId: "G-1F5N5DN03X"
};

firebase.initializeApp(config);

// Initialize Cloud Firestore through Firebase
var db = firebase.firestore();

// Disable deprecated features
db.settings({
	timestampsInSnapshots: true
});