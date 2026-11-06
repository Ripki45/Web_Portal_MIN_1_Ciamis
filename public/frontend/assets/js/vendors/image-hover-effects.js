/*=============================================
=          Creative Image Hover Effects      =
=============================================*/

// Available effect types for easy reference
const EFFECT_TYPES = {
    'ZOOM_MORPH': 'zoom-morph',      // Smooth zoom with morphing and gradient overlay
    'TILT_3D': 'tilt-3d',           // 3D tilt effect following mouse movement
    'GLITCH': 'glitch',              // Digital glitch with random displacement
    'PARTICLE': 'particle',          // Animated floating particles
    'LIQUID': 'liquid',              // Liquid wave effect with color morphing
    'HOLOGRAM': 'hologram',          // Holographic scan line effect
    'NEON': 'neon',                  // Neon glow with bright colors
    'VINTAGE': 'vintage'             // Vintage sepia with retro overlay
};

/**
 * Creates a smooth zoom effect with morphing and gradient overlay
 * Features:
 * - Smooth scale and rotation on hover
 * - Dynamic border radius change
 * - Gradient overlay animation
 * - Enhanced brightness and saturation
 */
function createZoomMorphEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden',
        'border-radius': '16px',
        'cursor': 'pointer'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)',
        'filter': 'brightness(1) contrast(1)'
    });

    $container.hover(
        function () {
            // Apply zoom and rotation with enhanced filters
            $img.css({
                'transform': 'scale(1.05) rotate(1deg)',
                'filter': 'brightness(1.1) contrast(1.1) saturate(1.2)',
                'border-radius': '16px'
            });

            // Add gradient overlay for enhanced visual effect
            $container.append('<div class="zoom-overlay"></div>');
            $('.zoom-overlay').css({
                'position': 'absolute',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%',
                'background': 'linear-gradient(45deg, rgba(255,255,255,0.1), rgba(0,0,0,0.1))',
                'opacity': '0',
                'transition': 'opacity 0.6s',
                'pointer-events': 'none'
            });

            // Animate overlay opacity
            setTimeout(() => {
                $('.zoom-overlay').css('opacity', '1');
            }, 100);
        },
        function () {
            // Reset to original state
            $img.css({
                'transform': 'scale(1) rotate(0deg)',
                'filter': 'brightness(1) contrast(1) saturate(1)',
                'border-radius': '15px'
            });
            $('.zoom-overlay').remove();
        }
    );
}

/**
 * Creates a 3D tilt effect that responds to mouse movement
 * Features:
 * - Real-time 3D rotation based on mouse position
 * - Perspective and preserve-3d transforms
 * - Smooth transition back to original state
 * - Interactive depth perception
 */
function createTilt3DEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'perspective': '1000px',
        'transform-style': 'preserve-3d'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'transform 0.3s ease-out',
        'transform-style': 'preserve-3d'
    });

    // Track mouse movement for 3D tilt calculation
    $container.on('mousemove', function (e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        // Calculate rotation angles based on mouse distance from center
        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;

        $img.css({
            'transform': `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`
        });
    });

    // Reset to original state when mouse leaves
    $container.on('mouseleave', function () {
        $img.css({
            'transform': 'rotateX(0deg) rotateY(0deg) scale(1)'
        });
    });
}

/**
 * Creates a digital glitch effect with random displacement
 * Features:
 * - Cloned images with different filters
 * - Random displacement animation
 * - Color channel separation
 * - Mix-blend-mode effects
 */
function createGlitchEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden'
    });

    // Clone images to create glitch layers
    const $glitchImg1 = $img.clone();
    const $glitchImg2 = $img.clone();

    $container.append($glitchImg1, $glitchImg2);

    const allImages = $container.find('img');
    allImages.css({
        'position': 'absolute',
        'top': '0',
        'left': '0',
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover'
    });

    $container.hover(
        function () {
            // Create glitch effect with random displacement
            setInterval(() => {
                const randomX = Math.random() * 10 - 5;
                const randomY = Math.random() * 10 - 5;

                // First glitch layer with green tint
                $glitchImg1.css({
                    'transform': `translate(${randomX}px, ${randomY}px)`,
                    'filter': 'hue-rotate(90deg)',
                    'opacity': '0.8',
                    'mix-blend-mode': 'multiply'
                });

                // Second glitch layer with blue tint
                $glitchImg2.css({
                    'transform': `translate(${-randomX}px, ${-randomY}px)`,
                    'filter': 'hue-rotate(180deg)',
                    'opacity': '0.6',
                    'mix-blend-mode': 'screen'
                });
            }, 100);
        },
        function () {
            clearInterval();
            $glitchImg1.remove();
            $glitchImg2.remove();
        }
    );
}

