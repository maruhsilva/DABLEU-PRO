$('.cards1').slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: $("#arrow-left"),
    nextArrow: $("#arrow-right"),
    responsive: [
        {
            breakpoint: 1920,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              infinite: false,
              dots: false
            }
        },
      {
        breakpoint: 1440,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: false,
          dots: false
        }
      },
      {
        breakpoint: 800,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });