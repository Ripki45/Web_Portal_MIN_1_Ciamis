(function ($) {
    ('use strict');
    /*=============================================
    =              Preloader       =
    =============================================*/
    function preloader() {
        $('#preloader').fadeOut();
    }
    /*=============================================
    =     Offcanvas Menu      =
    =============================================*/
    function offcanvasMenu() {
        $('.menu-tigger').on('click', function () {
            $('.offCanvas__info, .offCanvas__overly').addClass('active');
            return false;
        });
        $('.menu-close, .offCanvas__overly').on('click', function () {
            $('.offCanvas__info, .offCanvas__overly').removeClass('active');
        });
    }
    /*=============================================
    =          Data Background      =
    =============================================*/
    function dataBackground() {
        $('[data-background]').each(function () {
            $(this).css('background-image', 'url(' + $(this).attr('data-background') + ')');
        });
    }
    /*=============================================
    =          Multi-line Underline Effect      =
    =============================================*/
    function initMultilineUnderline() {
        const underlineElements = document.querySelectorAll('.hover-underline h1, .hover-underline h2, .hover-underline h3, .hover-underline h4, .hover-underline h5, .hover-underline h6');

        underlineElements.forEach((element) => {
            // Store original text
            const originalText = element.textContent;

            // Create temporary element to measure text width
            const tempElement = document.createElement('span');
            tempElement.style.visibility = 'hidden';
            tempElement.style.position = 'absolute';
            tempElement.style.whiteSpace = 'nowrap';
            tempElement.style.fontSize = getComputedStyle(element).fontSize;
            tempElement.style.fontFamily = getComputedStyle(element).fontFamily;
            tempElement.style.fontWeight = getComputedStyle(element).fontWeight;
            document.body.appendChild(tempElement);

            // Split text into words
            const words = originalText.split(' ');
            const lines = [];
            let currentLine = '';

            // Get container width
            const containerWidth = element.offsetWidth;

            words.forEach((word) => {
                const testLine = currentLine + (currentLine ? ' ' : '') + word;
                tempElement.textContent = testLine;

                if (tempElement.offsetWidth > containerWidth && currentLine) {
                    lines.push(currentLine);
                    currentLine = word;
                } else {
                    currentLine = testLine;
                }
            });

            if (currentLine) {
                lines.push(currentLine);
            }

            // Remove temporary element
            document.body.removeChild(tempElement);

            // Create new HTML with line-wrapper for each line
            if (lines.length > 1) {
                const wrappedHTML = lines.map((line) => `<span class="line-wrapper">${line}</span>`).join('<br>');

                element.innerHTML = wrappedHTML;
            } else {
                // If only 1 line, still wrap for consistent effect
                element.innerHTML = `<span class="line-wrapper">${originalText}</span>`;
            }
        });
    }

    /*=============================================
    =    		Odometer counterup	      =
    =============================================*/
    function odometerCounter() {
        if ($('.odometer').length > 0) {
            $('.odometer').each(function () {
                var $this = $(this);
                var countNumber = $this.attr('data-count');

                // Initialize odometer with value 0
                $this.html('0');

                // Use appear to trigger animation when element enters viewport
                $this.appear(
                    function () {
                        var $element = $(this);

                        // Check if already animated to avoid triggering multiple times
                        if (!$element.hasClass('odometer-animated')) {
                            $element.addClass('odometer-animated');

                            // Add small delay to make animation look more natural
                            setTimeout(function () {
                                $element.html(countNumber);
                            }, 100);
                        }
                    },
                    {
                        accY: -50, // Trigger when element is 50px away from viewport
                    },
                );
            });
        }
    }
    /*=============================================
    =    		Magnific Popup		      =
    =============================================*/
    function magnificPopup() {
        $('.popup-image').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom',
            gallery: {
                enabled: true,
            },
        });
        /* magnificPopup video view */
        $('.popup-video').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-with-zoom',
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out',
                opener: function (openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                },
            },
        });
    }

    /*=============================================
    =           Go to top       =
    =============================================*/
    function progressPageLoad() {
        var progressWrap = document.querySelector('.btn-scroll-top');
        if (progressWrap != null) {
            var progressPath = document.querySelector('.btn-scroll-top path');
            var pathLength = progressPath.getTotalLength();
            var offset = 50;
            progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
            progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
            progressPath.style.strokeDashoffset = pathLength;
            progressPath.getBoundingClientRect();
            progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
            window.addEventListener('scroll', function (event) {
                var scroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var progress = pathLength - (scroll * pathLength) / height;
                progressPath.style.strokeDashoffset = progress;
                var scrollElementPos = document.body.scrollTop || document.documentElement.scrollTop;
                if (scrollElementPos >= offset) {
                    progressWrap.classList.add('active-progress');
                } else {
                    progressWrap.classList.remove('active-progress');
                }
            });
            progressWrap.addEventListener('click', function (e) {
                e.preventDefault();
                window.scroll({
                    top: 0,
                    left: 0,
                    behavior: 'smooth',
                });
            });
        }
    }
    /*=============================================
    =           Aos Active       =
    =============================================*/
    function aosAnimation() {
        // Check if AOS is loaded
        if (typeof AOS === 'undefined') {
            console.error('AOS library not loaded');
            return;
        }

        console.log('Initializing AOS...');

        AOS.init({
            duration: 1000,
            mirror: true,
            once: true,
            disable: false, // Enable AOS on all devices
            startEvent: 'DOMContentLoaded', // Start earlier
            debounceDelay: 50,
            throttleDelay: 99,
            offset: 120,
        });

        console.log('AOS initialized successfully');

        // Refresh AOS to ensure it detects elements
        setTimeout(function () {
            AOS.refresh();
            console.log('AOS refreshed');
        }, 100);
    }
    /*=============================================
    =    		 Wow Active  	         =
    =============================================*/
    function wowAnimation() {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: true,
            live: true,
            scrollContainer: null,
        });
        wow.init();
    }
    /*=============================================
    =    		Carousel Ticker		      =
    =============================================*/
    function carauselScroll() {
        $('.carouselTicker-left').each(function () {
            $(this).carouselTicker({
                direction: 'prev',
                speed: 1,
                delay: 40,
            });
        });
        $('.carouselTicker-right').each(function () {
            $(this).carouselTicker({
                direction: 'next',
                speed: 1,
                delay: 40,
            });
        });
    }

    // link hover effect 1
    function initLinkEffect1() {
        // Find all <a> elements with class .link-effect-1
        const linkElements = document.querySelectorAll('a.link-effect-1');

        linkElements.forEach(function (linkElement) {
            // Check if already processed to avoid duplicates
            if (linkElement.querySelector('span.text-2')) {
                return;
            }

            // Find the first <span> element inside the <a> tag
            const firstSpan = linkElement.querySelector('span');

            if (firstSpan) {
                // Add class "text-1" to the first <span> element
                firstSpan.classList.add('text-1');

                // Get the content of the first <span> element
                const spanContent = firstSpan.textContent;

                // Create a new <span> element with class "text-2"
                const secondSpan = document.createElement('span');
                secondSpan.className = 'text-2';
                secondSpan.textContent = spanContent;

                // Append the new <span> element to the <a> tag
                linkElement.appendChild(secondSpan);
            }
        });
    }
    // button hover effect 1
    function initButtonEffect3() {
        const buttons = document.querySelectorAll('.button-effect-1');

        buttons.forEach((button) => {
            // Get the original text content
            const originalText = button.textContent.trim();

            // Create the HTML structure with wrapper span and two text spans
            const textStructure = `
                <span class="btn-text">
                    <span class="btn-text-1">${originalText}</span>
                    <span class="btn-text-2">${originalText}</span>
                </span>
            `;

            // Replace button content with the new structure
            button.innerHTML = textStructure;
        });
    }

    /*=============================================
    =          Sidebar Menu Toggle Functionality   =
    =============================================*/
    function initSidebarMenu() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebarLeft = document.querySelector('.sidebar-left');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        const closeSidebar = document.querySelector('.close-sidebar');

        // Add event listener for navbar toggler if it exists
        if (navbarToggler && sidebarLeft && sidebarOverlay) {
            navbarToggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarLeft.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        // Add event listener for overlay if it exists
        if (sidebarOverlay && sidebarLeft) {
            sidebarOverlay.addEventListener('click', function () {
                sidebarLeft.classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        // Add event listener for close button if it exists
        if (closeSidebar && sidebarLeft && sidebarOverlay) {
            closeSidebar.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarLeft.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        // Toggle submenu when clicking on menu items with children
        document.querySelectorAll('.sidebar-nav .collapse-toggle').forEach((toggle) => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                const parentItem = this.closest('.nav-item');
                const submenu = this.nextElementSibling;

                // Toggle active class on parent item
                parentItem.classList.toggle('active');

                // Toggle the submenu
                if (submenu && submenu.classList.contains('collapse-menu')) {
                    if (submenu.style.maxHeight) {
                        submenu.style.maxHeight = null;
                    } else {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    }
                }
            });
        });

        // Toggle submenu for items with has-child class
        document.querySelectorAll('.sidebar-nav .has-child').forEach((childToggle) => {
            childToggle.addEventListener('click', function (e) {
                e.preventDefault();
                const parentItem = this.closest('.nav-item-has-child');
                const submenu = this.nextElementSibling;

                // Toggle active class on parent item
                parentItem.classList.toggle('active');

                // Toggle the submenu
                if (submenu && submenu.classList.contains('sub-menu')) {
                    if (submenu.style.maxHeight) {
                        submenu.style.maxHeight = null;
                    } else {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    }
                }
            });
        });

        // Close sidebar when clicking on close button
        document.querySelectorAll('.close-popup').forEach((closeBtn) => {
            closeBtn.addEventListener('click', function () {
                document.querySelector('.sidebar-left').classList.remove('active');
                document.querySelector('.popup-search-overlay').classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
    /*=============================================
    =          Tab Functionality         =
    =============================================*/
    function initTabs() {
        const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"], [data-toggle="tab"]');

        if (!tabLinks.length) return;

        // Function to show tab
        function showTab(tabLink) {
            if (!tabLink || !tabLink.getAttribute('href')) return;

            const tabId = tabLink.getAttribute('href');
            const tabPane = document.querySelector(tabId);

            if (!tabPane) return;

            // Hide all tab panes and deactivate all tab links
            document.querySelectorAll('.tab-pane').forEach((pane) => {
                pane.classList.remove('show', 'active');
            });

            tabLinks.forEach((link) => {
                link.classList.remove('active');
                link.setAttribute('aria-selected', 'false');
            });

            // Show the selected tab pane and activate the tab link
            tabLink.classList.add('active');
            tabLink.setAttribute('aria-selected', 'true');
            tabPane.classList.add('show', 'active');

            // Trigger a custom event in case other scripts need to know about tab changes
            const event = new Event('shown.bs.tab');
            tabLink.dispatchEvent(event);
        }

        // Handle click events
        tabLinks.forEach((tabLink) => {
            tabLink.addEventListener('click', function (e) {
                e.preventDefault();
                showTab(this);
            });

            // Set initial ARIA attributes
            tabLink.setAttribute('role', 'tab');
            tabLink.setAttribute('aria-selected', 'false');

            // Set up keyboard navigation
            tabLink.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    showTab(this);
                } else if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const tabs = Array.from(tabLinks);
                    const currentIndex = tabs.indexOf(this);
                    let nextIndex = e.key === 'ArrowRight' ? currentIndex + 1 : currentIndex - 1;

                    // Loop around if at the start/end
                    if (nextIndex >= tabs.length) nextIndex = 0;
                    if (nextIndex < 0) nextIndex = tabs.length - 1;

                    tabs[nextIndex].focus();
                }
            });
        });

        // Show first tab by default if none is active
        const activeTab = document.querySelector('.nav-link.active, .nav-tabs .active');
        if (activeTab) {
            showTab(activeTab);
        } else if (tabLinks.length > 0) {
            showTab(tabLinks[0]);
        }
    }

    /*=============================================
    =          View Mode Switching Functionality         =
    =============================================*/
    function initViewModeSwitching() {
        const gridViewBtn = document.querySelector('.btn-grid-view');
        const listViewBtn = document.querySelector('.btn-list-view');
        const gridViewContent = document.querySelector('.grid-view');
        const listViewContent = document.querySelector('.list-view');

        if (!gridViewBtn || !listViewBtn || !gridViewContent || !listViewContent) {
            return;
        }

        // Function to switch to grid view
        function switchToGridView() {
            // Update button states
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');

            // Update content visibility using Bootstrap classes
            gridViewContent.classList.add('d-flex');
            gridViewContent.classList.remove('d-none');
            listViewContent.classList.add('d-none');
            listViewContent.classList.remove('d-flex');
        }

        // Function to switch to list view
        function switchToListView() {
            // Update button states
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');

            // Update content visibility using Bootstrap classes
            listViewContent.classList.add('d-flex');
            listViewContent.classList.remove('d-none');
            gridViewContent.classList.add('d-none');
            gridViewContent.classList.remove('d-flex');
        }

        // Add click event listeners
        gridViewBtn.addEventListener('click', function (e) {
            e.preventDefault();
            switchToGridView();
        });

        listViewBtn.addEventListener('click', function (e) {
            e.preventDefault();
            switchToListView();
        });

        // Initialize with grid view as default (already set in HTML)
        // No need to call switchToGridView() since HTML already has the correct initial state
    }

    /*=============================================
    =          Popup Search Functionality         =
    =============================================*/
    function initPopupSearch() {
        const searchBtn = document.querySelector('.search-btn');
        const popupSearch = document.querySelector('.popup-search');
        const closeBtn = document.querySelector('.close-popup');
        const popupOverlay = document.querySelector('.popup-search-overlay');

        function showPopup() {
            document.body.style.overflow = 'hidden';
            popupSearch.classList.add('show');
            popupOverlay.classList.add('active');
            // Focus on search input when popup opens
            const searchInput = popupSearch.querySelector('input[type="text"]');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        }

        function hidePopup() {
            document.body.style.overflow = '';
            popupSearch.classList.remove('show');
            popupOverlay.classList.remove('active');
        }

        if (searchBtn && popupSearch && popupOverlay) {
            // Show popup when search button is clicked
            searchBtn.addEventListener('click', function (e) {
                e.preventDefault();
                showPopup();
            });
        }

        if (closeBtn && popupSearch && popupOverlay) {
            // Hide popup when close button is clicked
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                hidePopup();
            });
        }

        // Close popup when clicking on overlay
        if (popupOverlay) {
            popupOverlay.addEventListener('click', function () {
                hidePopup();
            });
        }

        // Close popup when clicking outside of the popup content
        if (popupSearch) {
            popupSearch.addEventListener('click', function (e) {
                if (e.target === popupSearch) {
                    hidePopup();
                }
            });
        }
    }

    function customSwiper() {
        const swiperTopbar = new Swiper('.swiper-topbar', {
            slidesPerView: 1,
            spaceBetween: 0,
            direction: 'vertical',
            loop: true,
            autoplay: {
                delay: 5000,
            },
            navigation: {
                nextEl: '.switch-btn-next',
                prevEl: '.switch-btn-prev',
            },
        });
        const swiperCardHero = new Swiper('.swiper-card-hero', {
            slidesPerView: 3,
            spaceBetween: 30,
            slidesPerGroup: 1,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });
        const slider2 = new Swiper('.slider-2', {
            slidesPerView: 3,
            spaceBetween: 27,
            slidesPerGroup: 1,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });

        const swiperPopupSearch = new Swiper('.swiper-popup-search', {
            slidesPerView: 3,
            spaceBetween: 15,
            slidesPerGroup: 1,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });

        var galleryThumbs = new Swiper('.gallery-thumbs', {
            spaceBetween: 16,
            slidesPerView: 3,
            freeMode: true,
            loop: true,
            direction: 'vertical',
            // watchSlidesProgress: true,
        });

        var galleryTop = new Swiper('.gallery-left', {
            spaceBetween: 10,
            slidesPerView: 1,
            loop: true,
            thumbs: {
                swiper: galleryThumbs,
            },
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-btn-next',
                prevEl: '.swiper-btn-prev',
            },
            on: {
                init: function () {
                    // Set initial background
                    const activeSlide = this.slides[this.activeIndex];
                    const bgUrl = activeSlide.getAttribute('data-bg');
                    if (bgUrl) {
                        document.getElementById('gallery-background').style.backgroundImage = `url(${bgUrl})`;
                    }
                },
                slideChange: function () {
                    // Update background when slide changes
                    const activeSlide = this.slides[this.activeIndex];
                    const bgUrl = activeSlide.getAttribute('data-bg');
                    if (bgUrl) {
                        const bgElement = document.getElementById('gallery-background');
                        bgElement.style.transition = 'background-image 0.8s ease-in-out';
                        bgElement.style.backgroundImage = `url(${bgUrl})`;
                    }
                },
            },
        });

        const slider1 = new Swiper('.slider-1', {
            slidesPerView: 1,
            spaceBetween: 30,
            slidesPerGroup: 1,
            effect: 'fade',
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 2000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
        const slider4 = new Swiper('.slider-4', {
            slidesPerView: 4,
            spaceBetween: 30,
            slidesPerGroup: 1,
            centeredSlides: false,
            loop: true,
            // autoplay: {
            //     delay: 5000,
            // },
            breakpoints: {
                1200: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
            navigation: {
                nextEl: '.swiper-btn-next',
                prevEl: '.swiper-btn-prev',
            },
        });
        const swiperInsta = new Swiper('.swiper-insta', {
            slidesPerView: 6,
            spaceBetween: 18,
            slidesPerGroup: 1,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 4000,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 9,
                    spaceBetween: 18,
                },
                992: {
                    slidesPerView: 6,
                    spaceBetween: 18,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 18,
                },
                576: {
                    slidesPerView: 3,
                    spaceBetween: 18,
                },
                0: {
                    slidesPerView: 2,
                    spaceBetween: 18,
                },
            },
        });
        const sliderSec3Home2 = new Swiper('.swiper-sec-3-home-2', {
            slidesPerView: 2,
            spaceBetween: 20,
            slidesPerGroup: 1,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 5000,
                reverseDirection: true,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 3.5,
                },
                992: {
                    slidesPerView: 2.5,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
            navigation: {
                nextEl: '.swiper-btn-next',
                prevEl: '.swiper-btn-prev',
            },
            on: {
                afterInit: function () {
                    // set padding left slide
                    var leftPadding = 0;
                    var swipperRoot = $('.swipper-root');
                    if (swipperRoot.length > 0) {
                        leftPadding = swipperRoot.offset().left;
                    }
                    if ($('.box-swiper-padding').length > 0) {
                        $('.box-swiper-padding').css('padding-left', leftPadding + 'px');
                    }
                },
            },
        });
    }
    /*=============================================
    =          Text Animation Styles          =
    =============================================*/
    function initTextAnimations() {
        // Ensure GSAP and ScrollTrigger are available
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('GSAP or ScrollTrigger not loaded. Text animations will be skipped.');
            return;
        }

        // Register ScrollTrigger plugin
        gsap.registerPlugin(ScrollTrigger);

        // Text Animation Style 1 - Word-based animation
        if ($('.text-anime-style-1').length) {
            let staggerAmount = 0.05,
                delayValue = 0.3,
                animatedTextElements = document.querySelectorAll('.text-anime-style-1');

            animatedTextElements.forEach((element) => {
                // Check if element is already processed
                if (element.dataset.textAnimated === 'true') {
                    return;
                }

                let animationSplitText = new SplitText(element, { type: 'chars, words' });

                // Set initial state
                gsap.set(animationSplitText.words, {
                    x: 30,
                    autoAlpha: 0,
                });

                // Create animation
                gsap.to(animationSplitText.words, {
                    duration: 1,
                    delay: delayValue,
                    x: 0,
                    autoAlpha: 1,
                    stagger: staggerAmount,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 85%',
                        end: 'bottom 15%',
                        toggleActions: 'play none none reverse',
                        refreshPriority: -1,
                    },
                });

                // Mark as processed
                element.dataset.textAnimated = 'true';
            });
        }

        // Text Animation Style 2 - Character-based animation
        if ($('.text-anime-style-2').length) {
            let staggerAmount = 0.03,
                translateXValue = 20,
                delayValue = 0.1,
                easeType = 'power2.out',
                animatedTextElements = document.querySelectorAll('.text-anime-style-2');

            animatedTextElements.forEach((element) => {
                // Check if element is already processed
                if (element.dataset.textAnimated === 'true') {
                    return;
                }

                let animationSplitText = new SplitText(element, { type: 'chars, words' });

                // Set initial state
                gsap.set(animationSplitText.chars, {
                    x: translateXValue,
                    autoAlpha: 0,
                });

                // Create animation
                gsap.to(animationSplitText.chars, {
                    duration: 0.4,
                    delay: delayValue,
                    x: 0,
                    autoAlpha: 1,
                    stagger: staggerAmount,
                    ease: easeType,
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 85%',
                        end: 'bottom 15%',
                        toggleActions: 'play none none reverse',
                        refreshPriority: -1,
                    },
                });

                // Mark as processed
                element.dataset.textAnimated = 'true';
            });
        }

        // Text Animation Style 3 - Advanced character animation with 3D effects
        if ($('.text-anime-style-3').length) {
            let animatedTextElements = document.querySelectorAll('.text-anime-style-3');

            animatedTextElements.forEach((element) => {
                // Check if element is already processed
                if (element.dataset.textAnimated === 'true') {
                    return;
                }

                // Clean up previous animations if any
                if (element.animation) {
                    element.animation.progress(1).kill();
                    element.split.revert();
                }

                // Create split text
                element.split = new SplitText(element, {
                    type: 'lines,words,chars',
                    linesClass: 'split-line',
                });

                // Set perspective for 3D effect
                gsap.set(element, { perspective: 400 });

                // Set initial state
                gsap.set(element.split.chars, {
                    opacity: 0,
                    x: 50,
                    rotateX: 90,
                });

                // Create animation
                element.animation = gsap.to(element.split.chars, {
                    x: 0,
                    y: 0,
                    rotateX: 0,
                    opacity: 1,
                    duration: 1,
                    ease: Back.easeOut,
                    stagger: 0.02,
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 90%',
                        end: 'bottom 10%',
                        toggleActions: 'play none none reverse',
                        refreshPriority: -1,
                    },
                });

                // Mark as processed
                element.dataset.textAnimated = 'true';
            });
        }

        // Refresh ScrollTrigger after all animations are set up
        ScrollTrigger.refresh();
    }

    // Function to reinitialize text animations (useful for dynamic content)
    function reinitTextAnimations() {
        // Clear existing animations
        document.querySelectorAll('.text-anime-style-1, .text-anime-style-2, .text-anime-style-3').forEach((el) => {
            el.dataset.textAnimated = 'false';
        });

        // Reinitialize
        initTextAnimations();
    }

    // Make function globally accessible
    window.reinitTextAnimations = reinitTextAnimations;

    /* actionMusic
  -------------------------------------------------------------------------------------*/
    const actionMusic = () => {
        if (!$('.box-music').length) return;

        $(document).one('click touchstart keydown', () => {
            if (Howler.ctx && Howler.ctx.state === 'suspended') {
                Howler.ctx.resume();
            }
        });

        const players = [];

        $('.box-music').each(function () {
            const $box = $(this);
            let sound = null;

            const getSound = () => {
                if (!sound) {
                    sound = new Howl({
                        src: [$box.data('src')],
                        html5: true,
                        onplay() {
                            players.forEach((p) => p !== sound && p.pause());
                            requestAnimationFrame(update);
                            $box.find('.play-btn').hide();
                            $box.find('.pause-btn').show();
                        },
                        onpause() {
                            $box.find('.play-btn').show();
                            $box.find('.pause-btn').hide();
                        },
                        onend() {
                            $box.find('.play-btn').show();
                            $box.find('.pause-btn').hide();
                            $box.find('.progress').css('width', '0%');
                            $box.find('.time-display').text('00:00');
                        },
                    });
                    players.push(sound);
                }
                return sound;
            };

            function update() {
                if (!sound || !sound.playing()) return;
                const seek = sound.seek() || 0;
                const duration = sound.duration() || 1;

                $box.find('.progress').css('width', `${(seek / duration) * 100}%`);
                const min = String(Math.floor(seek / 60)).padStart(2, '0');
                const sec = String(Math.floor(seek % 60)).padStart(2, '0');
                $box.find('.time-display').text(`${min}:${sec}`);

                if (sound.playing()) requestAnimationFrame(update);
            }

            $box.on('click', '.play-btn', () => {
                const s = getSound();

                if (Howler.ctx && Howler.ctx.state === 'suspended') {
                    Howler.ctx.resume().then(() => s.play());
                } else {
                    s.play();
                }
            });

            $box.on('click', '.pause-btn', () => sound && sound.pause());

            $box.on('click', '.prev-btn, .next-btn', () => {
                const s = getSound();
                s.stop();
                s.play();
            });

            $box.on('click', '.progress-bar', function (e) {
                if (!sound) return;
                const percent = e.offsetX / $(this).width();
                sound.seek(sound.duration() * percent);
                update();
            });
        });
    };
    function inputFocus() {
        $('input')
            .focus(function () {
                $(this).closest('div.input-group').addClass('focus');
            })
            .blur(function () {
                $(this).closest('div.input-group').removeClass('focus');
            });
        $('textarea')
            .focus(function () {
                $(this).closest('div.input-group').addClass('focus');
            })
            .blur(function () {
                $(this).closest('div.input-group').removeClass('focus');
            });
        $('select')
            .focus(function () {
                $(this).closest('div.input-group').addClass('focus');
            })
            .blur(function () {
                $(this).closest('div.input-group').removeClass('focus');
            });
    }
    /*=============================================
    =           Page Load       =
    =============================================*/
    // Toggle active class for bookmark
    $(document).on('click', '.book-mark', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
    });

    $(window).on('load', function () {
        preloader();
        progressPageLoad();
        offcanvasMenu();
        dataBackground();
        aosAnimation();
        customSwiper();
        wowAnimation();
        carauselScroll();
        actionMusic();
        initSidebarMenu();
        initTabs();
        inputFocus();
        odometerCounter();
        magnificPopup();
        initPopupSearch();
        initMultilineUnderline();
        initLinkEffect1();
        initButtonEffect3();
        initTextAnimations();
        initViewModeSwitching(); // Initialize view mode switching

        // Refresh ScrollTrigger after all animations are initialized
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
        }
    });

    // Initialize odometer earlier in document ready
    $(document).ready(function () {
        odometerCounter();
        initTextAnimations();
        // Initialize AOS earlier
        aosAnimation();
    });

    // Function to re-initialize odometer for new content
    window.reinitOdometer = function () {
        odometerCounter();
    };

    // Handle window resize to refresh text animations
    $(window).on('resize', function () {
        // Debounce resize event
        clearTimeout(window.resizeTimeout);
        window.resizeTimeout = setTimeout(function () {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        }, 250);
    });
})(jQuery);
