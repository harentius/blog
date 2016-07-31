((global, $) ->
  'use strict'

  $(() ->
    $adsenceLoader = $('.content-12345')

    if $adsenceLoader.length > 0
      $.getScript( "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js")
      global.adsbygoogle ||= []
      global.adsbygoogle.push({})
      $adsenceLoader.html(
        '<ins class="adsbygoogle"
              style="display:inline-block;width:336px;height:280px"
              data-ad-client="ca-pub-8463813510139079"
              data-ad-slot="7797505349"
        ></ins>'
      )
  )
)(window, jQuery)
