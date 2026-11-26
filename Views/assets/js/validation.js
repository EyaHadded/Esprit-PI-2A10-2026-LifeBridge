/**
 * Views/assets/js/validation.js  —  MEMBRE 5 : Validation côté JavaScript
 * Partagé par le frontoffice et le backoffice.
 * - champs obligatoires, format e-mail, mots de passe
 * - affichage du champ « code d'inscription » selon le rôle choisi
 * Les doublons d'e-mail sont vérifiés côté serveur avec PHP et PDO.
 */
(function () {
    'use strict';

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    var nomRe   = /^[\p{L}][\p{L}' \-]{1,99}$/u;

    function field(form, name) {
        return form.elements[name] || null;
    }

    function collectErrors(form) {
        var errors = [];
        var type = form.dataset.validate;
        var pwdRequired = form.dataset.passwordRequired === '1';
        var nom = field(form, 'nom');
        var email = field(form, 'email');
        var pwd = field(form, 'password');
        var confirm = field(form, 'password_confirm');
        var role = field(form, 'role');
        var code = field(form, 'code');

        if (role && code && role.value !== 'citoyen' && code.value.trim() === '') {
            errors.push("Le code d'inscription est obligatoire pour ce type de compte.");
        }
        if (nom) {
            var n = nom.value.trim();
            if (n === '') { errors.push("Le nom complet est obligatoire."); }
            else if (!nomRe.test(n)) { errors.push("Nom complet : 2 à 100 caractères (lettres, espaces, ' ou -)."); }
        }
        if (email) {
            var m = email.value.trim();
            if (m === '') { errors.push("L'adresse e-mail est obligatoire."); }
            else if (!emailRe.test(m)) { errors.push("Format d'e-mail invalide."); }
        }
        if (pwd) {
            if (type === 'login') {
                if (pwd.value === '') { errors.push("Le mot de passe est obligatoire."); }
            } else if (pwd.value !== '' || pwdRequired) {
                if (pwd.value.length < 8) { errors.push("Le mot de passe doit contenir au moins 8 caractères."); }
                if (confirm && pwd.value !== confirm.value) { errors.push("Les mots de passe ne correspondent pas."); }
            }
        }
        return errors;
    }

    function showErrors(form, errors) {
        var box = form.querySelector('.js-errors');
        if (!box) { return; }
        box.textContent = '';
        errors.forEach(function (msg) {
            var d = document.createElement('div');
            d.textContent = msg; // textContent : aucune injection HTML possible
            box.appendChild(d);
        });
        box.hidden = errors.length === 0;
    }

    function toggleCode(form) {
        var role = field(form, 'role');
        var group = form.querySelector('[data-code-group]');
        if (role && group) { group.hidden = role.value === 'citoyen'; }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-validate]').forEach(function (form) {
            form.addEventListener('submit', function (ev) {
                var errors = collectErrors(form);
                showErrors(form, errors);
                if (errors.length > 0) { ev.preventDefault(); }
            });
            var role = field(form, 'role');
            if (role && form.querySelector('[data-code-group]')) {
                role.addEventListener('change', function () { toggleCode(form); });
                toggleCode(form);
            }
        });

        // Confirmation avant suppression (pas de JS inline à cause de la CSP)
        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (ev) {
                if (!window.confirm(form.dataset.confirm)) { ev.preventDefault(); }
            });
        });
    });
})();
