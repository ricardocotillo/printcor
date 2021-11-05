jQuery( document ).ready( function( $ ) {

  const menu = $('#nav-main-mobile')

  new Splide('#home .splide', {
    autoplay: true,
    loop: true,
  }).mount()

  $('#experiencia .splide').each((i, el) => {
    new Splide(el).mount()
  })

  $('#hamburger').click(function() {
    menu.fadeIn('fast')
  })

  $('#nav-main-mobile .close').click(function() {
    menu.fadeOut('fast')
  })

  window.onload = function() {
    $('#splash-screen').fadeOut('fast')
  }
})