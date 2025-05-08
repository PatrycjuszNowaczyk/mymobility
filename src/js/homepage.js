const swiperHomeSlider = new Swiper(
  "#slider .swiper", {
      loop: true,
      slidesPerView: 1,
      effect: "fade",
      speed: 1000,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },

      // spaceBetween: 20,
      // autoHeight: true,
  }
); 

const swiperHomeTeam = new Swiper(
    "#zespol .swiper", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        autoHeight: true,
        // pagination: {
        //     el: '.swiper-pagination',
        //     clickable: true,
        // },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev"
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
            autoHeight: false,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 20,
          },
        },
    }
); 

const swiperHomePartners = new Swiper(
    "#partnerzy .swiper", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev"
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 20,
          },
        },
    }
); 