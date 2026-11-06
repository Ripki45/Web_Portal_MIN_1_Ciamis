(function ($) {
    ('use strict');
    // Detect mobile device (Do not remove!!!)
    var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Nokia|Opera Mini/i.test(navigator.userAgent) ? true : false;
    if (isMobile) {
        $('body').addClass('is-mobile');
    }
    var tl = gsap.timeline();
    var $pageAppear = $('.appear');
    if ($pageAppear.length) {
        tl.from(
            $pageAppear,
            {
                duration: 2,
                y: 40,
                autoAlpha: 0,
                stagger: 0.3,
                ease: Expo.easeOut,
                clearProps: 'all',
            },
            1.5,
        );
    }
    //PARALLAX ITEM
    $('.parallax-item').wrap('<div class="parallax-item-wrap"></div>');
    const itemWraps = document.querySelectorAll('.parallax-item-wrap');
    const calcValue = (value, inputMax, inputMin, outputMax, outputMin) => {
        const percent = (value - inputMin) / (inputMax - inputMin);
        const output = percent * (outputMax - outputMin) + outputMin;
        return output;
    };
    itemWraps.forEach(function (itemWrap) {
        const item = itemWrap.querySelector('.parallax-item');
        itemWrap.addEventListener('mousemove', function (e) {
            const mouseX = e.clientX;
            const mouseY = e.clientY;
            const rect = itemWrap.getBoundingClientRect();
            const offsetX = rect.left + rect.width / 2;
            const offsetY = rect.top + rect.height / 2;
            const deltaX = mouseX - offsetX;
            const deltaY = mouseY - offsetY;
            const percentX = deltaX / (itemWrap.clientWidth / 2);
            const percentY = deltaY / (itemWrap.clientHeight / 2);
            TweenMax.to(item, 0.5, {
                x: calcValue(percentX, 1, -1, 5, -5),
                y: calcValue(percentY, 1, -1, 5, -5),
                rotationX: calcValue(percentY, 1, -1, 5, -5),
                rotationY: calcValue(percentX, 1, -1, -5, 5),
                ease: Power1.easeOut,
            });
        });
        itemWrap.addEventListener('mouseleave', function (e) {
            TweenMax.to(item, 0.5, {
                x: 0,
                y: 0,
                rotationX: 0,
                rotationY: 0,
                ease: Power1.easeOut,
            });
        });
    });
    //GSAP scrollTrigger
    gsap.registerPlugin(ScrollTrigger);
    let animationRotate = document.querySelectorAll('.animation-rotate');
    animationRotate.forEach((animationRotate) => {
        gsap.to(animationRotate, {
            duration: 2,
            rotation: 360,
            ease: 'linear',
            repeat: -1,
        });
    });
    let scrollRotate = document.querySelectorAll('.scroll-rotate');
    scrollRotate.forEach((scrollRotate) => {
        gsap.to(scrollRotate, {
            scrollTrigger: {
                trigger: scrollRotate,
                scrub: 2,
            },
            rotation: 720,
        });
    });
    // Image parallax
    // ===============
    var imageParallax = document.querySelectorAll('.parallax-image');
    if (imageParallax.length > 0) {
        $('.parallax-image').each(function () {
            // Add wrap <div>.
            $(this).wrap('<div class="parallax-image-wrap"><div class="parallax-image-inner"></div></div>');
            // Add overflow hidden.
            $('.parallax-image-wrap').css({ overflow: 'hidden' });
            var $animImageParallax = $(this);
            var $aipWrap = $animImageParallax.parents('.parallax-image-wrap');
            var $aipInner = $aipWrap.find('.parallax-image-inner');
            let tl_ImageParallax = gsap.timeline({
                scrollTrigger: {
                    trigger: $aipWrap,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true,
                    onEnter: () => animImgParallaxRefresh(),
                },
            });
            tl_ImageParallax.to($animImageParallax, { yPercent: 30, ease: 'none' });
            function animImgParallaxRefresh() {
                tl_ImageParallax.scrollTrigger.refresh();
            }
            // Zoom in
            let tl_aipZoomIn = gsap.timeline({
                scrollTrigger: {
                    trigger: $aipWrap,
                    start: 'top 99%',
                },
            });
            tl_aipZoomIn.from($aipInner, { duration: 1.5, autoAlpha: 0, scale: 1.2, ease: Power2.easeOut, clearProps: 'all' });
        });
    }
})(jQuery);
