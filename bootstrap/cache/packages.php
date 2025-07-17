<?php return array (
  'barryvdh/laravel-debugbar' => 
  array (
    'aliases' => 
    array (
      'Debugbar' => 'Barryvdh\\Debugbar\\Facades\\Debugbar',
    ),
    'providers' => 
    array (
      0 => 'Barryvdh\\Debugbar\\ServiceProvider',
    ),
  ),
  'barryvdh/laravel-dompdf' => 
  array (
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\DomPDF\\Facade\\Pdf',
      'Pdf' => 'Barryvdh\\DomPDF\\Facade\\Pdf',
    ),
    'providers' => 
    array (
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ),
  ),
  'botble/api' => 
  array (
    'aliases' => 
    array (
      'ApiHelper' => 'Botble\\Api\\Facades\\ApiHelper',
    ),
    'providers' => 
    array (
      0 => 'Botble\\Api\\Providers\\ApiServiceProvider',
    ),
  ),
  'botble/assets' => 
  array (
    'aliases' => 
    array (
      'Assets' => 'Botble\\Assets\\Facades\\AssetsFacade',
    ),
    'providers' => 
    array (
      0 => 'Botble\\Assets\\Providers\\AssetsServiceProvider',
    ),
  ),
  'botble/data-synchronize' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\DataSynchronize\\Providers\\DataSynchronizeServiceProvider',
    ),
  ),
  'botble/dev-tool' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\DevTool\\Providers\\DevToolServiceProvider',
    ),
  ),
  'botble/form-builder' => 
  array (
    'aliases' => 
    array (
      'FormBuilder' => 'Kris\\LaravelFormBuilder\\Facades\\FormBuilder',
    ),
    'providers' => 
    array (
      0 => 'Kris\\LaravelFormBuilder\\FormBuilderServiceProvider',
    ),
  ),
  'botble/get-started' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\GetStarted\\Providers\\GetStartedServiceProvider',
    ),
  ),
  'botble/git-commit-checker' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\GitCommitChecker\\Providers\\GitCommitCheckerServiceProvider',
    ),
  ),
  'botble/installer' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Installer\\Providers\\InstallerServiceProvider',
    ),
  ),
  'botble/menu' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Menu\\Providers\\MenuServiceProvider',
    ),
    'aliases' => 
    array (
      'Menu' => 'Botble\\Menu\\Facades\\Menu',
    ),
  ),
  'botble/optimize' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Optimize\\Providers\\OptimizeServiceProvider',
    ),
    'aliases' => 
    array (
      'OptimizerHelper' => 'Botble\\Optimize\\Facades\\OptimizerHelper',
    ),
  ),
  'botble/page' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Page\\Providers\\PageServiceProvider',
    ),
  ),
  'botble/platform' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Base\\Providers\\BaseServiceProvider',
      1 => 'Botble\\Base\\Providers\\CommandServiceProvider',
      2 => 'Botble\\Base\\Providers\\EventServiceProvider',
      3 => 'Botble\\Base\\Providers\\ComposerServiceProvider',
      4 => 'Botble\\Base\\Providers\\MailConfigServiceProvider',
      5 => 'Botble\\Base\\Providers\\FormServiceProvider',
      6 => 'Botble\\Support\\Providers\\SupportServiceProvider',
      7 => 'Botble\\Table\\Providers\\TableServiceProvider',
      8 => 'Botble\\ACL\\Providers\\AclServiceProvider',
      9 => 'Botble\\Dashboard\\Providers\\DashboardServiceProvider',
      10 => 'Botble\\Media\\Providers\\MediaServiceProvider',
      11 => 'Botble\\JsValidation\\Providers\\JsValidationServiceProvider',
      12 => 'Botble\\Chart\\Providers\\ChartServiceProvider',
      13 => 'Botble\\Icon\\Providers\\IconServiceProvider',
    ),
    'aliases' => 
    array (
      'Action' => 'Botble\\Base\\Facades\\Action',
      'AdminAppearance' => 'Botble\\Base\\Facades\\AdminAppearance',
      'AdminHelper' => 'Botble\\Base\\Facades\\AdminHelper',
      'Assets' => 'Botble\\Base\\Facades\\Assets',
      'BaseHelper' => 'Botble\\Base\\Facades\\BaseHelper',
      'Breadcrumbs' => 'Botble\\Base\\Facades\\Breadcrumbs',
      'DashboardMenu' => 'Botble\\Base\\Facades\\DashboardMenu',
      'CoreIcon' => 'Botble\\Icon\\Facades\\Icon',
      'EmailHandler' => 'Botble\\Base\\Facades\\EmailHandler',
      'Filter' => 'Botble\\Base\\Facades\\Filter',
      'Form' => 'Botble\\Base\\Facades\\Form',
      'Html' => 'Botble\\Base\\Facades\\Html',
      'JsValidator' => 'Botble\\JsValidation\\Facades\\JsValidator',
      'MacroableModels' => 'Botble\\Base\\Facades\\MacroableModels',
      'MetaBox' => 'Botble\\Base\\Facades\\MetaBox',
      'PageTitle' => 'Botble\\Base\\Facades\\PageTitle',
      'PanelSectionManager' => 'Botble\\Base\\Facades\\PanelSectionManager',
      'RvMedia' => 'Botble\\Media\\Facades\\RvMedia',
      'Setting' => 'Botble\\Setting\\Facades\\Setting',
    ),
  ),
  'botble/plugin-management' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\PluginManagement\\Providers\\PluginManagementServiceProvider',
    ),
  ),
  'botble/revision' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Revision\\Providers\\RevisionServiceProvider',
    ),
  ),
  'botble/seo-helper' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\SeoHelper\\Providers\\SeoHelperServiceProvider',
    ),
    'aliases' => 
    array (
      'SeoHelper' => 'Botble\\SeoHelper\\Facades\\SeoHelper',
    ),
  ),
  'botble/shortcode' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Shortcode\\Providers\\ShortcodeServiceProvider',
    ),
    'aliases' => 
    array (
      'Shortcode' => 'Botble\\Shortcode\\Facades\\Shortcode',
    ),
  ),
  'botble/sitemap' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Sitemap\\Providers\\SitemapServiceProvider',
    ),
  ),
  'botble/slug' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Slug\\Providers\\SlugServiceProvider',
    ),
    'aliases' => 
    array (
      'SlugHelper' => 'Botble\\Slug\\Facades\\SlugHelper',
    ),
  ),
  'botble/theme' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Theme\\Providers\\ThemeServiceProvider',
      1 => 'Botble\\Theme\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'Theme' => 'Botble\\Theme\\Facades\\Theme',
      'ThemeOption' => 'Botble\\Theme\\Facades\\ThemeOption',
      'ThemeManager' => 'Botble\\Theme\\Facades\\Manager',
      'AdminBar' => 'Botble\\Theme\\Facades\\AdminBar',
      'SiteMapManager' => 'Botble\\Theme\\Facades\\SiteMapManager',
    ),
  ),
  'botble/widget' => 
  array (
    'providers' => 
    array (
      0 => 'Botble\\Widget\\Providers\\WidgetServiceProvider',
    ),
    'aliases' => 
    array (
      'Widget' => 'Botble\\Widget\\Facades\\Widget',
      'WidgetGroup' => 'Botble\\Widget\\Facades\\WidgetGroup',
    ),
  ),
  'laravel/pail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Pail\\PailServiceProvider',
    ),
  ),
  'laravel/sail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sail\\SailServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/socialite' => 
  array (
    'aliases' => 
    array (
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
    'providers' => 
    array (
      0 => 'Laravel\\Socialite\\SocialiteServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'maatwebsite/excel' => 
  array (
    'aliases' => 
    array (
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
    ),
    'providers' => 
    array (
      0 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
    ),
  ),
  'mews/purifier' => 
  array (
    'aliases' => 
    array (
      'Purifier' => 'Mews\\Purifier\\Facades\\Purifier',
    ),
    'providers' => 
    array (
      0 => 'Mews\\Purifier\\PurifierServiceProvider',
    ),
  ),
  'mollie/laravel-mollie' => 
  array (
    'aliases' => 
    array (
      'Mollie' => 'Mollie\\Laravel\\Facades\\Mollie',
    ),
    'providers' => 
    array (
      0 => 'Mollie\\Laravel\\MollieServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
  'spatie/laravel-ignition' => 
  array (
    'aliases' => 
    array (
      'Flare' => 'Spatie\\LaravelIgnition\\Facades\\Flare',
    ),
    'providers' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\IgnitionServiceProvider',
    ),
  ),
  'tightenco/ziggy' => 
  array (
    'providers' => 
    array (
      0 => 'Tighten\\Ziggy\\ZiggyServiceProvider',
    ),
  ),
  'vigstudio/laravel-ai' => 
  array (
    'aliases' => 
    array (
      'OpenAI' => 'OpenAI\\Laravel\\Facades\\OpenAI',
    ),
    'providers' => 
    array (
      0 => 'VigStudio\\LaravelAI\\ServiceProvider',
    ),
  ),
  'vigstudio/laravel-openai-client' => 
  array (
    'providers' => 
    array (
      0 => 'OpenAI\\Laravel\\ServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-buttons' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\ButtonsServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-html' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\HtmlServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-oracle' => 
  array (
    'aliases' => 
    array (
      'DataTables' => 'Yajra\\DataTables\\Facades\\DataTables',
    ),
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\DataTablesServiceProvider',
    ),
  ),
);