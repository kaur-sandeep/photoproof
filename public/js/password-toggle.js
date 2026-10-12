(function () {
    const eyeIcon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    const eyeOffIcon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18"></path><path d="M10.6 5.1A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a15.8 15.8 0 0 1-3.1 3.8M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10.8 10.8 0 0 0 4.2-.9"></path><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path></svg>';

    function addToggle(input) {
        if (input.dataset.passwordToggle === 'true') return;

        const wrapper = document.createElement('span');
        wrapper.className = 'password-toggle-wrapper';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        input.dataset.passwordToggle = 'true';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'password-toggle-button';
        button.setAttribute('aria-label', 'Show password');
        button.setAttribute('aria-pressed', 'false');
        button.title = 'Show password';
        button.innerHTML = eyeIcon;
        button.addEventListener('click', function () {
            const showing = input.type === 'password';
            input.type = showing ? 'text' : 'password';
            button.innerHTML = showing ? eyeOffIcon : eyeIcon;
            button.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');
            button.setAttribute('aria-pressed', String(showing));
            button.title = showing ? 'Hide password' : 'Show password';
            input.focus();
        });
        wrapper.appendChild(button);
    }

    function initialize(root) {
        if (root.matches && root.matches('input[type="password"]')) addToggle(root);
        root.querySelectorAll('input[type="password"]').forEach(addToggle);
    }

    const style = document.createElement('style');
    style.textContent = '.password-toggle-wrapper{position:relative;display:block;width:100%;}.password-toggle-wrapper>input{padding-right:2.75rem!important;}.password-toggle-button{position:absolute;top:50%;right:.65rem;z-index:5;display:inline-flex;align-items:center;justify-content:center;width:1.75rem;height:1.75rem;padding:0;border:0;border-radius:4px;background:transparent;color:#64748b;cursor:pointer;transform:translateY(-50%);}.password-toggle-button:hover{color:#1d4ed8;}.password-toggle-button:focus-visible{outline:2px solid #2563eb;outline-offset:2px;}.password-toggle-button svg{width:1.15rem;height:1.15rem;fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.8;}';
    document.head.appendChild(style);

    initialize(document);
    new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType === Node.ELEMENT_NODE) initialize(node);
            });
        });
    }).observe(document.documentElement, { childList: true, subtree: true });
})();
