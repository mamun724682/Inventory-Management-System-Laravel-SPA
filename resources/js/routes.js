// Auth Components
let login = require('./components/auth/login.vue').default;
let register = require('./components/auth/register.vue').default;
let forget = require('./components/auth/forget.vue').default;
let logout = require('./components/auth/logout.vue').default;

let home = require('./components/home.vue').default;

// Employee Components
let storeEmployee = require('./components/employee/create.vue').default;
let employee = require('./components/employee/index.vue').default;
let editEmployee = require('./components/employee/edit.vue').default;

// Supplier Components
let storeSupplier = require('./components/supplier/create.vue').default;
let supplier = require('./components/supplier/index.vue').default;
let editSupplier = require('./components/supplier/edit.vue').default;

// Category Components
let storeCategory = require('./components/category/create.vue').default;
let category = require('./components/category/index.vue').default;
let editCategory = require('./components/category/edit.vue').default;

// Product Components
let storeProduct = require('./components/product/create.vue').default;
let product = require('./components/product/index.vue').default;
let editProduct = require('./components/product/edit.vue').default;

// Expense Components
let storeExpense = require('./components/expense/create.vue').default;
let expense = require('./components/expense/index.vue').default;
let editExpense = require('./components/expense/edit.vue').default;

export const routes = [

  //Auth Route
  { path: '/', component: login, name:'/' },
  { path: '/register', component: register, name:'register' },
  { path: '/forget', component: forget, name:'forget' },
  { path: '/logout', component: logout, name:'logout' },

  { path: '/home', component: home, name:'home' },
  
  // Employee Route
  { path: '/store-employee', component: storeEmployee, name:'storeEmployee' },
  { path: '/employee', component: employee, name:'employee' },
  { path: '/edit-employee/:id', component: editEmployee, name:'editEmployee' },

  // Supplier Route
  { path: '/store-supplier', component: storeSupplier, name:'storeSupplier' },
  { path: '/supplier', component: supplier, name:'supplier' },
  { path: '/edit-supplier/:id', component: editSupplier, name:'editSupplier' },

  // Category Route
  { path: '/store-category', component: storeCategory, name:'storeCategory' },
  { path: '/category', component: category, name:'category' },
  { path: '/edit-category/:id', component: editCategory, name:'editCategory' },

  // Product Route
  { path: '/store-product', component: storeProduct, name:'storeProduct' },
  { path: '/product', component: product, name:'product' },
  { path: '/edit-product/:id', component: editProduct, name:'editProduct' },

  // Expense Route
  { path: '/store-expense', component: storeExpense, name:'storeExpense' },
  { path: '/expense', component: expense, name:'expense' },
  { path: '/edit-expense/:id', component: editExpense, name:'editExpense' },
]