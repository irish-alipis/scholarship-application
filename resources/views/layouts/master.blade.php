<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Scholarship System')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description"
    content="A Tailwind CSS admin template is a pre-designed web page for an admin dashboard. Optimizing it for SEO includes using meta descriptions and ensuring it's responsive and fast-loading.">
  <meta name="keywords"
    content="dashboard,admin dashboard,template dashboard,html,html dashboard,admin dashboard template,admin template,tailwind ui,admin panel,html and css,html admin template,tailwind framework,html css javascript,tailwind css dashboard,dashboard html css,admin,template admin panel,dashboard html template">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('backend/assets/images/brand-logos/favicon.ico') }}">

  <!-- Main JS -->
  <script src="{{ asset('backend/assets/js/main.js') }}"></script>

  <!-- Style Css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">

  <!-- Simplebar Css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/libs/simplebar/simplebar.min.css') }}">

  <!-- Color Picker Css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

  <!-- Sweet alert -->
   <link rel="stylesheet" href="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.css') }}">

  @stack('styles')
</head>

<body>

  <!-- ========== Switcher  ========== -->
  <div id="hs-overlay-switcher" class="hs-overlay hidden ti-offcanvas ti-offcanvas-right !border-0" tabindex="-1">
    <div class="ti-offcanvas-header z-10 relative">
      <h5 class="ti-offcanvas-title">
        Switcher
      </h5>
      <button type="button"
        class="ti-btn flex-shrink-0 p-0  transition-none text-defaulttextcolor dark:text-defaulttextcolor/70 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white  dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
        data-hs-overlay="#hs-overlay-switcher">
        <span class="sr-only">Close modal</span>
        <i class="ri-close-circle-line leading-none text-lg"></i>
      </button>
    </div>
    <div class="ti-offcanvas-body !p-0 !border-b dark:border-white/10 z-10 relative !h-auto">
      <div class="flex rtl:space-x-reverse" aria-label="Tabs" role="tablist" role="tablist">
        <button type="button"
          class="hs-tab-active:bg-success/20 w-full !py-2 !px-4 hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-success dark:hs-tab-active:bg-success/20 dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-success -mb-px bg-white font-semibold text-center  text-defaulttextcolor dark:text-defaulttextcolor/70 rounded-none hover:text-gray-700 dark:bg-bodybg dark:border-white/10  active"
          id="switcher-item-1" data-hs-tab="#switcher-1" aria-controls="switcher-1" role="tab">
          Theme Style
        </button>
        <button type="button"
          class="hs-tab-active:bg-success/20 w-full !py-2 !px-4 hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-success dark:hs-tab-active:bg-success/20 dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-success -mb-px  bg-white font-semibold text-center  text-defaulttextcolor dark:text-defaulttextcolor/70 rounded-none hover:text-gray-700 dark:bg-bodybg dark:border-white/10  dark:hover:text-gray-300"
          id="switcher-item-2" data-hs-tab="#switcher-2" aria-controls="switcher-2" role="tab">
          Theme Colors
        </button>
      </div>
    </div>
    <div class="ti-offcanvas-body !h-full overflow-auto" id="switcher-body">
      <div id="switcher-1" role="tabpanel" aria-labelledby="switcher-item-1" class="space-y-6">
        <div>
          <p class="switcher-style-head">Theme Color Mode:</p>
          <div class="grid grid-cols-3 switcher-style">
            <div class="flex items-center">
              <input type="radio" name="theme-style" class="ti-form-radio" id="switcher-light-theme" checked>
              <label for="switcher-light-theme"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Light</label>
            </div>
            <div class="flex items-center">
              <input type="radio" name="theme-style" class="ti-form-radio" id="switcher-dark-theme">
              <label for="switcher-dark-theme"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Dark</label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Directions:</p>
          <div class="grid grid-cols-3  switcher-style">
            <div class="flex items-center">
              <input type="radio" name="direction" class="ti-form-radio" id="switcher-ltr" checked>
              <label for="switcher-ltr"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">LTR</label>
            </div>
            <div class="flex items-center">
              <input type="radio" name="direction" class="ti-form-radio" id="switcher-rtl">
              <label for="switcher-rtl"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">RTL</label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Navigation Styles:</p>
          <div class="grid grid-cols-3  switcher-style">
            <div class="flex items-center">
              <input type="radio" name="navigation-style" class="ti-form-radio" id="switcher-vertical" checked>
              <label for="switcher-vertical"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Vertical</label>
            </div>
            <div class="flex items-center">
              <input type="radio" name="navigation-style" class="ti-form-radio" id="switcher-horizontal">
              <label for="switcher-horizontal"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Horizontal</label>
            </div>
          </div>
        </div>
        <div class="!mt-0 navigation-menu-styles">
          <p class="switcher-style-head">Navigation Menu Style:</p>
          <div class="grid grid-cols-2 gap-2 switcher-style">
            <div class="flex">
              <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-menu-click">
              <label for="switcher-menu-click"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Menu
                Click</label>
            </div>
            <div class="flex">
              <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-menu-hover">
              <label for="switcher-menu-hover"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Menu
                Hover</label>
            </div>
            <div class="flex">
              <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-icon-click">
              <label for="switcher-icon-click"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Icon
                Click</label>
            </div>
            <div class="flex">
              <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-icon-hover">
              <label for="switcher-icon-hover"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Icon
                Hover</label>
            </div>
          </div>
        </div>
        <div class="!mt-0 sidemenu-layout-styles">
          <p class="switcher-style-head">Sidemenu Layout Syles:</p>
          <div class="grid grid-cols-2 gap-2 switcher-style">
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-default-menu"
                checked>
              <label for="switcher-default-menu"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold ">Default
                Menu</label>
            </div>
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-closed-menu">
              <label for="switcher-closed-menu"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold ">
                Closed
                Menu</label>
            </div>
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-icontext-menu">
              <label for="switcher-icontext-menu"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold ">Icon
                Text</label>
            </div>
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-icon-overlay">
              <label for="switcher-icon-overlay"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold ">Icon
                Overlay</label>
            </div>
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-detached">
              <label for="switcher-detached"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold ">Detached</label>
            </div>
            <div class="flex">
              <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-double-menu">
              <label for="switcher-double-menu"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Double
                Menu</label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Page Styles:</p>
          <div class="grid grid-cols-3  switcher-style">
            <div class="flex">
              <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-regular" checked>
              <label for="switcher-regular"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Regular</label>
            </div>
            <div class="flex">
              <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-classic">
              <label for="switcher-classic"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Classic</label>
            </div>
            <div class="flex">
              <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-modern">
              <label for="switcher-modern"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">
                Modern</label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Layout Width Styles:</p>
          <div class="grid grid-cols-3 switcher-style">
            <div class="flex">
              <input type="radio" name="layout-width" class="ti-form-radio" id="switcher-full-width" checked>
              <label for="switcher-full-width"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">FullWidth</label>
            </div>
            <div class="flex">
              <input type="radio" name="layout-width" class="ti-form-radio" id="switcher-boxed">
              <label for="switcher-boxed"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Boxed</label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Menu Positions:</p>
          <div class="grid grid-cols-3  switcher-style">
            <div class="flex">
              <input type="radio" name="data-menu-positions" class="ti-form-radio" id="switcher-menu-fixed" checked>
              <label for="switcher-menu-fixed"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Fixed</label>
            </div>
            <div class="flex">
              <input type="radio" name="data-menu-positions" class="ti-form-radio" id="switcher-menu-scroll">
              <label for="switcher-menu-scroll"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Scrollable
              </label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Header Positions:</p>
          <div class="grid grid-cols-3 switcher-style">
            <div class="flex">
              <input type="radio" name="data-header-positions" class="ti-form-radio" id="switcher-header-fixed" checked>
              <label for="switcher-header-fixed"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">
                Fixed</label>
            </div>
            <div class="flex">
              <input type="radio" name="data-header-positions" class="ti-form-radio" id="switcher-header-scroll">
              <label for="switcher-header-scroll"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Scrollable
              </label>
            </div>
          </div>
        </div>
        <div class="!mt-0">
          <p class="switcher-style-head">Loader:</p>
          <div class="grid grid-cols-3 switcher-style">
            <div class="flex">
              <input type="radio" name="page-loader" class="ti-form-radio" id="switcher-loader-enable" checked>
              <label for="switcher-loader-enable"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">
                Enable</label>
            </div>
            <div class="flex">
              <input type="radio" name="page-loader" class="ti-form-radio" id="switcher-loader-disable">
              <label for="switcher-loader-disable"
                class="text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2  font-semibold">Disable
              </label>
            </div>
          </div>
        </div>
      </div>
      <div id="switcher-2" class="hidden" role="tabpanel" aria-labelledby="switcher-item-2">
        <div class="theme-colors">
          <p class="switcher-style-head">Menu Colors:</p>
          <div class="flex switcher-style space-x-3 rtl:space-x-reverse">
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-white" type="radio" name="menu-colors"
                id="switcher-menu-light" checked>
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Light Menu
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-dark" type="radio" name="menu-colors"
                id="switcher-menu-dark">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Dark Menu
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-primary" type="radio" name="menu-colors"
                id="switcher-menu-primary">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Color Menu
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-gradient" type="radio" name="menu-colors"
                id="switcher-menu-gradient">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Gradient Menu
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-transparent" type="radio"
                name="menu-colors" id="switcher-menu-transparent">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Transparent Menu
              </span>
            </div>
          </div>
          <div class="px-4 text-textmuted text-[.6875rem] !mb-3"><b class="me-2">Note:</b>If you want to change color
            Menu
            dynamically
            change from below Theme Primary color picker.</div>
        </div>
        <div class="theme-colors">
          <p class="switcher-style-head">Header Colors:</p>
          <div class="flex switcher-style space-x-3 rtl:space-x-reverse">
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-white !border" type="radio"
                name="header-colors" id="switcher-header-light" checked>
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Light Header
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-dark" type="radio" name="header-colors"
                id="switcher-header-dark">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Dark Header
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-primary" type="radio" name="header-colors"
                id="switcher-header-primary">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Color Header
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-gradient" type="radio"
                name="header-colors" id="switcher-header-gradient">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Gradient Header
              </span>
            </div>
            <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
              <input class="hs-tooltip-toggle ti-form-radio color-input color-transparent" type="radio"
                name="header-colors" id="switcher-header-transparent">
              <span
                class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                role="tooltip">
                Transparent Header
              </span>
            </div>
          </div>
          <div class="px-4 text-textmuted text-[.6875rem] !mb-3"><b class="me-2">Note:</b>If you want to change color
            Header dynamically
            change from below Theme Primary color picker.</div>
        </div>
        <div class="theme-colors">
          <p class="switcher-style-head">Theme Primary:</p>
          <div class="flex switcher-style space-x-3 rtl:space-x-reverse">
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-primary-1" type="radio" name="theme-primary"
                id="switcher-primary" checked>
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-primary-2" type="radio" name="theme-primary"
                id="switcher-primary1">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-primary-3" type="radio" name="theme-primary"
                id="switcher-primary2">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-primary-4" type="radio" name="theme-primary"
                id="switcher-primary3">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-primary-5" type="radio" name="theme-primary"
                id="switcher-primary4">
            </div>
            <div class="ti-form-radio switch-select ps-0 mt-1 color-primary-light">
              <div class="theme-container-primary"></div>
              <div class="pickr-container-primary"></div>
            </div>
          </div>
        </div>
        <div class="theme-colors">
          <p class="switcher-style-head">Theme Background:</p>
          <div class="flex switcher-style space-x-3 rtl:space-x-reverse">
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-bg-1" type="radio" name="theme-background"
                id="switcher-background" checked>
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-bg-2" type="radio" name="theme-background"
                id="switcher-background1">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-bg-3" type="radio" name="theme-background"
                id="switcher-background2">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-bg-4" type="radio" name="theme-background"
                id="switcher-background3">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio color-input color-bg-5" type="radio" name="theme-background"
                id="switcher-background4">
            </div>
            <div class="ti-form-radio switch-select ps-0 mt-1 color-bg-transparent">
              <div class="theme-container-background hidden"></div>
              <div class="pickr-container-background"></div>
            </div>
          </div>
        </div>
        <div class="menu-image theme-colors">
          <p class="switcher-style-head">Menu With Background Image:</p>
          <div class="flex switcher-style space-x-3 rtl:space-x-reverse flex-wrap gap-3">
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio bgimage-input bg-img1" type="radio" name="theme-images" id="switcher-bg-img">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio bgimage-input bg-img2" type="radio" name="theme-images" id="switcher-bg-img1">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio bgimage-input bg-img3" type="radio" name="theme-images" id="switcher-bg-img2">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio bgimage-input bg-img4" type="radio" name="theme-images" id="switcher-bg-img3">
            </div>
            <div class="ti-form-radio switch-select">
              <input class="ti-form-radio bgimage-input bg-img5" type="radio" name="theme-images" id="switcher-bg-img4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ti-offcanvas-footer sm:flex justify-between">
      <a href="javascript:void(0);" id="reset-all" class="w-full ti-btn ti-btn-danger-full m-1">Reset</a>
    </div>
  </div>
  <!-- ========== END Switcher  ========== -->

  <!-- Loader -->
  <div id="loader">
    <img src="{{ asset('backend/assets/images/media/loader.svg') }}" alt="">
  </div>
  <!-- Loader -->
  <div class="page">
    <!-- Start::Header -->
    <header class="app-header">
      <nav class="main-header" aria-label="Global">
        <div class="main-header-container !px-[0.85rem]">

          <div class="header-content-left">
            <!-- Start::header-element -->
            <div class="header-element">
              <div class="horizontal-logo">
                <a href="{{ route('dashboard') }}" class="header-logo">
                  <img src="{{ asset('backend/assets/images/brand-logos/desktop-logo.png') }}" alt="logo"
                    class="desktop-logo">
                  <img src="{{ asset('backend/assets/images/brand-logos/toggle-logo.png') }}" alt="logo"
                    class="toggle-logo">
                  <img src="{{ asset('backend/assets/images/brand-logos/desktop-dark.png') }}" alt="logo"
                    class="desktop-dark">
                  <img src="{{ asset('backend/assets/images/brand-logos/toggle-dark.png') }}" alt="logo"
                    class="toggle-dark">
                  <img src="{{ asset('backend/assets/images/brand-logos/desktop-white.png') }}" alt="logo"
                    class="desktop-white">
                </a>
              </div>
            </div>
            <!-- End::header-element -->

            <!-- End::header-element -->
            <div class="header-element !items-center">
              <!-- Start::header-link -->
              <a aria-label="Hide Sidebar"
                class="sidemenu-toggle animated-arrow header-link  hor-toggle horizontal-navtoggle inline-flex items-center"
                href="javascript:void(0);"><i class="header-icon fe fe-align-left"></i></a>
              <div class="main-header-center hidden lg:block">
                <input
                  class="form-control placeholder:!text-headerprimecolor placeholder:opacity-70 placeholder:font-thin placeholder:text-sm"
                  placeholder="Search for anything..." type="search">
                <button class="btn"><i class="fa fa-search hidden md:block opacity-[0.5]"></i></button>
              </div>
              <!-- End::header-link -->
            </div>
          </div>
          <div class="header-content-right">

            <div class="header-element hs-dropdown search-dropdown ti-dropdown  lg:!hidden [--auto-close:inside]">
              <a href="javascript:void(0);" class="header-link dropdown-toggle" id="hs-dropdown-auto-close-inside"
                data-bs-toggle="dropdown" aria-expanded="false"> <svg xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 -960 960 960" class="header-link-icon">
                  <path
                    d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z">
                  </path>
                </svg> </a>

              <div class="hs-dropdown-menu ti-dropdown-menu hidden" aria-labelledby="hs-dropdown-auto-close-inside">
                <div>
                  <div class="input-group w-full p-2"> <input type="text" class="form-control" placeholder="Search....">
                    <div class="ti-btn ti-btn-primary-full !mb-0"> <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- start header country -->
            <div class="header-element  header-country hs-dropdown ti-dropdown"
              data-hs-dropdown-placement="bottom-right">

              <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="flag-icon1">
                  <circle cx="256" cy="256" r="256" fill="#f0f0f0"></circle>
                  <g fill="#0052b4">
                    <path
                      d="M52.92 100.142c-20.109 26.163-35.272 56.318-44.101 89.077h133.178L52.92 100.142zM503.181 189.219c-8.829-32.758-23.993-62.913-44.101-89.076l-89.075 89.076h133.176zM8.819 322.784c8.83 32.758 23.993 62.913 44.101 89.075l89.074-89.075H8.819zM411.858 52.921c-26.163-20.109-56.317-35.272-89.076-44.102v133.177l89.076-89.075zM100.142 459.079c26.163 20.109 56.318 35.272 89.076 44.102V370.005l-89.076 89.074zM189.217 8.819c-32.758 8.83-62.913 23.993-89.075 44.101l89.075 89.075V8.819zM322.783 503.181c32.758-8.83 62.913-23.993 89.075-44.101l-89.075-89.075v133.176zM370.005 322.784l89.075 89.076c20.108-26.162 35.272-56.318 44.101-89.076H370.005z">
                    </path>
                  </g>
                  <g fill="#d80027">
                    <path
                      d="M509.833 222.609H289.392V2.167A258.556 258.556 0 00256 0c-11.319 0-22.461.744-33.391 2.167v220.441H2.167A258.556 258.556 0 000 256c0 11.319.744 22.461 2.167 33.391h220.441v220.442a258.35 258.35 0 0066.783 0V289.392h220.442A258.533 258.533 0 00512 256c0-11.317-.744-22.461-2.167-33.391z">
                    </path>
                    <path
                      d="M322.783 322.784L437.019 437.02a256.636 256.636 0 0015.048-16.435l-97.802-97.802h-31.482v.001zM189.217 322.784h-.002L74.98 437.019a256.636 256.636 0 0016.435 15.048l97.802-97.804v-31.479zM189.217 189.219v-.002L74.981 74.98a256.636 256.636 0 00-15.048 16.435l97.803 97.803h31.481zM322.783 189.219L437.02 74.981a256.328 256.328 0 00-16.435-15.047l-97.802 97.803v31.482z">
                    </path>
                  </g>
                </svg>
              </a>

              <div class="hs-dropdown-menu ti-dropdown-menu min-w-[10rem] hidden !left-[-7rem] !-mt-2"
                aria-labelledby="dropdown-flag">
                <div class="ti-dropdown-divider divide-y divide-gray-200 dark:divide-white/10">
                  <div class="py-2 first:pt-0 last:pb-0">
                    <div class="ti-dropdown-item !p-[0.65rem] ">
                      <div class="flex items-center space-x-2 rtl:space-x-reverse w-full">
                        <div class="flex items-center rounded-full">
                          <img src="{{ asset('backend/assets/images/flags/french_flag.jpg') }}" alt="flag-img"
                            class="h-[1.1875rem] w-[1.1875rem] rounded-full">
                        </div>
                        <div>
                          <p class="!text-[0.8125rem] font-medium">
                            French
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="ti-dropdown-item !p-[0.65rem]">
                      <div class="flex items-center space-x-2 rtl:space-x-reverse w-full">
                        <div class="flex items-center rounded-full">
                          <img src="{{ asset('backend/assets/images/flags/germany_flag.jpg') }}" alt="flag-img"
                            class="h-[1.1875rem] w-[1.1875rem] rounded-full">
                        </div>
                        <div>
                          <p class="!text-[0.8125rem] font-medium">
                            German
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="ti-dropdown-item !p-[0.65rem]">
                      <div class="flex items-center space-x-2 rtl:space-x-reverse w-full">
                        <div class="flex items-center rounded-full">
                          <img src="{{ asset('backend/assets/images/flags/italy_flag.jpg') }}" alt="flag-img"
                            class="h-[1.1875rem] w-[1.1875rem] rounded-full">
                        </div>
                        <div>
                          <p class="!text-[0.8125rem] font-medium">
                            Italian
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="ti-dropdown-item !p-[0.65rem]">
                      <div class="flex items-center space-x-2 rtl:space-x-reverse w-full">
                        <div class="flex items-center rounded-full">
                          <img src="{{ asset('backend/assets/images/flags/russia_flag.jpg') }}" alt="flag-img"
                            class="h-[1.1875rem] w-[1.1875rem] rounded-full">
                        </div>
                        <div>
                          <p class="!text-[0.8125rem] font-medium">
                            Russian
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="ti-dropdown-item !p-[0.65rem]">
                      <div class="flex items-center space-x-2 rtl:space-x-reverse w-full">
                        <div class="flex items-center rounded-full">
                          <img src="{{ asset('backend/assets/images/flags/spain_flag.jpg') }}" alt="flag-img"
                            class="h-[1.1875rem] w-[1.1875rem] rounded-full">
                        </div>
                        <div>
                          <p class="!text-[0.8125rem] font-medium">
                            Spanish
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end header country -->

            <!-- light and dark theme -->
            <div class="header-element header-theme-mode hidden !items-center sm:block !py-[1rem] !px-[0.65rem]">
              <a aria-label="anchor"
                class="hs-dark-mode-active:hidden flex hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium transition-all text-xs dark:bg-bodybg dark:hover:bg-black/20 dark:text-white/70 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                href="javascript:;" data-hs-theme-click-value="dark">
                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24" viewBox="0 -960 960 960"
                  width="24">
                  <path
                    d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z" />
                </svg>
              </a>
              <a aria-label="anchor"
                class="hs-dark-mode-active:flex hidden hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium text-defaulttextcolor  transition-all text-xs dark:bg-bodybg  dark:hover:bg-black/20 dark:text-white/70 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                href="javascript:;" data-hs-theme-click-value="light">
                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" fill="currentColor" height="24"
                  viewBox="0 -960 960 960" width="24">
                  <path
                    d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z" />
                </svg>
              </a>
            </div>
            <!-- End light and dark theme -->

            <ul id="header-cart-items-scroll" class="hidden"></ul>

            <!--Header Notifictaion -->
            @php
              $headerNotifications = auth()->user()->appNotifications()->latest()->take(5)->get();
              $headerUnread = auth()->user()->appNotifications()->where('is_read', false)->count();
              $isApplicantUser = auth()->user()->hasRole(\App\Enums\Role::Applicant);
            @endphp
            <div
              class="header-element py-[1rem] px-[0.65rem] notifications-dropdown header-notification hs-dropdown ti-dropdown !hidden md:!flex"
              data-hs-dropdown-placement="bottom-right">
              <button id="dropdown-notification" type="button"
                class="hs-dropdown-toggle relative ti-dropdown-toggle !p-0 !border-0 flex-shrink-0  !rounded-full !shadow-none align-middle text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px" viewBox="0 0 24 24"
                  width="24px" fill="currentColor">
                  <path d="M0 0h24v24H0V0z" fill="none" />
                  <path
                    d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z" />
                </svg>
                @if ($headerUnread > 0)
                  <span class="flex absolute h-5 w-5 -top-[0.6rem] ltr:right-0 rtl:left-0  ltr:-mr-[0.6rem] rtl:-ml-1">
                    <span
                      class="animate-slow-ping absolute inline-flex top-[7px] -start-[1.4px] h-[10px] w-[10px] !rounded-full bg-success/50 opacity-75"></span>
                    <span class="pulse-success"></span>
                  </span>
                @endif
              </button>
              <div
                class="main-header-dropdown !-mt-2 !p-0 hs-dropdown-menu ti-dropdown-menu  bg-white !w-[20rem] !border-0 border-defaultborder hidden !m-0"
                aria-labelledby="dropdown-notification">
                <div class="menu-header-content bg-primary text-white">
                  <div class="flex items-center justify-between">
                    <h6 class="mb-0 text-[.9375rem] font-semibold text-white">Notifications</h6>
                  </div>
                  <p class="dropdown-title-text subtext mb-0 text-white opacity-[0.6] pb-0 text-[0.75rem] ">You have
                    {{ $headerUnread }} unread notifications</p>
                </div>
                <div class="dropdown-divider"></div>
                <ul class="list-none mb-0" id="header-notification-scroll">
                  @forelse ($headerNotifications as $n)
                    <li class="dropdown-item px-3">
                      <div class="ms-1">
                        <h5 class="notification-label text-defaulttextcolor mb-1">{{ $n->title }}</h5>
                        <div class="notification-subtext">{{ $n->created_at->diffForHumans() }}</div>
                      </div>
                    </li>
                  @empty
                    <li class="dropdown-item px-3">
                      <div class="notification-subtext">No notifications yet.</div>
                    </li>
                  @endforelse
                </ul>
                @if ($isApplicantUser)
                  <div class="text-center !rounded-bl-md !rounded-br-md dropdown-footer">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('applicant.notifications.index') ? route('applicant.notifications.index') : 'javascript:void(0);' }}"
                      class="text-primary fs-13">VIEW ALL</a>
                  </div>
                @endif
              </div>
            </div>
            <!--End Header Notifictaion -->

            <!-- Fullscreen -->
            <div class="header-element header-fullscreen py-[1rem] md:px-[0.65rem] px-2">
              <!-- Start::header-link -->
              <a aria-label="anchor" onclick="openFullscreen();" href="javascript:void(0);"
                class="inline-flex flex-shrink-0 justify-center items-center gap-2  !rounded-full font-medium dark:hover:bg-black/20 dark:text-textmuted dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10">
                <i class="bx bx-fullscreen full-screen-open header-link-icon"></i>
                <i class="bx bx-exit-fullscreen full-screen-close header-link-icon hidden"></i>
              </a>
              <!-- End::header-link -->
            </div>
            <!-- End Full screen -->

            <!-- Start::header-element -->
            <div class="header-element header-sidebar !hidden md:!flex">
              <!-- Start::header-link-->
              <a href="javascript:void(0);" class="header-link hs-dropdown-toggle" data-hs-overlay="#hs-overlay-chat">
                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="3" y1="12" x2="21" y2="12"></line>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
              </a>
              <!-- End::header-link-->
            </div>
            <!-- End::header-element -->

            <!-- Header Profile -->
            <div
              class="header-element md:!px-[0.5rem] px-2 hs-dropdown profile-dropdown !items-center ti-dropdown [--placement:bottom-left]">
              <button id="dropdown-profile" type="button"
                class="hs-dropdown-toggle ti-dropdown-toggle !gap-2 !p-0 flex-shrink-0 me-0 !rounded-full !shadow-none text-xs align-middle !border-0 !shadow-transparent ">
                <img class="inline-block rounded-full " src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                  width="37" height="37" alt="Profile">
              </button>
              <div
                class="main-header-dropdown !-mt-2 !p-0 hs-dropdown-menu ti-dropdown-menu  bg-white  !border-0 border-defaultborder hidden !m-0"
                aria-labelledby="dropdown-profile">
                <ul class="dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end main-profile-menu"
                  aria-labelledby="mainHeaderProfile">
                  <li>
                    <div class="main-header-profile bg-primary menu-header-content text-white">
                      <div class="my-auto">
                        <h6 class="mb-0 leading-none text-white">{{ auth()->user()->name }}</h6><span
                          class="text-[.6875rem] opacity-[0.7] leading-none">{{ ucfirst(auth()->user()->role->value) }}</span>
                      </div>
                    </div>
                  </li>
                  <li><a class="dropdown-item text-defaulttextcolor flex " href="{{ route('profile.edit') }}"><i
                        class="bx bx-user-circle text-[1.125rem] me-2 opacity-[0.7]"></i>Profile</a></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit"
                        class="dropdown-item text-defaulttextcolor !rounded-bl-md !rounded-br-md flex w-full"><i
                          class="bx bx-log-out text-[1.125rem] me-2 opacity-[0.7]"></i>Log Out</button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
            <!-- End Header Profile -->

            <!-- Switcher Icon -->
            <div class="header-element px-[0.48rem]">
              <button aria-label="button" type="button"
                class="hs-dropdown-toggle switcher-icon inline-flex flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium  align-middle transition-all text-xs dark:text-white/70 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                data-hs-overlay="#hs-overlay-switcher">
                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon animate-spin-slow" width="24"
                  height="24" viewBox="0 0 24 24">
                  <path
                    d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2-2-.916-2-2 .916-2 2-2z">
                  </path>
                  <path
                    d="m2.845 16.136 1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65.998-1.729 1.145.662a.997.997 0 0 0 1.188-.142 6.071 6.071 0 0 1 2.384-1.399A1 1 0 0 0 11 5.3V4h2v1.3a1 1 0 0 0 .708.956 6.083 6.083 0 0 1 2.384 1.399.999.999 0 0 0 1.188.142l1.144-.661 1 1.729-1.124.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378 0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649-.998 1.729-1.145-.661a.996.996 0 0 0-1.188.142 6.071 6.071 0 0 1-2.384 1.399A1 1 0 0 0 13 18.7l.002 1.3H11v-1.3a1 1 0 0 0-.708-.956 6.083 6.083 0 0 1-2.384-1.399.992.992 0 0 0-1.188-.141l-1.144.662-1-1.729 1.124-.651a1 1 0 0 0 .471-1.108z">
                  </path>
                </svg>
              </button>
            </div>
            <!-- Switcher Icon -->

            <!-- End::header-element -->
          </div>
        </div>
      </nav>
    </header>
    <!-- End::Header -->

    <!-- Start::Off-canvas sidebar-->
    <div id="hs-overlay-chat" class="hs-overlay hidden ti-offcanvas ti-offcanvas-right overflow-auto" tabindex="-1">
      <div class="ti-offcanvas-header !py-2 rounded-none">
        <h5 class="text-[.875rem] uppercase mb-0 text-defaulttextcolor font-semibold" id="sidebarLabel">Notifications
        </h5>
        <button type="button"
          class="ti-btn flex-shrink-0 p-0  transition-none text-defaulttextcolor dark:text-defaulttextcolor/70 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white  dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
          data-hs-overlay="#hs-overlay-chat">
          <span class="sr-only">Close modal</span>
          <i class="ri-close-fill leading-none text-lg"></i>
        </button>
      </div>
      <div class="ti-offcanvas-body rounded-none p-0">
        <ul class="nav nav-tabs  p-4" role="tablist">
          <div class=" rtl:space-x-reverse" aria-label="Tabs" role="tablist" role="tablist">
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-2 rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white  bg-light  font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  active"
              id="chat-item" data-hs-tab="#chat" aria-controls="chat" role="tab">
              <i class="fe fe-message-circle text-[.9375rem] me-2 inline-flex"></i>Chat
            </button>
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-2  rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white   bg-light font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  dark:hover:text-gray-300"
              id="notification-item" data-hs-tab="#notification" aria-controls="notification" role="tab">
              <i class="fe fe-bell text-[.9375rem] me-2 inline-flex"></i> Notifications
            </button>
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-0 rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white   bg-light font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  dark:hover:text-gray-300"
              id="friends-item" data-hs-tab="#friends" aria-controls="friends" role="tab">
              <i class="fe fe-users text-[.9375rem] me-2 inline-flex"></i>Friends
            </button>
          </div>
        </ul>
        <div class="tab-content !border-0 ">
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 show border-defaultborder dark:border-defaultborder/10 "
            id="chat" role="tabpanel" aria-labelledby="chat-item">
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-primary avatar-rounded avatar-md">CH</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>New Websites is Created</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">30 mins ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-danger avatar-rounded avatar-md">N</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Prepare For the Next Project</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">2 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-info avatar-rounded avatar-md">S</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Decide the live Discussion</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">3 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-warning avatar-rounded avatar-md">K</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Meeting at 3:00 pm</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">4 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-success avatar-rounded avatar-md">R</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">1 day ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-pinkmain avatar-rounded avatar-md">MS</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">1 day ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex items-center border-b border-defaultborder dark:border-defaultborder/10  p-3">
              <div class="">
                <span class="avatar bg-purplemain avatar-rounded avatar-md">L</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">45 minutes ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="list flex border-b border-defaultborder dark:border-defaultborder/10 items-center p-3">
              <div class="">
                <span class="avatar bg-indigomain avatar-rounded avatar-md">U</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="mb-0 flex ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">2 days ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 border-defaultborder dark:border-defaultborder/10  hidden"
            id="notification" role="tabpanel" aria-labelledby="notification-item">
            <div class="ti-list-group ti-list-group-flush ">
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/1.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Madeleine</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    3 hours ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/2.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Anthony</strong> New product Launching...
                  <div class="small text-textmuted">
                    5 hour ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/3.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> New Schedule Realease......
                  <div class="small text-textmuted">
                    45 minutes ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/4.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Madeleine</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    3 hours ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/5.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Anthony</strong> New product Launching...
                  <div class="small text-textmuted">
                    5 hour ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/6.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> New Schedule Realease......
                  <div class="small text-textmuted">
                    45 minutes ago
                  </div>
                </div>
              </div>
              <div
                class="ti-list-group-item  !border-b border-defaultborder dark:border-defaultborder/10 !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="avatar avatar-lg avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/7.jpg') }}" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    12 minutes ago
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 border-defaultborder dark:border-defaultborder/10  active hidden"
            id="friends" role="tabpanel" aria-labelledby="friends-item">
            <div class="ti-list-group ti-list-group-flush ">
              <div class="ti-list-group-item flex !border-t-0 items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/1.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Mozelle Belt</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/2.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/5.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/6.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/8.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/8.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Mozelle Belt</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/9.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/10.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/11.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/12.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/2.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/2.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex  items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/3.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div
                class="ti-list-group-item flex !border-b border-defaultborder dark:border-defaultborder/10 items-center">
                <span class="avatar avatar-md online avatar-rounded flex-shrink-0">
                  <img src="{{ asset('backend/assets/images/faces/4.jpg') }}" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End::Off-canvas sidebar-->

    <!--chat-modal-->
    <div class="hs-overlay hidden ti-modal" id="chatmodel">
      <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
        <div class="ti-modal-content chat !border-0">
          <div class="box overflow-hidden !mb-0 !border-0 !shadow-none">
            <div class="action-header  flex items-center clearfix">
              <div class="float-start xs:hidden flex">
                <div class="avatar avatar-lg rounded-circle me-3"> <img
                    src="{{ asset('backend/assets/images/faces/6.jpg') }}" class="rounded-circle user_img" alt="img">
                </div>
                <div class="items-center">
                  <h5 class="text-fixed-white mb-0">Daneil Scott</h5> <span class="dot-label bg-success"></span><span
                    class="me-3 text-fixed-white">online</span>
                </div>
              </div>
              <ul class="ah-actions actions ms-auto items-center float-end">
                <li class="call-icon"> <a href="#" class="hidden md:block phone-button" data-hs-overlay="#audiomodal">
                    <i class="fe fe-phone"></i> </a> </li>
                <li class="video-icon"> <a href="#" class="hidden md:block phone-button" data-hs-overlay="#videomodal">
                    <i class="fe fe-video"></i> </a> </li>
                <li class="hs-dropdown ti-dropdown">
                  <a href="#" data-bs-toggle="dropdown" aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                  <ul class="ti-dropdown-menu hs-dropdown-menu dropdown-menu-end hidden">
                    <li class="ti-dropdown-item"><i class="fa fa-user-circle"></i> View profile</li>
                    <li class="ti-dropdown-item"><i class="fa fa-users"></i>Add friends</li>
                    <li class="ti-dropdown-item"><i class="fa fa-plus"></i> Add to group</li>
                    <li class="ti-dropdown-item"><i class="fa fa-ban"></i> Block</li>
                  </ul>
                </li>
                <li> <a href="" class="" data-bs-dismiss="modal" aria-label="Close"> <i
                      class="fe fe-x-circle text-fixed-white"></i> </a> </li>
              </ul>
            </div>
            <div class="box-body msg_card_body">
              <div class="chat-box-single-line"> <abbr
                  class="timestamp !text-defaulttextcolor dark:!text-defaulttextcolor/70">February 1st, 2019</abbr>
              </div>
              <div class="flex justify-start">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> Hi, how are you Jenna Side? <span class="msg_time">8:40 AM, Today</span>
                </div>
              </div>
              <div class="flex justify-end ">
                <div class="msg_cotainer_send"> Hi Connor Paige i am good tnx how about you? <span
                    class="msg_time_send">8:55 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/9.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> I am good too, thank you for your chat template <span class="msg_time">9:00
                    AM,
                    Today</span> </div>
              </div>
              <div class="flex justify-end ">
                <div class="msg_cotainer_send"> You welcome Connor Paige <span class="msg_time_send">9:05 AM,
                    Today</span>
                </div>
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/9.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give
                  <span class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/9.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give
                  <span class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/9.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give
                  <span class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/9.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
              </div>
              <div class="flex justify-start">
                <div class="img_cont_msg"> <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
                    class="rounded-circle user_img_msg" alt="img"> </div>
                <div class="msg_cotainer"> Okay Bye, text you later.. <span class="msg_time">9:12 AM, Today</span>
                </div>
              </div>
            </div>
            <div class="box-footer border-t">
              <div class="msb-reply flex">
                <div class="input-group"> <input type="text" class="form-control " placeholder="Typing...."> <button
                    type="button" class="ti-btn ti-btn-primary-full !mb-0"> <i class="far fa-paper-plane"
                      aria-hidden="true"></i> </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--chat-modal-->


    <!--Video Modal -->
    <div id="videomodal" class="hs-overlay hidden ti-modal">
      <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
        <div class="ti-modal-content !bg-[#3b4863] !border-0">
          <div class="mx-auto text-center p-[3rem]">
            <button type="button"
              class="hs-dropdown-toggle relative -end-[226px] -top-[29px] !text-[1.5rem] !font-medium text-white"
              data-hs-overlay="#videomodal">
              <span class="sr-only">Close</span>
              <i class="bi bi-x"></i>
            </button>
            <h5 class="text-white">Valex Video call</h5>
            <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
              class="rounded-full !h-[90px]  mt-4 mb-3 inline-flex" alt="img">
            <h4 class="mb-1 font-semibold text-white">Daneil Scott</h4>
            <h6 class="loading animate-loadingtext text-white">Calling...</h6>
            <div class="mt-[3rem] mb-[2rem]">
              <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-4">
                  <a class="icon icon-shape rounded-full mb-0" href="javascript:void(0);">
                    <i class="fas fa-video-slash"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="icon icon-shape rounded-full text-white mb-0" href="javascript:void(0);"
                    data-hs-overlay="#videomodal">
                    <i class="fas fa-phone !bg-danger !text-white"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="icon icon-shape rounded-full mb-0" href="javascript:void(0);">
                    <i class="fas fa-microphone-slash"></i>
                  </a>
                </div>
              </div>
            </div>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div>
    <!--End modal -->

    <!-- Audio Modal -->
    <div id="audiomodal" class="hs-overlay hidden ti-modal">
      <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
        <div class="ti-modal-content border-0">
          <div class="mx-auto text-center p-[3rem]">
            <button type="button"
              class="hs-dropdown-toggle relative -end-[226px] -top-[29px] !text-[1.5rem] !font-medium text-[#8c9097]"
              data-hs-overlay="#audiomodal">
              <span class="sr-only">Close</span>
              <i class="bi bi-x"></i>
            </button>
            <h6 class="text-defaulttextcolor dark:text-defaulttextcolor/70">Valex Voice call</h6>
            <img src="{{ asset('backend/assets/images/faces/6.jpg') }}"
              class="rounded-full !h-[90px] mt-6 mb-4 inline-flex" alt="img">
            <h5 class="mb-1 font-medium text-defaulttextcolor dark:text-defaulttextcolor/70">Daneil Scott</h5>
            <h6 class="loading animate-loadingtext text-defaulttextcolor dark:text-defaulttextcolor/70">Calling...</h6>
            <div class="mt-[2rem] mb-[2rem]">
              <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-4">
                  <a class="icon icon-shape rounded-circle mb-0" href="javascript:void(0);">
                    <i class="fas fa-volume-up !bg-light !text-defaulttextcolor"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="icon icon-shape rounded-circle text-white mb-0" href="javascript:void(0);"
                    data-hs-overlay="#audiomodal">
                    <i class="fas fa-phone text-white !bg-success"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="icon icon-shape  rounded-circle mb-0" href="javascript:void(0);">
                    <i class="fas fa-microphone-slash !bg-light !text-defaulttextcolor"></i>
                  </a>
                </div>
              </div>
            </div>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div>
    <!--End modal -->

    <aside class="app-sidebar" id="sidebar">
      @include('layouts.sidebar')
    </aside>

    <!-- Start::content  -->
    <div class="content">
      <!-- Start::main-content -->
      <div class="main-content">
        <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
          <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">@yield('page-title')</h5>
          </div>
        </div>

        @include('layouts.partials.flash')

        @yield('content')

      </div>
    </div>
    <!-- End::content  -->

    <!-- ========== Search Modal ========== -->
    <div id="search-modal" class="hs-overlay ti-modal hidden mt-[1.75rem]">
      <div class="ti-modal-box">
        <div class="ti-modal-content !border !border-defaultborder dark:!border-defaultborder/10 !rounded-[0.5rem]">
          <div class="ti-modal-body">

            <div class="input-group border-[2px] border-primary rounded-[0.25rem] w-full flex">
              <a href="javascript:void(0);"
                class="input-group-text flex items-center bg-light border-e-[#dee2e6] !py-[0.375rem] !px-[0.75rem] !rounded-none !text-[0.875rem]"
                id="Search-Grid"><i class="fe fe-search header-link-icon text-[0.875rem]"></i></a>

              <input type="search" class="form-control border-0 px-2 !text-[0.8rem] w-full focus:ring-transparent"
                placeholder="Search" aria-label="Username">

              <a href="javascript:void(0);"
                class="flex items-center input-group-text bg-light !py-[0.375rem] !px-[0.75rem]" id="voice-search"><i
                  class="fe fe-mic header-link-icon"></i></a>
              <div class="hs-dropdown ti-dropdown">
                <a href="javascript:void(0);"
                  class="flex items-center hs-dropdown-toggle ti-dropdown-toggle btn btn-light btn-icon !bg-light !py-[0.375rem] !rounded-none !px-[0.75rem] text-[0.95rem] h-[2.413rem] w-[2.313rem]">
                  <i class="fe fe-more-vertical"></i>
                </a>

                <ul class="absolute hs-dropdown-menu ti-dropdown-menu !-mt-2 !p-0 hidden">
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Action</a></li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Another action</a></li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Something else here</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Separated link</a></li>
                </ul>
              </div>
            </div>
            <div class="mt-5">
              <p class="font-normal  text-[#8c9097] text-[0.813rem] dark:text-gray-200 mb-2">Are You Looking For...</p>

              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i
                  class="fe fe-user me-2"></i>People<a href="javascript:void(0)"
                  class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i
                    class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i
                  class="fe fe-file-text me-2"></i>Pages<a href="javascript:void(0)"
                  class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i
                    class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i
                  class="fe fe-align-left me-2"></i>Articles<a href="javascript:void(0)"
                  class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i
                    class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i
                  class="fe fe-server me-2"></i>Tags<a href="javascript:void(0)"
                  class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i
                    class="fe fe-x"></i></a></span>

            </div>


            <div class="my-[1.5rem]">
              <p class="font-normal  text-[#8c9097] text-[0.813rem] mb-2">Recent Search :</p>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-2 !text-[0.8125rem] alert">
                <a href="notifications.html"><span>Notifications</span></a>
                <a class="ms-auto leading-none" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-2 !text-[0.8125rem] alert">
                <a href="alerts.html"><span>Alerts</span></a>
                <a class="ms-auto leading-none" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-0 !text-[0.8125rem] alert">
                <a href="mail.html"><span>Mail</span></a>
                <a class="ms-auto lh-1" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>
            </div>
          </div>

          <div class="ti-modal-footer !py-[1rem] !px-[1.25rem]">
            <div class="inline-flex rounded-md  shadow-sm">
              <button type="button"
                class="ti-btn-group !px-[0.75rem] !py-[0.45rem]  rounded-s-[0.25rem] !rounded-tr-none !rounded-br-none ti-btn-primary !text-[0.75rem] dark:border-white/10">
                Search
              </button>
              <button type="button"
                class="ti-btn-group  ti-btn-primary-full rounded-e-[0.25rem] dark:border-white/10 !text-[0.75rem] !rounded-tl-none !rounded-bl-none !px-[0.75rem] !py-[0.45rem]">
                Clear Recents
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ========== END Search Modal ========== -->

    <!-- Footer Start -->
    <footer
      class="footer mt-auto xl:ps-[15rem]  font-normal font-inter bg-white  leading-normal !text-[0.875rem] shadow-[0_0_0.4rem_rgba(0,0,0,0.1)] dark:bg-bodybg py-4 text-center">
      <div class="container">
        <span class="text-gray dark:text-defaulttextcolor/50"> Copyright © <span id="year"></span> <a
            href="javascript:void(0);" class="text-defaulttextcolor font-semibold dark:text-defaulttextcolor">Valex</a>.
          Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
            <span class="font-semibold text-primary underline">Spruko</span>
          </a> All
          rights
          reserved
        </span>
      </div>
    </footer>
    <!-- Footer End -->

  </div>

  <!-- Back To Top -->
  <div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill text-xl"></i></span>
  </div>

  <div id="responsive-overlay"></div>


  <!-- popperjs -->
  <script src="{{ asset('backend/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

  <!-- Color Picker JS -->
  <script src="{{ asset('backend/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

  <!-- sidebar JS -->
  <script src="{{ asset('backend/assets/js/defaultmenu.js') }}"></script>

  <!-- Switch JS -->
  <script src="{{ asset('backend/assets/js/switch.js') }}"></script>

  <!-- sticky JS -->
  <script src="{{ asset('backend/assets/js/sticky.js') }}"></script>


  <!-- Simplebar JS -->
  <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>

  <!-- Preline JS -->
  <script src="{{ asset('backend/assets/libs/preline/preline.js') }}"></script>


  <!-- Custom-Switcher JS -->
  <script src="{{ asset('backend/assets/js/custom-switcher.js') }}"></script>

  <!-- Custom JS -->
  <script src="{{ asset('backend/assets/js/custom.js') }}"></script>

  @stack('scripts')

  <!-- Sweey alert-->
  <script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

  <script>
    document.addEventListener('submit', function (e) {
      const form = e.target;
      const message = form.dataset.confirm;
      if (!message) return;

      e.preventDefault();
      Swal.fire({
        title: 'Confirmation Required',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#26bf4a',
        cancelButtonColor: '#23b7e5',
      }).then(function (result) {
        if (result.isConfirmed) form.submit();
      });
    });
  </script>

</body>

</html>