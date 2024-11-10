'use strict'
jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend(jQuery.easing, {
    def: 'easeOutQuad',
    easeInExpo: function(x, t, b, c, d) {
        return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
    },
    easeOutExpo: function(x, t, b, c, d) {
        return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
    },
    easeInOutExpo: function(x, t, b, c, d) {
        if (t == 0) return b;
        if (t == d) return b + c;
        if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
        return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
    },
    easeOutElastic: function(x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * .3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    },
    easeOutElastic: function(x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * .3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    }
});
//  Agregar underline a los inputs del formulario de registro
$('.form-reg input').focus(function() {
    $(this).parent().css('box-shadow', 'inset 0 1px 15px rgba(41, 128, 185, 0.15)')
}).blur(function() {
    $(this).parent().css('box-shadow', 'none');
});
/*
 *   Dropdown para el menu   
 */
$('.perfil').click(function() {
    $('.dropdown').slideToggle('slow', 'easeInOutExpo').click(function(e) {
        e.stopPropagation()
    });
})
//  Mostrar y ocultar el menu
var click = true;
var boxWidth = '';
if (innerWidth <= 768) {
    boxWidth = '100%';
} else {
    boxWidth = '70%';
}
$('.btn-show-hide-menu').click(function() {
    if (click) {
        $('.cont-menu').css({
            'margin-left': '-100%'
        })
        $('.main').css('width', '70%')
        click = false;
    } else {
        $('.cont-menu').css({
            'margin-left': '-15px'
        })
        $('.main').css('width', boxWidth)
        click = true
    }
    $('.btn-show-hide-menu').toggleClass('rotate')
})
$('.menu-icon').click(function(e) {
    if (click) {
        $('.menu').css({
            'margin-left': '-15px'
        }).parent().fadeIn('fast')
        click = false;
    } else {
        $('.menu').css({
            'margin-left': '-100%'
        }).parent().fadeOut('fast')
        click = true;
    }
})
$('.cont-menu').not('.menu').click(function() {
    $('.menu').css({
        'margin-left': '-100%'
    }).parent().fadeOut('fast')
    click = true;
})
$('.menu').click(function(e) {
    e.stopPropagation()
})

console.info('Plataforma de pagos de CAASD EN LINEA \nAuthor: Algenis Mosquea \nEmail: amosquea@aceadominicana.com');