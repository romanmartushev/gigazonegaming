themePath = "/wp-content/themes/gigazone-gaming"
require =
  baseUrl: themePath+'/js'
  paths:
    #third party libraries
    jquery: [ "../../../../bower_components/jquery/dist/jquery.min" ]
    bootstrap: [ "../../../../bower_components/bootstrap/dist/js/bootstrap.min" ]
    underscore: [ "../../../../bower_components/underscore/underscore-min" ]
    handlebars: [ "../../../../bower_components/handlebars/handlebars.min" ]
    imager: [ "../../../../bower_components/imager.js/dist/Imager.min" ]
    responsiveSlides: [ "../../../../bower_components/ResponsiveSlides/responsiveslides.min" ]
    iFrameResize: [ "../../../../bower_components/iframe-resizer/js/iframeResizer.min" ]
    Slider: [ "../../../../bower_components/seiyria-bootstrap-slider/dist/bootstrap-slider.min" ]
    Switch: [ "../../../../bower_components/bootstrap-switch/dist/js/bootstrap-switch.min" ]
    # modules
    init: ["init"]
    variables: ["variables"]
    functions: ["functions"]
    mainNavigation: ["modules/main-navigation"]
    responsiveImages: ["modules/responsive-images"]
    photoRotator: ["modules/photo-rotator"]
    links: ["modules/links"]
    stickyFooter: ["modules/sticky-footer"]
    searchResults: ["modules/search-results"]
    posts: ["modules/posts"]
    form: ["modules/form"],
    duplicate: ["modules/duplicate"],
    tab: ["modules/tab"],
    Utility: ["modules/Utility"]
  waitSeconds: 0
  shim:
    "bootstrap": [ "jquery" ]
    "responsiveSlides": [ "jquery" ]
    "iFrameResize": [ "jquery" ]
    "underscore": exports: "_"
    "handlebars": exports: "Handlebars"
    "imager": exports: "Imager"
  priority: [ "jquery" ]