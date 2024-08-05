import {initializeApp} from "firebase/app";
import {getAuth} from "firebase/auth";

const firebaseConfig = {
    apiKey: "AIzaSyA_4i3G6hkzzG2e4yhVEqN2QVTjwhteTqY",
    authDomain: "rk-online-388407.firebaseapp.com",
    projectId: "rk-online-388407",
    storageBucket: "rk-online-388407.appspot.com",
    messagingSenderId: "1024600086527",
    appId: "1:1024600086527:web:a1ad2dd80b7dd6f7e2b9ca",
    measurementId: "G-8CLGVBG59H"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);
