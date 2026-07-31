/* Işık Çelik — Tarihçe: scroll ile oynayan film (pinned scrollytelling).
   GSAP yoksa veya reduced-motion açıksa statik akışa düşer. */
(function () {
    'use strict';

    var stage = document.querySelector('.tlx-stage');
    if (!stage) { return; }

    var docEl = document.documentElement;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var shotMode = window.location.search.indexOf('_shot') !== -1;

    var layers = stage.querySelectorAll('.tlx-layer');
    var frames = stage.querySelectorAll('.tlx-frame');
    var ticks = stage.querySelectorAll('.tlx-ticker .tick');
    var barFill = stage.querySelector('.tlx-bar-fill');
    var count = frames.length;

    function enableStatic() {
        docEl.classList.add('no-motion');
        layers.forEach(function (l) {
            l.classList.add('active');
            if (l.tagName === 'VIDEO') {
                var p = l.play();
                if (p) { p.catch(function () {}); }
            }
        });
        frames.forEach(function (f) { f.classList.add('active'); });
    }

    if (reduced || shotMode || typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
        enableStatic();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);
    ScrollTrigger.config({ ignoreMobileResize: true });

    var current = -1;
    var setStage = function (idx) {
        if (idx === current) { return; }
        current = idx;
        frames.forEach(function (f, i) { f.classList.toggle('active', i === idx); });
        ticks.forEach(function (t, i) { t.classList.toggle('active', i === idx); });
        layers.forEach(function (l, i) {
            var on = i === idx;
            l.classList.toggle('active', on);
            if (l.tagName === 'VIDEO') {
                if (on) {
                    var p = l.play();
                    if (p) { p.catch(function () {}); }
                } else {
                    l.pause();
                }
            }
        });

        /* Yıl geçişi: derinlikten blur ile vurup oturur, ardından metal parlaması süpürülür */
        var frame = frames[idx];
        var year = frame.querySelector('.tlx-year');
        var textEls = frame.querySelectorAll('.tlx-text h3, .tlx-text p');
        if (year) {
            gsap.killTweensOf([year].concat(Array.prototype.slice.call(textEls)));
            var tl = gsap.timeline();
            tl.fromTo(year,
                { yPercent: 42, autoAlpha: 0, scale: 1.18, filter: 'blur(14px)', backgroundPosition: '130% 0, 0 0' },
                { yPercent: 0, autoAlpha: 1, scale: 1, filter: 'blur(0px)', duration: .75, ease: 'back.out(1.5)' }
            );
            // Parlama şeridi rakamların üzerinden geçer
            tl.to(year, {
                backgroundPosition: '-30% 0, 0 0',
                duration: .9,
                ease: 'power2.inOut'
            }, '-=0.15');
            tl.fromTo(textEls,
                { x: -34, autoAlpha: 0 },
                { x: 0, autoAlpha: 1, duration: .55, ease: 'power3.out', stagger: .09 },
                '-=0.95');
        }
    };

    var st = ScrollTrigger.create({
        trigger: stage,
        start: 'top top',
        end: '+=' + (count * 80) + '%',
        pin: true,
        scrub: true,
        onUpdate: function (self) {
            setStage(Math.min(count - 1, Math.floor(self.progress * count)));
            if (barFill) { barFill.style.width = (self.progress * 100) + '%'; }
        }
    });
    setStage(0);

    /* Yıl şeridinden bölüme atlama */
    ticks.forEach(function (tick, i) {
        tick.addEventListener('click', function () {
            var target = st.start + (st.end - st.start) * ((i + 0.5) / count);
            window.scrollTo({ top: target, behavior: 'smooth' });
        });
    });
})();
