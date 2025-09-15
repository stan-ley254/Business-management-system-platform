import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.getElementById('password').addEventListener('input', function() {
  let value = this.value;
  document.getElementById('length').className = value.length >= 8 ? 'text-green-600' : 'text-red-600';
  document.getElementById('upper').className = /[A-Z]/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('lower').className = /[a-z]/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('number').className = /\d/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('symbol').className = /[^A-Za-z0-9]/.test(value) ? 'text-green-600' : 'text-red-600';
});
