var $swiperSelector = $('.swiper-container');

$swiperSelector.each(function(index) {
    var $this = $(this);
    $this.addClass('swiper-slider-' + index);

    var dragSize = $this.data('drag-size') ? $this.data('drag-size') : 50;
    var freeMode = $this.data('free-mode') ? $this.data('free-mode') : false;
    var loop = $this.data('loop') ? $this.data('loop') : false;
    var slidesDesktop = $this.data('slides-desktop') ? $this.data('slides-desktop') : 1;
    var spaceBetween = $this.data('space-between') ? $this.data('space-between'): 20;

    var swiper = new Swiper('.swiper-slider-' + index, {
        direction: 'horizontal',
        loop: loop,
        freeMode: freeMode,
        spaceBetween: spaceBetween,
        slidesPerView: slidesDesktop,
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
            dragSize: dragSize,
        },
        autoplay: {
            delay: 5000,
            pauseOnMouseEnter: true,
            disableOnInteraction: false
        },
    });
});