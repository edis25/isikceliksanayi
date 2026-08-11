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
        // Journey: ilk video oynasın, statik düzende hepsi görünür kalır
        document.querySelectorAll('.c-stage-video').forEach(function (v, i) {
            v.classList.add('active');
            var p = v.play();
            if (p) { p.catch(function () {}); }
        });
        document.querySelectorAll('.c-stage').forEach(function (s) { s.classList.add('active'); });
    }

    /* Ürün vitrini: ok tuşları — pinned modda sayfayı, statik modda bandı kaydırır */
    var shelfTrack = document.querySelector('.c-track');
    if (shelfTrack) {
        var shelfStep = function () {
            var card = shelfTrack.querySelector('.c-product');
            return card ? (card.getBoundingClientRect().width + 22) * 2 : 600;
        };
        var shelfMove = function (dir) {
            if (docEl.classList.contains('no-motion')) {
                shelfTrack.scrollBy({ left: dir * shelfStep(), behavior: 'smooth' });
            } else {
                // Pin sırasında scroll, banda 1:1 aktarılır
                window.scrollBy({ top: dir * shelfStep(), behavior: 'smooth' });
            }
        };
        var prevBtn = document.querySelector('[data-shelf-prev]');
        var nextBtn = document.querySelector('[data-shelf-next]');
        if (prevBtn) { prevBtn.addEventListener('click', function () { shelfMove(-1); }); }
        if (nextBtn) { nextBtn.addEventListener('click', function () { shelfMove(1); }); }
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

    /* ---------- S2 Üretim yolculuğu: pinned sahneler + scroll'a bağlı video ---------- */
    var journey = document.querySelector('.c-journey');
    if (journey) {
        var stages = journey.querySelectorAll('.c-stage');
        var videos = journey.querySelectorAll('.c-stage-video');
        var dots = journey.querySelectorAll('.c-stage-dots .dot');
        var progress = journey.querySelector('.c-journey-progress');
        var count = stages.length;
        var current = -1;

        // Videolar kendiliğinden oynamaz; kareyi scroll yönetir.
        // Klipler belleğe (blob) alınır: Range desteği olmayan sunucularda bile video
        // tam "seekable" olur ve kare kare ileri/geri sarılabilir.
        var videoFracs = [];
        videos.forEach(function (v, i) {
            v.pause();
            v.removeAttribute('autoplay');
            v.removeAttribute('loop');
            videoFracs[i] = 0;
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
        });

        var setStage = function (idx) {
            if (idx === current) { return; }
            current = idx;
            stages.forEach(function (s, i) { s.classList.toggle('active', i === idx); });
            dots.forEach(function (d, i) { d.classList.toggle('active', i === idx); });
            videos.forEach(function (v, i) { v.classList.toggle('active', i === idx); });
            if (progress) { progress.style.height = ((idx + 1) / count * 100) + '%'; }
        };

        // Her frame'de hedef kareye yumuşakça yaklaş (seek çakışmasız, akıcı ileri/geri sarma)
        gsap.ticker.add(function () {
            if (current < 0) { return; }
            var v = videos[current];
            if (!v || !v.duration || v.readyState < 2 || v.seeking) { return; }
            var target = Math.min(v.duration - 0.05, videoFracs[current] * v.duration);
            var diff = target - v.currentTime;
            if (Math.abs(diff) < 0.02) { return; }
            // Ağır, sinematik takip; yalnızca çok uzak sıçramalarda anında atla
            v.currentTime = Math.abs(diff) > 2.2 ? target : v.currentTime + diff * 0.16;
        });

        ScrollTrigger.create({
            trigger: '.c-journey-viewport',
            start: 'top top',
            end: '+=' + (count * 90) + '%',
            pin: true,
            scrub: 1.2,
            onUpdate: function (self) {
                var pos = self.progress * count;
                var idx = Math.min(count - 1, Math.floor(pos));
                setStage(idx);
                videoFracs[idx] = Math.min(Math.max(pos - idx, 0), 1);
            }
        });
        setStage(0);
    }


    /* S3 ürün vitrini: scroll kilidi YOK — ok tuşlarıyla gezinme yukarıda bağlandı */

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
