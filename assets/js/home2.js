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

        // Videolar kendiliğinden oynamaz; kareyi scroll yönetir
        var videoTargets = [];
        var videosUnlocked = false;
        videos.forEach(function (v, i) {
            v.pause();
            v.removeAttribute('autoplay');
            v.removeAttribute('loop');
            videoTargets[i] = 0;
        });

        // Tarayıcının kare çizmesini garantile: bir kez sessizce oynat-durdur
        var unlockVideos = function () {
            if (videosUnlocked) { return; }
            videosUnlocked = true;
            videos.forEach(function (v) {
                var p = v.play();
                if (p) { p.then(function () { v.pause(); }).catch(function () {}); }
            });
        };
        window.addEventListener('touchstart', unlockVideos, { once: true, passive: true });
        window.addEventListener('wheel', unlockVideos, { once: true, passive: true });

        var setStage = function (idx) {
            if (idx === current) { return; }
            current = idx;
            stages.forEach(function (s, i) { s.classList.toggle('active', i === idx); });
            dots.forEach(function (d, i) { d.classList.toggle('active', i === idx); });
            videos.forEach(function (v, i) { v.classList.toggle('active', i === idx); });
            if (progress) { progress.style.height = ((idx + 1) / count * 100) + '%'; }
        };

        // Her frame'de hedef kareye yumuşakça yaklaş (seek çakışmalarını önler, akıcı sarar)
        gsap.ticker.add(function () {
            if (current < 0) { return; }
            var v = videos[current];
            if (!v || !v.duration || v.seeking) { return; }
            var target = videoTargets[current];
            var diff = target - v.currentTime;
            if (Math.abs(diff) < 0.02) { return; }
            // Uzak sıçramalarda hızlı, yakında yumuşak takip
            v.currentTime = Math.abs(diff) > 1.2 ? target : v.currentTime + diff * 0.35;
        });

        ScrollTrigger.create({
            trigger: '.c-journey-viewport',
            start: 'top top',
            end: '+=' + (count * 90) + '%',
            pin: true,
            scrub: true,
            onEnter: unlockVideos,
            onUpdate: function (self) {
                var pos = self.progress * count;
                var idx = Math.min(count - 1, Math.floor(pos));
                setStage(idx);
                var v = videos[idx];
                if (v && v.duration) {
                    var lp = Math.min(Math.max(pos - idx, 0), 1);
                    videoTargets[idx] = Math.min(v.duration - 0.05, lp * v.duration);
                }
            }
        });
        setStage(0);
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
                scrub: true,
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