/**
 * Creates animated floating particles effect
 * Features:
 * - 20 randomly positioned particles
 * - Colorful HSL-based particles
 * - Floating animation with rotation
 * - Brightness enhancement on hover
 */
function createParticleEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'filter 0.5s'
    });

    $container.hover(
        function () {
            // Enhance image brightness
            $img.css('filter', 'brightness(1.1)');

            // Create animated particles
            for (let i = 0; i < 20; i++) {
                const $particle = $('<div class="particle"></div>');
                $container.append($particle);

                // Random particle properties
                const size = Math.random() * 4 + 2;
                const x = Math.random() * 100;
                const y = Math.random() * 100;

                $particle.css({
                    'position': 'absolute',
                    'width': size + 'px',
                    'height': size + 'px',
                    'background': `hsl(${Math.random() * 360}, 70%, 60%)`,
                    'border-radius': '50%',
                    'left': x + '%',
                    'top': y + '%',
                    'pointer-events': 'none',
                    'animation': `particleFloat 2s ease-in-out infinite`
                });
            }
        },
        function () {
            $img.css('filter', 'brightness(1)');
            $('.particle').remove();
        }
    );
}

/**
 * Creates a liquid wave effect with color morphing
 * Features:
 * - Liquid wave animation from bottom
 * - Color gradient morphing
 * - Smooth scale and rotation
 * - Custom cubic-bezier timing
 */
function createLiquidEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden',
        'border-radius': '20px'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)'
    });

    $container.hover(
        function () {
            // Apply liquid-like transformation
            $img.css({
                'transform': 'scale(1.1) rotate(1deg)',
                'filter': 'hue-rotate(15deg) saturate(1.3)'
            });

            // Create liquid wave effect
            const $wave = $('<div class="liquid-wave"></div>');
            $container.append($wave);

            $wave.css({
                'position': 'absolute',
                'bottom': '0',
                'left': '0',
                'width': '100%',
                'height': '30%',
                'background': 'linear-gradient(45deg, rgba(0,255,255,0.3), rgba(255,0,255,0.3))',
                'transform': 'translateY(100%)',
                'transition': 'transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                'pointer-events': 'none'
            });

            // Animate wave rising
            setTimeout(() => {
                $wave.css('transform', 'translateY(0)');
            }, 100);
        },
        function () {
            $img.css({
                'transform': 'scale(1) rotate(0deg)',
                'filter': 'hue-rotate(0deg) saturate(1)'
            });
            $('.liquid-wave').remove();
        }
    );
}

/**
 * Creates a holographic scan line effect
 * Features:
 * - Holographic color transformation
 * - Animated scan line
 * - Brightness and contrast enhancement
 * - Futuristic visual appeal
 */
function createHologramEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'all 0.5s'
    });

    $container.hover(
        function () {
            // Apply holographic filters
            $img.css({
                'filter': 'brightness(1.2) contrast(1.1) hue-rotate(180deg)',
                'transform': 'scale(1.05)'
            });

            // Create holographic scan line
            const $scanLine = $('<div class="hologram-scan"></div>');
            $container.append($scanLine);

            $scanLine.css({
                'position': 'absolute',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '2px',
                'background': 'linear-gradient(90deg, transparent, cyan, transparent)',
                'animation': 'hologramScan 2s linear infinite',
                'pointer-events': 'none'
            });
        },
        function () {
            $img.css({
                'filter': 'brightness(1) contrast(1) hue-rotate(0deg)',
                'transform': 'scale(1)'
            });
            $('.hologram-scan').remove();
        }
    );
}

/**
 * Creates a neon glow effect with bright colors
 * Features:
 * - Neon border glow
 * - Bright color enhancement
 * - Box-shadow neon effects
 * - Cyberpunk aesthetic
 */
function createNeonEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden',
        'border-radius': '10px'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'all 0.4s'
    });

    $container.hover(
        function () {
            // Apply neon glow to image
            $img.css({
                'filter': 'brightness(1.3) contrast(1.2) saturate(1.5)',
                'box-shadow': '0 0 20px rgba(0,255,255,0.8), 0 0 40px rgba(255,0,255,0.6)'
            });

            // Add neon border effect
            $container.css({
                'box-shadow': '0 0 10px rgba(0,255,255,0.5), inset 0 0 10px rgba(255,0,255,0.3)',
                'border': '2px solid rgba(0,255,255,0.8)'
            });
        },
        function () {
            $img.css({
                'filter': 'brightness(1) contrast(1) saturate(1)',
                'box-shadow': 'none'
            });
            $container.css({
                'box-shadow': 'none',
                'border': 'none'
            });
        }
    );
}

