var swiper=new Swiper(".slide-container",{slidesPerView:4,spaceBetween:20,sliderPerGroup:4,loop:!0,centerSlide:"true",fade:"true",grabCursor:"true",pagination:{el:".swiper-pagination",clickable:!0,dynamicBullets:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},autoplay:{delay:5e3,disableOnInteraction:!1},breakpoints:{0:{slidesPerView:2},520:{slidesPerView:3},768:{slidesPerView:4},1e3:{slidesPerView:5},1200:{slidesPerView:6}}}),swiper=new Swiper(".article-container",{slidesPerView:4,spaceBetween:20,sliderPerGroup:4,loop:!0,centerSlide:"true",fade:"true",grabCursor:"true",pagination:{el:".swiper-pagination",clickable:!0,dynamicBullets:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},breakpoints:{0:{slidesPerView:1},520:{slidesPerView:1},768:{slidesPerView:1},1e3:{slidesPerView:1},1200:{slidesPerView:1}}});