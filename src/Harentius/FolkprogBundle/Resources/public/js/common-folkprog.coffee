((global, $) ->
  'use strict'

  $(() ->
    $.getScript( "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js")
    $adsenceArticlesLoader = $('.content-12345')
    $adsenceCategoryLoader = $('.a-content-category')
    isShowCategoryAd = location.href.match(/\/category\//)

    if ($adsenceArticlesLoader.length == 0) && (!isShowCategoryAd || $adsenceCategoryLoader.length == 0)
      return

    global.adsbygoogle ||= []

    if $adsenceArticlesLoader.length > 0
      $adsenceArticlesLoader.html(
        '<ins class="adsbygoogle"
            style="display:inline-block;width:336px;height:280px"
            data-ad-client="ca-pub-8463813510139079"
            data-ad-slot="7797505349"
        ></ins>'
      )
      global.adsbygoogle.push({})

    if isShowCategoryAd && $adsenceCategoryLoader.length > 0
      $adsenceCategoryLoader.html(
        '<ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-8463813510139079"
           data-ad-slot="3758121747"
           data-ad-format="auto"
        ></ins>'
      )
      global.adsbygoogle.push({})
  )
)(window, jQuery)