/**
 * Creates a vintage sepia effect with retro overlay
 * Features:
 * - Sepia color transformation
 * - Vintage radial gradient overlay
 * - Subtle rotation
 * - Retro aesthetic appeal
 */
function createVintageEffect($container, $img) {
    $container.css({
        'position': 'relative',
        'overflow': 'hidden',
        'border-radius': '8px'
    });

    $img.css({
        'width': '100%',
        'height': '100%',
        'object-fit': 'cover',
        'transition': 'all 0.6s'
    });

    $container.hover(
        function () {
            // Apply vintage filters
            $img.css({
                'filter': 'sepia(0.8) contrast(1.2) brightness(0.9) saturate(0.8)',
                'transform': 'scale(1.05) rotate(-1deg)'
            });

            // Add vintage overlay
            const $vintageOverlay = $('<div class="vintage-overlay"></div>');
            $container.append($vintageOverlay);

            $vintageOverlay.css({
                'position': 'absolute',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%',
                'background': 'radial-gradient(circle, rgba(255,215,0,0.1) 0%, rgba(139,69,19,0.2) 100%)',
                'opacity': '0',
                'transition': 'opacity 0.6s',
                'pointer-events': 'none'
            });

            // Animate overlay opacity
            setTimeout(() => {
                $vintageOverlay.css('opacity', '1');
            }, 100);
        },
        function () {
            $img.css({
                'filter': 'sepia(0) contrast(1) brightness(1) saturate(1)',
                'transform': 'scale(1) rotate(0deg)'
            });
            $('.vintage-overlay').remove();
        }
    );
}

/**
 * Main function to initialize all creative hover effects
 * Maps different CSS classes to their respective effects
 */
function initCreativeImageHoverEffects() {
    // Zoom Morph Effect for hover-effect-1 class
    $('.hover-effect-1').each(function () {
        createZoomMorphEffect($(this), $(this).find('img').first());
    });

    // Tilt 3D Effect for hover-effect-2 class
    $('.hover-effect-2').each(function () {
        createTilt3DEffect($(this), $(this).find('img').first());
    });

    // Glitch Effect for hover-effect-3 class
    $('.hover-effect-3').each(function () {
        createGlitchEffect($(this), $(this).find('img').first());
    });

    // Particle Effect for hover-effect-4 class
    $('.hover-effect-4').each(function () {
        createParticleEffect($(this), $(this).find('img').first());
    });

    // Liquid Effect for hover-effect-5 class
    $('.hover-effect-5').each(function () {
        createLiquidEffect($(this), $(this).find('img').first());
    });

    // Hologram Effect for hover-effect-6 class
    $('.hover-effect-6').each(function () {
        createHologramEffect($(this), $(this).find('img').first());
    });

    // Neon Effect for hover-effect-7 class
    $('.hover-effect-7').each(function () {
        createNeonEffect($(this), $(this).find('img').first());
    });

    // Vintage Effect for hover-effect-8 class
    $('.hover-effect-8').each(function () {
        createVintageEffect($(this), $(this).find('img').first());
    });

    // Add custom CSS animations
    addCustomCSS();
}

/**
 * Adds custom CSS animations and keyframes
 * Includes particle floating animation and hologram scan line
 */
function addCustomCSS() {
    const customCSS = `
        @keyframes particleFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 1; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.7; }
        }
        
        @keyframes hologramScan {
            0% { top: 0%; }
            100% { top: 100%; }
        }
    `;

    // Prevent duplicate CSS injection
    if (!$('#creative-hover-effects-css').length) {
        $('<style id="creative-hover-effects-css">' + customCSS + '</style>').appendTo('head');
    }
}

// Initialize effects when DOM is ready
$(document).ready(function () {
    initCreativeImageHoverEffects();
});

// Initialize effects when window is fully loaded
$(window).on('load', function () {
    initCreativeImageHoverEffects();
});

// Export functions for external use
window.CreativeImageHoverEffects = {
    init: initCreativeImageHoverEffects,
    EFFECT_TYPES: EFFECT_TYPES
};
