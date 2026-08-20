/**
 * Invisible-mode captcha.eu interception, generic across any form/validator
 * (powermail's own JS validation, Parsley, native HTML5 required attributes,
 * or none at all) - see CaptchaEuFieldViewHelper's docblock for why this
 * exists instead of captcha.eu's own KROT.interceptForm() auto-listener.
 *
 * How it stays compatible with other validators:
 * A "submit" listener added directly on a <form> (as powermail's and
 * Parsley's own validation do) always runs before a listener added on
 * `document` for the same event, because bubble-phase listeners fire
 * target-first, then upwards - regardless of which script ran first or
 * which listener was *registered* first. So by listening on `document`
 * instead of the form itself, this handler always sees the outcome of
 * every other validator: if any of them already called preventDefault()
 * (submission rejected), this handler backs off and does nothing. Only an
 * otherwise-valid submission gets intercepted here, solved, and completed.
 *
 * Caveat: a validator that calls stopPropagation()/stopImmediatePropagation()
 * instead of just preventDefault() would stop this handler from ever seeing
 * the event. That is unusual - well-behaved validators only preventDefault().
 */
(function () {
    if (window.__guglerCaptchaEuBound) {
        return;
    }
    window.__guglerCaptchaEuBound = true;

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        var hiddenField = form.querySelector('.captcha_at_hidden_field');
        if (!hiddenField || hiddenField.dataset.guglerCaptchaSolving === '1') {
            return;
        }

        if (event.defaultPrevented) {
            // Some other validator already rejected this submission - leave it rejected.
            return;
        }

        if (typeof window.KROT === 'undefined' || typeof window.KROT.getSolution !== 'function') {
            // SDK not loaded (yet) - don't block submission because of that.
            return;
        }

        event.preventDefault();
        hiddenField.dataset.guglerCaptchaSolving = '1';

        var publicKey = hiddenField.getAttribute('data-public-key') || '';
        window.KROT.getSolution(publicKey)
            .then(function (solution) {
                hiddenField.value = JSON.stringify(solution);
            })
            .catch(function () {
                // Solving failed client-side - submit anyway and let the
                // server-side CaptchaEuMethod::verify() be the real gate.
                hiddenField.value = '';
            })
            .finally(function () {
                hiddenField.dataset.guglerCaptchaSolving = '';
                HTMLFormElement.prototype.submit.call(form);
            });
    });
})();
