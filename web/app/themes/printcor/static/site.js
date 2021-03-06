jQuery( document ).ready( function( $ ) {

  const menu = $('#nav-main-mobile')

  const splide = $('#home .splide')

  if (splide.length > 0) {
    new Splide('#home .splide', {
      autoplay: true,
      type: 'loop',
      interval: 3000,
      pauseOnHover: false,
    }).mount()
  }

  const splides = $('#experiencia .splide')
  
  if (splides.length > 0) {
    splides.each((i, el) => {
      new Splide(el, {
        autoplay: true,
        type: 'loop',
        interval: 3000,
        perMove: 1,
        pagination: false,
      }).mount()
    })
  }

  const clientes = $('#clientes .splide')
  if (clientes.length > 0) {
    clientes.each((i, el) => {
      new Splide(el, {
        autoplay: true,
        type: 'loop',
        interval: 3000,
        pagination: false,
      }).mount()
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