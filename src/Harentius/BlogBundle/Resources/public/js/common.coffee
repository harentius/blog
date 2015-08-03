"use strict"
hljs.initHighlightingOnLoad()

jQuery(($) ->
  $('.months-expander').on('click', () ->
    $(this).closest('.year-content-wrapper').find('.months-list').slideToggle()
  )
)
