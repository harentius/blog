((global, $) ->
  'use strict'

  $(() ->
    $adsenceArticlesLoader = $('.content-12345')
    $adsenceCategoryLoader = $('.a-content-category')
    hasToShowCategoryAd = location.href.match(/\/category\//)

    if ($adsenceArticlesLoader.length == 0) && (!hasToShowCategoryAd || $adsenceCategoryLoader.length == 0)
      return

    $.getScript( "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js")
    global.adsbygoogle ||= []

    if $adsenceArticlesLoader.length > 0
      global.adsbygoogle.push({})
      $adsenceArticlesLoader.html(
        '<ins class="adsbygoogle"
              style="display:inline-block;width:336px;height:280px"
              data-ad-client="ca-pub-8463813510139079"
              data-ad-slot="7797505349"
        ></ins>'
      )

    if $adsenceCategoryLoader.length > 0
      global.adsbygoogle.push({})
      $adsenceArticlesLoader.html(
        '<ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-8463813510139079"
           data-ad-slot="3758121747"
           data-ad-format="auto"
        ></ins>'
      )
  )
)(window, jQuery)
