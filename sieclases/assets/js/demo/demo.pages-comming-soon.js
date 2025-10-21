"use strict";
var d = new Date();
d.setDate(d.getDate() + 10);
var countDownDate = new Date(d).getTime();
var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("demo").innerHTML = "<b>" + days + "</b> days : <b>" + hours + "</b> h : <b>" + minutes + "</b> m : <b>" + seconds + "</b> s ";
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "Times over";
    }
}, 1000);