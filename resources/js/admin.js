require('./bootstrap');

import Vue from 'vue';

var app = new Vue({
    el: '#app',
    data: {
        conversations: [
            {id: 1, userid: 1, c_id: 1, admin_id: null, reply: 'Hi', even: true},
            {id: 2, userid: 1, c_id: 1, admin_id: null, reply: 'Any there?', even: true},
            {id: 3, userid: 1, c_id: 1, admin_id: 1, reply: 'Hello, how may I help?', even: false}
        ],
        isActive: 'activeclass'
    }
});

