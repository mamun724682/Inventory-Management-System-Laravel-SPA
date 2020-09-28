let login = require('./components/auth/login.vue').default;
let register = require('./components/auth/register.vue').default;
let forget = require('./components/auth/forget.vue').default;
let logout = require('./components/auth/logout.vue').default;
// End Auth

let home = require('./components/home.vue').default;

// Employee
let storeEmployee = require('./components/employee/create.vue').default;
let employee = require('./components/employee/index.vue').default;
let editEmployee = require('./components/employee/edit.vue').default;

export const routes = [
  { path: '/', component: login, name:'/' },
  { path: '/register', component: register, name:'register' },
  { path: '/forget', component: forget, name:'forget' },
  { path: '/logout', component: logout, name:'logout' },

  { path: '/home', component: home, name:'home' },

  // Employee
  { path: '/store-employee', component: storeEmployee, name:'storeEmployee' },
  { path: '/employee', component: employee, name:'employee' },
  { path: '/edit-employee/:id', component: editEmployee, name:'editEmployee' },
]