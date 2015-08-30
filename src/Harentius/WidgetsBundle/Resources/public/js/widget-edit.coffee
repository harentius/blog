jQuery(($) ->
  'use strict'

  urls =
    widgetAdminRouteFields: (route) ->
      Routing.generate('widget_admin_route_fields', { route: route } )

  $('select[name$="[route][name]"]').on('change', () ->
    val = $(this).val()
    getRouteFieldsWrapper = () -> $('.route-fields-wrapper')

    if val
      $.get(urls.widgetAdminRouteFields(val)).done((response) ->
        # TODO: names
      )
    else
      getRouteFieldsWrapper.empty()
  )
)
