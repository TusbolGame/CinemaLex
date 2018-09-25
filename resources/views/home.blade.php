<html lang="{{ app()->getLocale() }}">
    <head>
        <title>{{ config('app.name') }}</title>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#db5945">
        <meta name="description" content="Watch movies & TV shows online or stream right to your smart TV, game console, PC, Mac, mobile, tablet and more. Start your free trial today.">
        <link rel="manifest" href="manifest.json">
        <link rel="icon" sizes="192x192" href="{{asset('img/logo.png')}}">
        <link rel="stylesheet" href="{{asset('/css/app.css')}}">
        <link rel="stylesheet" href="{{asset('themes/'.config('plugin.theme').'/css/main.css')}}">
  </head>
  <body>
   <!--  container content: START -->  
<div class="{{config('plugin.theme')}}"></div>
   <!--  container content: END -->
 <noscript>

  <div class="container">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="alert alert-danger">
      JavaScript is disabled in your web browser!
    </div>
  </div>
  </div>
 </noscript>  <!--  container javascript alert: END -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://js.stripe.com/v3/"> </script>
   <script src="/js/admin-js/manifest.js"></script>
   <script src="/js/admin-js/vendor.js"></script>
   <script src="/js/app.js"></script>
   <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>
  </body>
</html>
