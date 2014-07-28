# Google Analytics Tracker

* Author: [Diogo Silva](https://github.com/diogomiguel/ee_ga_tracker)

## Version 0.0.1

Tested only on Expression Engine 2.7+. Shouldn't raise any compatibility problems on any Expression Engine 2+ version, but use with caution.

* Requires: ExpressionEngine 2.7+

## Description

Drop in solution which allows to track server-side events and data within Google Analytics, based on [server side google analytics](https://github.com/dancameron/server-side-google-analytics)

## Instalation

Unzip the downloaded zip and place the 'ga_tracker' folder in /expressionengine/third_party/. In the ExpressionEngine Control Panel navigate to Addons -> Modules and click "Install" next to the "GA Tracker" addon.

Click on "GA Tracker" to access the settings screen and enter your Google Analytics Key (UA-XXXXXXXX-X)

## Documentation

Generate a track page view link

	{exp:ga_tracker:pageview_link page="page_to_track_url" title="page_title" rurl="url_to_redirect_to_after_tracking"}

Generate a send an event link

	{exp:ga_tracker:event_link event="event_name" category="event_category" label="event_label" rurl="url_to_redirect_to_after_tracking"}