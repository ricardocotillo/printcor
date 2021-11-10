jQuery( document ).ready( function( $ ) {

  const menu = $('#nav-main-mobile')

  const splide = $('#home .splide')

  if (splide.length > 0) {
    new Splide('#home .splide', {
      autoplay: true,
      loop: true,
    }).mount()
  }

  const splides = $('#experiencia .splide')
  
  if (splides.length > 0) {
    splides.each((i, el) => {
      new Splide(el).mount()
    })
  }

  const clientes = $('#clientes .splide')
  if (clientes.length > 0) {
    clientes.each((i, el) => {
      new Splide(el).mount()
    })
  }

  $('#hamburger').click(function() {
    menu.fadeIn('fast')
  })

  $('#nav-main-mobile .close').click(function() {
    menu.fadeOut('fast')
  })

  $('#splash-screen').fadeOut('fast')
})