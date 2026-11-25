/**
 * public/js/validation.js
 * Validation cote client (JS). Le serveur (PHP) revalide TOUJOURS.
 * Formulaires concernes : <form data-validate="login|register|user-add|user-edit">
 * Confirmation de suppression : <form data-confirm="message">
 */
(function () {
  'use strict';

  var USERNAME_RE = /^[a-zA-Z0-9_\-]{3,50}$/;
  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function value(form, name) {
    var el = form.elements[name];
    return el ? el.value : null;
  }

  function clearErrors(form) {
    form.querySelectorAll('.field-error').forEach(function (n) { n.remove(); });
    form.querySelectorAll('.is-invalid').forEach(function (n) { n.classList.remove('is-invalid'); });
  }

  function addError(form, name, message) {
    var input = form.elements[name];
    if (!input) { return; }
    input.classList.add('is-invalid');
    var div = document.createElement('div');
    div.className = 'field-error';
    div.textContent = message;
    input.parentNode.insertBefore(div, input.nextSibling);
  }

  function validate(form, type) {
    var errors = [];
    var username = value(form, 'username');
    var email = value(form, 'email');
    var pw = value(form, 'password');
    var pw2 = value(form, 'password_confirm');

    if (username !== null && !USERNAME_RE.test(username.trim())) {
      errors.push(['username', 'Username must be 3 to 50 characters (letters, digits, _ or -).']);
    }
    if (email !== null && !EMAIL_RE.test(email.trim())) {
      errors.push(['email', 'Invalid email address.']);
    }

    if (type === 'login') {
      if (pw === '') { errors.push(['password', 'Please enter your password.']); }
    } else if (pw !== null) {
      var optional = (type === 'user-edit');
      if (pw === '') {
        if (!optional) { errors.push(['password', 'Password is required.']); }
      } else if (pw.length < 8) {
        errors.push(['password', 'Password must be at least 8 characters long.']);
      }
      if (pw2 !== null && pw !== pw2) {
        errors.push(['password_confirm', 'Passwords do not match.']);
      }
    }
    return errors;
  }

  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      clearErrors(form);
      var errors = validate(form, form.getAttribute('data-validate'));
      if (errors.length > 0) {
        event.preventDefault();
        errors.forEach(function (e) { addError(form, e[0], e[1]); });
        var first = form.elements[errors[0][0]];
        if (first) { first.focus(); }
      }
    });
  });

  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!window.confirm(form.getAttribute('data-confirm'))) {
        event.preventDefault();
      }
    });
  });
})();
