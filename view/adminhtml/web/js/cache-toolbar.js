define(['mage/translate'], function ($t) {
    'use strict';

    const COOKIE_NAME     = 'pronko_fast_admin_promo_dismissed';
    const AUTO_DISMISS_MS = 3000;

    const PROMO_MESSAGES = [
        { text: 'Magento admin taking forever? <strong>Fast Admin loads orders in 0.3s.</strong>', utm: 'msg-forever' },
        { text: 'Order grid: 5s → 0.3s. <strong>⚡ That’s Fast Admin.</strong>', utm: 'msg-contrast' },
        { text: 'Still waiting on Magento admin? <strong>Fast Admin was built for speed.</strong>', utm: 'msg-waiting' },
        { text: 'Your team loses hours on slow admin. <strong>⚡ Fast Admin fixes that.</strong>', utm: 'msg-team' },
        { text: 'You cleared cache in 1 click. <strong>Imagine the whole admin this fast.</strong>', utm: 'msg-context' },
    ];
    const selectedPromo = PROMO_MESSAGES[Math.floor(Math.random() * PROMO_MESSAGES.length)];

    const toolbar = document.getElementById('pronko-cache-toolbar');
    if (!toolbar) return {};

    const config = {
        smartClearUrl:   toolbar.dataset.smartClearUrl,
        fullClearUrl:    toolbar.dataset.fullClearUrl,
        shortcutEnabled: toolbar.dataset.shortcutEnabled === '1',
        formKey:         toolbar.dataset.formKey,
    };

    const message    = document.getElementById('pronko-cache-message');
    const icon       = document.getElementById('pronko-cache-icon');
    const spinner    = document.getElementById('pronko-cache-spinner');
    const actions    = document.getElementById('pronko-cache-actions');
    const smartBtn   = document.getElementById('pronko-smart-clear');
    const fullBtn    = document.getElementById('pronko-full-clear');
    const dismissBtn = document.getElementById('pronko-cache-dismiss');
    const promo          = document.getElementById('pronko-cache-promo');
    const promoText      = document.getElementById('pronko-promo-text');
    const promoCta       = document.getElementById('pronko-promo-cta');
    const promoDismissBtn = document.getElementById('pronko-promo-dismiss');

    // Captured once from the server-rendered markup so the SVG lives in one place.
    const defaultIcon = icon ? icon.innerHTML : '';

    let autoDismissTimer = null;
    let smartClearHappened = false;
    let inFlight = false;

    // state: 'hidden' | 'outdated' | 'loading' | 'cleared'
    function setState(state, text) {
        toolbar.dataset.state = state;
        toolbar.classList.remove(
            'pronko-cache-toolbar--hidden',
            'pronko-cache-toolbar--outdated',
            'pronko-cache-toolbar--loading',
            'pronko-cache-toolbar--cleared'
        );

        const isHidden  = state === 'hidden';
        const isCleared = state === 'cleared';
        const isLoading = state === 'loading';

        toolbar.classList.add(isHidden ? 'pronko-cache-toolbar--hidden' : `pronko-cache-toolbar--${state}`);

        if (text !== undefined && message) {
            message.textContent = text;
        }

        if (icon) icon.innerHTML = isCleared ? '&#x2713;' : defaultIcon;
        if (spinner) spinner.style.display = isLoading ? 'inline-block' : 'none';
        if (actions) actions.style.display = (isCleared || isLoading) ? 'none' : 'flex';
        if (dismissBtn) dismissBtn.style.display = state === 'outdated' ? 'block' : 'none';
    }

    function hide() {
        setState('hidden');
    }

    function showCleared(data) {
        setState('cleared', data.message);
        if (autoDismissTimer) clearTimeout(autoDismissTimer);
        autoDismissTimer = setTimeout(hide, AUTO_DISMISS_MS);

        if (!smartClearHappened && promo) {
            smartClearHappened = true;
            showPromoIfNeeded();
        }
    }

    function postClear(url, callback) {
        if (inFlight) return;
        inFlight = true;
        setState('loading', $t('Clearing cache…'));
        const body = new URLSearchParams({ form_key: config.formKey });
        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body,
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showCleared(data);
                    if (typeof callback === 'function') callback(data);
                } else {
                    setState('outdated', data.message || $t('Clear failed — try again.'));
                }
            })
            .catch(() => setState('outdated', $t('Request failed — try again.')))
            .finally(() => { inFlight = false; });
    }

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    }

    function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
    }

    function showPromoIfNeeded() {
        if (!promo) return;
        if (getCookie(COOKIE_NAME)) return;
        if (promoText) promoText.innerHTML = selectedPromo.text;
        if (promoCta) {
            const url = new URL(promoCta.href);
            url.searchParams.set('utm_source', 'cache-toolbar');
            url.searchParams.set('utm_medium', 'banner');
            url.searchParams.set('utm_campaign', 'oss-module');
            url.searchParams.set('utm_content', selectedPromo.utm);
            promoCta.href = url.toString();
        }
        promo.classList.remove('pronko-cache-promo--hidden');
    }

    function dismissPromo() {
        if (!promo) return;
        const days = parseInt(promo.dataset.cookieDays, 10) || 30;
        setCookie(COOKIE_NAME, '1', days);
        promo.classList.add('pronko-cache-promo--hidden');
    }

    smartBtn && smartBtn.addEventListener('click', () => postClear(config.smartClearUrl));
    fullBtn  && fullBtn.addEventListener('click',  () => postClear(config.fullClearUrl));
    dismissBtn && dismissBtn.addEventListener('click', hide);
    promoDismissBtn && promoDismissBtn.addEventListener('click', dismissPromo);

    if (config.shortcutEnabled) {
        document.addEventListener('keydown', e => {
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                postClear(config.smartClearUrl);
            }
        });
    }

    return {};
});
