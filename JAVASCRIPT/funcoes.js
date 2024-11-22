// SLIDE INDEX PROMO

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
        breakpoint: 1180,
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

// SLIDE INDEX INFO

  $('.cards2').slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    prevArrow: $("#seta-left"),
    nextArrow: $("#seta-right"),
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
        breakpoint: 1180,
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

  // SLIDE PRODUTOS

  $('.center').slick({
    centerMode: true,
    centerPadding: '60px',
    slidesToShow: 3,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          arrows: false,
          centerMode: true,
          centerPadding: '40px',
          slidesToShow: 3
        }
      },
      {
        breakpoint: 480,
        settings: {
          arrows: false,
          centerMode: true,
          centerPadding: '40px',
          slidesToShow: 1
        }
      }
    ]
  });

// AUMENTAR DIMINUIR QUANTIDADE

  function process(quant){
    var value = parseInt(document.getElementById("quantia").value);
    value+=quant;
    if(value < 1){
      document.getElementById("quantia").value = 1;
    }else{
    document.getElementById("quantia").value = value;
    }
  }

  // function process(quant){
  //   var value = parseInt(document.getElementById("quantia").value);
  //   value+=quant;
  //   if(value < 1){
  //     document.getElementById("quantia").value = 1;
  //   }else{
  //   document.getElementById("quantia").value = value;
  //   }
  // }



// BUSCA CEP

 $("#inputZip").blur(function(){
      // Remove tudo o que não é número para fazer a pesquisa
      var cep = this.value.replace(/[^0-9]/, "");
      
      // Validação do CEP; caso o CEP não possua 8 números, então cancela
      // a consulta
      if(cep.length != 8){
          return false;
      }
      
      // A url de pesquisa consiste no endereço do webservice + o cep que
      // o usuário informou + o tipo de retorno desejado (entre "json",
      // "jsonp", "xml", "piped" ou "querty")
      var url = "https://viacep.com.br/ws/"+cep+"/json/";
      
      // Faz a pesquisa do CEP, tratando o retorno com try/catch para que
      // caso ocorra algum erro (o cep pode não existir, por exemplo) a
      // usabilidade não seja afetada, assim o usuário pode continuar//
      // preenchendo os campos normalmente
      $.getJSON(url, function(dadosRetorno){
          try{
              // Preenche os campos de acordo com o retorno da pesquisa
              $("#inputAddress").val(dadosRetorno.logradouro);
              $("#inputAddress2").val(dadosRetorno.bairro);
              $("#inputCity").val(dadosRetorno.localidade);
              $("#inputState").val(dadosRetorno.uf);
              $("#inputNumber").focus();
          }catch(ex){}
      });
  });

  var input = document.querySelector('#input input');
  var img = document.querySelector('#input i');
  img.addEventListener('click', function () {
    input.type = input.type == 'text' ? 'password' : 'text';
  });

