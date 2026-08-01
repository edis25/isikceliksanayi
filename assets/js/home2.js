/* Işık Çelik — kreatif ana sayfa motion katmanı (GSAP ScrollTrigger).
   GSAP yüklenemezse, mobilde veya reduced-motion'da sayfa statik ve eksiksiz çalışır. */
(function () {
    'use strict';

    var docEl = document.documentElement;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Split: başlık kelimelerini sarmala (motion açık/kapalı fark etmeksizin güvenli) */
    var splitTargets = document.querySelectorAll('[data-split]');

    function enableStatic() {
        docEl.classList.add('no-motion');
        // Üretim adımları: videolar kendiliğinden dönsün
        document.querySelectorAll('.c-proc-step video').forEach(function (v) {
            v.loop = true;
            var p = v.play();
            if (p) { p.catch(function () {}); }
        });
    }

    var shotMode = window.location.search.indexOf('_shot') !== -1;
    if (reduced || shotMode || typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
        enableStatic();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);
    // Mobilde adres çubuğu gizlenirken pin'lerin zıplamasını önle
    ScrollTrigger.config({ ignoreMobileResize: true });

    /* ---------- Kinetik tipografi ---------- */
    splitTargets.forEach(function (el) {
        var words = el.textContent.trim().split(/\s+/);
        el.innerHTML = words.map(function (w) {
            return '<span class="w"><i>' + w + '</i></span>';
        }).join(' ');
        gsap.to(el.querySelectorAll('.w > i'), {
            y: 0,
            duration: 1,
            ease: 'power4.out',
            stagger: 0.07,
            scrollTrigger: { trigger: el, start: 'top 85%' }
        });
    });

    /* ---------- S1 Hero: parallax ---------- */
    var heroVideo = document.querySelector('.c-hero-media video');
    if (heroVideo) {
        gsap.to(heroVideo, {
            scale: 1.15,
            ease: 'none',
            scrollTrigger: { trigger: '.c-hero', start: 'top top', end: 'bottom top', scrub: true }
        });
        gsap.to('.c-hero-content', {
            y: -80,
            opacity: 0.25,
            ease: 'none',
            scrollTrigger: { trigger: '.c-hero', start: '40% top', end: 'bottom top', scrub: true }
        });
    }

    /* ---------- S2 Üretim yolculuğu: alt alta duotone adımlar, video scroll'a bağlı ---------- */
    var procSteps = document.querySelectorAll('.c-proc-step');
    if (procSteps.length) {
        var procVideos = [];
        var procFracs = [];

        procSteps.forEach(function (step, i) {
            var v = step.querySelector('video');
            procVideos[i] = v;
            procFracs[i] = 0;

            if (v) {
                v.pause();
                v.removeAttribute('autoplay');
                v.removeAttribute('loop');
                // Blob'a al: Range desteği olmayan sunucularda da tam seekable olsun
                var srcEl = v.querySelector('source');
                var srcUrl = (srcEl && srcEl.src) || v.currentSrc || v.src;
                if (srcUrl) {
                    fetch(srcUrl)
                        .then(function (r) { return r.blob(); })
                        .then(function (b) {
                            v.src = URL.createObjectURL(b);
                            v.preload = 'auto';
                            v.load();
                        })
                        .catch(function () {});
                }
            }

            // Adım görünür alandan geçerken video scroll'a bağlı ileri/geri sarar
            ScrollTrigger.create({
                trigger: step,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
                onUpdate: function (self) {
                    procFracs[i] = self.progress;
                }
            });

            // İçerik girişleri: numara + başlık + metin süzülerek gelir
            gsap.from(step.querySelectorAll('.c-proc-no, h3, .c-proc-big, p'), {
                y: 46,
                autoAlpha: 0,
                duration: .9,
                ease: 'power3.out',
                stagger: .1,
                scrollTrigger: { trigger: step, start: 'top 70%' }
            });
        });

        // Her frame'de görünür videoları hedef kareye yumuşakça taşı
        gsap.ticker.add(function () {
            procVideos.forEach(function (v, i) {
                if (!v || !v.duration || v.readyState < 2 || v.seeking) { return; }
                var target = Math.min(v.duration - 0.05, procFracs[i] * v.duration);
                var diff = target - v.currentTime;
                if (Math.abs(diff) < 0.02) { return; }
                v.currentTime = Math.abs(diff) > 2.2 ? target : v.currentTime + diff * 0.16;
            });
        });
    }

    /* ---------- S3 Ürün vitrini: yatay scroll ---------- */
    var track = document.querySelector('.c-track');
    if (track) {
        var getDistance = function () {
            return Math.max(0, track.scrollWidth - window.innerWidth);
        };
        gsap.to(track, {
            x: function () { return -getDistance(); },
            ease: 'none',
            scrollTrigger: {
                trigger: '.c-shelf-viewport',
                start: 'top top',
                end: function () { return '+=' + getDistance(); },
                pin: true,
                scrub: 1,
                invalidateOnRefresh: true
            }
        });
    }

    /* ---------- S4 Enerji: hafif parallax ---------- */
    var energyVideo = document.querySelector('.c-energy-media video');
    if (energyVideo) {
        gsap.fromTo(energyVideo, { scale: 1.12 }, {
            scale: 1,
            ease: 'none',
            scrollTrigger: { trigger: '.c-energy', start: 'top bottom', end: 'bottom top', scrub: true }
        });
    }

    /* ---------- S5 Global: hüzme çizimi ---------- */
    document.querySelectorAll('.c-arc').forEach(function (path, i) {
        var len = path.getTotalLength();
        path.style.strokeDasharray = len;
        path.style.strokeDashoffset = len;
        gsap.to(path, {
            strokeDashoffset: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: '.c-global-viz',
                start: 'top 80%',
                end: 'top 25%',
                scrub: true
            }
        });
    });
    gsap.from('.c-dest', {
        scale: 0,
        transformOrigin: 'center',
        stagger: 0.15,
        duration: 0.5,
        ease: 'back.out(2)',
        scrollTrigger: { trigger: '.c-global-viz', start: 'top 45%' }
    });
    gsap.from('.c-region:not(.c-region-origin)', {
        y: 14,
        opacity: 0,
        stagger: 0.12,
        duration: 0.5,
        scrollTrigger: { trigger: '.c-global-viz', start: 'top 45%' }
    });

    /* ---------- S6: haber kartları + magnetic buton ---------- */
    gsap.from('.c-news-card', {
        y: 50,
        opacity: 0,
        stagger: 0.12,
        duration: 0.7,
        ease: 'power3.out',
        scrollTrigger: { trigger: '.c-news', start: 'top 70%' }
    });

    var magnet = document.querySelector('.c-magnetic');
    if (magnet) {
        var strength = 28;
        magnet.addEventListener('mousemove', function (e) {
            var r = magnet.getBoundingClientRect();
            var x = (e.clientX - r.left - r.width / 2) / (r.width / 2);
            var y = (e.clientY - r.top - r.height / 2) / (r.height / 2);
            gsap.to(magnet, { x: x * strength, y: y * strength, duration: 0.3 });
        });
        magnet.addEventListener('mouseleave', function () {
            gsap.to(magnet, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.4)' });
        });
    }
})();
