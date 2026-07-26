/* Işık Çelik — site etkileşimleri (bağımlılıksız) */
(function () {
    'use strict';

    /* Header: scroll ile koyulaşma */
    var header = document.querySelector('.site-header');
    if (header) {
        var onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 40);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* Mobil menü */
    var toggle = document.querySelector('.nav-toggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            document.body.classList.toggle('nav-open');
            toggle.setAttribute('aria-expanded', document.body.classList.contains('nav-open') ? 'true' : 'false');
        });
        document.querySelectorAll('.mobile-nav a').forEach(function (a) {
            a.addEventListener('click', function () {
                document.body.classList.remove('nav-open');
            });
        });
    }

    /* Scroll'da açığa çıkma animasyonları */
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    } else {
        document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
    }

    /* Sayaç animasyonu — orijinal biçimdeki binlik ayırıcıyı korur */
    var animateCounter = function (el) {
        var raw = el.getAttribute('data-value') || el.textContent;
        var match = raw.match(/^([\d.,]+)(.*)$/);
        if (!match) { return; }
        var numStr = match[1];
        var suffix = match[2] || '';
        var grouped = numStr.indexOf('.') !== -1;
        var target = parseInt(numStr.replace(/[.,]/g, ''), 10);
        if (isNaN(target)) { return; }

        var format = function (n) {
            var s = String(n);
            if (grouped) {
                s = s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            return s;
        };
        var dur = 1600;
        var start = null;
        var suffixHtml = suffix ? '<span class="suffix">' + suffix + '</span>' : '';
        var step = function (ts) {
            if (!start) { start = ts; }
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.innerHTML = format(Math.round(target * eased)) + suffixHtml;
            if (p < 1) { requestAnimationFrame(step); }
        };
        requestAnimationFrame(step);
    };

    if ('IntersectionObserver' in window) {
        var cio = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    cio.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        document.querySelectorAll('.stat-value[data-value]').forEach(function (el) { cio.observe(el); });
    }

    /* Ürün galerisi: küçük görsele tıklayınca ana görsel değişir */
    var mainImg = document.getElementById('product-main-img');
    if (mainImg) {
        document.querySelectorAll('.product-thumb').forEach(function (btn) {
            btn.addEventListener('click', function () {
                mainImg.src = btn.getAttribute('data-src');
                document.querySelectorAll('.product-thumb').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
            });
        });
    }

    /* Hero videosu: otomatik oynatma engellenirse posteri göster */
    var heroVideo = document.querySelector('.hero-media video');
    if (heroVideo) {
        var p = heroVideo.play();
        if (p !== undefined) {
            p.catch(function () { heroVideo.style.display = 'none'; });
        }
    }
})();
