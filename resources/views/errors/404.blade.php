@include('errors._frame', ['code' => 404, 't' => [
    'tr' => ['h' => 'Sayfa bulunamadı', 'p' => 'Aradığınız sayfa taşınmış, adı değişmiş ya da hiç var olmamış olabilir.'],
    'en' => ['h' => 'Page not found', 'p' => 'The page you are looking for may have been moved, renamed, or never existed.'],
    'ar' => ['h' => 'الصفحة غير موجودة', 'p' => 'قد تكون الصفحة التي تبحث عنها قد نُقلت أو غُيّر اسمها أو لم تكن موجودة.'],
]])
